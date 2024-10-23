<?php
require_once __DIR__ . '../../connection.php';

session_start();
if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: ../Verify/login.php");  
    exit();
} 

if(!isset($_POST['event_id'])){
    header("location:event-manage.php");
}

if (!empty($_FILES['banner']['name'])) {

    $targetDir = "banner/";
    $targetFile = $targetDir . basename($_FILES['banner']['name']);
    move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile);

    $bannerUrl = $_FILES['banner']['name'];
    $bannername = $_FILES['banner']['name'];
} else {
   $bannerUrl = $_POST['default_banner_url'];
   $bannername = $_POST['default_banner_name'];
}

$query = "UPDATE events 
            SET event_name = ?, 
                description = ?, 
                max_participants = ?, 
                event_date = ?, 
                event_time = ?, 
                banner_url = ?,
                banner_name = ?,
                event_status = ?,
                location = ? 
            WHERE event_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute([
    $_POST['event_name'],
    $_POST['description'],
    $_POST['max_capacity'],
    $_POST['date'],
    $_POST['time'],
    $bannerUrl,
    $bannername,
    $_POST['status'],
    $_POST['location'],
    'E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT)
]);

header("location:event-manage.php");
