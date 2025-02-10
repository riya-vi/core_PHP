<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
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

    session_start();

    $mailSentMessage = "Mail has been sent Successfully , Please Check Your Email !";
    $_SESSION['emailSentMessage'] = $mailSentMessage;
} catch (Exception $e) {
    echo "Error sending email: " . $mail->ErrorInfo;
    throw new Exception($mail->ErrorInfo);
}

