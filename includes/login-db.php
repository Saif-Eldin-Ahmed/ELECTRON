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

    // Get User's IP Address
    function getUserIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Take the first IP in the list
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return trim($ip);
    }

    // Updates Users IP Address
    if (getUserIP() !== $user['last_login_ip']) {
        $stmt = $pdo->prepare("UPDATE `users` SET `last_login_ip` = :ip WHERE `id` = :id");
        $stmt->execute([':ip' => getUserIP(), ':id' => $user['id']]);
        $user['last_login_ip'] = getUserIP();
    }

    // Update Last Log in Timestamp
    $stmt = $pdo->prepare("UPDATE `users` SET `last_login_at` = NOW() WHERE `id` = :id");
    $stmt->execute([':id' => $user['id']]);
    $user['last_login_at'] = date('Y-m-d H:i:s');

    // Phone Number Normalization
    if ($user['phone'] === null) {
        $user['phone'] = 'N/A';
    }

    // Success — you can now create a session or return user details
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'firstname' => $user['firstname'],
        'lastname'  => $user['lastname']
    ]);

    session_start();

    $_SESSION['id']             = $user['id'];
    $_SESSION['email']          = $user['email'];
    $_SESSION['firstname']      = $user['firstname'];
    $_SESSION['lastname']       = $user['lastname'];
    $_SESSION['phone']          = $user['phone'];
    $_SESSION['created_at']     = $user['created_at'];
    $_SESSION['last_login_at']  = $user['last_login_at'];
    $_SESSION['last_login_ip']  = $user['last_login_ip'];
    $_SESSION['pro_img']        = $user['pro_img'];
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
