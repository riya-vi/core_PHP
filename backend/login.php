<?php
include '../config/dataBaseConnect.php';
require '../common/sqlQueries.php';

$emailRequireErr = $emailInvalidErr = $passwordRequireErr  ="";
$email = $password = "";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["email"])) {
    $emailRequireErr = "Email is required";
    $_SESSION['emailRequireErr'] = $emailRequireErr ;

  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailInvalidErr = "Invalid email format";
      $_SESSION['emailInvalidErr'] = $emailInvalidErr ;
    }
  }

  if (empty($_POST["password"])) {
    $passwordRequireErr = "Password is required";
    $_SESSION['passwordRequireErr'] = $passwordRequireErr ;
  } else {
    $password = test_input($_POST["password"]);
  }

}
function test_input($data)
{
  return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($emailRequireErr == "" && $passwordRequireErr == "" && !empty($_POST["email"]) && !empty($_POST["password"])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $loginErr = '' ;

    $sql = loginUserQuery($email) ;
    $result = $connection->query($sql);

    if ($result->num_rows == 1) { 
      while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
          $_SESSION['email'] = $email;
          $_SESSION['password'] = $password;
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['role_id'] = $row['role_id'] ;
          echo '<script>alert("Logged in Successfully")</script>';
          header("Location: ../frontend/dashboard.php");
          exit();
        } else {
          $invalidPassworLoginErr = "invalid password !";
          $_SESSION['invalidPassworLoginErr'] = $invalidPassworLoginErr ;
        }
      }
    } else {
      $emailNotExistsLoginErr = "email does not exist !";
      $_SESSION['emailNotExistsLoginErr'] = $emailNotExistsLoginErr ;
    }

    $connection->close();
  } else {
    echo '';
  }
}


include '../frontend/loginForm.php' ;
?>


