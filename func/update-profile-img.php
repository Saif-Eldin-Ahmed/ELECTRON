<?php
// ============================================================
//  func/update-profile-img.php — Upload & Update Profile Image
//  Accepts POST (multipart) with a 'photo' file field.
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
    echo json_encode(['success' => false, 'error' => 'Please log in first.']);
    exit;
}

// ---- Validate the upload -----------------------------------------
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
$maxBytes = 3 * 1024 * 1024; // 3 MB

if ($file['size'] > $maxBytes) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Image must be under 3 MB.']);
    exit;
}

// Validate MIME type from actual file content (not trusting the browser header)
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
$uploadDir = __DIR__ . '/../assets/proImgs/';
$destPath  = $uploadDir . $filename;
$publicPath = 'assets/proImgs/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save image. Check server permissions.']);
    exit;
}

// ---- Persist to database -----------------------------------------
try {
    $pdo = getDBConnection();

    // Fetch old image path to delete it (skip default)
    $old = $pdo->prepare("SELECT pro_img FROM users WHERE id = :id LIMIT 1");
    $old->execute([':id' => $userId]);
    $oldPath = $old->fetchColumn();

    $stmt = $pdo->prepare("UPDATE users SET pro_img = :img WHERE id = :id");
    $stmt->execute([':img' => $publicPath, ':id' => $userId]);

    // Delete old uploaded file (but not the default placeholder)
    if ($oldPath && $oldPath !== 'assets/proImgs/Default.jpg') {
        $fullOldPath = __DIR__ . '/../' . $oldPath;
        if (file_exists($fullOldPath)) {
            @unlink($fullOldPath);
        }
    }

    // Keep session in sync
    $_SESSION['pro_img'] = $publicPath;

    echo json_encode(['success' => true, 'new_src' => $publicPath]);

} catch (PDOException $e) {
    // Upload succeeded but DB failed — clean up the file
    @unlink($destPath);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
