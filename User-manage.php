<?php
session_start();
require_once 'connection.php';

if (!(isset($_SESSION['role'])) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch users from the database
$sql = "SELECT user_id, name, email, role, created_at FROM users WHERE role = 'user' ORDER BY created_at ASC";
$result = $connection->query($sql);

$users = [];
if (!empty($result)) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert script -->
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
                        <a class="nav-link" href="event-manage.php">Event Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="User-manage.php">User Management</a>
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
                        <a href="show_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-primary">Show</a>

                        <form id="deleteUserForm<?php echo $user['user_id']; ?>" action="delete-user.php" method="POST" style="display: none;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        </form>

                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo htmlspecialchars($user['user_id']); ?>')">Delete</button>

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
        window.location.href = 'logout.php';
    });
}

function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form associated with the userId
            document.getElementById('deleteUserForm' + userId).submit();
        }
    });
}
</script>
</body>
</html>



