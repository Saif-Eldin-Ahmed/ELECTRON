<?php
require_once 'includes/config.php';

try {
    $pdo = getDBConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Validate product ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: search.php');
    exit;
}

// Fetch product with brand and category
$stmt = $pdo->prepare(
    "SELECT p.*, b.name AS brand_name, b.slug AS brand_slug, c.name AS category_name, c.slug AS category_slug
     FROM products p
     LEFT JOIN brands b ON p.brand_id = b.id
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.id = :id AND p.status = 'published'
     LIMIT 1"
);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: search.php');
    exit;
}

// Parse images
$imgs = json_decode($product['imgs'], true);
if (!is_array($imgs) || empty($imgs)) {
    $imgs = ['assets/prdctImgs/Default.png'];
}
$primary_img = $imgs[0];
$gallery_imgs = $imgs;

// Parse specifications
$specs = [];
if (!empty($product['specifications'])) {
    $decoded = json_decode($product['specifications'], true);
    if (is_array($decoded)) {
        $specs = $decoded;
    }
}

// Fetch related products (same category, excluding current)
$related_stmt = $pdo->prepare(
    "SELECT p.id, p.name, p.price, p.compare_at_price, p.imgs, b.name AS brand_name, c.name AS category_name
     FROM products p
     LEFT JOIN brands b ON p.brand_id = b.id
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.category_id = :cat_id AND p.id != :id AND p.status = 'published'
     LIMIT 4"
);
$related_stmt->execute([':cat_id' => $product['category_id'], ':id' => $id]);
$related_products = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

