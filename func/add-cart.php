<?php
// ============================================================
//  func/add-cart.php — Add to Cart Handler
//  Accepts POST requests, adds product to the user's cart
//  and decrements the product's stock_quantity accordingly.
// ============================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

// Start session to identify the user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please log in to add items to your cart.', 'login_required' => true]);
    exit;
}

$user_id    = intval($_SESSION['id']);
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity   = isset($_POST['quantity'])   ? intval($_POST['quantity'])   : 1;

// Validate inputs
if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
    exit;
}

if ($quantity <= 0) {
    $quantity = 1;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Lock the product row for the duration of the transaction
    $stmt = $pdo->prepare("SELECT id, stock_quantity, name FROM products WHERE id = :id AND status = 'published' LIMIT 1 FOR UPDATE");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
        exit;
    }

    // Check if item is already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id LIMIT 1");
    $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // How many extra units are being added on top of what's already in the cart
        $new_qty   = $existing['quantity'] + $quantity;
        $delta     = $quantity; // units being added now

        // Check stock
        if ($new_qty > $product['stock_quantity']) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => "Only {$product['stock_quantity']} units available. You already have {$existing['quantity']} in your cart."
            ]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :qty WHERE id = :id");
        $stmt->execute([':qty' => $new_qty, ':id' => $existing['id']]);
    } else {
        $delta = $quantity; // all units are new

        // Check stock
        if ($quantity > $product['stock_quantity']) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => "Only {$product['stock_quantity']} units available."
            ]);
            exit;
        }

        // Insert new cart item
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :qty)");
        $stmt->execute([
            ':user_id'    => $user_id,
            ':product_id' => $product_id,
            ':qty'        => $quantity
        ]);
    }

    // Decrement stock by the number of units just added
    $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - :delta WHERE id = :id");
    $stmt->execute([':delta' => $delta, ':id' => $product_id]);

    $pdo->commit();

    // Get updated cart count
    $stmt = $pdo->prepare("SELECT SUM(quantity) AS total FROM cart_items WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $cart_total = intval($stmt->fetchColumn());

    http_response_code(200);
    echo json_encode([
        'success'    => true,
        'message'    => htmlspecialchars($product['name']) . ' added to cart!',
        'cart_count' => $cart_total
    ]);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
