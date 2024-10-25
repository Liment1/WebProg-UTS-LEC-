<?php
session_start();
require_once 'connection.php';

if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
} 

if(!isset($_POST['event_id'])){
    header("location:event-manage.php");
}

$stmt = $connection->prepare("SELECT banner_url FROM events WHERE event_id = ?");
$stmt->execute([ 'E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT)]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $imageFile = $row['banner_url'];
    $imagePath = 'banner/' . $imageFile;

    if (file_exists($imagePath)) {
        unlink($imagePath);  
    }
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


$query = "DELETE FROM events 
            WHERE event_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute(['E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT)]);

header("location:event-manage.php");
?>