<?php
// ============================================================
//  includes/checkout.php — Mock Checkout API
//  Clears the user's cart to simulate successful checkout.
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
    echo json_encode(['success' => false, 'error' => 'Please log in to complete checkout.', 'login_required' => true]);
    exit;
}

$user_id = intval($_SESSION['id']);

try {
    $pdo = getDBConnection();

    // 1. Fetch current cart to make sure it's not empty
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart_items WHERE user_id = :uid");
    $stmt->execute([':uid' => $user_id]);
    $count = intval($stmt->fetchColumn());

    if ($count === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Your cart is already empty.']);
        exit;
    }

    // 2. Mock order placement: delete all items from cart
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = :uid");
    $stmt->execute([':uid' => $user_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
