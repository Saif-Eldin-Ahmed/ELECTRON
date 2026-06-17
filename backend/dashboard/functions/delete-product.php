<?php
// ============================================================
//  dashboard/functions/delete-product.php — Delete Product API
//  Accepts POST requests and permanently removes a product.
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

// Only admins may delete
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
    exit;
}

try {
    $pdo = getDBConnection();

    // Confirm the product exists first
    $check = $pdo->prepare("SELECT id, name FROM products WHERE id = :id LIMIT 1");
    $check->execute([':id' => $id]);
    $product = $check->fetch();

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
        exit;
    }

    // Remove any cart references first (FK safety)
    $pdo->prepare("DELETE FROM cart_items WHERE product_id = :id")->execute([':id' => $id]);

    // Delete the product
    $pdo->prepare("DELETE FROM products WHERE id = :id")->execute([':id' => $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Product "' . htmlspecialchars($product['name']) . '" deleted.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
