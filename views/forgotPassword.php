<?php
include '../config/dataBaseConnect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toEmail = $_POST['email'];

    $sql = "SELECT id FROM users WHERE email = '$toEmail'";
    // echo $sql ;
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        echo "email exist";

        $mail = new PHPMailer;

        try {
            $mail->isSMTP();
            $mail->Host = 'mail.devvivanshinfotech.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mail@devvivanshinfotech.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port  = '465';

            $mail->setFrom('mail@devvivanshinfotech.com');
            $mail->addAddress($email);
            // $mail->addAddress('');

            $mail->isHTML(true);
            $mail->Subject = '';
            $mail->Body = '';
            $mail->AltBody = '';
            $mail->send();
            echo "Mail has been sent Successfully!";
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo '<script>alert("email does not exist!")</script>';
        // header("Location: forgotPassword.html");
    }
}
