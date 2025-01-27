<?php
include '../config/dataBaseConnect.php';
 
$emailErr = $passwordErr = "";
$email = $password = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
  }
}
function test_input($data)
{
  return $data;
}

session_start();

// verify user from database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($emailErr == "" && $passwordErr == "" && !empty($_POST["email"]) && !empty($_POST["password"])) {

    // Database User Verification 
    $email = $_POST['email'];
    $password = $_POST['password'];
    // $loginErr = '' ;

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $connection->query($sql);

    if ($result->num_rows == 1) {
      while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
          $_SESSION['email'] = $email;
          $_SESSION['password'] = $password;
          echo isset($_SESSION['email']);
          echo "done";
          echo '<script>alert("Logged in Successfully")</script>';
          header("Location: dashboard.php");
          exit();
        } else {
          $loginErr = "invalid password !";
        }
      }
    } else {
      $loginErr = "email does not exist !";
    }

    // Close connection
    $connection->close();
  } else {
    echo "";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

  <div class="container">
    <h1> Login form</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form_group">
        <label for="email">Email :</label>
        <input type="text" id="email" name="email" value="<?= (isset($_POST['email'])) ? strip_tags($_POST['email']) : '' ?>">
        <span class="error">
          <?php echo $emailErr; ?>
        </span>
      </div>

      <div class="form_group">
        <label for="password">Password :</label>
        <input type="password" id="password" name="password"><span class="error">
          <?php echo $passwordErr; ?>
        </span>
      </div>

      <div class="form_group">
        <p>don't have an account ? <a href="/registration"><span>sign up</span></a></p>
      </div>

      <div class="form_group">
        <span class="error"> <?php echo $loginErr ?></span>
      </div>
      
      <div class="form_group">
        <button>Login</button>
      </div>
    </form>
  </div>
  <?php





  ?>
</body>

</html>
