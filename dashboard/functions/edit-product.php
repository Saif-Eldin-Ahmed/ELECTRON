<?php
// ============================================================
//  dashboard/functions/edit-product.php — Edit Product API
//  Accepts POST requests and updates a product.
// ============================================================

header('Content-Type: application/json');

require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only admins may edit products
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

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$product_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid or missing product ID.']);
    exit;
}

// Fetch current product data to verify it exists and parse its current state
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Product not found.']);
    exit;
}

$current_imgs = json_decode($product['imgs'] ?: '[]', true) ?: [];

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
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Product name is required.']);
    exit;
} elseif ($price <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Product price must be greater than 0.']);
    exit;
}

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

$upload_dir = CLOUDINARY_URL;
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$error = '';

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
                return CLOUDINARY_URL . $new_filename;
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
    $image_paths[] = 'assets/prdctImgs/Default.png';
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
                    $image_paths[] = CLOUDINARY_URL . $new_filename;
                } else {
                    $error = "Failed to move gallery image file: {$file_name}";
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
    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully!',
        'product_id' => $product_id
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $e->getMessage()]);
}
