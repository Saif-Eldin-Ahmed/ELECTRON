<?php

echo "This is PHP";
include "includes/config.php";
include "includes/header.php";

try {
    $pdo = getDBConnection();
    echo "connected";
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
</head>

<body>
    <h1>This is HTML</h1>
</body>

</html>