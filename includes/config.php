<?php
// ============================================================
//  Database Configuration — PDO Connection
// ============================================================


define('DB_HOST', 'Place your host here');
define('DB_PORT', 'Place The Database port');
define('DB_USER', 'Place The Database username');
define('DB_PASS', 'Place The Database password');
define('DB_NAME', 'Place The Database name');


function getDBConnection(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}
