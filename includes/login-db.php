<?php
// ============================================================
//  login-db.php — Login Handler
//  Accepts POST requests and logs in a user.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}


// Get user-submitted data
$email    = trim($_POST['email']    ?? '');
$password =       $_POST['password'] ?? '';

$errors = [];

if (empty($email)) {
    $errors[] = 'Email is required.';
}

if (empty($password)) {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => implode(' ', $errors)]);
    exit;
}

try {
    $pdo = getDBConnection();

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Invalid email or password.']);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Invalid email or password.']);
        exit;
    }

    // Success — you can now create a session or return user details
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'user' => [
            'id'         => $user['id'],
            'firstname'  => $user['firstname'],
            'lastname'   => $user['lastname'],
            'email'      => $user['email'],
            'phone'      => $user['phone'],
            'created_at' => $user['created_at'],
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
