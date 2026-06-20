<?php
// ============================================================
//  dashboard/index.php — Admin Dashboard
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

// Fetch stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$published_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'published'")->fetchColumn();
$draft_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'draft'")->fetchColumn();
$low_stock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= low_stock_limit")->fetchColumn();

// Pagination & search
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where_clause = "1=1";
$bindings = [];
if ($search !== '') {
    $where_clause = "(p.name LIKE :search_name OR p.description LIKE :search_desc OR p.sku LIKE :search_sku)";
    $bindings[':search_name'] = '%' . $search . '%';
    $bindings[':search_desc'] = '%' . $search . '%';
    $bindings[':search_sku'] = '%' . $search . '%';
}

// Total search count
$count_query = "SELECT COUNT(*) FROM products p WHERE " . $where_clause;
$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute($bindings);
$total_filtered = $stmt_count->fetchColumn();
$total_pages = ceil($total_filtered / $limit);
if ($total_pages < 1) $total_pages = 1;
if ($page > $total_pages) $page = $total_pages;

// Fetch products
$query = "SELECT p.*, c.name AS category_name, b.name AS brand_name 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          LEFT JOIN brands b ON p.brand_id = b.id
          WHERE {$where_clause}
          ORDER BY p.id DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
foreach ($bindings as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page headers
$page_title = "ELECTRON | Admin Dashboard";
$body_class = "bg-zinc-950 text-white font-body-md min-h-screen";

// Include header (will include tailwind and fonts)
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="icon" href="../favicon.png" type="image/png">
    <script src="../assets/js/tailwind-config.js"></script>
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
                <a href="/dashboard/index.php" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white rounded-lg sidebar-item-active transition-all">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    Products
                </a>
                <a href="/dashboard/add-product.php" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-zinc-400 hover:text-white rounded-lg hover:bg-zinc-900 transition-all">
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
    <main class="flex-1 flex flex-col min-h-screen overflow-y-auto">
        <!-- Top Bar Header -->
        <header class="h-24 border-b border-zinc-800 flex items-center justify-between px-8 bg-zinc-950/80 backdrop-blur-md">
            <div>
                <h1 class="font-['Space_Grotesk'] text-xl font-bold tracking-tight text-white">Products Management</h1>
                <p class="text-xs text-zinc-400">Add, edit, or remove catalog items</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="add-product.php" class="flex items-center gap-2 px-5 py-2.5 text-xs font-bold uppercase tracking-widest bg-white text-zinc-950 rounded-lg hover:bg-zinc-100 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">add</span>
                    Add Product
                </a>
            </div>
        </header>

        <!-- Stats Grid -->
        <section class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat 1 -->
            <div class="glass-card rounded-2xl p-6 flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-zinc-900 flex items-center justify-center border border-zinc-800">
                    <span class="material-symbols-outlined text-zinc-400">inventory</span>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-1">Total Products</p>
                    <p class="text-2xl font-black text-white"><?php echo $total_products; ?></p>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="glass-card rounded-2xl p-6 flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-950/30 flex items-center justify-center border border-emerald-900/50">
                    <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-1">Published</p>
                    <p class="text-2xl font-black text-white"><?php echo $published_products; ?></p>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="glass-card rounded-2xl p-6 flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-amber-950/30 flex items-center justify-center border border-amber-900/50">
                    <span class="material-symbols-outlined text-amber-400">edit_document</span>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-1">Drafts</p>
                    <p class="text-2xl font-black text-white"><?php echo $draft_products; ?></p>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="glass-card rounded-2xl p-6 flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-red-950/30 flex items-center justify-center border border-red-900/50">
                    <span class="material-symbols-outlined text-red-400">warning</span>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-1">Low Stock</p>
                    <p class="text-2xl font-black text-white"><?php echo $low_stock; ?></p>
                </div>
            </div>
        </section>

        <!-- Product Table/List Section -->
        <section class="p-8 pt-0 flex-grow">
            <!-- Notifcations -->
            <div class="hidden mb-6 p-4 bg-red-950/40 border border-red-900/60 rounded-xl text-red-200 text-xs font-semibold uppercase tracking-wider" id="err-msg">
            </div>

            <div class="hidden mb-6 p-4 bg-emerald-950/40 border border-emerald-900/60 rounded-xl text-emerald-200 text-xs font-semibold uppercase tracking-wider" id="success-msg">
            </div>
            <div class="glass-card rounded-2xl overflow-hidden flex flex-col h-full">
                <!-- Table Header Options -->
                <div class="p-6 border-b border-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <form action="index.php" method="GET" class="relative w-full sm:w-80">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search product name, SKU..." class="w-full bg-zinc-900 border border-zinc-850 rounded-lg py-2 pl-4 pr-10 text-xs font-bold uppercase tracking-widest text-white placeholder-zinc-500 focus:outline-none focus:border-zinc-700 focus:ring-0 transition-colors" />
                        <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-white flex items-center">
                            <span class="material-symbols-outlined text-base">search</span>
                        </button>
                    </form>

                    <div class="text-xs text-zinc-500 font-medium">
                        Found <?php echo $total_filtered; ?> products
                    </div>
                </div>

                <!-- Main Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-800 bg-zinc-900/20 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                <th class="p-6">Product</th>
                                <th class="p-6">SKU / ID</th>
                                <th class="p-6">Category</th>
                                <th class="p-6">Brand</th>
                                <th class="p-6">Price</th>
                                <th class="p-6">Stock</th>
                                <th class="p-6">Status</th>
                                <th class="p-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-zinc-500 text-xs font-bold uppercase tracking-wider">
                                        No Products Found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                    $p_imgs = json_decode($product['imgs'], true);
                                    $p_img = (!empty($p_imgs) && is_array($p_imgs)) ? '../' . $p_imgs[0] : '../assets/prdctImgs/Default.png';
                                    ?>
                                    <tr id="product-row-<?php echo $product['id']; ?>" class="hover:bg-zinc-900/10 transition-colors">
                                        <td class="p-6 flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-lg bg-zinc-900 border border-zinc-800 overflow-hidden flex items-center justify-center p-1.5 flex-shrink-0">
                                                <img class="w-full h-full object-contain" src="<?php echo htmlspecialchars($p_img); ?>" alt="">
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-white"><?php echo htmlspecialchars($product['name']); ?></p>
                                                <p class="text-[10px] text-zinc-500">ID: <?php echo $product['id']; ?></p>
                                            </div>
                                        </td>
                                        <td class="p-6 text-xs text-zinc-400 font-mono">
                                            <?php echo htmlspecialchars($product['sku'] ?: 'N/A'); ?>
                                        </td>
                                        <td class="p-6 text-xs text-zinc-400">
                                            <?php echo htmlspecialchars($product['category_name'] ?: 'N/A'); ?>
                                        </td>
                                        <td class="p-6 text-xs text-zinc-400">
                                            <?php echo htmlspecialchars($product['brand_name'] ?: 'N/A'); ?>
                                        </td>
                                        <td class="p-6">
                                            <p class="text-xs font-bold text-white">$<?php echo number_format($product['price'], 2); ?></p>
                                            <?php if ($product['compare_at_price'] > $product['price']): ?>
                                                <p class="text-[10px] text-red-500 line-through font-medium">$<?php echo number_format($product['compare_at_price'], 2); ?></p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-6 ">
                                            <?php
                                            $qty = intval($product['stock_quantity']);
                                            $low = intval($product['low_stock_limit']);
                                            if ($qty === 0) {
                                                echo '<span class="px-2 py-1 bg-red-950/50 text-red-400 text-[10px] font-bold uppercase tracking-wider rounded border border-red-900/30 ">Out</span>';
                                            } elseif ($qty <= $low) {
                                                echo '<span class="px-2 py-1 bg-amber-950/50 text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded border border-amber-900/30 ">' . $qty . '</span>';
                                            } else {
                                                echo '<span class="px-2 py-1 bg-emerald-950/50 text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded border border-emerald-900/30">' . $qty . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="p-6">
                                            <?php if ($product['status'] === 'published'): ?>
                                                <span class="inline-flex items-center gap-1.5 text-xs text-emerald-400 font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Published
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 text-xs text-zinc-500 font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span> Draft
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-6 text-right whitespace-nowrap">
                                            <?php if ($product['status'] === 'published'): ?>
                                                <button onclick="pubProduct(<?php echo (isset($product['id'])) ? $product['id'] : ''; ?>, 'hide', '<?php echo (isset($product['status'])) ? $product['status'] : ''; ?>')" class="material-symbols-outlined text-xs font-bold text-zinc-400 hover:text-yellow-400 uppercase tracking-widest border border-zinc-800 hover:border-yellow-700 px-3 py-1.5 rounded-lg transition-colors">
                                                    visibility_off
                                                </button>
                                            <?php else: ?>
                                                <button onclick="pubProduct(<?php echo (isset($product['id'])) ? $product['id'] : ''; ?>, 'publish', '<?php echo (isset($product['status'])) ? $product['status'] : ''; ?>')" class="material-symbols-outlined text-xs font-bold text-zinc-400 hover:text-emerald-400 uppercase tracking-widest border border-zinc-800 hover:border-emerald-700 px-3 py-1.5 rounded-lg transition-colors">
                                                    visibility
                                                </button>
                                            <?php endif; ?>
                                            <a href="edit.php?id=<?php echo $product['id']; ?>" target="_blank" class="material-symbols-outlined text-xs font-bold text-zinc-400 hover:text-white uppercase tracking-widest border border-zinc-800 hover:border-zinc-700 px-3 py-1.5 rounded-lg transition-colors">
                                                edit
                                            </a>
                                            <?php if ($product['status'] === 'published'): ?>
                                                <a href="../product.php?id=<?php echo $product['id']; ?>" target="_blank" class="material-symbols-outlined text-xs font-bold text-zinc-400 hover:text-white uppercase tracking-widest border border-zinc-800 hover:border-zinc-700 px-3 py-1.5 rounded-lg transition-colors">
                                                    inventory_2
                                                </a>
                                            <?php else: ?>
                                                <a class="material-symbols-outlined text-xs font-bold text-zinc-800 uppercase tracking-widest border border-zinc-800  px-3 py-1.5 rounded-lg transition-colors cursor-default">
                                                    inventory_2
                                                </a>
                                            <?php endif; ?>
                                            <button onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars(addslashes($product['name'])); ?>')" class="material-symbols-outlined text-xs font-bold text-zinc-400 hover:text-red-400 uppercase tracking-widest border border-zinc-800 hover:border-red-800 px-3 py-1.5 rounded-lg transition-colors">
                                                delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="p-6 border-t border-zinc-800 flex justify-between items-center bg-zinc-900/10">
                        <div class="text-xs text-zinc-500 font-medium">
                            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                        </div>
                        <div class="flex gap-2">
                            <?php if ($page > 1): ?>
                                <a href="?q=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" class="px-3 py-1.5 bg-zinc-900 border border-zinc-800 text-xs font-bold uppercase tracking-wider text-zinc-300 hover:text-white rounded transition-colors">Prev</a>
                            <?php else: ?>
                                <button class="px-3 py-1.5 bg-zinc-950 border border-zinc-900 text-xs font-bold uppercase tracking-wider text-zinc-650 rounded cursor-not-allowed" disabled>Prev</button>
                            <?php endif; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?q=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" class="px-3 py-1.5 bg-zinc-900 border border-zinc-800 text-xs font-bold uppercase tracking-wider text-zinc-300 hover:text-white rounded transition-colors">Next</a>
                            <?php else: ?>
                                <button class="px-3 py-1.5 bg-zinc-950 border border-zinc-900 text-xs font-bold uppercase tracking-wider text-zinc-650 rounded cursor-not-allowed" disabled>Next</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>

<script>
    // Helper to dynamically resolve endpoints relative to the dashboard directory,
    // avoiding 404 errors caused by missing trailing slashes (e.g., /dashboard vs /dashboard/)

    async function pubProduct(id, order, status) {
        const data = new FormData()
        data.append('id', id)
        data.append('order', order)
        data.append('status', status)
        try {
            const res = await fetch('/dashboard/functions/hide-pub.php', {
                method: 'POST',
                body: data
            });
            const json = await res.json();

            if (json.success) {
                document.getElementById('success-msg').textContent = json.message;
                document.getElementById('success-msg').classList.remove('hidden');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                document.getElementById('err-msg').textContent = json.error;
                document.getElementById('err-msg').classList.remove('hidden');
            }

        } catch (err) {
            document.getElementById('err-msg').textContent = "Database error: " + err.message;
            document.getElementById('err-msg').classList.remove('hidden');
        }
    }

    async function deleteProduct(id, name) {
        if (!confirm(`Permanently delete "${name}"?\n\nThis cannot be undone.`)) return;

        const data = new FormData();
        data.append('id', id);

        try {
            const res = await fetch(getApiUrl('/dashboard/functions/delete-product.php'), {
                method: 'POST',
                body: data
            });
            const json = await res.json();

            if (json.success) {
                // Fade out the row, then remove it
                const row = document.getElementById('product-row-' + id);
                if (row) {
                    row.style.transition = 'opacity 0.4s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 420);
                }
                document.getElementById('success-msg').textContent = json.message;
                document.getElementById('success-msg').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('success-msg').classList.add('hidden');
                }, 3000);
            } else {
                document.getElementById('err-msg').textContent = json.error;
                document.getElementById('err-msg').classList.remove('hidden');
            }
        } catch (err) {
            document.getElementById('err-msg').textContent = "Network error: " + err.message;
            document.getElementById('err-msg').classList.remove('hidden');
        }
    }
</script>

</html>