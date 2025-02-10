<?php
include '../layout/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit User';
include '../common/htmlHeader.php' ;
?>

<body>
    <div class="container">
        <h1>Edit User Details</h1>

        <?php
        if (!empty($rows['file_path'])) {
            $imagePath = '../' . $rows['file_path'];
        } else {
            $imagePath = '../storage/default.jpg';
        }
        ?>
        <img src="<?= $imagePath ?>" alt="Profile Image" width="150" height="150" class="center">

        <form method="post" action="../backend/editUser.php?id=<?php echo $rows['id']; ?>" enctype="multipart/form-data">
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
                        $selected = $rows['country_id'] == $country['id'] ? 'selected' : '';
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
            <button type="submit" name="cancel"><a href="../frontend/dashboard.php" style="color: white;">Cancel</a></button>
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