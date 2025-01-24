<?php


include '../config/dataBaseConnect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validations
    $firstNameErr  = $lastNameErr = $emailErr  = $phoneErr = $addressErr  = $countryErr = $stateErr  = $pincodeErr = $passwordErr = $confirmPassErr = "";
    $isAnyError = false;

    if (empty($_POST["firstName"])) {
        $firstNameErr = "First Name is required";
        $isAnyError = true;
    } else {
        $firstName = test_input($_POST["firstName"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
            $firstNameErr = "Only letters and white spaces are allowed";
            $isAnyError = true;
        }
    }

    if (empty($_POST["lastName"])) {
        $lastNameErr = "Last Name is required";
        $isAnyError = true;
    } else {
        $lastName = test_input($_POST["lastName"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
            $lastNameErr = "Only letters and white spaces are allowed";
            $isAnyError = true;
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $isAnyError = true;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $isAnyError = true;
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone No. is required";
        $isAnyError = true;
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneErr = "Phone number must be 10 digits";
            $isAnyError = true;
        }
    }

    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
        $isAnyError = true;
    } else {
        $address = test_input($_POST["address"]);
    }

    if (empty($_POST["country"])) {
        $countryErr = "Must select a country";
        $isAnyError = true;
    } else {
        $country = test_input($_POST["country"]);
    }

    if (empty($_POST["states"])) {
        $stateErr = "Must select a state";
        $isAnyError = true;
    } else {
        $state = test_input($_POST["states"]);
    }

    if (empty($_POST["pincode"])) {
        $pincodeErr = "Pincode is required";
        $isAnyError = true;
    } else {
        $pincode = test_input($_POST["pincode"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $isAnyError = true;
    } else {
        $password = test_input($_POST["password"]);
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $passwordErr = "Password must be at least 8 characters,one letter,digit,special character";
            $isAnyError = true;
        }
    }

    if (empty($_POST["confirmPass"])) {
        $confirmPassErr = "Confirm Password is required";
        $isAnyError = true;
    } else {
        $confirmPass = test_input($_POST["confirmPass"]);
        if ($_POST['password'] !== $_POST['confirmPass']) {
            $confirmPassErr = "Password did not match.";
            $isAnyError = true;
        }
    }

    // insert data in database
    if ($isAnyError == false) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phone'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $state = $_POST['states'];
        $pincode = $_POST['pincode'];
        $password = $_POST['password'];

        //encrypt password
        $options = ["cost" => 10];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

        $sql = "INSERT INTO `users` (`first_name` ,`last_name`, `email`, `phone_no`, `address` , `country`, `state` , `pincode`, `password`) VALUES ('$firstName' ,'$lastName', '$email', '$phoneNo', '$address', '$country', '$state' , '$pincode', '$hashedPassword')";

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["add_message"] = "User Added Successfully !";
            header("Location: dashboard.php");
        } else {
            echo "error inserting data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }

        // connection close
        $connection->close();
    }
}


