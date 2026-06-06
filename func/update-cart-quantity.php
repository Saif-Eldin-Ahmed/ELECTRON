<?php
// ============================================================
//  includes/update-cart-quantity.php — Update Cart Quantity API
//  Increments or decrements the quantity of a product in the cart.
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
$action     = isset($_POST['action']) ? trim($_POST['action']) : ''; // 'increment' or 'decrement'

if ($product_id <= 0 || !in_array($action, ['increment', 'decrement'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Lock the product row for the duration of the transaction
    $stmt = $pdo->prepare("SELECT stock_quantity, name FROM products WHERE id = :id AND status = 'published' LIMIT 1 FOR UPDATE");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
        exit;
    }

    // Get current item in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id LIMIT 1");
    $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Item not in cart.']);
        exit;
    }

    $current_qty = intval($existing['quantity']);

    if ($action === 'increment') {
        $new_qty = $current_qty + 1;
        if ($new_qty > intval($product['stock_quantity'])) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => "Only {$product['stock_quantity']} units available in stock."
            ]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :qty WHERE id = :id");
        $stmt->execute([':qty' => $new_qty, ':id' => $existing['id']]);

        // Decrement stock by 1
        $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - 1 WHERE id = :id")
            ->execute([':id' => $product_id]);

    } else { // decrement
        $new_qty = $current_qty - 1;
        if ($new_qty <= 0) {
            // Remove item if quantity falls to 0 or less
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = :id");
            $stmt->execute([':id' => $existing['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :qty WHERE id = :id");
            $stmt->execute([':qty' => $new_qty, ':id' => $existing['id']]);
        }

        // Restore 1 unit back to stock (whether item was removed or just decremented)
        $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + 1 WHERE id = :id")
            ->execute([':id' => $product_id]);
    }

    $pdo->commit();

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
        'subtotal'   => $subtotal
    ]);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
