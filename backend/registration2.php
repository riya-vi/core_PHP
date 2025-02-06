<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1> Registration form</h1>

        <form method="post" action="backend.php">

            <div class="form_group">
                <label for="firstName">First name:</label>
                <input type="text" id="firstName" name="firstName"
                    value="<?= (isset($_POST['firstName'])) ? strip_tags($_POST['firstName']) : '' ?>"> <span class="error">
                    <?php echo $errors['firstName'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="lastName">Last name:</label>
                <input type="text" id="lastName" name="lastName"
                    value="<?= (isset($_POST['lastName'])) ? strip_tags($_POST['lastName']) : '' ?>"><span class="error">
                    <?php echo $errors['lastName'] ?? '';  ?>
                </span>
            </div>

            <div class="form_group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email"
                    value="<?= (isset($_POST['email'])) ? strip_tags($_POST['email']) : '' ?>">
                <span class="error">
                    <?php echo $errors['email'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="phone">Phone No. :</label>
                <input type="text" id="phone" name="phone"
                    value="<?= (isset($_POST['phone'])) ? strip_tags($_POST['phone']) : '' ?>"><span class="error">
                    <?php echo $errors['phone'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="address">Address :</label>
                <textarea name="address" id="address" value=""> <?php if (isset($_POST['address'])) {
                                                                    echo trim($_POST['address']);
                                                                } ?>
                </textarea>
                <span class="error" onchange="" onclick="">
                    <?php echo $errors['address'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="country">Country :</label>
                <select name="country" id="country" value="">
                    <option value="">Select Country</option>
                </select>
                <span class="error">
                    <?php echo $errors['country'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="state">State :</label>
                <select name="state" id="state" value="">
                    <option value="">Select State</option>
                </select>
                <span class="error">
                    <?php echo $errors['state'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="pincode">Pincode :</label>
                <input type="text" name="pincode" id="pincode"
                    value="<?= (isset($_POST['pincode'])) ? strip_tags($_POST['pincode']) : '' ?>"><span class="error">
                    <?php echo $errors['pincode'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="password">Password :</label>
                <input type="password" id="password" name="password"
                    value="<?= (isset($_POST['password'])) ? strip_tags($_POST['password']) : '' ?>"><span
                    class="error">
                    <?php echo $errors['password'] ?? ''; ?>
                </span>
            </div>

            <div class="form_group">
                <label for="confirmPass">Confirm Password :</label>
                <input type="password" id="confirmPass" name="confirmPass"
                    value="<?= (isset($_POST['confirmPass'])) ? strip_tags($_POST['confirmPass']) : '' ?>"><span
                    class="error">
                    <?php echo $errors['confirmPass'] ?? '' ?>
                </span>
            </div>

            <div class="form_group">
                <button type="submit"> register</button>
            </div>

            <div class="form_group">
                <p>already have an account ? <a href="./login.php"><span>Login</span></a></p>
            </div>
        </form>
    </div>
</body>

</html>

<script src="./js/dynamicCountryState.js"></script>
<script>
    document.addEventListener('DOMContentLoaded' , function(){
        countryStateDropdowns('country' , 'state' , '<?= $_POST['country'] ?? '' ?>' , '<?= $_POST['state'] ?? '' ?>')
    });
</script>
```



<?php
include '../config/dataBaseConnect.php';
require_once './formValidation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validateForm($_POST);

    if (empty($errors)) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phone'];
        $address =  trim($_POST['address']);
        $country = $_POST['country'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $password = $_POST['password'];

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
}
?>


<?php
include '../config/dataBaseConnect.php';
require_once './formValidation.php';

function handleFormSubmission($postData, $connection) {
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
    $errors = handleFormSubmission($_POST, $connection);
}
?>

