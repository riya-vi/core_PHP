<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle= 'Reset Password' ;
include '../common/htmlHeader.php' ;
?>
<?php
include '../common/sessions.php';
invalidTokenErrForResetPassword();
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



