<?php
require_once 'connection.php';

session_start();
if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'user') {
    header("Location:login.php");
    exit();
} 

if(!isset($_POST['event_id'])){
    header("location:index.php");
}

$curreventid =  'E'.str_pad($_POST['event_id'],4,"0",STR_PAD_LEFT);
$curruserid =  $_SESSION["user_id"];

$query = "UPDATE events 
            SET curr_participants = curr_participants-1 
            WHERE Event_id = ?";
$stmt = $connection->prepare($query);
echo "$curreventid";
$stmt->execute([$curreventid]);

$query = "DELETE FROM registrations WHERE user_id = ? AND Event_id =?";
$stmt = $connection->prepare($query);
$stmt->execute([$curruserid, $curreventid]);

header("location:../user/user-event.php");
