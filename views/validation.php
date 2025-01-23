<?php

function validateInput($data, $type) {
    $error = '';
    switch ($type) {
        case 'text':
            if (empty($data)) {
                $error = "This field is required";
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $data)) {
                $error = "Only letters and white spaces are allowed";
            }
            break;

        case 'email':
            if (empty($data)) {
                $error = "Email is required";
            } elseif (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format";
            }
            break;

        case 'phone':
            if (empty($data)) {
                $error = "Phone No. is required";
            } elseif (!preg_match("/^[0-9]{10}$/", $data)) {
                $error = "Phone number must be 10 digits";
            }
            break;

        case 'password':
            if (empty($data)) {
                $error = "Password is required";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $data)) {
                $error = "Password must be at least 8 characters, one letter, one digit, and one special character";
            }
            break;

        case 'confirm_password':
            if (empty($data)) {
                $error = "Confirm Password is required";
            }
            break;
    }

    return $error;
}
?>
