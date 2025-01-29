<?php
include '../config/dataBaseConnect.php' ;
use PHPMailer\PHPMailer\PHPMailer ;
use PHPMailer\PHPMailer\Exception ;

require 'vendor/autoload.php' ;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'] ;

    $sql = "SELECT id FROM users WHERE email = '$email'";
    echo $query ;
    $result = $connection->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $userId = $row['id'];

        echo "user exist" ;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com' ;
            $mail->SMTPAuth = true ;
            $mail->Username = '';
            $mail->Password = ''; 
            $mail->SMTPSecure = 'tls';
            $mail->Port  = '' ;

            $mail->setFrom('');
            $mail->addAddress('');
            $mail->addAddress('');

            $mail->isHTML(true);
            $mail->Subject = '';
            $mail->Body = '';
            $mail->AltBody = '';
            $mail->send();
            echo "Mail has been sent Successfully!" ;
            
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    }
    else{
        
        echo '<script>alert("email does not exist!")</script>';
        // header("Location: forgotPassword.html");
    }

}

?>