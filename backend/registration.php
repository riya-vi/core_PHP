<?php
include '../config/dataBaseConnect.php';
require  '../common/formValidation.php';

function registerUser($postData, $connection) {
    $errors = validateForm($postData);

    if (empty($errors)) {
        $firstName = $postData['firstName'];
        $lastName = $postData['lastName'];
        $email = $postData['email'];
        $phoneNo = $postData['phone'];
        $address =  trim($postData['address']);
        $country = $postData['country'];
        $state = $postData['state'];
        $pincode = $postData['pincode'];
        $password = $postData['password'];

        $options = ["cost" => 10];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

        $sql = "INSERT INTO users (first_name, last_name, email, phone_no, address, country_id, state_id, pincode, password) 
        VALUES ('$firstName', '$lastName', '$email', '$phoneNo', '$address', '$country' , '$state','$pincode', '$hashedPassword')";

        if ($connection->query($sql)) {
            header("Location: login.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
    return $errors;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = registerUser($_POST, $connection);
}

?>


<?php
// include '../config/dataBaseConnect.php' ;
// include '../common/formValidation.php';
// $errors = [];

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Validate the form data
//     $errors = validateForm($_POST);

//     if (empty($errors)) {
//         // Assigning form data to variables
//         $firstName = $_POST['firstName'];
//         $lastName = $_POST['lastName'];
//         $email = $_POST['email'];
//         $phoneNo = $_POST['phone'];
//         $address = trim($_POST['address']);
//         $country = $_POST['country'];
//         $state = $_POST['state'];
//         $pincode = $_POST['pincode'];
//         $password = $_POST['password'];

//         // Hash the password
//         $options = ["cost" => 10];
//         $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

//         // Prepare SQL query
//         $sql = "INSERT INTO users (first_name, last_name, email, phone_no, address, country_id, state_id, pincode, password) 
//                 VALUES ('$firstName', '$lastName', '$email', '$phoneNo', '$address', '$country', '$state', '$pincode', '$hashedPassword')";

//         // Execute the query and handle success/failure
//         if ($connection->query($sql)) {
//             // Redirect to login page upon successful registration
//             header("Location: ../views/login.php");
//             exit;
//         } else {
//             echo "Error inserting data: " . $connection->error;
//         }
//     }
// }

?>
