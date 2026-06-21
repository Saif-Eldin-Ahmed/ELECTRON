<?php
// ============================================================
//  Cloudinary Upload Helper
// ============================================================

define('CLOUDINARY_CLOUD_NAME', 'dam41zcfu');
define('CLOUDINARY_API_KEY', '272493645737237');
define('CLOUDINARY_API_SECRET', '4uj8nFrxpHSh_9X-9wdkDSxh9RA');

function uploadToCloudinary(string $filePath, string $folder): array
{
    if (!file_exists($filePath)) {
        return ['success' => false, 'url' => null, 'error' => 'File not found.'];
    }

    $timestamp = time();

    $paramsToSign = [
        'folder'    => $folder,
        'timestamp' => $timestamp,
    ];

    ksort($paramsToSign);
    $signatureString = '';
    foreach ($paramsToSign as $key => $value) {
        $signatureString .= $key . '=' . $value . '&';
    }
    $signatureString = rtrim($signatureString, '&') . CLOUDINARY_API_SECRET;
    $signature = sha1($signatureString);

    $postFields = [
        'file'      => new CURLFile($filePath),
        'api_key'   => CLOUDINARY_API_KEY,
        'timestamp' => $timestamp,
        'signature' => $signature,
        'folder'    => $folder,
    ];

    $url = 'https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/upload';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return ['success' => false, 'url' => null, 'error' => 'cURL error: ' . $curlError];
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200 || !isset($result['secure_url'])) {
        $errorMsg = $result['error']['message'] ?? 'Unknown Cloudinary upload error.';
        return ['success' => false, 'url' => null, 'error' => $errorMsg];
    }

    return [
        'success'    => true,
        'url'        => $result['secure_url'],
        'public_id'  => $result['public_id'],
        'error'      => null,
    ];
}
