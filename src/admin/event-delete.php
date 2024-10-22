<?php
require_once __DIR__ . '../../connection.php';
if(!isset($_POST['event_id'])){
    header("location:event-manage.php");
}

// delete image form database
$stmt = $connection->prepare("SELECT banner_url FROM events WHERE event_id = ?");
$stmt->execute([ 'E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT)]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $imageFile = $row['banner_url'];
    $imagePath = 'banner/' . $imageFile;
    echo $imagePath;

    if (file_exists($imagePath)) {
        unlink($imagePath);  
    }
}


$query = "DELETE FROM events 
            WHERE event_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute(['E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT)]);

header("location:event-manage.php");
?>