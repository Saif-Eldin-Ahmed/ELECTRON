<?php
// ============================================================
//  dashboard/edit.php — Edit Product Page
// ============================================================

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

$acc = true; // Exclude front-end navbar
require_once '/includes/config.php';

try {
    $pdo = getDBConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch categories and brands for options
$categories = $pdo->query("SELECT * FROM categories ORDER BY parent_id ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);
$brands = $pdo->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$product_id) {
    header("Location: /dashboard/index.php");
    exit;
}

// Fetch current product data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

// Parse current images and specifications
$current_imgs = json_decode($product['imgs'] ?: '[]', true) ?: [];
$current_specs = json_decode($product['specifications'] ?: '[]', true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize fields
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $compare_at_price = !empty($_POST['compare_at_price']) ? floatval($_POST['compare_at_price']) : null;
    $description = trim($_POST['description'] ?? '');
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $low_stock_limit = intval($_POST['low_stock_limit'] ?? 5);
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $brand_id = !empty($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $status = trim($_POST['status'] ?? 'draft');
    $warranty_months = !empty($_POST['warranty_months']) ? intval($_POST['warranty_months']) : null;
    $weight_grams = !empty($_POST['weight_grams']) ? intval($_POST['weight_grams']) : null;
    $dimensions = trim($_POST['dimensions'] ?? '') ?: null;

    // Build Specifications JSON
    $spec_keys = $_POST['spec_keys'] ?? [];
    $spec_vals = $_POST['spec_vals'] ?? [];
    $specs_array = [];
    foreach ($spec_keys as $idx => $key) {
        $key = trim($key);
        $val = trim($spec_vals[$idx] ?? '');
        if ($key !== '' && $val !== '') {
            $specs_array[$key] = $val;
        }
    }
    $specifications_json = json_encode($specs_array);

    // Validate requirements
    if (empty($name)) {
        $error = "Product name is required.";
    } elseif ($price <= 0) {
        $error = "Product price must be greater than 0.";
    } else {
        // Generate slug if empty
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        }

        // Handle Image edits/removals
        $keep_imgs = $_POST['keep_imgs'] ?? [];
        $image_paths = [];

        // Retain only selected previous images
        foreach ($current_imgs as $img_path) {
            if (in_array($img_path, $keep_imgs)) {
                $image_paths[] = $img_path;
            }
        }

        $upload_dir = '/assets/prdctImgs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Helper function to process single upload
        $process_upload = function ($file_input_name) use ($upload_dir, &$error) {
            if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES[$file_input_name]['tmp_name'];
                $file_name = $_FILES[$file_input_name]['name'];
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $allowed)) {
                    $new_filename = 'img_' . uniqid() . '.' . $ext;
                    $dest = $upload_dir . $new_filename;
                    if (move_uploaded_file($file_tmp, $dest)) {
                        return '/assets/prdctImgs/' . $new_filename;
                    } else {
                        $error = "Failed to move uploaded file: {$file_name}";
                    }
                } else {
                    $error = "Unsupported image format: {$ext}";
                }
            }
            return null;
        };

        // 1. Process New Primary Image (if uploaded)
        $new_primary_path = $process_upload('primary_image');
        if ($new_primary_path) {
            // Replace the primary image (first element or insert at beginning)
            if (!empty($image_paths)) {
                $image_paths[0] = $new_primary_path;
            } else {
                $image_paths[] = $new_primary_path;
            }
        } elseif (empty($image_paths)) {
            // If all images were removed, fall back to Default
            $image_paths[] = '/assets/prdctImgs/Default.png';
        }

        // 2. Process New Gallery Images
        if (isset($_FILES['gallery_images'])) {
            $total_files = count($_FILES['gallery_images']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['gallery_images']['tmp_name'][$i];
                    $file_name = $_FILES['gallery_images']['name'][$i];
                    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (in_array($ext, $allowed)) {
                        $new_filename = 'img_' . uniqid() . '.' . $ext;
                        $dest = $upload_dir . $new_filename;
                        if (move_uploaded_file($file_tmp, $dest)) {
                            $image_paths[] = 'assets/prdctImgs/' . $new_filename;
                        }
                    }
                }
            }
        }

        $imgs_json = json_encode($image_paths);

        if (empty($error)) {
            // Database Update
            try {
                $pdo->beginTransaction();

                $stmtUpdate = $pdo->prepare(
                    "UPDATE `products` SET
                        `name` = :name,
                        `slug` = :slug,
                        `description` = :description,
                        `price` = :price,
                        `compare_at_price` = :compare_at_price,
                        `sku` = :sku,
                        `stock_quantity` = :stock_quantity,
                        `low_stock_limit` = :low_stock_limit,
                        `brand_id` = :brand_id,
                        `category_id` = :category_id,
                        `status` = :status,
                        `imgs` = :imgs,
                        `specifications` = :specifications,
                        `warranty_months` = :warranty_months,
                        `weight_grams` = :weight_grams,
                        `dimensions` = :dimensions,
                        `updated_by` = :updated_by
                    WHERE `id` = :id"
                );

                $stmtUpdate->execute([
                    ':name' => $name,
                    ':slug' => $slug,
                    ':description' => $description,
                    ':price' => $price,
                    ':compare_at_price' => $compare_at_price,
                    ':sku' => $sku ?: null,
                    ':stock_quantity' => $stock_quantity,
                    ':low_stock_limit' => $low_stock_limit,
                    ':brand_id' => $brand_id,
                    ':category_id' => $category_id,
                    ':status' => $status,
                    ':imgs' => $imgs_json,
                    ':specifications' => $specifications_json ?: '{}',
                    ':warranty_months' => $warranty_months,
                    ':weight_grams' => $weight_grams,
                    ':dimensions' => $dimensions,
                    ':updated_by' => intval($_SESSION['id']),
                    ':id' => $product_id
                ]);

                // Sync product_images table
                $stmtDelImgs = $pdo->prepare("DELETE FROM `product_images` WHERE `product_id` = :pid");
                $stmtDelImgs->execute([':pid' => $product_id]);

                foreach ($image_paths as $idx => $path) {
                    $is_primary = ($idx === 0) ? 1 : 0;
                    $stmtImg = $pdo->prepare(
                        "INSERT INTO `product_images` (`product_id`, `image_url`, `is_primary`, `sort_order`) 
                        VALUES (:pid, :url, :is_primary, :sort)"
                    );
                    $stmtImg->execute([
                        ':pid' => $product_id,
                        ':url' => $path,
                        ':is_primary' => $is_primary,
                        ':sort' => $idx
                    ]);
                }

                $pdo->commit();
                $success = "Product updated successfully!";

                // Refresh local data
                $stmt->execute([':id' => $product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                $current_imgs = json_decode($product['imgs'] ?: '[]', true) ?: [];
                $current_specs = json_decode($product['specifications'] ?: '[]', true) ?: [];
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Database update failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ELECTRON | Edit Product</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="/assets/js/tailwind-config.js"></script>
    <link href="/assets/css/style.css" rel="stylesheet" />
    <style>
        .glass-card {
            background: rgba(24, 24, 27, 0.65);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body class="bg-zinc-950 text-zinc-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 border-r border-zinc-800 bg-zinc-950/80 backdrop-blur-md flex flex-col justify-between hidden md:flex">
        <div>
            <!-- Brand Logo -->
            <div class="h-24 flex items-center px-8 border-b border-zinc-800">
                <a href="../index.php" class="text-2xl font-black tracking-tighter text-white font-['Space_Grotesk'] uppercase flex items-center gap-2">
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
            <span class="material-symbols-outlined text-2xl text-zinc-400">account_circle</span>
            <div class="overflow-hidden">
                <p class="text-xs font-bold uppercase tracking-wider text-white truncate">Administrator</p>
                <p class="text-[10px] text-zinc-500 truncate">admin@electron.com</p>
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
                <h1 class="font-['Space_Grotesk'] text-xl font-bold tracking-tight text-white">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h1>
            </div>
        </header>

        <!-- Form container -->
        <section class="p-8 max-w-5xl">
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-950/40 border border-red-900/60 rounded-xl text-red-200 text-xs font-semibold uppercase tracking-wider">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-emerald-950/40 border border-emerald-900/60 rounded-xl text-emerald-200 text-xs font-semibold uppercase tracking-wider">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form action="/dashboard/edit.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Info Section -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Basic Info</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Product Name *</label>
                            <input type="text" name="name" required oninput="generateSlug(this.value)" value="<?php echo htmlspecialchars($product['name']); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. Apple iPhone 15 Pro Max">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">URL Slug (Auto-generated)</label>
                            <input type="text" id="slugInput" name="slug" value="<?php echo htmlspecialchars($product['slug']); ?>" class="w-full bg-zinc-900 border border-zinc-850 rounded-lg py-2.5 px-4 text-xs font-medium text-zinc-400 focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. apple-iphone-15-pro-max">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">SKU Code</label>
                            <input type="text" name="sku" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. AP-IP15PM-256G">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Category *</label>
                            <select name="category_id" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-bold uppercase tracking-widest text-zinc-300 focus:outline-none focus:border-zinc-500 focus:ring-0 cursor-pointer">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
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
                                    <option value="<?php echo $brand['id']; ?>" <?php echo ($product['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0 placeholder-zinc-650" placeholder="Product details, features, description..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Pricing & Inventory</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Price ($ USD) *</label>
                            <input type="number" step="0.01" required name="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0.00">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Compare At Price ($ USD)</label>
                            <input type="number" step="0.01" name="compare_at_price" value="<?php echo htmlspecialchars($product['compare_at_price'] ?? ''); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0.00">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Stock Quantity *</label>
                            <input type="number" required name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="0">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Low Stock Limit *</label>
                            <input type="number" required name="low_stock_limit" value="<?php echo htmlspecialchars($product['low_stock_limit']); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="5">
                        </div>
                    </div>
                </div>

                <!-- Specs & Media -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Media & Specifications</h2>

                    <!-- Current Images -->
                    <?php if (!empty($current_imgs)): ?>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Current Images (Select to keep)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                <?php foreach ($current_imgs as $idx => $img_path): ?>
                                    <div class="relative bg-zinc-900 border border-zinc-800 rounded-lg p-2 flex flex-col items-center gap-2">
                                        <img class="w-20 h-20 object-contain rounded" src="../<?php echo htmlspecialchars($img_path); ?>" alt="">
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <input type="checkbox" name="keep_imgs[]" value="<?php echo htmlspecialchars($img_path); ?>" checked class="rounded bg-zinc-950 border-zinc-800 text-white focus:ring-0 cursor-pointer">
                                            <span class="text-[9px] uppercase tracking-wider font-bold text-zinc-400">
                                                <?php echo ($idx === 0) ? 'Primary' : 'Gallery'; ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Images Upload -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Replace Primary Image</label>
                            <input type="file" name="primary_image" class="w-full bg-zinc-900 border border-zinc-805 rounded-lg py-2 px-3 text-xs text-zinc-400 cursor-pointer focus:outline-none">
                            <p class="text-[10px] text-zinc-500">Upload a new image to replace the primary listing image.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Add Gallery Images</label>
                            <input type="file" name="gallery_images[]" multiple class="w-full bg-zinc-900 border border-zinc-805 rounded-lg py-2 px-3 text-xs text-zinc-400 cursor-pointer focus:outline-none">
                            <p class="text-[10px] text-zinc-500">Hold ctrl/cmd to select multiple supplementary images to append.</p>
                        </div>
                    </div>

                    <!-- Dynamic Spec Builder -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Specifications (Key-Value)</label>
                            <button type="button" onclick="addSpecRow()" class="text-[10px] font-bold uppercase tracking-widest bg-zinc-800 hover:bg-zinc-700 text-white py-1 px-3 rounded-md transition-all">Add Spec</button>
                        </div>

                        <div id="specifications_container" class="space-y-3">
                            <?php if (!empty($current_specs)): ?>
                                <?php foreach ($current_specs as $key => $val): ?>
                                    <div class="flex items-center gap-3 spec-row">
                                        <input type="text" name="spec_keys[]" value="<?php echo htmlspecialchars($key); ?>" placeholder="Key (e.g. screen)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-semibold text-white focus:outline-none">
                                        <input type="text" name="spec_vals[]" value="<?php echo htmlspecialchars($val); ?>" placeholder="Value (e.g. 6.7 inches)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-medium text-white focus:outline-none">
                                        <button type="button" onclick="removeSpecRow(this)" class="w-10 h-10 bg-zinc-900/50 hover:bg-red-950/40 border border-zinc-800 hover:border-red-900/40 text-zinc-400 hover:text-red-400 flex items-center justify-center rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="flex items-center gap-3 spec-row">
                                    <input type="text" name="spec_keys[]" placeholder="Key (e.g. screen)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-semibold text-white focus:outline-none">
                                    <input type="text" name="spec_vals[]" placeholder="Value (e.g. 6.7 inches)" class="w-1/2 bg-zinc-900 border border-zinc-800 rounded-lg py-2 px-3 text-xs font-medium text-white focus:outline-none">
                                    <button type="button" onclick="removeSpecRow(this)" class="w-10 h-10 bg-zinc-900/50 hover:bg-red-950/40 border border-zinc-800 hover:border-red-900/40 text-zinc-400 hover:text-red-400 flex items-center justify-center rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Attributes & Meta -->
                <div class="glass-card rounded-2xl p-8 space-y-6">
                    <h2 class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">Advanced Specifications & Settings</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Warranty (Months)</label>
                            <input type="number" name="warranty_months" value="<?php echo htmlspecialchars($product['warranty_months'] ?? ''); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 12">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Weight (Grams)</label>
                            <input type="number" name="weight_grams" value="<?php echo htmlspecialchars($product['weight_grams'] ?? ''); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 221">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400">Dimensions</label>
                            <input type="text" name="dimensions" value="<?php echo htmlspecialchars($product['dimensions'] ?? ''); ?>" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-medium text-white focus:outline-none focus:border-zinc-500 focus:ring-0" placeholder="e.g. 159.9 x 76.7 x 8.3 mm">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-8 pt-4">
                        <div class="flex items-center gap-3">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-400 mr-2">Status (Visibility)</label>
                            <select name="status" class="bg-zinc-900 border border-zinc-800 rounded-lg py-2.5 px-4 text-xs font-bold uppercase tracking-widest text-zinc-300 focus:outline-none cursor-pointer">
                                <option value="draft" <?php echo ($product['status'] === 'draft') ? 'selected' : ''; ?>>Draft / Hidden</option>
                                <option value="published" <?php echo ($product['status'] === 'published') ? 'selected' : ''; ?>>Published / Visible</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex items-center gap-2 px-8 py-3.5 text-xs font-bold uppercase tracking-widest bg-white text-zinc-950 rounded-full hover:bg-zinc-100 transition-all shadow-md active:scale-[0.98]">
                        <span class="material-symbols-outlined text-base font-bold">save</span>
                        Save Changes
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
    </script>
</body>

</html>