<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <h1>Reset Password</h1>

        <form action="../backend/resetPassword.php" method="POST">

            <input type="hidden" name="resetToken" value="<?= $_GET['token']  ?>">

            <div class="form_group">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" value="">
                <span style="color: red;">
                    <?php
                    session_start();
                    if (isset($_SESSION['newPasswordRequireErr'])) {
                        echo "Password is required";
                        unset($_SESSION['newPasswordRequireErr']);
                    } elseif (isset($_SESSION['newPasswordInvalidErr'])) {
                        echo "Password must be at least 8 characters, one uppercase letter, one digit, and one special character";
                        unset($_SESSION['newPasswordInvalidErr']);
                    }

                    ?></span>
            </div>
            <div class="form_group">
                <label for="confirmNewPassword">Re-Enter New Password:</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                <span style="color: red;">
                    <?php 
                    session_start();
                    // echo $_SESSION['confirmNewPasswordErr'] ;
                    if (isset($_SESSION['confirmNewPasswordErr'])) {
                        echo "Please re-enter the password";
                        unset($_SESSION['confirmNewPasswordErr']);
                    } elseif (isset($_SESSION['confirmNewPasswordNotMatchErr'])) {
                        echo "Passwords did not match!";
                        unset($_SESSION['confirmNewPasswordNotMatchErr']);
                    }
                    ?></span>
            </div>
            <div class="form_group">
                <button type="submit" name="resetPassword">Reset Password</button>
            </div>
        </form>
    </div>
</body>

</html>