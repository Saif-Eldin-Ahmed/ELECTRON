<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo isset($page_title) ? $page_title : 'ELECTRON | Digital Flagship'; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="assets/js/tailwind-config.js"></script>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="<?php echo isset($body_class) ? $body_class : 'bg-background text-on-background font-body-md selection:bg-secondary-container'; ?>">

    <?php
    if (!isset($home)) {
        include 'includes/nav.php';
    }
    ?>