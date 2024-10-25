<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['email'])) {
    require_once 'connection.php';
    $email = $_POST['email'];

    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $token = bin2hex(random_bytes(50)); 

        $stmt = $connection->prepare("INSERT INTO password_resets (email, token) VALUES (:email, :token)");
        $stmt->execute([':email' => $email, ':token' => $token]);

        $resetLink = "https://eventwebprog.my.id/LEC/reset_password.php?token=" . $token;

  
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                          
            $mail->Host       = 'mail.eventwebprog.my.id';                    
            $mail->SMTPAuth   = true;                             
            $mail->Username   = 'userevent@eventwebprog.my.id';         
            $mail->Password   = 'userevent_1';                    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
            $mail->Port       = 587;                                

            //Recipients
            $mail->setFrom('userevent@eventwebprog.my.id', 'eventwebprog.my.id');
            $mail->addAddress($email);                               

            // Content
            $mail->isHTML(true);                                      
            $mail->Subject = 'Reset Your Password';
            $mail->Body    = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";
            $mail->AltBody = "Click the link below to reset your password: $resetLink";  

            $mail->send();
            echo 'Reset link has been sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>
