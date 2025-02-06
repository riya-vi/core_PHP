<?php
include   '../config/dataBaseConnect.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toEmail = $_POST['email'];

    $sql = "SELECT id FROM users WHERE email = '$toEmail'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        $resetToken = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $insertTokenQuery = "INSERT INTO password_reset_tokens (`user_id` , `token`, `expiry`) VALUES ('$userId', '$resetToken', '$expiry')";

        $insertTokenQueryResult = $connection->query($insertTokenQuery);

        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'mail.devvivanshinfotech.com';
            $mail->Username = 'mail@devvivanshinfotech.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port  = '465';

            $mail->setFrom('mail@devvivanshinfotech.com');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';

            $resetLink = "http://localhost/php/frontend/resetPasswordForm.php?token=" . $resetToken;

            $mail->Body = "<h3>To Reset Your Password </h3> Click <a href='$resetLink'>this link</a> ";

            $mail->send();

            $mailSentMessage = "Mail has been sent Successfully , Please Check Your Email !";
            $_SESSION['emailSentMessage'] = $mailSentMessage;

        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
            throw new Exception($mail->ErrorInfo);
        }
    } else {
       
        $forgotPassEmailErr = "email does not exist";
        $_SESSION['forgotPassEmailErr'] = $forgotPassEmailErr;
    }
}
?>


<?php include '../frontend/forgotPasswordForm.php';
