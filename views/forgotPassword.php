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

        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'mail.devvivanshinfotech.com' ;
            $mail->Username = 'mail@devvivanshinfotech.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port  = '465';

            $mail->setFrom('mail@devvivanshinfotech.com');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';

            $url = "http://localhost/php/views/resetPassword.php?email=$toEmail" ;
            
            $mail->Body = "<h3>To Reset Your Password </h3> Click <a href='$url'>this link</a> ";

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
                <button type="submit">Send Reset Link</button>
            </div>
            <div class="form_group">
                <p>Back to<a href="./login.php"> Login</a></p>
            </div>
        </form>
    </div>

</body>

</html>