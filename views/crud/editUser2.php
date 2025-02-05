<?php


include '../../config/dataBaseConnect.php';
include '../formValidation.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = validateForm($_POST);

    if (empty($errors)) {
        $id = $_POST['id'];
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

        $uploadDir = realpath(__DIR__ . '/../../storage/profile_images/') . '/';
        
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

            unlink('../../' . $_POST['existingFilePath']);

            if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $fileDestination)) {
                $filePath = '/storage/profile_images/' . $fileName;
            }
        }

        $sql = "UPDATE `users` SET 
            `first_name` = '$firstName',  
            `last_name` = '$lastName',  
            `email` = '$email',  
            `phone_no` = '$phoneNo', 
            `address` = '$address',  
            `country_id` = '$country',  
            `state_id` = '$state',  
            `file_path` = '$filePath'  
            WHERE `id` = '$id'";

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["edit_message"] = "Record Updated Successfully!";
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "Error updating data: " . $connection->error;
        }
    }
}


$id = $_GET['id'];
$query = "SELECT u.*, c.name AS country, s.name AS state  FROM users u LEFT JOIN countries c ON u.country_id = c.id LEFT JOIN states s ON u.state_id = s.id WHERE u.id = $id";
$result = $connection->query($query);
$rows = $result->fetch_assoc();


// $id = $rows['id'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboardStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Edit User</title>
</head>

<body>
    <?php include '../layout/navbar.php'; ?>

    <div class="container">
        <h1>Edit User Details</h1>

        <?php
        if (!empty($rows['file_path'])) {
            $imagePath = '../../' . $rows['file_path'];
        } else {
            $imagePath = '../../storage/default.jpg';
        }
        ?>
        <img src="<?= $imagePath ?>" alt="Profile Image" width="150" height="150" class="center">

        <form method="post" action="editUser2.php?id=<?php echo $rows['id']; ?>" enctype="multipart/form-data">
            <div class="form_group">
                <label for="profilePhoto">Profile Photo :</label>
                <input type="file" id="profilePhoto" name="profilePhoto">
                <input type="hidden" name="existingFilePath" value="<?php echo $rows['file_path']; ?>">
            </div>
            <div class="form_group">
                <label for="firstName">First name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo $rows['first_name']; ?>">
                <span class="error">
                    <?php echo $errors['firstName'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="lastName">Last name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo $rows['last_name']; ?>">
                <span class="error">
                    <?php echo $errors['lastName'] ?? '';  ?>
                </span>
            </div>
            <div class="form_group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" value="<?php echo $rows['email']; ?>">
                <span class="error">
                    <?php echo $errors['email'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="phone">Phone No. :</label>
                <input type="text" id="phone" name="phone" value="<?php echo $rows['phone_no']; ?>">
                <span class="error">
                    <?php echo $errors['phone'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="address">Address :</label>
                <textarea name="address" id="address"><?php echo $rows['address']; ?></textarea>
                <span class="error" onchange="" onclick="">
                    <?php echo $errors['address'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="country">Country :</label>
                <select name="country" id="country">
                   
                    <?php
                    $countries = $connection->query("SELECT id, name FROM countries where id =  {$rows['country_id']} ");
                    while ($country = $countries->fetch_assoc()) {
                        $selected = $rows['country_id'] == $country['id'] ? 'selected' : '' ;
                        echo "<option value='{$country['id']}' $selected>{$country['name']}</option>";
                    }
                    ?>
   
                </select>
                <span class="error">
                    <?php echo $errors['country'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="state">State :</label>
                <select name="state" id="state">
                    <option value="">Select State</option>
                    <?php
                    $states = $connection->query("SELECT id, name FROM states WHERE country_id = {$rows['country_id']}");
                    while ($state = $states->fetch_assoc()) {
                        $selected = $rows['state_id'] == $state['id'] ? 'selected' : '';
                        echo "<option value='{$state['id']}' $selected>{$state['name']}</option>";
                    }
                    ?>
                </select>
                <span class="error">
                    <?php echo $errors['state'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="pincode">Pincode :</label>
                <input type="text" name="pincode" id="pincode" value="<?php echo $rows['pincode']; ?>">
                <span class="error">
                    <?php echo $errors['pincode'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="password">Password :</label>
                <input type="password" id="password" name="password" value="<?php echo $rows['password']; ?>">
                <span class="error">
                    <?php echo $errors['password'] ?? ''; ?>
                </span>
            </div>
            <div class="form_group">
                <label for="confirmPass">Confirm Password :</label>
                <input type="password" id="confirmPass" name="confirmPass" value="<?php echo $rows['password']; ?>">
                <span class="error">
                    <?php echo $errors['confirmPass'] ?? '' ?>
                </span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <div class="form_group">
                <button type="submit" name="submit">Edit User</button>
            </div>
        </form>

        <div class="form_group">
            <button type="submit" name="cancel"><a href="../dashboard.php" style="color: white;">Cancel</a></button>
        </div>
    </div>
</body>

</html>

<script src="../js/dynamicCountryState.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        countryStateDropdowns('country', 'state', '<?= $_POST['country_id'] ?? '' ?>', '<?= $_POST['state'] ?? '' ?>')
    });
</script>