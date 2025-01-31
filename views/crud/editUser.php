<?php
include './dataBaseConnect.php';
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

        $countryList = [
            '1' => 'india',
            '2' => 'United States',
            '3' => 'Canada',
            '4' =>  'japan',
        ];

        $options = ["cost" => 10];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

        $defaultPhoto = '../storage/default.jpg';
        $defaultPath = '../storage/default.jpg';
        // $fileDestination = 'assa';
        // print_r($_FILES['profilePhoto']);
        if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
            $file = $_FILES['profilePhoto'];
            //  var_dump($file);
            $uploadDir = realpath(__DIR__ . '/../../storage/profile_images/') .'/';
            $fileName =  basename($file['name']);
            $fileDestination = $uploadDir . $fileName;   

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            // if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $fileDestination)) {
            //     echo "File successfully moved.";
            //     $fileName = $fileDestination;
            // } else {
            //     echo "Error moving file.";
            //     echo $_FILES['profilePhoto']['error'];
            //     $fileName = $defaultPhoto;
            // }

            // $uploads_dir = 'uploads/';
            // $name = 'demo.png';
            // if (is_uploaded_file($_FILES['profilePhoto']['tmp_name']))
            // {       
            //     echo "uploadded";
            //     //in case you want to move  the file in uploads directory
            //     move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $uploadDir.$name);
            //     echo 'moved file to destination directory';
            //     exit;
            // }else{
            //     echo "not uploadded";
            // }

            if (in_array($_FILES['profilePhoto']['type'], $allowedTypes) && $_FILES['profilePhoto']['size'] < 5000000) { 
                if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'],$uploadDir. $_FILES['profilePhoto']['name'])) {
                    $fileName = $fileDestination;
                } else {
                    echo "Error uploading file.";
                    // echo $_FILES['profilePhoto']['error'];
                    $fileName = $defaultPhoto;
                    // exit;
                }
            } else {
                echo "Invalid file type or size.";
                $fileName = $defaultPhoto;
                // exit;
            }
        } else {
            // echo "in else";
            $fileName = $defaultPhoto;
        }

        // echo "FInal one";
        echo $fileDestination;
        $sql = "UPDATE `users` SET  `first_name` = '$firstName' ,`last_name` ='$lastName', `email`=  '$email', `phone_no`='$phoneNo', `address`='$address' , `country`='$countryList[$country]', `state` ='$state' , `file_path` = '$fileDestination' WHERE `id`= '$id'";

        if ($connection->query($sql)) {
            // session_start();
            // $_SESSION["edit_message"] = "Record Updated Successfully !";
            // header("Location: ../dashboard.php");
        } else {
            echo "error updating  data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
            die;
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getCountries') {
    $query = "SELECT country_id , name FROM countries";
    $result = $connection->query($query);
    $countries = [];
    while ($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    echo json_encode($countries);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getStates' && isset($_GET['country_id'])) {
    isset($_GET['country_id']) ;
    die() ;
    $countryId = $_GET['country_id'];
    $query = "SELECT state_id, name FROM states WHERE country_id = $countryId";
    $result = $connection->query($query);

    $states = [];
    while ($row = $result->fetch_assoc()) {
        $states[] = $row;
    }

    echo json_encode($states);
    exit;
}
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

    <?php
    $id = $_GET['id'];
    $query = "SELECT * FROM `users` WHERE id = " . $_GET['id'];
    echo $query;
    if ($result = $connection->query($query)) {
        while ($rows = $result->fetch_assoc()) {
    ?>
            <div class="container">
                <h1>Edit User Details</h1>
                <form method="post" action="editUser.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                    <div class="form_group">
                        <label for="firstName">Profile Photo :</label>
                        <input type="file" id="profilePhoto" name="profilePhoto">
                    </div>
                    <div class="form_group">
                        <label for="firstName">First name:</label>
                        <input type="text" id="firstName" name="firstName"
                            value="<?php echo $rows['first_name']; ?>"> <span class="error">
                            <?php echo $errors['firstName'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="lastName">Last name:</label>
                        <input type="text" id="lastName" name="lastName"
                            value="<?php echo $rows['last_name']; ?>"><span class="error">
                            <?php echo $errors['lastName'] ?? '';  ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email"
                            value="<?php echo $rows['email']; ?>">
                        <span class="error">
                            <?php echo $errors['email'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="phone">Phone No. :</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo $rows['phone_no']; ?>"><span class="error">
                            <?php echo $errors['phone'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="address">Address :</label>
                        <textarea name="address" id="address" value=""><?php echo $rows['address']; ?>
                        </textarea>
                        <span class="error">
                            <?php echo $errors['address'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="country">Country :</label>
                        <select name="country" id="country" value="">
                            <option value=""><?php echo $rows['country']; ?></option>
                        </select>
                        <span class="error">
                            <?php
                            echo $errors['country'] ?? '';;
                            ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="state">State :</label>
                        <select name="state" id="state" value="">
                            <option value=""><?php echo $rows['state']; ?></option>
                        </select><span class="error">
                            <?php echo $errors['state'] ?? '';  ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="pincode">Pincode :</label>
                        <input type="text" name="pincode" id="pincode"
                            value="<?php echo $rows['pincode']; ?>"><span class="error">
                            <?php echo $errors['pincode'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="password">Password :</label>
                        <input type="password" id="password" name="password"
                            value="<?php echo $rows['password']; ?>"><span
                            class="error">
                            <?php echo $errors['password'] ?? ''; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="confirmPass">Confirm Password :</label>
                        <input type="password" id="confirmPass" name="confirmPass"
                            value="<?php echo $rows['password']; ?>"><span
                            class="error">
                            <?php echo $errors['confirmPass'] ?? '' ?>
                        </span>
                    </div>
                    <input type="text" name="id" style="visibility: hidden;" value="<?php echo $id ?>">
                    <div class="form_group">
                        <button type="submit">Edit User</button>
                    </div>
                </form>
            </div>
    <?php
        }
    }
    ?>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const selectedCountry = '<?= $_POST['country'] ?? '' ?>';
        const selectedState = '<?= $_POST['state'] ?? '' ?>';
        fetch('http://localhost/php/views/crud/editUser.php?action=getCountries')
            .then(response => response.json())
            .then(countries => {
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.country_id;
                    option.textContent = country.name;

                    // if (country.id === selectedCountry) {
                    //     option.selected = true;
                    // }
                    countrySelect.appendChild(option);
                });
                if (selectedCountry) {
                    fetchStates(selectedCountry, selectedState);
                }
            })
            .catch(error => console.error('Error fetching countries:', error));

        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            stateSelect.innerHTML = '<option value="">Select State</option>';

            if (countryId) {
                fetchStates(countryId);
            }
        });

        function fetchStates(countryId, preselectedState = '') {
            fetch(`http://localhost/php/views/crud/editUser.php?action=getStates&country_id=${countryId}`)
                .then(response => response.json())
                .then(states => {
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.name;
                        option.textContent = state.name;
                        // if (state.id === preselectedState) {
                        //     option.selected = true;
                        // }
                        stateSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching states:', error));
        }
    });
</script>