<?php
// ============================================================
//  register-db.php — Registration Handler
//  Accepts POST requests and inserts a new user into the DB.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once 'config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

// ---- Collect & Sanitize Input --------------------------------
$fullname = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email']    ?? '');
$phone    = trim($_POST['phone']    ?? '');
$password =       $_POST['password'] ?? '';

// ---- Server-Side Validation ----------------------------------
$errors = [];

if (empty($fullname)) {
    $errors[] = 'Full name is required.';
} elseif (strlen($fullname) < 2) {
    $errors[] = 'Full name must be at least 2 characters.';
} elseif (strlen($fullname) > 255) {
    $errors[] = 'Full name is too long.';
}

if (empty($email)) {
    $errors[] = 'Email address is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
} elseif (strlen($email) > 255) {
    $errors[] = 'Email address is too long.';
}

if (!empty($phone)) {
    if (!preg_match('/^\+[1-9]\d{6,14}$/', $phone)) {
        $errors[] = 'Phone number must start with + and country code, followed by digits (e.g., +1234567890).';
    }
} else {
    $phone = null;
}

if (empty($password)) {
    $errors[] = 'Password is required.';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => implode(' ', $errors)]);
    exit;
}

// ---- Database Operation ---------------------------------------
try {
    $pdo = getDBConnection();

    // Hash the password securely using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    // PDO Prepared Statement — prevents SQL injection entirely
    $stmt = $pdo->prepare(
        'INSERT INTO `users` (`fullname`, `email`, `phone`, `password`) VALUES (:fullname, :email, :phone, :password)'
    );

    $stmt->execute([
        ':fullname' => $fullname,
        ':email'    => $email,
        ':phone'    => $phone,
        ':password' => $hashedPassword,
    ]);

    $newId = $pdo->lastInsertId();

    if ($phone === null) {
        $phone = 'N/A';
    }
    // Return success with the inserted record details
    echo json_encode([
        'success'  => true,
        'message'  => 'Registration successful! Welcome, ' . htmlspecialchars($fullname) . '.',
        'record'   => [
            'id'         => $newId,
            'fullname'   => $fullname,
            'email'      => $email,
            'phone'      => $phone,
            'created_at' => date('Y-m-d H:i:s'),
        ],
    ]);
} catch (PDOException $e) {
    // Handle duplicate entry (unique constraint violations)
    if ($e->getCode() === '23000') {
        $msg = 'A user with this email or username already exists.';
        if (str_contains($e->getMessage(), 'email')) {
            $msg = 'An account with this email address already exists.';
        }
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => $msg]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
}
