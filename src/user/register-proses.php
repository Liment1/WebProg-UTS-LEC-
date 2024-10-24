<?php
require_once '../connection.php';
session_start();

$user_id = $_SESSION['user_id'];
$event_id = 'E' . str_pad($_POST['event_id'], 4, '0', STR_PAD_LEFT);
$sql = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$user_id, $event_id]);
$existingRegistration = $stmt->fetch();

if ($existingRegistration) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'You have already registered for this event!',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../index.php';
            }
        });
    </script>";
    exit();
}

$query = "UPDATE events 
            SET curr_participants = curr_participants+1 
            WHERE Event_id = ?";
$stmt = $connection->prepare($query);
$stmt->execute([$event_id]);

$sql = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
$stmt = $connection->prepare($sql);

$stmt->execute([$user_id, $event_id]);
echo "test";

header('Location: ../../index.php');
exit();
?>
