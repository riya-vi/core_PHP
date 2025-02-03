<?php
include '../config/dataBaseConnect.php';

$newPasswordErr = $confirmNewPasswordErr = "";
$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

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

    if (empty($newPasswordErr) && empty($confirmNewPasswordErr)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $email = $connection->real_escape_string($email);

        $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";

        if ($connection->query($sql) === TRUE) {
            $updateMessage = "Password updated successfully!";
            header("Location: login.php");
            // include './views/login.php'

        } else {
            $updateMessage = "Error updating password: " . $connection->error;
        }
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
        <?php
        echo $updateMessage;
        ?>

        <form action="resetPassword.php" method="POST">

            <input type="text" name="email" style="visibility: hidden;" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">

           
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