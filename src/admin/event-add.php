<?php
session_start();
require_once __DIR__ . '../../connection.php';
if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: src/login.php");  
    exit();
} 

if(!isset($_POST['event_id'])){
    header("location:event-manage.php");
}

$targetDir = "banner/";
$targetFile = $targetDir . basename($_FILES['banner']['name']);
move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile);

$bannerUrl = $_FILES['banner']['name'];
$bannername = $_FILES['banner']['name'];

$stmt = $connection->query("SELECT MAX(Event_ID) AS id FROM events");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $lastId = $row['id']; 
    $numericPart = (int)substr($lastId, 1); 
    $nextNumericPart = $numericPart + 1; 
    $nextId = 'E' . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT); 
} else {
    $nextId = 'E0001';
}

$query = "INSERT INTO events VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $connection->prepare($query);
$stmt->execute([$nextId, 
$_POST['event_name'],
    $_POST['status'],
    $_POST['date'],
    $_POST['time'],
    $_POST['location'],
    $_POST['description'],
    0,
    $_POST['max_capacity'],
    $bannername,
    $bannerUrl
]);

header("location:event-manage.php");

?>