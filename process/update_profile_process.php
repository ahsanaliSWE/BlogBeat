<?php
session_start();
include('../require/database.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php?msg=Login First..!&color=alert-danger");
    exit();
}

if ($_SESSION['user']['is_active'] == 'InActive') {
    header("Location: ../login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
    exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_profile') {

    $user_id = $_SESSION['user']['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $password = trim($_POST['password'] ?? '');

    $update_data = [
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'email'       => $email,
        'gender'      => $gender,
        'address'     => $address,
        'updated_at'  => date('Y-m-d H:i:s')
    ];

    // If password is provided, hash and include it
    if (!empty($password)) {
        $update_data['password'] = $password;
    }

    // Handle profile image
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === 0) {
        $ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (in_array(strtolower($ext), $allowed_ext)) {
            $upload_dir = '../images/users/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . "_" . basename($_FILES['user_image']['name']);
            $target_file = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['user_image']['tmp_name'], $target_file)) {
                $update_data['user_image'] = $file_name;
                unlink('../images/users/' . $_SESSION['user']['user_image']); // Remove old image
            }
        }
    }

   
    $update_result = $db->update('user', $update_data, "user_id = '$user_id'");

    if ($update_result) {

        foreach ($update_data as $key => $value) {
            $_SESSION['user'][$key] = $value;
        }

        header("Location: ../edit_profile.php?msg=Profile updated successfully&color=alert-success");
        exit();
    } else {
        header("Location: ../edit_profile.php?msg=Failed to update profile&color=alert-danger");
        exit();
    }
} else {
    header("Location: ../edit_profile.php?msg=Invalid request&color=alert-warning");
    exit();
}
?>
