<?php

require 'connection.php';

if(isset($_POST)){
    $stmt = $connection->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $token]);
}


header("Location: reset_password.php")

?>