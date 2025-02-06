<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

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
                    session_start();
                    if (isset($_SESSION['forgotPassEmailErr'])) {
                        echo "email does not exist";
                        unset($_SESSION['forgotPassEmailErr']);
                    }
                    ?></span>
            </div>

            <div class="form_group">
                <span class="success">
                    <?php
                    session_start();

                    if (isset($_SESSION['emailSentMessage'])) {
                        echo "Mail has been sent Successfully , Please Check Your Email !";
                        unset($_SESSION['emailSentMessage']);
                    }
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