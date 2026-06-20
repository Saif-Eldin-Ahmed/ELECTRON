<?php
// ============================================================
//  dashboard/add-product.php — Add Product Page
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

require_once '/includes/config.php';

try {
    $pdo = getDBConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch categories and brands for options
$categories = $pdo->query("SELECT * FROM categories ORDER BY parent_id ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);
$brands = $pdo->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ELECTRON | Add Product</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="/assets/js/tailwind-config.js"></script>
    <link href="/dashboard/style.css" rel="stylesheet" />
</head>

<body class="bg-zinc-950 text-zinc-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 border-r border-zinc-800 bg-zinc-950/80 backdrop-blur-md flex flex-col justify-between hidden md:flex">
        <div>
            <!-- Brand Logo -->
            <div class="h-24 flex items-center px-8 border-b border-zinc-800">
                <a href="/index.php" class="text-2xl font-black tracking-tighter text-white font-['Space_Grotesk'] uppercase flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-3xl">bolt</span>
                    ELECTRON
                </a>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-2">Management</p>
                <a href="/dashboard/index.php" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-zinc-400 hover:text-white rounded-lg hover:bg-zinc-900 transition-all">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    Products
                </a>
                <a href="/dashboard/add-product.php" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white rounded-lg sidebar-item-active transition-all">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    Add Product
                </a>
                <a href="/search.php" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-zinc-400 hover:text-white rounded-lg hover:bg-zinc-900 transition-all">
                    <span class="material-symbols-outlined text-lg">storefront</span>
                    Store Front
                </a>
            </nav>
        </div>

        <!-- User Account Details -->
        <div class="p-6 border-t border-zinc-800 flex items-center gap-3">
            <img src="<?php echo $_SESSION['pro_img'] ? '../' . $_SESSION['pro_img'] : '../assets/proImgs/Default.jpg'; ?>" alt="Profile Picture" class="w-10 h-10 rounded-full">
            <div class="overflow-hidden">
                <p class="text-xs font-bold uppercase tracking-wider text-white truncate"><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></p>
                <p class="text-[10px] text-zinc-500 truncate"><?php echo $_SESSION['email']; ?></p>
            </div>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <main class="flex-grow flex flex-col min-h-screen overflow-y-auto">
        <!-- Top Bar Header -->
        <header class="h-24 border-b border-zinc-800 flex items-center justify-between px-8 bg-zinc-950/80 backdrop-blur-md">
            <div>
                <a href="/dashboard/index.php" class="text-xs font-bold uppercase tracking-widest text-zinc-500 hover:text-white flex items-center gap-1.5 mb-1">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Back to Catalog
                </a>
                <h1 class="font-['Space_Grotesk'] text-xl font-bold tracking-tight text-white">Add New Product</h1>
            </div>
        </header>

        <!-- Form container -->
        <section class="p-8 max-w-5xl">
            <!-- Dynamic Alert Containers -->
            <div id="error-alert" class="hidden mb-6 p-4 bg-red-950/40 border border-red-900/60 rounded-xl text-red-200 text-xs font-semibold uppercase tracking-wider"></div>
            <div id="success-alert" class="hidden mb-6 p-4 bg-emerald-950/40 border border-emerald-900/60 rounded-xl text-emerald-200 text-xs font-semibold uppercase tracking-wider"></div>

            <form id="addProductForm" enctype="multipart/form-data" class="space-y-8">
                <!-- Info Section -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Basic Info</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Product Name *</label>
                            <input type="text" name="name" required oninput="generateSlug(this.value)" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. Apple iPhone 15 Pro Max">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">URL Slug (Auto-generated)</label>
                            <input type="text" id="slugInput" name="slug" class="w-full bg-zinc-900 border border-zinc-850 rounded-lg py-2.5 px-4 text-xs font-medium text-zinc-400 focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. apple-iphone-15-pro-max">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">SKU Code</label>
                            <input type="text" name="sku" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. AP-IP15PM-256G">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Category *</label>
                            <select name="category_id" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-bold uppercase tracking-widest text-zinc-300 focus:outline-none focus:border-zinc-500 focus:ring-0 cursor-pointer">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo ($cat['parent_id'] ? '— ' : '') . htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Brand *</label>
                            <select name="brand_id" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-bold uppercase tracking-widest text-zinc-300 focus:outline-none focus:border-zinc-500 focus:ring-0 cursor-pointer">
                                <option value="">Select Brand</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0 placeholder-zinc-650" placeholder="Product details, features, description..."></textarea>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Pricing & Inventory</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Price ($ USD) *</label>
                            <input type="number" step="0.01" required name="price" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0.00">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Compare At Price ($ USD)</label>
                            <input type="number" step="0.01" name="compare_at_price" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0.00">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Stock Quantity *</label>
                            <input type="number" required name="stock_quantity" value="10" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Low Stock Limit *</label>
                            <input type="number" required name="low_stock_limit" value="5" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="5">
                        </div>
                    </div>
                </div>

                <!-- Specs & Media -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Media & Specifications</h2>

                    <!-- Images Upload -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Primary Product Image</label>
                            <input type="file" name="primary_image" class="w-full bg-zinc-900 border border-zinc-805 rounded-lg py-2 px-3 text-xs text-zinc-400 cursor-pointer focus:outline-none">
                            <p class="text-[10px] text-zinc-500">Main image displayed in listings and product head.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Gallery Images</label>
                            <input type="file" name="gallery_images[]" multiple class="w-full bg-zinc-900 border border-zinc-805 rounded-lg py-2 px-3 text-xs text-zinc-400 cursor-pointer focus:outline-none">
                            <p class="text-[10px] text-zinc-500">Hold ctrl/cmd to select multiple supplementary images.</p>
                        </div>
                    </div>

                    <!-- Dynamic Spec Builder -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Specifications (Key-Value)</label>
                            <button type="button" onclick="addSpecRow()" class="text-[10px] font-bold uppercase tracking-widest bg-zinc-800 hover:bg-zinc-700 text-white py-1 px-3 rounded-md transition-all">Add Spec</button>
                        </div>

                        <div id="specifications_container" class="space-y-3">
                            <div class="flex items-center gap-3 spec-row">
                                <input type="text" name="spec_keys[]" placeholder="Key (e.g. screen)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-semibold text-white focus:outline-none">
                                <input type="text" name="spec_vals[]" placeholder="Value (e.g. 6.7 inches)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-medium text-white focus:outline-none">
                                <button type="button" onclick="removeSpecRow(this)" class="w-10 h-10 bg-zinc-900/50 hover:bg-red-950/40 border border-zinc-800 hover:border-red-900/40 text-zinc-400 hover:text-red-400 flex items-center justify-center rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes & Meta -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Advanced Specifications & Settings</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Warranty (Months)</label>
                            <input type="number" name="warranty_months" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 12">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Weight (Grams)</label>
                            <input type="number" name="weight_grams" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 221">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Dimensions</label>
                            <input type="text" name="dimensions" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 159.9 x 76.7 x 8.3 mm">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-8 pt-4">
                        <div class="flex items-center gap-3">
                            <select name="status" class="bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-bold uppercase tracking-widest text-zinc-300 focus:outline-none cursor-pointer">
                                <option value="draft">Save as Draft</option>
                                <option value="published">Publish Immediately</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex items-center gap-2 px-8 py-3.5 text-xs font-bold uppercase tracking-widest bg-white text-zinc-950 rounded-full hover:bg-zinc-100 transition-all shadow-md active:scale-[0.98]">
                        <span class="material-symbols-outlined text-base font-bold">save</span>
                        Save Product
                    </button>
                    <a href="/dashboard/index.php" class="flex items-center gap-2 px-8 py-3.5 text-xs font-bold uppercase tracking-widest border border-zinc-800 text-zinc-400 hover:text-white rounded-full hover:bg-zinc-900 transition-all active:scale-[0.98]">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </main>

    <script>
        function generateSlug(text) {
            const slug = text
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '') // remove non-word chars
                .replace(/[\s_-]+/g, '-') // replace spaces/underscores with hyphens
                .replace(/^-+|-+$/g, ''); // trim leading/trailing hyphens
            document.getElementById('slugInput').value = slug;
        }

        function addSpecRow() {
            const container = document.getElementById('specifications_container');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 spec-row';
            row.innerHTML = `
                <input type="text" name="spec_keys[]" placeholder="Key (e.g. storage)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-semibold text-white focus:outline-none">
                <input type="text" name="spec_vals[]" placeholder="Value (e.g. 512GB)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-medium text-white focus:outline-none">
                <button type="button" onclick="removeSpecRow(this)" class="w-10 h-10 bg-zinc-900/50 hover:bg-red-950/40 border border-zinc-800 hover:border-red-900/40 text-zinc-400 hover:text-red-400 flex items-center justify-center rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-base">delete</span>
                </button>
            `;
            container.appendChild(row);
        }

        function removeSpecRow(button) {
            const row = button.closest('.spec-row');
            if (row) {
                row.remove();
            }
        }

        // Helper to dynamically resolve endpoints relative to the dashboard directory,
        // Intercept form submit and send via Fetch (AJAX)
        document.getElementById('addProductForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const errorAlert = document.getElementById('error-alert');
            const successAlert = document.getElementById('success-alert');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Clear previous alerts
            errorAlert.classList.add('hidden');
            successAlert.classList.add('hidden');

            // Disable submit button during requests
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            }

            try {
                const formData = new FormData(this);
                const response = await fetch('/dashboard/functions/add-product.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    successAlert.textContent = result.message;
                    successAlert.classList.remove('hidden');
                    this.reset();

                    // Reset custom specifications container
                    document.getElementById('slugInput').value = '';
                    document.getElementById('specifications_container').innerHTML = `
                        <div class="flex items-center gap-3 spec-row">
                            <input type="text" name="spec_keys[]" placeholder="Key (e.g. screen)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-semibold text-white focus:outline-none">
                            <input type="text" name="spec_vals[]" placeholder="Value (e.g. 6.7 inches)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-medium text-white focus:outline-none">
                            <button type="button" onclick="removeSpecRow(this)" class="w-10 h-10 bg-zinc-900/50 hover:bg-red-950/40 border border-zinc-800 hover:border-red-900/40 text-zinc-400 hover:text-red-400 flex items-center justify-center rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </div>
                    `;
                } else {
                    errorAlert.textContent = result.error || 'Failed to add product.';
                    errorAlert.classList.remove('hidden');
                }
            } catch (err) {
                errorAlert.textContent = 'An error occurred: ' + err.message;
                errorAlert.classList.remove('hidden');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                }
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>
</body>

</html>