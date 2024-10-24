<?php
session_start();
require_once 'connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id']; 
$event_id = 'E' . str_pad($_POST['event_id'], 4, '0', STR_PAD_LEFT);

// Check if the user is already registered for this event
$sql = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$user_id, $event_id]);
$existingRegistration = $stmt->fetch();

if ($existingRegistration) {
    // If already registered, redirect back to index.php with a form submission
    echo "<form id='redirectForm' action='index.php' method='POST'>
            <input type='hidden' name='error_message' value='You have already registered for this event!'>
          </form>
          <script type='text/javascript'>
            document.getElementById('redirectForm').submit();
          </script>";
    exit();
}

// Otherwise, register the user
$query = "UPDATE events SET curr_participants = curr_participants + 1 WHERE event_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute([$event_id]);

$sql = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
$stmt = $connection->prepare($sql);
$stmt->execute([$user_id, $event_id]);

header('Location: index.php');
exit();
?>

