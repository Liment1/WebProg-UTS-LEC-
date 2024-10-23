<?php
require_once __DIR__ . '../../connection.php';

session_start();
if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: ../Verify/login.php");  
    exit();
} 

$sql = "SELECT user_id, name, email, role, created_at FROM users WHERE role = 'user' ORDER BY role = 'admin' DESC, created_at ASC";
$result = $connection->query($sql);

if (!empty($result)) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }
} else {
    echo "Tidak ada data.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="event-manage.php">Admin Page</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="event-manage.php">Event Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="User-manage.php">User Management</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Welcome, <span id="userName">User</span>
                    <button class="btn btn-outline-light ms-3" onclick="logout()">Logout</button>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">User Management</h1>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th> 
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php 
                        $display_id = 1; 
                        foreach ($users as $user): 
                    ?>
                    <tr>
                        <td><?php echo $display_id++; ?></td> 
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td>
                            <a href="show_user.php?user_id=<?php echo $user['user_id']; ?>" 
                               class="btn btn-sm btn-primary">Show</a>
                            <a href="user_management.php?delete_user_id=<?php echo $user['user_id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
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
        window.location.href = '../logout.php'; 
    });
}
</script>

</body>
</html>

