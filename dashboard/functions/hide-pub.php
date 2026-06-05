<?php

// ============================================================
// =============== Hide or Publish functions ==================
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../../includes/config.php';


// Only allow POST requests

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

$id     = $_POST['id'];
$status = $_POST['status'];
$order  = $_POST['order'];

if ($status == $order) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'Product is already in the desired state.']);
    exit;
}

// DB connection
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare(
        'UPDATE `products` SET `status` = :status WHERE `id` = :id'
    );
    if ($order == 'hide') {
        $stmt->execute([
            ':status' => 'draft',
            ':id' => $id,
        ]);
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Product hidden successfully.']);
    } else {
        $stmt->execute([
            ':status' => 'published',
            ':id' => $id,
        ]);
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Product published successfully.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
