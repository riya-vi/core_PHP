<?php
include   '../config/dataBaseConnect.php';

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toEmail = $_POST['email'];

    $sql = "SELECT id FROM users WHERE email = '$toEmail'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc() ;
        $userId = $user['id'] ; 

        $resetToken = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $insertTokenQuery = "INSERT INTO password_reset_tokens (`user_id` , `token`, `expiry`) VALUES ('$userId', '$resetToken', '$expiry')" ;

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

            $resetLink = "http://localhost/php/views/resetPassword.php?token=" . $resetToken;

            $mail->Body = "<h3>To Reset Your Password </h3> Click <a href='$resetLink'>this link</a> ";

            $mail->send();

          $mailSentMessage = "Mail has been sent Successfully , Please Check Your Email !";
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
            throw new Exception($mail->ErrorInfo);
        }
    } else {
        $emailErr = "email does not exist";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h1>Forgot Password </h1>
        <form action="./forgotPassword.php" method="POST">
            <div class="form_group">
                <p>Enter your Email and we will send you reset Password link</p>
            </div>
            <div class="form_group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" required>
            </div>

            <div class="form_group">
                <span class="error"><?php echo $emailErr ?></span>
            </div>

            <div class="form_group">
                <span class="success"><?php echo $mailSentMessage ?></span>
            </div>

            <div class="form_group">
                <button type="submit">Send Reset Link</button>
            </div>
            <div class="form_group">
                <p>Back to<a href="./login.php"> Login</a></p>
            </div>
        </form>
    </div>

</body>

</html>