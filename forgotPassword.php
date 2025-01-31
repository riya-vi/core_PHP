<?php
include './config/dataBaseConnect.php';

require_once(__DIR__ . '/vendor/autoload.php');

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require '/usr/share/phpmail/Exception.php';
// require 'mail/PHPMailer.php';
// require 'mail/SMTP.php';

// require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
// require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
// require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toEmail = $_POST['email'];

    $sql = "SELECT id FROM users WHERE email = '$toEmail'";
    // echo $sql ;
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        echo "email exist";

        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = 2;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'mail.devvivanshinfotech.com';
            // $mail->SMTPAuth = true;
            $mail->Username = 'mail@devvivanshinfotech.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port  = '465';

            $mail->setFrom('mail@devvivanshinfotech.com');
            $mail->addAddress($toEmail);
            // $mail->addAddress('');

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $mail->Body = 'To Reset your Password click <a href="./resetPassword.php"> here </a>';
            // $mail->AltBody = '';
            $mail->send();
            echo "Mail has been sent Successfully!";
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
            throw new Exception($mail->ErrorInfo);
        }
    } else {
        echo '<script>alert("email does not exist!")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./views/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Forgot  Password </h1>
        <form action="./forgotPassword.php" method="POST">
            <div class="form_group">
                <p>Enter your Email and we will send you reset Password link</p>
            </div>
            <div class="form_group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" required>
            </div>
        
            <div class="form_group">
                <button type="submit">Send Reset Link</button>
            </div>
            <div class="form_group">
                <p>Back to<a href="../views/login.php"> Login</a></p>
                <!-- <a href="./resetPassword.php"></a> -->
            </div>
        </form>
    </div>

</body>

</html>