function test_input($data)
{
    return $data;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/dashboardStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Add User</title>

</head>

<body>

<?php
include './layout/navbar.php' ;
?>
    <h1>Enter User Details</h1>

    <div class="container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

<div class="form_group">
    <label for="firstName">First name:</label>
    <input type="text" id="firstName" name="firstName"
        value="<?= (isset($_POST['firstName'])) ? strip_tags($_POST['firstName']) : '' ?>"> <span class="error">
        <?php echo $firstNameErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="lastName">Last name:</label>
    <input type="text" id="lastName" name="lastName"
        value="<?= (isset($_POST['lastName'])) ? strip_tags($_POST['lastName']) : '' ?>"><span class="error">
        <?php echo $lastNameErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="email">Email :</label>
    <input type="text" id="email" name="email"
        value="<?= (isset($_POST['email'])) ? strip_tags($_POST['email']) : '' ?>">
    <span class="error">
        <?php echo $emailErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="phone">Phone No. :</label>
    <input type="text" id="phone" name="phone"
        value="<?= (isset($_POST['phone'])) ? strip_tags($_POST['phone']) : '' ?>"><span class="error">
        <?php echo $phoneErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="address">Address :</label>
    <textarea name="address" id="address" value=""> <?php if (isset($_POST['address'])) {
                                                        echo $_POST['address'];
                                                    } ?>
</textarea>
    <span class="error" onchange="" onclick="">
        <?php echo $addressErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="country">Country :</label>
    <select name="country" id="selectCountry" value="">
        <option value="">Select Country</option>
    </select>
    <span class="error">
        <?php echo $countryErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="state">State :</label>
    <select name="states" id="selectStates" value="">
        <option value="">Select State</option>
    </select><span class="error">
        <?php echo $stateErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="pincode">Pincode :</label>
    <input type="text" name="pincode" id="pincode"
        value="<?= (isset($_POST['pincode'])) ? strip_tags($_POST['pincode']) : '' ?>"><span class="error">
        <?php echo $pincodeErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="password">Password :</label>
    <input type="password" id="password" name="password"
        value="<?= (isset($_POST['password'])) ? strip_tags($_POST['password']) : '' ?>"><span
        class="error">
        <?php echo $passwordErr; ?>
    </span>
</div>

<div class="form_group">
    <label for="confirmPass">Confirm Password :</label>
    <input type="password" id="confirmPass" name="confirmPass"
        value="<?= (isset($_POST['confirmPass'])) ? strip_tags($_POST['confirmPass']) : '' ?>"><span
        class="error">
        <?php echo $confirmPassErr; ?>
    </span>
</div>

<div class="form_group">
    <button type="submit"> Add User</button>
</div>


</form>
    </div>


</body>

</html>


<script>
    const country = document.getElementById("selectCountry");
    const state = document.getElementById("selectStates");
    state.disabled = true;
    country.addEventListener("change", stateHandle);

    function stateHandle() {
        if (country.value === " ") {
            state.disabled = true;
        } else {
            state.disabled = false;
        }
    }

    // dynamic
    document.addEventListener('DOMContentLoaded', function() {
        console.log("on DOm");

        const countries = {
            "India": ["Gujarat", "Maharastra", "Tamilnadu", "Rajasthan"],
            "canada": ["Alberta", "BritishColumbia", "Manitoba", "Quebec"],
            "USA": ["California", "Alaska", "Georgia"],
            "Japan": ["Hokkaido", "Fukushima", "Hiroshima"]
        };
        const countrySelect = document.getElementById('selectCountry');
        const stateSelect = document.getElementById('selectStates');
        const selectedCountry = "<?php echo isset($_POST['country']) ? $_POST['country'] : ''; ?>";
        const selectedState = "<?php echo isset($_POST['states']) ? $_POST['states'] : ''; ?>";

        // console.log("selectedCountry", selectedCountry);

        for (let country in countries) {
            // console.log("country", country);

            let option = document.createElement('option');
            option.value = country;
            option.textContent = country;
            if (selectedCountry && country == selectedCountry) {
                option.selected = true;
            }
            // console.log("option", option);

            countrySelect.appendChild(option);
        }

        stateSelect.innerHTML = '<option value="" disabled selected>Select a state</option>';

        let states = countries[countrySelect.value];
        if (states) {
            console.log("dada");

            for (let state of states) {
                let option = document.createElement('option');
                option.value = state;
                option.innerText = state;
                if (selectedState && state == selectedState) {
                    option.selected = true;
                }
                console.log("option", option);

                stateSelect.appendChild(option);
            }
        }

        countrySelect.addEventListener('change', function() {
            console.log("nonad add");

            stateSelect.innerHTML = '<option value="" disabled selected>Select a state</option>';

            let states = countries[countrySelect.value];
            for (let state of states) {
                let option = document.createElement('option');
                option.value = state;
                option.innerText = state;
                if (selectedState && state == selectedState) {
                    option.selected = true;
                }
                console.log("option", option);

                stateSelect.appendChild(option);
            }
        });
    })
</script>