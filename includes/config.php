<?php
// ============================================================
//  Database Configuration — PDO Connection
// ============================================================


define('DB_HOST', 'mysql.railway.internal');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', 'RMeWegmKCighsoyculHNZWFcpDlDzDGY');
define('DB_NAME', 'railway');
define('CLOUDINARY_API_KEY', '272493645737237');
define('CLOUDINARY_API_SECRET', '4uj8nFrxpHSh_9X-9wdkDSxh9RA');
define('CLOUDINARY_NAME', 'dam41zcfu');
define('CLOUDINARY_URL', 'cloudinary://' . CLOUDINARY_API_KEY . ':' . CLOUDINARY_API_SECRET . '@' . CLOUDINARY_NAME . '/');

/**
 * Returns an active PDO connection.
 * Auto-creates the database and users table if they do not exist.
 */
function getDBConnection(): PDO
{
    // Step 3 — Now connect with the target database selected
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}
