<?php
// ============================================================
//  dashboard/functions/add-product.php — Add Product API
//  Accepts POST requests and adds a new product.
// ============================================================

header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/storage-upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

try {
    $pdo = getDBConnection();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

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

if (empty($name)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Product name is required.']);
    exit;
} elseif ($price <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Product price must be greater than 0.']);
    exit;
}

if (empty($slug)) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
}

$image_paths = [];


$error = '';

$process_upload = function ($file_input_name) use (&$error) {
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES[$file_input_name]['tmp_name'];
        $file_name = $_FILES[$file_input_name]['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed)) {
            $uploadResult = uploadToCloudinary($file_tmp, "products");
            if (!$uploadResult['success']) {
                $error = "Failed to move uploaded file: {$file_name}.";
            } else {
                return $uploadResult['url'];
            }
        } else {
            $error = "Unsupported image format: {$ext}";
        }
    }
    return null;
};

$primary_path = $process_upload('primary_image');
if ($primary_path) {
    $image_paths[] = $primary_path;
} else {
    $primary_path = 'assets/prdctImgs/Default.png';
    $image_paths[] = $primary_path;
}

if (isset($_FILES['gallery_images'])) {
    $total_files = count($_FILES['gallery_images']['name']);
    for ($i = 0; $i < $total_files; $i++) {
        if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['gallery_images']['tmp_name'][$i];
            $file_name = $_FILES['gallery_images']['name'][$i];
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $uploadResult = uploadToCloudinary($file_tmp, "products");
                if (!$uploadResult['success']) {
                    $error = "Failed to move gallery image file: {$file_name}.";
                } else {
                    $image_paths[] = $uploadResult['url'];
                }
            } else {
                $error = "Unsupported gallery image format: {$ext}";
            }
        }
    }
}

if (!empty($error)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}

$imgs_json = json_encode($image_paths);

// Database Insertion
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO `products` (
            `name`, `slug`, `description`, `price`, `compare_at_price`, `sku`, 
            `stock_quantity`, `low_stock_limit`, `brand_id`, `category_id`, 
            `status`, `imgs`, `specifications`, `warranty_months`, `weight_grams`, 
            `dimensions`, `created_at`, `added_by`, `updated_by`
        ) VALUES (
            :name, :slug, :description, :price, :compare_at_price, :sku, 
            :stock_quantity, :low_stock_limit, :brand_id, :category_id, 
            :status, :imgs, :specifications, :warranty_months, :weight_grams, 
            :dimensions, NOW(), :added_by, :updated_by
        )"
    );

    $stmt->execute([
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
        ':added_by' => intval($_SESSION['id']),
        ':updated_by' => intval($_SESSION['id'])
    ]);

    $product_id = $pdo->lastInsertId();

    // Save to product_images
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
    echo json_encode([
        'success' => true,
        'message' => "Product added successfully! ID: {$product_id}",
        'product_id' => $product_id
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => "Database insertion failed: " . $e->getMessage()]);
}
