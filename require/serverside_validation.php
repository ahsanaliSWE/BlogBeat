<?php

    $alpha_pattern = '/^[A-Z][a-z]{2,}$/';
    $email_pattern = '/^[a-z]+\d*[@]{1}[a-z]+[.](com|net|org)$/';

    $first_name_msg = $last_name_msg = $email_msg = $password_msg = $confirm_password_msg = $gender_msg = $image_msg = null;

    $first_name_flag = $last_name_flag = $email_flag = $password_flag = $confirm_password_flag = $gender_flag = $image_flag = $dob_flag = true;
    $flag = false;

    $first_name         = trim($_POST["first_name"] ?? "");
    $last_name          = trim($_POST["last_name"] ?? "");
    $email              = trim($_POST["email"] ?? "");
    $password           = trim($_POST["password"] ?? "");
    $confirm_password   = trim($_POST["confirm_password"] ?? "");
    $gender             = $_POST["gender"] ?? null;
    $image              = $_FILES["user_image"] ?? null;
    $dob                = $_POST["dob"] ?? null;

    // First Name
    if ($first_name === "") {
        $fist_name_flag = false;
        $first_name_msg = "Required Field";
    } elseif (!preg_match($alpha_pattern, $first_name)) {
        $fist_name_flag = false;
        $first_name_msg = "Pattern Must Be Like e.g., Ahmed";
    }

    // Last Name
    if ($last_name === "") {
        $last_name_flag = false;
        $last_name_msg = "Required Field";
    } elseif (!preg_match($alpha_pattern, $last_name)) {
        $last_name_flag= false;
        $last_name_msg = "Pattern Must Be Like e.g., Khan";
    }

    // Email
    if ($email === "") {
        $email_flag = false;
        $email_msg = "Required Field";
    } else {
        $query = "SELECT * FROM user WHERE email = '$email'";
        $exists = $db->fetch_one($query);

        if ($exists) {
            $email_flag = false;
            $email_msg = "Already Registered with this Email";
        } elseif (!preg_match($email_pattern, $email)) {
            $email_flag = false;
            $email_msg = "Pattern Must Be Like e.g., ahmed123@gmail.com";
        }
    }

    // Password
    if ($password === "") {
        $password_flag = false;
        $password_msg = "Required Field";
    }elseif (strlen($password) < 8) {
        $password_flag = false;
        $password_msg = "Password Must Be At Least 8 Characters";
    } elseif (!preg_match('/[a-zA-Z]/', $password)) {
        $password_flag = false;
        $password_msg = "Password Must Contain At One Letter";
    }  elseif (!preg_match('/[0-9]/', $password)) {
        $password_flag = false;
        $password_msg = "Password Must Contain At Least One Number";
    }

    // Confirm Password
    if ($confirm_password === "") {
        $password_flag = false;
        $confirm_password_msg = "Required Field";
    } elseif ($confirm_password !== $password) {
        $password_flag = false;
        $confirm_password_msg = "Password and Confirm Password Must Be Same";
    }


    // Gender
    if (!$gender) {
        $gender_flag = false;
        $gender_msg = "Required Field";
    }

    // Date of Birth
    if ($dob === "") {
        $dob_flag = false;
        $dob_msg = "Required Field";
    } else {
        $dob_date = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dob_date)->y;

        if ($age < 13) {
            $dob_flag = false;
            $dob_msg = "You Must Be At Least 13 Years Old";
        }
    }

    // Image
    $allowed_types = ["jpg", "jpeg", "png"];
    $ext = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
    $size_limit = 2 * 1024 * 1024;

    if(empty($image["name"])) {
        $image_msg = "Optional Field";
    }elseif (!in_array($ext, $allowed_types)) {
        $image_flag = false;
        $image_msg = "Only JPG, JPEG, PNG files are allowed";
    } elseif ($image["size"] > $size_limit) {
        $image_flag = false;
        $image_msg = "Image must be less than 2MB";
    }

    if ($first_name_flag && $last_name_flag && $email_flag && $password_flag && $confirm_password_flag && $gender_flag && $dob_flag && $image_flag) {
        $flag = true;
    } else {
        $flag = false;
    }

?>
