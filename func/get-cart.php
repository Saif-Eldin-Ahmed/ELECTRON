<?php
// ============================================================
//  includes/get-cart.php — Fetch Cart Items API
//  Returns the current logged-in user's cart items as JSON.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is not logged in, return a guest status code/flag
if (!isset($_SESSION['id'])) {
    echo json_encode([
        'success'        => false,
        'login_required' => true,
        'message'        => 'Please log in to view your cart.'
    ]);
    exit;
}

$user_id = intval($_SESSION['id']);

try {
    $pdo = getDBConnection();

    // Query cart items with product info
    $stmt = $pdo->prepare("
        SELECT 
            c.product_id, 
            c.quantity, 
            p.name, 
            p.price, 
            p.stock_quantity, 
            p.imgs 
        FROM cart_items c
        INNER JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
        ORDER BY c.id DESC
    ");
    $stmt->execute([':user_id' => $user_id]);
    $raw_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $items = [];
    $subtotal = 0;
    $cart_count = 0;

    foreach ($raw_items as $item) {
        $price = floatval($item['price']);
        $qty = intval($item['quantity']);
        $line_total = $price * $qty;

        $subtotal += $line_total;
        $cart_count += $qty;

        // Decode product images and get primary
        $product_imgs = json_decode($item['imgs'], true);
        $primary_img = 'assets/prdctImgs/Default.png';
        if (is_array($product_imgs) && !empty($product_imgs)) {
            $primary_img = $product_imgs[0];
        }

        $items[] = [
            'product_id'     => intval($item['product_id']),
            'quantity'       => $qty,
            'name'           => $item['name'],
            'price'          => $price,
            'line_total'     => $line_total,
            'stock_quantity' => intval($item['stock_quantity']),
            'image'          => $primary_img
        ];
    }

    echo json_encode([
        'success'    => true,
        'items'      => $items,
        'subtotal'   => $subtotal,
        'cart_count' => $cart_count
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage()
    ]);
}
