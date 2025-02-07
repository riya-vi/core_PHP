<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

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