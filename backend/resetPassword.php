<?php
include '../config/dataBaseConnect.php';
include '../common/sqlQueries.php';
session_start();

$newPasswordErr = $confirmNewPasswordErr = "";
$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resetToken = $_POST['resetToken'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // check if token exist before reseting the password
    $tokenExistQuery = checkTokenExistQueryToResetPass($resetToken);

    $result = $connection->query($tokenExistQuery);

    if (empty($newPassword)) {
        $newPasswordRequireErr = "Password is required";
        $_SESSION['newPasswordRequireErr'] = $newPasswordRequireErr;
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
        $newPasswordInvalidErr = "Password must be at least 8 characters, one uppercase letter, one digit, and one special character";
        $_SESSION['newPasswordInvalidErr'] = $newPasswordInvalidErr;
    }

    if (empty($confirmNewPassword)) {
        $confirmNewPasswordErr = "Please re-enter the password";
        $_SESSION['confirmNewPasswordErr'] = $confirmNewPasswordErr;
    } elseif ($confirmNewPassword !== $newPassword) {
        $confirmNewPasswordNotMatchErr = "Passwords did not match!";
        $_SESSION['confirmNewPasswordNotMatchErr'] = $confirmNewPasswordNotMatchErr;
    }


    if ($result->num_rows > 0) {
        $resetRequest = $result->fetch_assoc();

        // set the expiry for token 
        if (strtotime($resetRequest['expiry']) > time()) {
            $userId = $resetRequest['user_id'];

            if (empty($newPasswordRequireErr) && empty($newPasswordInvalidErr) && empty($confirmNewPasswordErr) && empty($confirmNewPasswordNotMatchErr)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // if token exist and within expiry then update the password
                $updatePasswordQuery = updatePasswordQuery($userId, $hashedPassword);

                // delete the token after updating user password
                $deleteTokenQuery = deleteTokenQueryAfterResetPassword($resetToken);

                if ($connection->query($updatePasswordQuery) === TRUE) {
                    $deleteTokenResult = $connection->query($deleteTokenQuery);
                    if ($deleteTokenResult) {
                        header("Location: login.php?success=resetPassword");
                    } else {
                        echo "Error deleting token: " . $connection->error;
                    }
                } else {
                    echo "Error updating password: " . $connection->error;
                }
            } 
        } else {
            echo "the reset link has expired";
            exit;
        }
    }else {
        header("Location: ../frontend/forgotPasswordForm.php?error=invalidToken");
    }
}


include '../frontend/resetPasswordForm.php'
?>
