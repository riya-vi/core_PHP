<?php
$pageTitle = 'forgot Password';
include '../common/htmlHeader.php' ;
include '../common/sessions.php';

// this function gives error if given token is not verified against database token
invalidTokenErrForResetPassword();
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <h1>Forgot Password </h1>
        <form action="../backend/forgotPassword.php" method="POST">
            <div class="form_group">
                <p>Enter your Email and we will send you reset Password link</p>
            </div>
            <div class="form_group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" required>
            </div>

            <div class="form_group">
                <span class="error">
                    <?php
                    emailNotExistsOnForgotPasswordPage();
                    ?>
            </div>

            <div class="form_group">
                <span class="success">
                    <?php
                    mailSentMessage();
                    ?></span>
            </div>

            <div class="form_group">
                <button type="submit">Send Reset Link</button>
            </div>
            <div class="form_group">
                <p>Back to<a href="../frontend/loginForm.php"> Login</a></p>
            </div>
        </form>
    </div>

</body>

</html>