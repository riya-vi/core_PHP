<?php
// include './config/dataBaseConnect.php';

// $newPasswordErr = $reEnterNewPassErr = "";


// if ($_SERVER["REQUEST_METHOD"] == "POST") {


//     if (empty($_POST["newPassword"])) {
//         $newPasswordErr = "Password is required";
//     } else {
//         $newPassword = test_input($_POST["newPassword"]);
//         if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
//             $newPasswordErr = "Password must be at least 8 characters,one letter,digit,special character";
//         }
//     }

//     if (empty($_POST["reEnterNewPass"])) {
//         $reEnterNewPassErr = "Re enter a password";
//     } else {
//         $reEnterNewPass = test_input($_POST["reEnterNewPass"]);
//         if ($_POST['reEnterNewPass'] !== $_POST['newPassword']) {
//             $reEnterNewPassErr = "password did not match!";
//         }
//     }
// }
// function test_input($data)
// {
//     return $data;
// }

// if(isset($_POST['newPassword'])){
//     $newPassword = $_POST['newPassword'] ;
//     $confirmNewPass = $_POST['reEnterNewPass'] ;


//     if(!empty($newPassword) && !empty($confirmNewPass)){

       
//     }

// }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h1>Reset Password </h1>
        <form action="resetPassword.php" method="POST">
            <div class="form_group">
                <label for="newPassword"> New Password:</label>
                <input type="password" name="newPassword" id="newPassword" >
                <span style="color: red;"><?= $passwordErr  ?></span>
            </div>
            <div class="form_group">
                <label for="reEnterNewPass">Re-Enter New Password:</label>
                <input type="password" name="reEnterNewPass" id="reEnterNewPass" >
                <span style="color: red;"><?= $newPasswordErr  ?></span>
            </div>
            <div class="form_group">
                <button type="submit" name="newPassword"> Reset Password</button>
            </div>
        </form>
    </div>

</body>

</html>