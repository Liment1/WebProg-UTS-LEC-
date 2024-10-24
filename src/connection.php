<?php
// Connection details from the Azure PostgreSQL server
$host = 'events3webprog-server.postgres.database.azure.com';
$dbname = 'events3webprog-database';
$username = 'aqzctiokdc';  // Provided username from the Azure details
$password = 'oKCCpJJyD$XqEevb';  // Replace with your actual password

// Create a PDO instance for PostgreSQL
try {
    $connection = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception for debugging purposes
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to PostgreSQL on Azure.";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// $connection = new PDO("mysql:host=localhost;dbname=Eventreg;", "root", "");

?>
