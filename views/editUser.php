<?php
include '../config/dataBaseConnect.php';
include './formValidation.php' ;

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

        $sql = "UPDATE `users` SET `first_name` = '$firstName' ,`last_name` ='$lastName', `email`=  '$email', `phone_no`='$phoneNo', `address`='$address' , `country`='$country', `state` ='$state' WHERE `id`= '$id'";

        if ($connection->query($sql)) {
            session_start();
            $_SESSION["edit_message"] = "Record Updated Successfully !";
            header("Location: dashboard.php");
        } else {
            echo "error updating  data .";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }

        $connection->close();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getCountries') {
    $query = "SELECT id , name FROM countries";
    $result = $connection->query($query);
    $countries = [];
    while ($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    echo json_encode($countries);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getStates' && isset($_GET['country_id'])) {
    $countryId = $_GET['country_id'];
    $query = "SELECT id, name FROM states WHERE country_id = $countryId";
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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/dashboardStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Edit User</title>
</head>

<body>
    <?php include './layout/navbar.php'; ?>

    <h1>Edit User Details</h1>

    <?php
    $id = $_GET['id'];
    $query = "SELECT * FROM `users` WHERE id = " . $_GET['id'];
    if ($result = $connection->query($query)) {
        while ($rows = $result->fetch_assoc()) {
    ?>

            <div class="container">
                <form method="post" action="editUser.php?id=<?php echo $id; ?>">
                    <div class="form_group">
                        <label for="firstName">First name:</label>
                        <input type="text" id="firstName" name="firstName"
                            value="<?php echo $rows['first_name']; ?>"> <span class="error">
                            <?php echo $errors['firstName'] ?? '';?>
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
                        <span class="error" onchange="" onclick="">
                            <?php echo $errors['address'] ?? '' ; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="country">Country :</label>
                        <select name="country" id="selectCountry" value="">
                            <option value=""><?php echo $rows['country']; ?></option>
                        </select>
                        <span class="error">
                            <?php echo $errors['country'] ?? '' ;; ?>
                        </span>
                    </div>
                    <div class="form_group">
                        <label for="state">State :</label>
                        <select name="state" id="selectStates" value="">
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


<!-- <script>
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
</script> -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const selectedCountry = '<?= $_POST['country'] ?? '' ?>';
        const selectedState = '<?= $_POST['state'] ?? '' ?>';
        fetch('http://localhost/php/views/registration.php?action=getCountries')
            .then(response => response.json())
            .then(countries => {
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;

                    if (country.id === selectedCountry) {
                        option.selected = true;
                    }
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
            fetch(`http://localhost/php/views/registration.php?action=getStates&country_id=${countryId}`)
                .then(response => response.json())
                .then(states => {
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.name;
                        if (state.id === preselectedState) {
                            option.selected = true;
                        }
                        stateSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching states:', error));
        }
    });
</script>