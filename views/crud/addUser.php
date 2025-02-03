<?php
include '../../config/dataBaseConnect.php';
include '../formValidation.php';

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

        $sql = "INSERT INTO users (first_name, last_name, email, phone_no, address, country_id, state_id, pincode, password) 
        VALUES ('$firstName', '$lastName', '$email', '$phoneNo', '$address', '$country' , '$state','$pincode', '$hashedPassword')";

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["add_message"] = "User Added Successfully !";
            header("Location: ../dashboard.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
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
    <title>Add User</title>

</head>

<body>

    <?php
    include '../layout/navbar.php';
    ?>

    <div class="container">
        <h1>Enter User Details</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

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
                <textarea name="address" id="address" value="">
                <?php if (isset($_POST['address'])) {
                    echo $_POST['address'];
                }
                ?>
                </textarea>
                <span class="error">
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
                <button type="submit"> Add User</button>
            </div>
        </form>
        <div class="form_group">
            <a href="../dashboard.php"><button type="submit" style="float: right;">Cancel</button></a>
        </div>
    </div>
</body>

</html>

<script src="../js/dynamicCountryState.js"></script>
<script>
    document.addEventListener('DOMContentLoaded' , function(){
        countryStateDropdowns('country' , 'state' , '<?= $_POST['country'] ?? '' ?>' , '<?= $_POST['state'] ?? '' ?>')
    });
</script>

