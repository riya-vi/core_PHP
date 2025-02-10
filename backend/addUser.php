<?php

include '../config/dataBaseConnect.php';
require_once '../common/formValidation.php';
require '../common/sqlQueries.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = validateForm($_POST);

    if (empty($errors)) {

        $options = ["cost" => 10];
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);

        $sql = registerUserQuery($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['country'], $_POST['state'], $_POST['pincode'], $hashedPassword) ;

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["add_message"] = "User Added Successfully !";
            header("Location: ../frontend/dashboard.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
}


include '../frontend/addUserForm.php' ;
?>
