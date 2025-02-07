<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<?php
include '../common/sessions.php';
invalidTokenErrForResetPassword();
// echo "resetToken :" ;
// $resetToken = $_POST['resetToken'] ;
// die;
?>

<body>
    <div class="container">
        <h1>Reset Password</h1>

        <form action="../backend/resetPassword.php?" method="POST">

        <input type="hidden" name="resetToken" value="<?= htmlspecialchars($_GET['token']) ?>">

            <div class="form_group">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" value="">
                <span style="color: red;">
                    <?php
                    newPasswordErrOnResetPasswordForm();
                    ?></span>
            </div>
            <div class="form_group">
                <label for="confirmNewPassword">Re-Enter New Password:</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                <span style="color: red;">
                    <?php
                    confirmPasswordErrOnResetPasswordForm();
                    ?></span>
            </div>
            <div class="form_group">
                <button type="submit" name="resetPassword">Reset Password</button>
            </div>
        </form>
    </div>
</body>

</html>



