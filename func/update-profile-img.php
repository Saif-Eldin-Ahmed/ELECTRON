<?php
// ============================================================
//  func/update-profile-img.php — Upload & Update Profile Image
//  Accepts POST (multipart) with a 'photo' file field.
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/config.php';
require_once '../includes/storage-upload.php';

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
    echo json_encode(['success' => false, 'error' => 'Please log in first.']);
    exit;
}



if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit.',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form size limit.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
    ];
    $code = $_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE;
    $msg  = $uploadErrors[$code] ?? 'Upload failed.';
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

$file     = $_FILES['photo'];
$maxBytes = 3 * 1024 * 1024;

if ($file['size'] > $maxBytes) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Image must be under 3 MB.']);
    exit;
}

$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
$allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

if (!in_array($mimeType, $allowed)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Only JPEG, PNG, WebP, or GIF images are allowed.']);
    exit;
}

$ext      = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'][$mimeType];
$userId   = intval($_SESSION['id']);
$filename = 'user_' . $userId . '_' . time() . '.' . $ext;

$uploadResult = uploadToCloudinary($file['tmp_name'], 'users');

if (!$uploadResult['success']) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save image. Check server permissions.']);
    exit;
}

try {
    $pdo = getDBConnection();

    $old = $pdo->prepare("SELECT pro_img FROM users WHERE id = :id LIMIT 1");
    $old->execute([':id' => $userId]);
    $oldPath = $old->fetchColumn();

    $stmt = $pdo->prepare("UPDATE users SET pro_img = :img WHERE id = :id");
    $stmt->execute([':img' => $uploadResult['url'], ':id' => $userId]);

    if ($oldPath && $oldPath !== 'assets/proImgs/Default.jpg') {
        @unlink($oldPath);
    }

    $_SESSION['pro_img'] = $uploadResult['url'];

    echo json_encode(['success' => true, 'new_src' => $uploadResult['url']]);
} catch (PDOException $e) {
    @unlink($uploadResult['url']);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
