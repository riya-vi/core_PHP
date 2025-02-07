<?php
include '../config/dataBaseConnect.php';
require_once '../common/formValidation.php';
require '../common/sqlQueries.php' ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validateForm($_POST);

    if (empty($errors)) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phone'];
        $address = trim($_POST['address']);
        $country = $_POST['country'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $password = $_POST['password'];

        $options = ["cost" => 10];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

        $sql = registerUserQuery($firstName, $lastName, $email, $phoneNo, $address, $country , $state,$pincode, $hashedPassword);

        if ($connection->query($sql)) {
            header("Location: ./login.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    } 
}
?>

<?php include '../frontend/registrationForm.php'; ?>