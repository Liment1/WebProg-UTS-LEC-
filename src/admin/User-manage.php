<?php
$host = "localhost"; 
$user = "root";      
$password = "";      
$database = "test";  

$conn = new mysqli($host, $user, $password, $database);

// cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
echo "Koneksi berhasil!<br>";

$sql = "SELECT user_id, name, email, role, created_at FROM users WHERE role = 'user' ORDER BY role = 'admin' DESC, created_at ASC";
$result = $conn->query($sql);

$users = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    echo "Tidak ada data.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>
<body>
    <h1>User Management</h1>

    <table border="1">
        <tr>
            <th>ID</th> 
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
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
                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href="user_management.php?delete_user_id=<?php echo $user['user_id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No users found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
