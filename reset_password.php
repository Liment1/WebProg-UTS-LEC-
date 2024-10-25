<?php

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    ?>
    <form action="update_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Enter new password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php
} else {
    echo "Invalid token.";
}
?>