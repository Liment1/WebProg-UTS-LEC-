<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_reporting(E_ALL);
ini_set('display_errors', 1);
    include 'connection.php';


    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT); 


    $stmt = $connection->prepare("SELECT email FROM password_resets WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {

        $email = $result['email'];


        $stmt = $connection->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $new_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        $stmt = $connection->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid token.";
    }
}
?>

<button onclick="window.location.href='login.php'">Go to Login</button>
