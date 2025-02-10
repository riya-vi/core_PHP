<?php
include   '../config/dataBaseConnect.php';
include '../common/sqlQueries.php' ;
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toEmail = $_POST['email'];

    $sql = getUserWantsToResetPassword($toEmail) ;
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        $resetToken = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $insertTokenQuery = insertTokenQueryForForgotPass($userId , $resetToken ,$expiry);

        $insertTokenQueryResult = $connection->query($insertTokenQuery);

        require '../config/mailSetup.php' ;
        
    } else {
        $forgotPassEmailErr = "email does not exist";
        $_SESSION['forgotPassEmailErr'] = $forgotPassEmailErr;
    }
}

include '../frontend/forgotPasswordForm.php'
?>


