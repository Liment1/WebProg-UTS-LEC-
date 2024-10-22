<?php
require __DIR__ . '../../connection.php';

if (!isset($_GET['user_id'])) {
    echo "No user ID provided!";
    exit;
}

$ID = $_GET['user_id'];

try {
    $query = "SELECT e.Event_id AS Event_id, e.Event_Name AS Event_Name, e.description AS Event_description, u.User_Id AS User_id
              FROM Users AS u 
              JOIN registrations AS r ON (r.User_ID = u.USER_ID)   
              JOIN events AS e ON (e.Event_ID = r.Event_ID)
              WHERE u.User_Id = ?";
              
    $stmt = $connection->prepare($query);
    $stmt->execute([$ID]); 
    
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($events)) {
        echo "No events found for this user.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">User Events</h1>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Event ID</th> 
                    <th>Event Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['Event_id']); ?></td>
                    <td><?php echo htmlspecialchars($event['Event_Name']); ?></td>
                    <td><?php echo htmlspecialchars($event['Event_description']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
