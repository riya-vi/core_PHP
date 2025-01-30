<?php

/**
 * Handles the logic for validation error in user input data
 *
 * @param  post data entered by user in forms
 * @return errors  
 */

function validateForm($data)
{
    echo $data['country'] ;
    // print_r($data);
    // die();  
    $errors = [];

    if (empty($data['firstName'])) {
        $errors['firstName'] = "First Name is required";
    } else {
        $firstName = test_input($data['firstName']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
            $errors['firstName'] = "Only letters and white spaces are allowed";
        }
    }

    if (empty($data['lastName'])) {
        $errors['lastName'] = "Last Name is required";
    } else {
        $lastName = test_input($data['lastName']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
            $errors['lastName'] = "Only letters and white spaces are allowed";
        }
    }

    if (empty($data['email'])) {
        $errors['email'] = "Email is required";
    } else {
        $email = test_input($data['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    if (empty($data['phone'])) {
        $errors['phone'] = "Phone No. is required";
    } else {
        $phone = test_input($data['phone']);
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors['phone'] = "Phone number must be 10 digits";
        }
    }

    if (empty($data['address'])) {
        $errors['address'] = "Address is required";
    } else {
        $address = test_input($data['address']);
    }

    if (empty($data['country'])) {
        $errors['country'] = "Country is required";
    } else {
        $country = test_input($data['country']);
    }

    if (empty($data['state'])) {
        $errors['state'] = "state is required";
    } else {
        $state = test_input($data['state']);
    }

    if (empty($data['pincode'])) {
        $errors['pincode'] = "Pincode is required";
    } else {
        $pincode = test_input($data['pincode']);
    }

    if (empty($data['password'])) {
        $errors['password'] = "password is required";
    } else {
        $password = test_input($data['password']);
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $errors['password'] = "Password must be at least 8 characters,one letter,digit,special character";
        }
    }

    if (empty($data['confirmPass'])) {
        $errors['confirmPass'] = "Confirm password is required";
    } else {
        $confirmPass = test_input($data['confirmPass']);
        if ($data['password'] !== $data['confirmPass']) {
            $errors['confirmPass'] = "Password did not match.";
        }
    }
    return $errors;
}


function validateFile($fileData)
{
    // echo $fileData ;
    // print_r($fileData) ;
    // var_dump($_FILES) ;
    // die();
    // $fileErr = [] ;

    // if (empty($fileData['profilePhoto'])) {
    //         $fileErr['filePath'] = "file is required";
    //     } else {
    //         $filePath = testFileInput($fileData['filePath']);
    //         $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    //         $fileSizeLimit = 5000000; // 5MB
    //         $fileType = $_FILES['profilePhoto']['type'];
    //         $fileSize = $_FILES['profilePhoto']['size'];
    //         if (!in_array($fileType, $allowedTypes)) {
    //             $fileErr['filePath'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
    //         }

    //         if ($fileSize > $fileSizeLimit) {
    //             $fileErr['filePath'] = "File size exceeds 5MB limit.";
    //         }
    // }
}

function testFileInput($fileData)
{
    // print_r($fileData) ;
    // echo $fileData ;
    // die() ;
    return $fileData;
}



function test_input($data)
{
    return htmlspecialchars(trim($data));
}
