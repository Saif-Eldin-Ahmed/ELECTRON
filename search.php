<?php
// Page headers
$page_title = "ELECTRON | Search Results";
$body_class = "bg-background font-body-md text-on-background selection:bg-secondary-container";
include 'includes/header.php';

// Establish Database Connection
try {
    $pdo = getDBConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 1. Parse Parameters
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Category Filter Map
$category_map = [
    'smartphone' => [3, 10, 11, 12],
    'audio' => [2, 8, 9],
    'computing' => [1, 6, 7]
];
$selected_categories = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'] : [];

$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : null;

$camera = isset($_GET['camera']) ? $_GET['camera'] : '';
$battery = isset($_GET['battery']) && is_array($_GET['battery']) ? $_GET['battery'] : [];

$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'Best Quality';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$limit = 12;

// 2. Build SQL Queries
$where_clauses = ["p.status = 'published'"];
$bindings = [];

if ($search !== '') {
    $where_clauses[] = "(p.name LIKE :search_name OR p.description LIKE :search_desc)";
    $bindings[':search_name'] = '%' . $search . '%';
    $bindings[':search_desc'] = '%' . $search . '%';
}

if (!empty($selected_categories)) {
    $allowed_ids = [];
    foreach ($selected_categories as $cat_slug) {
        if (isset($category_map[$cat_slug])) {
            $allowed_ids = array_merge($allowed_ids, $category_map[$cat_slug]);
        }
    }
    if (!empty($allowed_ids)) {
        $allowed_ids = array_unique($allowed_ids);
        $placeholders = [];
        foreach ($allowed_ids as $idx => $id) {
            $key = ':cat_' . $idx;
            $placeholders[] = $key;
            $bindings[$key] = $id;
        }
        $where_clauses[] = "p.category_id IN (" . implode(',', $placeholders) . ")";
    }
}

if ($min_price !== null) {
    $where_clauses[] = "p.price >= :min_price";
    $bindings[':min_price'] = $min_price;
}
if ($max_price !== null) {
    $where_clauses[] = "p.price <= :max_price";
    $bindings[':max_price'] = $max_price;
}

if ($camera === '50') {
    $where_clauses[] = "(p.description LIKE '%50MP%' OR p.description LIKE '%108MP%' OR p.description LIKE '%200MP%' OR p.specifications LIKE '%50MP%' OR p.specifications LIKE '%108MP%' OR p.specifications LIKE '%200MP%')";
} elseif ($camera === '108') {
    $where_clauses[] = "(p.description LIKE '%108MP%' OR p.description LIKE '%200MP%' OR p.specifications LIKE '%108MP%' OR p.specifications LIKE '%200MP%')";
}

if (!empty($battery)) {
    $battery_conds = [];
    if (in_array('4500', $battery)) {
        $battery_conds[] = "(p.specifications LIKE '%5000%mAh%' OR p.specifications LIKE '%20000%mAh%')";
    }
    if (in_array('5000', $battery)) {
        $battery_conds[] = "(p.specifications LIKE '%5000%mAh%' OR p.specifications LIKE '%20000%mAh%')";
    }
    if (!empty($battery_conds)) {
        $where_clauses[] = "(" . implode(' OR ', $battery_conds) . ")";
    }
}

$where_sql = implode(' AND ', $where_clauses);

// Determine Sorting
$order_sql = "ORDER BY p.id ASC";
if ($sort === 'Popular') {
    $order_sql = "ORDER BY p.stock_quantity DESC";
} elseif ($sort === 'Newest') {
    $order_sql = "ORDER BY p.created_at DESC";
} elseif ($sort === 'Price: Low to High') {
    $order_sql = "ORDER BY p.price ASC";
}

// Get total results for pagination
$count_query = "SELECT COUNT(*) FROM products p WHERE " . $where_sql;
$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute($bindings);
$total_results = intval($stmt_count->fetchColumn());

$total_pages = ceil($total_results / $limit);
if ($total_pages < 1) {
    $total_pages = 1;
}
if ($page > $total_pages) {
    $page = $total_pages;
}
$offset = ($page - 1) * $limit;

// Fetch products for current page
$query = "SELECT p.*, c.name as category_name, b.name as brand_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          LEFT JOIN brands b ON p.brand_id = b.id 
          WHERE {$where_sql} 
          {$order_sql} 
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
foreach ($bindings as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Mobile Filter Backdrop -->
<div id="filterBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" onclick="closeFilterDrawer()"></div>

<!-- Mobile Filter Drawer -->
<aside id="filterDrawer" class="fixed top-0 left-0 h-full w-80 max-w-[85vw] bg-white dark:bg-zinc-950 border-r border-zinc-100 dark:border-zinc-900 z-50 overflow-y-auto transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="p-6">
        <!-- Drawer Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-zinc-100 dark:border-zinc-900">
            <h2 class="font-headline-md text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-widest">Filters</h2>
            <button onclick="closeFilterDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                <span class="material-symbols-outlined text-zinc-500 dark:text-zinc-400">close</span>
            </button>
        </div>
        <form action="search.php" method="GET" id="filterFormMobile" class="space-y-8">
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

            <div class="flex justify-end mb-2">
                <a href="search.php?q=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="text-xs font-medium text-zinc-400 hover:text-zinc-950 dark:hover:text-white transition-colors">Clear All</a>
            </div>

            <div class="space-y-8">
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Categories</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="smartphone" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('smartphone', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Smartphone</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="audio" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('audio', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Audio</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="computing" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('computing', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Computing</span>
                        </label>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Price Range</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between gap-4">
                            <input name="min_price" class="w-1/2 text-xs border-zinc-200 dark:border-zinc-800 dark:bg-zinc-900 rounded p-2 focus:ring-0 focus:border-zinc-950 focus:dark:border-white" placeholder="Min" type="number" step="0.01" value="<?php echo $min_price !== null ? htmlspecialchars($min_price) : ''; ?>" />
                            <input name="max_price" class="w-1/2 text-xs border-zinc-200 dark:border-zinc-800 dark:bg-zinc-900 rounded p-2 focus:ring-0 focus:border-zinc-950 focus:dark:border-white" placeholder="Max" type="number" step="0.01" value="<?php echo $max_price !== null ? htmlspecialchars($max_price) : ''; ?>" />
                        </div>
                        <button type="submit" class="w-full text-xs font-bold uppercase tracking-widest bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 py-2 rounded hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors">Apply</button>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Camera Spec</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="camera" value="50" class="w-5 h-5 border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="radio" <?php echo $camera === '50' ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 50MP</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="camera" value="108" class="w-5 h-5 border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="radio" <?php echo $camera === '108' ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 108MP</span>
                        </label>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Battery Life</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="battery[]" value="4500" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('4500', $battery) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 4500mAh</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="battery[]" value="5000" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('5000', $battery) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 5000mAh</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</aside>

<main class="max-w-[1440px] mx-auto flex px-6 lg:px-12 pt-32 pb-10 gap-gutter min-h-screen">
    <!-- Desktop Sidebar Filters -->
    <aside class="hidden lg:flex flex-col w-64 sticky top-32 h-fit bg-white dark:bg-zinc-950 p-6 rounded-lg border border-zinc-100 dark:border-zinc-900">
        <form action="search.php" method="GET" id="filterForm" class="space-y-8">
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

            <div class="mb-8">
                <div class="flex justify-between items-center mb-1">
                    <h2 class="font-headline-md text-sm font-bold text-zinc-900 dark:text-white uppercase tracking-widest">Filters</h2>
                    <a href="search.php?q=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="text-xs font-medium text-zinc-400 hover:text-zinc-950 dark:hover:text-white transition-colors">Clear All</a>
                </div>
                <p class="text-xs text-zinc-400">Refine Results</p>
            </div>

            <div class="space-y-8">
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Categories</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="smartphone" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('smartphone', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Smartphone</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="audio" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('audio', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Audio</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="category[]" value="computing" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('computing', $selected_categories) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">Computing</span>
                        </label>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Price Range</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between gap-4">
                            <input name="min_price" class="w-1/2 text-xs border-zinc-200 dark:border-zinc-800 dark:bg-zinc-900 rounded p-2 focus:ring-0 focus:border-zinc-950 focus:dark:border-white" placeholder="Min" type="number" step="0.01" value="<?php echo $min_price !== null ? htmlspecialchars($min_price) : ''; ?>" />
                            <input name="max_price" class="w-1/2 text-xs border-zinc-200 dark:border-zinc-800 dark:bg-zinc-900 rounded p-2 focus:ring-0 focus:border-zinc-950 focus:dark:border-white" placeholder="Max" type="number" step="0.01" value="<?php echo $max_price !== null ? htmlspecialchars($max_price) : ''; ?>" />
                        </div>
                        <button type="submit" class="w-full text-xs font-bold uppercase tracking-widest bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 py-2 rounded hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors">Apply</button>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Camera Spec</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="camera" value="50" class="w-5 h-5 border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="radio" <?php echo $camera === '50' ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 50MP</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="camera" value="108" class="w-5 h-5 border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="radio" <?php echo $camera === '108' ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 108MP</span>
                        </label>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900 dark:text-white">Battery Life</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="battery[]" value="4500" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('4500', $battery) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 4500mAh</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="battery[]" value="5000" class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-700 text-secondary focus:ring-secondary" type="checkbox" <?php echo in_array('5000', $battery) ? 'checked' : ''; ?> onchange="this.form.submit()" />
                            <span class="text-sm text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&gt;= 5000mAh</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </aside>

    <!-- Main Content -->
    <section class="flex-1">
        <!-- Summary & Sort -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <!-- Mobile Filter Trigger Button -->
            <button onclick="openFilterDrawer()" class="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm font-bold uppercase tracking-widest text-zinc-700 dark:text-zinc-300 hover:border-zinc-950 dark:hover:border-white hover:text-zinc-950 dark:hover:text-white transition-colors mb-4 md:mb-0">
                <span class="material-symbols-outlined text-lg">tune</span>
                Filters
            </button>
            <div>
                <h1 class="font-headline-lg text-2xl font-bold text-zinc-950 dark:text-white mb-1">
                    <?php
                    if ($search !== '') {
                        echo 'Search Results';
                    } else {
                        $start_idx = $total_results > 0 ? $offset + 1 : 0;
                        $end_idx = min($offset + $limit, $total_results);
                        echo "Showing {$start_idx}–{$end_idx} of {$total_results} results";
                    }
                    ?>
                </h1>
                <p class="text-zinc-500 text-sm">for "<?php echo $search !== '' ? htmlspecialchars($search) : 'All Products'; ?>"</p>
            </div>
            <div class="flex gap-4">
                <div class="relative inline-block text-left">
                    <form action="search.php" method="GET" id="sortForm" class="inline">
                        <?php
                        foreach ($_GET as $key => $value) {
                            if ($key !== 'sort' && $key !== 'page') {
                                if (is_array($value)) {
                                    foreach ($value as $v) {
                                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($v) . '">';
                                    }
                                } else {
                                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                }
                            }
                        }
                        ?>
                        <select name="sort" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded px-4 py-2 pr-10 text-sm font-medium text-zinc-700 dark:text-zinc-300 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 cursor-pointer">
                            <option value="Best Quality" <?php echo $sort === 'Best Quality' ? 'selected' : ''; ?>>Best Quality</option>
                            <option value="Popular" <?php echo $sort === 'Popular' ? 'selected' : ''; ?>>Popular</option>
                            <option value="Newest" <?php echo $sort === 'Newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="Price: Low to High" <?php echo $sort === 'Price: Low to High' ? 'selected' : ''; ?>>Price: Low to High</option>
                        </select>
                    </form>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-700 dark:text-zinc-300">
                        <span class="material-symbols-outlined text-sm" data-icon="expand_more">expand_more</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <?php if (empty($products)): ?>
                <div class="col-span-full py-20 text-center flex flex-col items-center justify-center bg-white dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-lg">
                    <span class="material-symbols-outlined text-6xl text-zinc-300 mb-4" data-icon="search_off">search_off</span>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-1">No Products Found</h3>
                    <p class="text-zinc-500 text-sm">Try adjusting your search query or filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $product_imgs = json_decode($product['imgs'], true);
                    $primary_img = 'assets/prdctImgs/Default.png';
                    if (is_array($product_imgs) && !empty($product_imgs)) {
                        $primary_img = $product_imgs[0];
                    }

                    $category_name = $product['category_name'] ?: 'General';
                    $brand_name = $product['brand_name'] ?: 'Generic';

                    // Generate deterministic ratings and reviews
                    $review_count = ($product['id'] * 17) % 150 + 10;
                    $rating = 4.0 + (($product['id'] * 3) % 11) / 10.0;
                    ?>
                    <article onclick="window.location.href='product.php?id=<?php echo $product['id']; ?>'" class="bg-white dark:bg-zinc-950 rounded-lg overflow-hidden border border-zinc-100 dark:border-zinc-900 product-card-shadow flex flex-col cursor-pointer transition-transform duration-300 hover:-translate-y-1">
                        <div class="relative aspect-square bg-zinc-50 dark:bg-zinc-900 group">
                            <img alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" src="<?php echo htmlspecialchars($primary_img); ?>" />
                            <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Sale</span>
                                </div>
                            <?php elseif ($product['stock_quantity'] <= 5): ?>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-orange-500 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Low Stock</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1"><?php echo htmlspecialchars($category_name); ?> | <?php echo htmlspecialchars($brand_name); ?></span>
                            <h3 class="font-headline-md text-lg font-bold text-zinc-950 dark:text-white mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <div class="flex items-center gap-1 mb-4">
                                <div class="flex text-yellow-500">
                                    <?php
                                    $full_stars = floor($rating);
                                    $has_half = ($rating - $full_stars) >= 0.5;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $full_stars) {
                                            echo '<span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: \'FILL\' 1;">star</span>';
                                        } elseif ($i == $full_stars + 1 && $has_half) {
                                            echo '<span class="material-symbols-outlined text-sm" data-icon="star_half" data-weight="fill" style="font-variation-settings: \'FILL\' 1;">star_half</span>';
                                        } else {
                                            echo '<span class="material-symbols-outlined text-sm" data-icon="star">star</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="text-xs text-zinc-400 font-medium">(<?php echo $review_count; ?>)</span>
                            </div>
                            <div class="mt-auto flex justify-between items-end">
                                <div class="flex flex-col">
                                    <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                                        <span class="text-xs text-zinc-400 line-through">$<?php echo number_format($product['compare_at_price'], 2); ?></span>
                                    <?php endif; ?>
                                    <p class="text-xl font-black text-zinc-950 dark:text-white">$<?php echo number_format($product['price'], 2); ?></p>
                                </div>
                                <a href="product.php?id=<?php echo $product['id']; ?>" onclick="event.stopPropagation();" class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 dark:border-white pb-0.5 hover:text-zinc-500 hover:border-zinc-500 transition-colors">Details</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="mt-20 flex justify-center items-center gap-4">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="w-12 h-12 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 rounded-full text-zinc-950 dark:text-white hover:border-zinc-950 dark:hover:border-white hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                        <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                    </a>
                <?php else: ?>
                    <button class="w-12 h-12 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 rounded-full text-zinc-300 dark:text-zinc-700 cursor-not-allowed" disabled>
                        <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                    </button>
                <?php endif; ?>

                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <button class="w-12 h-12 flex items-center justify-center bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 rounded-full font-label-bold"><?php echo $i; ?></button>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="w-12 h-12 flex items-center justify-center text-zinc-500 hover:text-zinc-950 dark:hover:text-white hover:bg-zinc-50 dark:hover:bg-zinc-900 rounded-full font-label-bold transition-colors"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $total_pages): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="w-12 h-12 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 rounded-full text-zinc-950 dark:text-white hover:border-zinc-950 dark:hover:border-white hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors">
                        <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                    </a>
                <?php else: ?>
                    <button class="w-12 h-12 flex items-center justify-center border border-zinc-200 dark:border-zinc-800 rounded-full text-zinc-300 dark:text-zinc-700 cursor-not-allowed" disabled>
                        <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
    function openFilterDrawer() {
        const drawer = document.getElementById('filterDrawer');
        const backdrop = document.getElementById('filterBackdrop');
        drawer.classList.remove('-translate-x-full');
        drawer.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeFilterDrawer() {
        const drawer = document.getElementById('filterDrawer');
        const backdrop = document.getElementById('filterBackdrop');
        drawer.classList.remove('translate-x-0');
        drawer.classList.add('-translate-x-full');
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }
</script>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>