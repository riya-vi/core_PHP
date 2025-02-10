<?php
include '../config/dataBaseConnect.php';
include '../common/formValidation.php';
require  '../common/sqlQueries.php' ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validateForm($_POST);

    if (empty($errors)) {
        // $id = $_POST['id'];
        // $firstName = $_POST['firstName'];
        // $lastName = $_POST['lastName'];
        // $email = $_POST['email'];
        // $phoneNo = $_POST['phone'];
        // $address = $_POST['address'];
        // $country = $_POST['country'];
        // $state = $_POST['state'];
        // $pincode = $_POST['pincode'];
        // $password = $_POST['password'];

        $options = ["cost" => 10];
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);

        $uploadDir = realpath(__DIR__ . '/../storage/profile_images/') . '/';

        $defaultPhoto = '/storage/default.jpg';
        $filePath = $_POST['existingFilePath'] ?? $defaultPhoto;

        if ($_FILES['profilePhoto']['error'] == 0) {
            $fileName = uniqid() . basename($_FILES['profilePhoto']['name']);
            $fileDestination = $uploadDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileSizeLimit = 5000000; // 5MB
            $fileType = $_FILES['profilePhoto']['type'];
            $fileSize = $_FILES['profilePhoto']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                echo '<script>alert("Invalid file type. Only JPEG, PNG, and GIF are allowed.")</script>';
                exit;
            }

            if ($fileSize > $fileSizeLimit) {
                echo '<script>alert("File size exceeds 5MB limit.")</script>';
                exit;
            }

           unlink('../' . $_POST['existingFilePath']);

            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $fileDestination)) {
                $filePath = '/storage/profile_images/' . $fileName;
            }
        }

        $sql = updateUserQuery($_POST['id'],  $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['country'], $_POST['state'], $_POST['pincode'], $filePath, $hashedPassword) ;

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["edit_message"] = "Record Updated Successfully!";
            header("Location: ../frontend/dashboard.php");
            exit;
        } else {
            echo "Error updating data: " . $connection->error;
        }
    }
}
$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $id";
$result = $connection->query($query);
$rows = $result->fetch_assoc(); 


include '../frontend/editUserForm.php'
?>
