<?php
// $connection = new PDO("mysql:host=localhost;dbname=evef9533_Eventreg;", "evef9533_admin", "kl@E5DauaxB{");

try {
    $connection = new PDO("mysql:host=localhost;dbname=evef9533_Eventreg", "evef9533_admin", "kl@E5DauaxB{");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>