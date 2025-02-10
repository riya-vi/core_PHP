<?php
$pageTitle= 'LOgin' ;
include '../common/htmlHeader.php' ;
?>

<!DOCTYPE html>
<html lang="en">
<body>
  <?php
  include '../common/sessions.php';
  passwordUpdateAndLoginFirstMessages() ;
  ?>
  <div class="container">
    <h1> Login form</h1>
    <form method="post" action="../backend/login.php">

      <div class="form_group">
        <label for="email">Email :</label>
        <input type="text" id="email" name="email" value="<?= (isset($_POST['email'])) ? strip_tags($_POST['email']) : '' ?>">
        <span class="error">
          <?php
          emailErrorOnLoginPage();
          ?>
        </span>
      </div>

      <div class="form_group">
        <label for="password">Password :</label>
        <input type="password" id="password" name="password"><span class="error">
          <?php
          passwordRequireErrOnLogin();
          ?>
        </span>
      </div>

      <div class="form_group">
        <span class="error">
          <?php
          emailPasswordVerificationOnLoginPage() ;
          ?></span>
      </div>

      <p><a href="../frontend/forgotPasswordForm.php">forgot password ?</a></p>

      <div class="form_group">
        <button>Login</button>
      </div>

      <div class="form_group">
        <p>don't have an account ? <a href="../frontend/registrationForm.php"><span>sign up</span></a></p>
      </div>
    </form>
  </div>

</body>

</html>