// If not enough related products, fetch from anywhere
if (count($related_products) < 2) {
    $fill_stmt = $pdo->prepare(
        "SELECT p.id, p.name, p.price, p.compare_at_price, p.imgs, b.name AS brand_name, c.name AS category_name
         FROM products p
         LEFT JOIN brands b ON p.brand_id = b.id
         LEFT JOIN categories c ON p.category_id = c.id
         WHERE p.id != :id AND p.status = 'published'
         ORDER BY RAND() LIMIT 4"
    );
    $fill_stmt->execute([':id' => $id]);
    $related_products = $fill_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ratings (deterministic)
$review_count = ($product['id'] * 17) % 150 + 10;
$rating = 4.0 + (($product['id'] * 3) % 11) / 10.0;

// Stock status
$stock = intval($product['stock_quantity']);
if ($stock === 0) {
    $stock_label = 'Out of Stock';
    $stock_class = 'text-red-600';
} elseif ($stock <= intval($product['low_stock_limit'])) {
    $stock_label = "Low Stock — Only {$stock} left";
    $stock_class = 'text-orange-500';
} else {
    $stock_label = 'In Stock';
    $stock_class = 'text-emerald-600';
}

$page_title = "ELECTRON | " . htmlspecialchars($product['name']);
$body_class = "bg-white font-body-md text-on-background selection:bg-secondary-container";
include 'includes/header.php';
?>

<main class="max-w-[1440px] mx-auto px-6 md:px-12 pt-28 md:pt-32 pb-24">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-zinc-400 mb-10 md:mb-14">
        <a href="index.php" class="hover:text-zinc-900 transition-colors">Home</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a href="search.php" class="hover:text-zinc-900 transition-colors">Shop</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a href="search.php?category[]=<?php echo urlencode(strtolower(explode(' ', $product['category_name'])[0])); ?>" class="hover:text-zinc-900 transition-colors"><?php echo htmlspecialchars($product['category_name']); ?></a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-zinc-900"><?php echo htmlspecialchars($product['name']); ?></span>
    </nav>

    <!-- Product Hero -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

        <!-- Gallery -->
        <div class="flex flex-col-reverse md:flex-row gap-4 lg:sticky lg:top-32">
            <!-- Thumbnails -->
            <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-visible pb-2 md:pb-0">
                <?php foreach ($gallery_imgs as $i => $img): ?>
                    <button
                        onclick="setMainImage('<?php echo htmlspecialchars($img); ?>', this)"
                        class="thumb-btn flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden border-2 transition-all duration-200 <?php echo $i === 0 ? 'border-zinc-950' : 'border-zinc-200 hover:border-zinc-400'; ?> bg-zinc-50">
                        <img src="<?php echo htmlspecialchars($img); ?>"
                            alt="View <?php echo $i + 1; ?>"
                            class="w-full h-full object-contain p-2">
                    </button>
                <?php endforeach; ?>
            </div>
            <!-- Main Image -->
            <div class="flex-1 bg-zinc-50 rounded-[2rem] overflow-hidden flex items-center justify-center aspect-square relative group">
                <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                    <span class="absolute top-5 left-5 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 z-10">Sale</span>
                <?php endif; ?>
                <img id="mainProductImage"
                    src="<?php echo htmlspecialchars($primary_img); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                    class="w-full h-full object-contain p-10 transition-transform duration-500 group-hover:scale-105">
            </div>
        </div>

        <!-- Product Info -->
        <div class="flex flex-col gap-6">
            <!-- Category & Brand -->
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 border border-zinc-200 px-3 py-1 rounded-full"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 border border-zinc-200 px-3 py-1 rounded-full"><?php echo htmlspecialchars($product['brand_name']); ?></span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-zinc-950 leading-tight"><?php echo htmlspecialchars($product['name']); ?></h1>

            <!-- Stars -->
            <div class="flex items-center gap-3">
                <div class="flex text-yellow-500">
                    <?php
                    $full_stars = floor($rating);
                    $has_half = ($rating - $full_stars) >= 0.5;
                    for ($i = 1; $i <= 5; $i++):
                        if ($i <= $full_stars): ?>
                            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">star</span>
                        <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">star_half</span>
                        <?php else: ?>
                            <span class="material-symbols-outlined text-xl">star</span>
                    <?php endif;
                    endfor; ?>
                </div>
                <span class="text-sm font-semibold text-zinc-600"><?php echo number_format($rating, 1); ?></span>
                <span class="text-sm text-zinc-400">(<?php echo $review_count; ?> reviews)</span>
            </div>

            <!-- Price -->
            <div class="flex items-end gap-4 py-4 border-y border-zinc-100">
                <span class="text-4xl md:text-5xl font-black text-zinc-950">$<?php echo number_format($product['price'], 2); ?></span>
                <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                    <div class="flex flex-col mb-1">
                        <span class="text-sm text-zinc-400 line-through">$<?php echo number_format($product['compare_at_price'], 2); ?></span>
                        <?php
                        $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
                        ?>
                        <span class="text-sm font-bold text-red-600"><?php echo $discount; ?>% off</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Stock Status -->
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base <?php echo $stock_class; ?>" style="font-variation-settings:'FILL' 1">
                    <?php echo $stock === 0 ? 'cancel' : ($stock <= intval($product['low_stock_limit']) ? 'warning' : 'check_circle'); ?>
                </span>
                <span class="text-sm font-bold <?php echo $stock_class; ?>"><?php echo $stock_label; ?></span>
            </div>

            <!-- Description -->
            <p class="text-zinc-600 text-base leading-relaxed"><?php echo htmlspecialchars($product['description']); ?></p>

            <!-- Key Specs Preview -->
            <?php if (!empty($specs)): ?>
                <div class="grid grid-cols-2 gap-3">
                    <?php foreach (array_slice($specs, 0, 4) as $key => $value): ?>
                        <div class="bg-zinc-50 rounded-xl p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-1"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?></p>
                            <p class="text-sm font-bold text-zinc-900"><?php echo htmlspecialchars(is_bool($value) ? ($value ? 'Yes' : 'No') : $value); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <?php if ($stock > 0): ?>
                    <button id="addToCartBtn" class="flex-1 bg-zinc-950 text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest text-sm hover:bg-zinc-800 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl">shopping_cart</span>
                        Add to Cart
                    </button>
                <?php else: ?>
                    <button class="flex-1 bg-zinc-200 text-zinc-500 px-8 py-4 rounded-full font-bold uppercase tracking-widest text-sm cursor-not-allowed flex items-center justify-center gap-2" disabled>
                        <span class="material-symbols-outlined text-xl">remove_shopping_cart</span>
                        Out of Stock
                    </button>
                <?php endif; ?>
            </div>

            <!-- Meta Info -->
            <div class="flex flex-wrap gap-x-6 gap-y-2 pt-2 text-xs text-zinc-400">
                <?php if ($product['sku']): ?><span>SKU: <strong class="text-zinc-600"><?php echo htmlspecialchars($product['sku']); ?></strong></span><?php endif; ?>
                <?php if ($product['warranty_months']): ?><span>Warranty: <strong class="text-zinc-600"><?php echo $product['warranty_months']; ?> months</strong></span><?php endif; ?>
                <?php if ($product['weight_grams']): ?><span>Weight: <strong class="text-zinc-600"><?php echo $product['weight_grams']; ?>g</strong></span><?php endif; ?>
                <?php if ($product['dimensions']): ?><span>Dimensions: <strong class="text-zinc-600"><?php echo htmlspecialchars($product['dimensions']); ?></strong></span><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Full Specifications -->
    <?php if (!empty($specs)): ?>
        <div class="mt-20 md:mt-28">
            <div class="flex items-center gap-6 mb-10">
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-zinc-950">Specifications</h2>
                <div class="flex-1 h-px bg-zinc-100"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($specs as $key => $value): ?>
                    <div class="group bg-zinc-50 hover:bg-zinc-100 rounded-2xl p-5 transition-colors">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?></p>
                        <p class="text-base font-bold text-zinc-900"><?php echo htmlspecialchars(is_bool($value) ? ($value ? 'Yes' : 'No') : $value); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <div class="mt-20 md:mt-28">
            <div class="flex items-center gap-6 mb-10">
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-zinc-950">You May Also Like</h2>
                <div class="flex-1 h-px bg-zinc-100"></div>
                <a href="search.php" class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5 whitespace-nowrap hover:text-zinc-500 hover:border-zinc-500 transition-colors">View All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($related_products as $rel): ?>
                    <?php
                    $rel_imgs = json_decode($rel['imgs'], true);
                    $rel_img = (is_array($rel_imgs) && !empty($rel_imgs)) ? $rel_imgs[0] : 'assets/proImgs/Default.jpg';
                    ?>
                    <a href="product.php?id=<?php echo $rel['id']; ?>" class="group bg-white border border-zinc-100 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col">
                        <div class="aspect-square bg-zinc-50 flex items-center justify-center overflow-hidden">
                            <img src="<?php echo htmlspecialchars($rel_img); ?>"
                                alt="<?php echo htmlspecialchars($rel['name']); ?>"
                                class="w-full h-full object-contain p-6 group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-1"><?php echo htmlspecialchars($rel['brand_name']); ?></span>
                            <h3 class="font-bold text-zinc-950 text-sm mb-3 leading-snug flex-grow"><?php echo htmlspecialchars($rel['name']); ?></h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-black text-zinc-950">$<?php echo number_format($rel['price'], 2); ?></span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 group-hover:text-zinc-950 transition-colors flex items-center gap-1">
                                    View <span class="material-symbols-outlined text-sm">north_east</span>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>

<script>
    // Gallery image switcher
    function setMainImage(src, btn) {
        document.getElementById('mainProductImage').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('border-zinc-950');
            b.classList.add('border-zinc-200');
        });
        btn.classList.remove('border-zinc-200');
        btn.classList.add('border-zinc-950');
    }

    // Wishlist toggle
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (wishlistBtn) {
        const icon = wishlistBtn.querySelector('.material-symbols-outlined');
        let wishlisted = false;
        wishlistBtn.addEventListener('click', () => {
            wishlisted = !wishlisted;
            icon.style.fontVariationSettings = wishlisted ? "'FILL' 1" : "'FILL' 0";
            wishlistBtn.classList.toggle('border-red-400', wishlisted);
            wishlistBtn.classList.toggle('text-red-500', wishlisted);
        });
    }

    // Add to Cart — real API call
    const cartBtn = document.getElementById('addToCartBtn');
    if (cartBtn) {
        cartBtn.addEventListener('click', async () => {
            // Prevent double-clicks
            if (cartBtn.disabled) return;
            cartBtn.disabled = true;

            const original = cartBtn.innerHTML;
            cartBtn.innerHTML = '<span class="material-symbols-outlined text-xl animate-spin">progress_activity</span> Adding...';

            try {
                const data = new FormData();
                data.append('product_id', '<?php echo $product["id"]; ?>');
                data.append('quantity', '1');

                const res = await fetch('func/add-cart.php', {
                    method: 'POST',
                    body: data
                });
                const json = await res.json();

                if (json.success) {
                    // Success state
                    cartBtn.innerHTML = '<span class="material-symbols-outlined text-xl" style="font-variation-settings:\'FILL\' 1">check_circle</span> Added!';
                    cartBtn.classList.add('bg-emerald-600');
                    cartBtn.classList.remove('bg-zinc-950', 'hover:bg-zinc-800');

                    // Update all cart badges in the navbar
                    document.querySelectorAll('.cart-badge-count').forEach(badge => {
                        badge.textContent = json.cart_count;
                        badge.classList.remove('hidden');
                    });

                    showToast(json.message, 'success');

                    setTimeout(() => {
                        cartBtn.innerHTML = original;
                        cartBtn.classList.remove('bg-emerald-600');
                        cartBtn.classList.add('bg-zinc-950', 'hover:bg-zinc-800');
                        cartBtn.disabled = false;
                    }, 2000);
                } else {
                    // Handle login redirect
                    if (json.login_required) {
                        showToast('Please log in to add items to your cart.', 'error');
                        setTimeout(() => {
                            window.location.href = 'login-page.php';
                        }, 1500);
                    } else {
                        showToast(json.error || 'Could not add to cart.', 'error');
                    }
                    cartBtn.innerHTML = original;
                    cartBtn.disabled = false;
                }
            } catch (err) {
                showToast('Something went wrong. Please try again.', 'error');
                cartBtn.innerHTML = original;
                cartBtn.disabled = false;
            }
        });
    }

    // Toast notification
    function showToast(message, type) {
        // Remove existing toast
        const existing = document.getElementById('cartToast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'cartToast';
        toast.className = 'fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl text-sm font-bold uppercase tracking-widest transform translate-y-4 opacity-0 transition-all duration-300';

        if (type === 'success') {
            toast.classList.add('bg-emerald-600', 'text-white');
            toast.innerHTML = '<span class="material-symbols-outlined text-xl" style="font-variation-settings:\'FILL\' 1">check_circle</span>' + message;
        } else {
            toast.classList.add('bg-red-600', 'text-white');
            toast.innerHTML = '<span class="material-symbols-outlined text-xl" style="font-variation-settings:\'FILL\' 1">error</span>' + message;
        }

        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        // Animate out after 3s
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>