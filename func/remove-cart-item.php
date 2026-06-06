<?php
// ============================================================
//  includes/remove-cart-item.php — Remove Cart Item API
//  Deletes a product item from the user's cart.
// ============================================================

header('Content-Type: application/json');

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please log in first.', 'login_required' => true]);
    exit;
}

$user_id    = intval($_SESSION['id']);
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

if ($product_id <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Delete item
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([
        ':user_id'    => $user_id,
        ':product_id' => $product_id
    ]);

    // Restore stock
    $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE id = :product_id");
    $stmt->execute([
        ':quantity'   => $quantity,
        ':product_id' => $product_id
    ]);

    // Get updated total quantity and subtotal of the cart
    $stmt = $pdo->prepare("
        SELECT 
            SUM(c.quantity) AS total_qty,
            SUM(c.quantity * p.price) AS subtotal
        FROM cart_items c
        INNER JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute([':user_id' => $user_id]);
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);

    $cart_count = intval($totals['total_qty']);
    $subtotal   = floatval($totals['subtotal']);

    echo json_encode([
        'success'    => true,
        'cart_count' => $cart_count,
        'subtotal'   => $subtotal,
        'message'    => 'Item removed from cart.'
    ]);
    $pdo->commit();
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
