<?php
include '../config/dataBaseConnect.php';

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
        $newPasswordErr = "Password is required";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
        $newPasswordErr = "Password must be at least 8 characters, one uppercase letter, one digit, and one special character";
    }

    if (empty($confirmNewPassword)) {
        $confirmNewPasswordErr = "Please re-enter the password";
    } elseif ($confirmNewPassword !== $newPassword) {
        $confirmNewPasswordErr = "Passwords did not match!";
    }

    if ($result->num_rows > 0) {
        $resetRequest = $result->fetch_assoc();

        if (strtotime($resetRequest['expiry']) > time()) {
            $userId = $resetRequest['user_id'];

            if (empty($newPasswordErr) && empty($confirmNewPasswordErr)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";

                $deleteTokenQuery = "DELETE FROM password_reset_tokens WHERE `token` = '$resetToken'" ;

                $deleteTokenResult = $connection->query($deleteTokenQuery) ;

                if ($connection->query($updatePasswordQuery) === TRUE) {
                    
                    header("Location: login.php?success=resetPassword");
                } else {
                    echo "Error updating password: " . $connection->error;
                }
            }
        }else{
            echo "the reset link has expired";
            exit;
        }
    }else{
        echo "invalid token" ;
        // exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h1>Reset Password</h1>

        <form action="resetPassword.php" method="POST">

        <input type="hidden" name="resetToken" value="<?= $_GET['token']  ?>">

            <div class="form_group">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" value="">
                <span style="color: red;"><?= $newPasswordErr ?></span>
            </div>
            <div class="form_group">
                <label for="confirmNewPassword">Re-Enter New Password:</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                <span style="color: red;"><?= $confirmNewPasswordErr ?></span>
            </div>
            <div class="form_group">
                <button type="submit" name="resetPassword">Reset Password</button>
            </div>
        </form>
    </div>
</body>

</html>