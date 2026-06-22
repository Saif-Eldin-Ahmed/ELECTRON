<?php
if (session_status() === PHP_SESSION_NONE) {
    // Keep the user logged in for 24 hours (86 400 seconds)
    $session_lifetime = 86400;
    ini_set('session.gc_maxlifetime', $session_lifetime);
    session_set_cookie_params([
        'lifetime' => $session_lifetime,
        'path'     => '/',
        'secure'   => false,   // change to true when using HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();

    // Server-side expiry check: destroy the session if it's older than 24 h
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $session_lifetime) {
        $_SESSION = [];
        session_destroy();
        session_start(); // restart a clean session for the current request
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo isset($page_title) ? $page_title : 'ELECTRON | Digital Flagship'; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="icon" href="favicon.png">
    <script src="assets/js/tailwind-config.js"></script>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="<?php echo isset($body_class) ? $body_class : 'min-h-[100vh] bg-background text-on-background font-body-md selection:bg-secondary-container'; ?>">
    <div id="page-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">
        <div class="loader z-[99999]">
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
            <div class="loader-block"></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('load', function() {
                document.getElementById('page-loader').style.translate = '-100%';
            });
        });
    </script>
    <?php
    if (!isset($acc)) {
        include 'nav.php';
    }
    ?>