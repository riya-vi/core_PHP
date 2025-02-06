<?php
include '../config/dataBaseConnect.php';
session_start();

$newPasswordErr = $confirmNewPasswordErr = "";
$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resetToken = $_POST['resetToken'];
    // echo $resetToken . "<br>";
    // die;
    // $email = $_POST['email'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    $tokenExistQuery = "SELECT user_id, expiry FROM password_reset_tokens WHERE `token` = '$resetToken'";

    // echo $tokenExistQuery ;
    // die; 

    $result = $connection->query($tokenExistQuery);

    if (empty($newPassword)) {
        $newPasswordRequireErr = "Password is required";
        $_SESSION['newPasswordRequireErr'] = $newPasswordRequireErr;
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
        $newPasswordInvalidErr = "Password must be at least 8 characters, one uppercase letter, one digit, and one special character";
        $_SESSION['newPasswordInvalidErr'] = $newPasswordInvalidErr;
    }


    if (empty($confirmNewPassword)) {
        $confirmNewPasswordErr = "Please re-enter the password";
        $_SESSION['confirmNewPasswordErr'] = $confirmNewPasswordErr;
    } 
    elseif ($confirmNewPassword !== $newPassword) {
        $confirmNewPasswordNotMatchErr = "Passwords did not match!";
        $_SESSION['confirmNewPasswordNotMatchErr'] = $confirmNewPasswordNotMatchErr;
    }

    if ($result->num_rows > 0) {
        $resetRequest = $result->fetch_assoc();

        if (strtotime($resetRequest['expiry']) > time()) {
            $userId = $resetRequest['user_id'];

            if (empty($newPasswordRequireErr) && empty($newPasswordInvalidErr) && empty($confirmNewPasswordErr) && empty($confirmNewPasswordNotMatchErr)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";

                $deleteTokenQuery = "DELETE FROM password_reset_tokens WHERE `token` = '$resetToken'";

                $deleteTokenResult = $connection->query($deleteTokenQuery);

                if ($connection->query($updatePasswordQuery) === TRUE) {

                    header("Location: login.php?success=resetPassword");
                } else {
                    echo "Error updating password: " . $connection->error;
                }
            }
        } else {
            echo "the reset link has expired";
            exit;
        }
    } else {
        echo "invalid token";
        // exit;
    }
}
