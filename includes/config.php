<?php
// ============================================================
//  Database Configuration — PDO Connection
// ============================================================


define("DB_HOST", getenv(DB_HOST));
define("DB_PORT", getenv(DB_PORT));
define("DB_NAME", getenv(DB_NAME));
define("DB_USER", getenv(DB_USER));
define("DB_PASS", getenv(DB_PASS));

function getDBConnection(): PDO
{

    $dsn = "mysql:host=" . DB_HOST  . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    return new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
}
