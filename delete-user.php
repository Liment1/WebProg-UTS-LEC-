<?php
session_start();
require_once 'connection.php';

if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
} 

if(!isset($_POST['user_id'])){
    header("location:event-manage.php");
}

$query = "DELETE FROM users 
            WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute([$_POST['user_id']]);

header("location:user-manage.php");
?>