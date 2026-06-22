<?php
// ============================================================
//  Database Configuration — PDO Connection
// ============================================================

require_once __DIR__ . '/env.php';
loadEnv();
define("DB_HOST", "mysql.railway.internal");
define("DB_PORT", "3306");
define("DB_NAME", "railway");
define("DB_USER", "root");
define("DB_PASS", "RMeWegmKCighsoyculHNZWFcpDlDzDGY");

function getDBConnection(): PDO
{

    $dsn = "mysql:host=" . DB_HOST  . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    return new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
}
