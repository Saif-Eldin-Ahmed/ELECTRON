<?php

echo "This is PHP";
include "includes/header.php";

try {
    $pdo = getDBConnection();
    echo "connected";
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>


<h1>This is HTML</h1>
</body>

</html>