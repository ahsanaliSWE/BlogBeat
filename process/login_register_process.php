<?php
    session_start();
    require_once("../require/database.php");
    require_once("mail_process.php");

    if(isset($_REQUEST["login"]) && $_REQUEST["login"]=="login"){

        $query = "SELECT * FROM USER WHERE email='".$_REQUEST["email"]."' AND password='".$_REQUEST["password"]."'";
        
        $result = $db->fetch_one($query);
       
                
        if ($result) {

            /* redirecting bug  in remeber me*/
            if (isset($_REQUEST['remember_me'])) {
                setcookie("remember_email", $_REQUEST['email'], time() + (7 * 24 * 60 * 60), "/");
                setcookie("remember_password", $_REQUEST['password'], time() + (7 * 24 * 60 * 60), "/");
            } else {
                // Clear cookies if unchecked
                setcookie("remember_email", "", time() - 3600, "/");
                setcookie("remember_password", "", time() - 3600, "/");
            }

            // Admin login
            if ($result["role_id"] == 1) {
                if ($result["is_active"] == "Active") {
                    $_SESSION["user"] = $result;
                    header("Location: ../admin/admin_dashboard.php?login=success");
                    exit();
                } else {
                    header("Location: ../login.php?msg=Your Account is Inactive.&color=alert-danger");
                    exit();
                }
            }

            // User login
            if ($result["role_id"] == 2) {

                switch ($result["is_approved"]) {
                        case "Approved":
                            
                            if ($result["is_active"] == "Active") {

                                $_SESSION["user"] = $result;
                                header("Location: ../home.php?login=success");

                            }elseif ($result["is_active"] == "InActive"){

                                header("Location: ../login.php?msg=Your Account is InActive. Please contact admin for more information.&color=alert-danger");
                            }
                            break;
                        case "Pending":
                            header("Location: ../login.php?msg=Your Account is Pending for Approval by Admin. Please wait for admin approval.&color=alert-warning");
                            break;
                        case "Rejected":
                            header("Location: ../login.php?msg=Account Request Rejected by Admin. Please contact admin for more information.&color=alert-danger");
                            break;
                }
                
                exit();
            }
        }else {
            header("Location: ../login.php?msg=Invalid Email or Password&color=alert-danger");
            exit();
        }

    }elseif(isset($_REQUEST["register"]) && $_REQUEST["register"]=="register"){


        extract($_REQUEST);

        include("../require/serverside_validation.php");

        
        if ($flag === true) {

            $profile_pic_path = null;
            if (!empty($_FILES["profile_pic"]["name"])) {
                $file_name = time() . "_" . $_FILES["profile_pic"]["name"];
                $target_path = "../images/users/" . $file_name;
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_path)) {
                    $profile_pic_path = "images/users/" . $file_name;
                }
            }

            $data = [
                "role_id"       => 2, 
                "first_name"    => $first_name,
                "last_name"     => $last_name,
                "email"         => $email,
                "password"      => $password,
                "gender"        => $gender,
                "date_of_birth" => $dob,
                "user_image"    => $file_name ?? null,
                "address"       => $address ?? null,
                "is_approved"   => "Pending",
                "is_active"     => "InActive",
            ];

            $result = $db->insert("user", $data);

            if ($result) {
                
                header("Location: ../pdf/credentials.php?name=$first_name $last_name&email=$email&password=$password&gender=$gender&dob=$dob");

                exit(); 
            } else {
                header("Location: ../login.php?register=success&msg=Error occurred during registration. Database error&color=alert-danger");
                exit();
            }

            
        }else {
            // Store submitted values and messages in SESSION
            session_start();
            $_SESSION['form_data'] = $_POST;
            $_SESSION['form_msg'] = [
                'first_name_msg' => $first_name_msg,
                'last_name_msg' => $last_name_msg,
                'email_msg' => $email_msg,
                'password_msg' => $password_msg,
                'confirm_password_msg' => $confirm_password_msg,
                'gender_msg' => $gender_msg,
                'dob_msg' => $dob_msg,
                'image_msg' => $image_msg
            ];
            header("Location: ../register.php?error=validation");
            exit;
        }

    }elseif(isset($_REQUEST["forget_password"]) && $_REQUEST["forget_password"]=="forget_password"){

        print_r($_REQUEST);


        $query = 'SELECT * FROM user WHERE email = "'. $_REQUEST['email'].'"';

        $result = $db->fetch_one($query);

        if($result){

            forget_password_mail($result['first_name']." ".$result['last_name'], $result['email'], $result['password']);

            header("location: ../forget_password.php?msg=Your credentials are sent on your email..!&color=alert-success");
            exit();
        }else{
            header("location: ../forget_password.php?msg=Account Not Found&color=alert-danger");
            exit();

        }
    }

?>