<?php

function checkLogin()
{
    if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
        header("Location: ../frontend/loginForm.php?accessMsg");
    }
}

function crudSuccessMessages()
{
    if (isset($_SESSION["edit_message"])) {
        echo '<div class="alert alert-success">Record Updated Successfully!</div>';
        unset($_SESSION["edit_message"]);
    } elseif (isset($_SESSION["delete_message"])) {
        echo '<div class="alert alert-success">Record Deleted Successfully!</div>';
        unset($_SESSION["delete_message"]);
    } elseif (isset($_SESSION["add_message"])) {
        echo '<div class="alert alert-success">User Added Successfully!</div>';
        unset($_SESSION["add_message"]);
    }
}

function passwordUpdateAndLoginFirstMessages()
{
    if (isset($_REQUEST['success']) == 'resetPassword') {
        echo "<div class='alert alert-success'>Password Updated Successfully !</div>";
    }
    if (isset($_REQUEST['accessMsg'])) {
        echo "<div class='alert alert-danger'>Please Do Login First !</div>";
    }
}

function emailErrorOnLoginPage()
{
    if (isset($_SESSION['emailRequireErr'])) {
        echo "Email is Required";
        unset($_SESSION['emailRequireErr']);
    }
    if (isset($_SESSION['emailInvalidErr'])) {
        echo "Invalid email format !";
        unset($_SESSION['emailInvalidErr']);
    }
}

function passwordRequireErrOnLogin()
{
    if (isset($_SESSION['passwordRequireErr'])) {
        echo "Password is Required";
        unset($_SESSION['passwordRequireErr']);
    }
}

function emailPasswordVerificationOnLoginPage()
{
    if (isset($_SESSION['invalidPassworLoginErr'])) {
        echo "invalid password !";
        unset($_SESSION['invalidPassworLoginErr']);
    }
    if (isset($_SESSION['emailNotExistsLoginErr'])) {
        echo "email does not exist !";
        unset($_SESSION['emailNotExistsLoginErr']);
    }
}

function emailNotExistsOnForgotPasswordPage()
{
    if (isset($_SESSION['forgotPassEmailErr'])) {
        echo "email does not exist";
        unset($_SESSION['forgotPassEmailErr']);
    }
}

function mailSentMessage()
{
    if (isset($_SESSION['emailSentMessage'])) {
        echo "Mail has been sent Successfully , Please Check Your Email !";
        unset($_SESSION['emailSentMessage']);
    }
}

function newPasswordErrOnResetPasswordForm()
{
    if (isset($_SESSION['newPasswordRequireErr'])) {
        echo "Password is required";
        unset($_SESSION['newPasswordRequireErr']);
    }
    if (isset($_SESSION['newPasswordInvalidErr'])) {
        echo "Password must be at least 8 characters, one uppercase letter, one digit, and one special character";
        unset($_SESSION['newPasswordInvalidErr']);
    }
}

function confirmPasswordErrOnResetPasswordForm()
{
    if (isset($_SESSION['confirmNewPasswordErr'])) {
        echo "Please re-enter the password";
        unset($_SESSION['confirmNewPasswordErr']);
    } 
    if (isset($_SESSION['confirmNewPasswordNotMatchErr'])) {
        echo "Passwords did not match!";
        unset($_SESSION['confirmNewPasswordNotMatchErr']);
    }
}

function invalidTokenErrForResetPassword()
{
    if (isset($_REQUEST['error']) == 'invalidToken') {
        echo '<script>alert("Invalid Token! can not reset password with this link please try again ")</script>';
    }
}
