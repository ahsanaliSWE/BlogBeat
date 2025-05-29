<?php
print_r($_REQUEST);

    session_start();
    include('../require/general.php');
    include('../require/database.php');
    include('mail_process.php');

    if(isset($_REQUEST['user_feedback'])){

        $user_id = $_SESSION['user']['user_id'];
        $name = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
        $email = $_SESSION['user']['email'];
        $feedback = htmlspecialchars(trim($_POST['feedback']));

        if(empty($feedback)){
            header("Location: ../contact_us.php?msg=Please enter your feedback..!");
            exit();
        }

        $feedback_data = [
            'user_id' => $user_id,
            'user_name' => $name,
            'user_email' => $email,
            'feedback' => $feedback,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        $insert_feedback = $db->insert('user_feedback', $feedback_data);

        if ($insert_feedback) {
            notify_admins_feedback($name, $email, $feedback);
            header("Location: ../contact_us.php?msg=Feedback submitted successfully..!");
            exit();
        } else {
            header("Location: ../contact_us.php?msg=Failed to submit feedback..!");
            exit();
        }
    }elseif(isset($_REQUEST['guest_feedback'])){

        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $feedback = htmlspecialchars(trim($_POST['feedback']));

        if(empty($name) || empty($email) || empty($feedback)){
            header("Location: ../contact_us.php?msg=Please fill all fields..!");
            exit();
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: ../contact_us.php?msg=Invalid email format..!");
            exit();
        }

        $feedback_data = [
            'user_name' => $name,
            'user_email' => $email,
            'feedback' => $feedback,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        $insert_feedback = $db->insert('user_feedback', $feedback_data);

        if ($insert_feedback) {
            notify_admins_feedback($name, $email, $feedback);
            header("Location: ../contact_us.php?msg=Feedback submitted successfully..!");
            exit();
        } else {
            header("Location: ../contact_us.php?msg=Failed to submit feedback..!");
            exit();
        }
    }
?>