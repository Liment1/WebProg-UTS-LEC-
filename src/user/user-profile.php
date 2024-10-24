<?php
session_start();
if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'user') {
    header("Location: ../Verify/login.php");  
    exit();
} 

require_once "../connection.php";

$userData = null;
$fetchUserSQL = "SELECT name, email FROM users WHERE user_id = ?"; 
$stmt = $connection->prepare($fetchUserSQL);
$stmt->execute([$_SESSION["user_id"]]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$userName = $userData ? $userData['name'] : '';
$userEmail = $userData ? $userData['email'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updatedName = $_POST['inputName'];
    $updatedEmail = $_POST['inputEmail'];
    $newPassword = $_POST['inputPassword'];

    $sql = "UPDATE users SET name = ?, email = ?";
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
    }
    $sql .= " WHERE user_id = ?";

    $stmt = $connection->prepare($sql);

    if (!empty($newPassword)) {
        $stmt->execute([$updatedName, $updatedEmail, $hashedPassword, $_SESSION["user_id"]]);
    } else {
        $stmt->execute([$updatedName, $updatedEmail, $_SESSION["user_id"]]);
    }
    
    if ($stmt) {
        $userName = $updatedName; 
        $userEmail = $updatedEmail;

        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile information has been updated successfully!',
                    confirmButtonColor: '#007bff'
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong while updating your profile.',
                    confirmButtonColor: '#dc3545'
                });
              </script>";
    }
}

$eventHistory = [];
$sql = "SELECT e.event_name, e.event_date 
        FROM registrations r 
        JOIN events e ON r.event_id = e.event_id 
        WHERE r.user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);

$eventHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 700px;
        }
        .profile-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-weight: bold;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-section h2 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        #eventHistory .list-group-item {
            border: none;
            border-bottom: 1px solid #dee2e6;
            padding-left: 0;
            padding-right: 0;
        }
        #eventHistory .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Browse Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user-event.php">My Registrations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="user-profile.php">My Profile</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <span id="userName"><?= htmlspecialchars($userName) ?></span>
                    <button class="btn btn-outline-light ms-3" onclick="logout()">Logout</button>
                </span>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1>User Profile Management</h1>

        <div class="profile-section">
            <h2>View Profile</h2>
            <div id="profileInfo">
                <p><strong>Name:</strong> <span id="displayName"><?= htmlspecialchars($userName) ?></span></p>
                <p><strong>Email:</strong> <span id="displayEmail"><?= htmlspecialchars($userEmail) ?></span></p>
            </div>
        </div>

        <div class="profile-section">
            <h2>Edit Profile</h2>
            <form id="editProfileForm" method="POST">
                <div class="mb-3">
                    <label for="inputName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputName" name="inputName" required value="<?= htmlspecialchars($userName) ?>">
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail" name="inputEmail" required value="<?= htmlspecialchars($userEmail) ?>">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">New Password (leave blank if unchanged)</label>
                    <input type="password" class="form-control" id="inputPassword" name="inputPassword">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <div class="profile-section">
            <h2>Event Registration History</h2>
            <ul id="eventHistory" class="list-group">
                <?php foreach ($eventHistory as $event): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($event['event_name']) ?></strong><br>
                        <small class="text-muted">Date: <?= htmlspecialchars($event['event_date']) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            Swal.fire({
                title: 'Logging out...',
                text: 'You will be redirected to the login page.',
                icon: 'info',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '../Verify/login.php'; 
            });
        }
    </script>

</body>
</html>