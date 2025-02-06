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
  if (isset($_REQUEST['success']) == 'resetPassword') {
    echo "<div class='alert alert-success'>Password Updated Successfully !</div>";
  }
  if(isset($_REQUEST['accessMsg'])){
    echo "<div class='alert alert-danger'>Please Do Login First !</div>";
    
  }
  ?>
  <div class="container">
    <h1> Login form</h1>
    <form method="post" action="../backend/login.php">

      <div class="form_group">
        <label for="email">Email :</label>
        <input type="text" id="email" name="email" value="<?= (isset($_POST['email'])) ? strip_tags($_POST['email']) : '' ?>">
        <span class="error">
        <?php 
        // session_start() ;
        if(isset($_SESSION['emailRequireErr'])){
          echo "Email is Required" ;
          unset($_SESSION['emailRequireErr']);
        }elseif(isset($_SESSION['emailInvalidErr'])){
          echo "Invalid email format !" ;
          unset($_SESSION['emailInvalidErr']) ;
        }else{
          echo "" ;
        }
        ?>
        </span>
      </div>

      <div class="form_group">
        <label for="password">Password :</label>
        <input type="password" id="password" name="password"><span class="error">
        <?php 
        if(isset($_SESSION['passwordRequireErr'])){
          echo "Password is Required" ;
          unset($_SESSION['passwordRequireErr']); 
        }
       ?>
        </span>
      </div>

      <div class="form_group">
        <span class="error"> <?php 
        if(isset($_SESSION['invalidPassworLoginErr'])){
          echo "invalid password !" ;
          unset($_SESSION['invalidPassworLoginErr']);
        }elseif(isset($_SESSION['emailNotExistsLoginErr'])){
          echo "email does not exist !" ;
          unset($_SESSION['emailNotExistsLoginErr']) ;
        }
      
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