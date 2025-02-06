<?php

include '../config/dataBaseConnect.php';
require_once '../common/formValidation.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // print_r($_POST);
    // return;
    $errors = validateForm($_POST);

    if (empty($errors)) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phone'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $password = $_POST['password'];

        $options = ["cost" => 10];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

        $sql = insertUserQuery($firstName, $lastName, $email, $phoneNo, $address, $country, $state, $pincode, $hashedPassword) ;

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["add_message"] = "User Added Successfully !";
            header("Location: ../frontend/dashboard.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }else{
        // print_r($errors) ;
    }
}

?>

<?php include '../frontend/addUserForm.php' ; ?>