<?php

    include('../require/database.php');

    if (isset($_REQUEST['user_id']) && isset($_REQUEST['action']) && ($_REQUEST['action'] === 'accept' || $_REQUEST['action'] === 'reject')) {
        $user_id = (int)$_POST['user_id'];
        $name = $_POST['name'];
        $action = $_POST['action'];

        if ($action === 'accept') {
            $db->execute_query("UPDATE user SET is_approved = 'Approved' WHERE user_id = $user_id");
            $msg= "User $name with ID $user_id has been approved.";
        } elseif ($action === 'reject') {
            $db->execute_query("UPDATE user SET is_approved = 'Rejected' WHERE user_id = $user_id");
            $msg= "User  $name with ID $user_id has been rejected.";
        }

        header("Location: ../admin/admin_dashboard.php?page=1&msg=$msg#registration_requests");
        exit;

    }elseif(isset($_REQUEST['user_id']) && isset($_REQUEST['action']) && ($_REQUEST['action'] === "change_status")) {
        $user_id = (int)$_REQUEST['user_id'];
        $name = $_REQUEST['name'];
        $status = $_REQUEST['status'];

        if ($status === 'Active') {
           $check = $db->execute_query("UPDATE user SET is_active = 'Active' WHERE user_id = $user_id");
            $msg= "User $name with ID $user_id has been activated. $check";
        } elseif ($status === 'InActive') {
           $check =  $db->execute_query("UPDATE user SET is_active = 'InActive' WHERE user_id = $user_id");
            $msg= "User $name with ID $user_id has been deactivated. $check";
        }

        header("location: ../admin/manage_users.php?page=1&msg=$msg");
        exit;
    }elseif (isset($_REQUEST['user_id']) && isset($_REQUEST['action']) && ($_REQUEST['action'] === "update_user")) {
        $user_id = (int)$_REQUEST['user_id'];

        // Fetch current user data
        $existing = $db->fetch_one("SELECT * FROM user WHERE user_id = $user_id");

        if (!$existing) {
            header("Location: ../admin/manage_users.php?page=1&msg=User not found");
            exit;
        }

        // Collect submitted fields
        $first_name = trim($_REQUEST['first_name']);
        $last_name = trim($_REQUEST['last_name']);
        $email = trim($_REQUEST['email']);
        $password = trim($_REQUEST['password']); // Optional
        $gender = $_REQUEST['gender'];
        $dob = $_REQUEST['date_of_birth'];
        $address = trim($_REQUEST['address']);
        $updated_at = date('Y-m-d H:i:s');

        $updates = [];

        if ($first_name !== $existing['first_name']) {
            $updates[] = "first_name = '" . addslashes($first_name) . "'";
        }
        if ($last_name !== $existing['last_name']) {
            $updates[] = "last_name = '" . addslashes($last_name) . "'";
        }
        if ($email !== $existing['email']) {
            $updates[] = "email = '" . addslashes($email) . "'";
        }
        if (!empty($password)) {
            $updates[] = "password = '" . addslashes($password) . "'";
        }
        if ($gender !== $existing['gender']) {
            $updates[] = "gender = '" . addslashes($gender) . "'";
        }
        if ($dob !== $existing['date_of_birth']) {
            $updates[] = "date_of_birth = '" . addslashes($dob) . "'";
        }
        if ($address !== $existing['address']) {
            $updates[] = "address = '" . addslashes($address) . "'";
        }
        if ($updated_at !== $existing['updated_at']) {
            $updates[] = "updated_at = '" . addslashes($updated_at) . "'";
        }



        // Handle image if uploaded
        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['user_image'];
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $new_name = "user_" . time() . "." . $ext;
            $path = "../images/users/" . $new_name;

            if (move_uploaded_file($image['tmp_name'], $path)) {
                $updates[] = "user_image = '$new_name'";
            }
        }

        if (!empty($updates)) {
            $update_query = "UPDATE user SET " . implode(", ", $updates) . ", updated_at = NOW() WHERE user_id = $user_id";
            $db->execute_query($update_query);
            $msg = "User updated successfully";
        } else {
            $msg = "No changes made";
        }

        header("location: ../admin/manage_users.php?page=1&msg=" . urlencode($msg));
        exit;
    } else {
        header("Location: ../admin/admin_dashboard.php?page=1&msg=Invalid request");
        exit;

    }



?>
