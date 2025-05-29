<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../libraries/PHPMailer/src/PHPMailer.php';
    require '../libraries/PHPMailer/src/SMTP.php';
    require '../libraries/PHPMailer/src/Exception.php';

    function registeration_mail($name, $email){
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username = 'johndoedummy79@gmail.com';               // SMTP username
            $mail->Password = 'rzxgrdjhdhnyowfe';                       // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('johndoedummy79@gmail.com', 'Admin');
            $mail->addAddress($email, $name);        // Add a recipient (user)

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Account Registration Pending Admin Approval';

            // Here is your HTML content
            $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                            </div>
                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Welcome, $name !</h2>
                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>Your account has been successfully registered. However, it is currently awaiting <strong style='color: rgb(255, 214, 10);'>admin approval</strong>.</p>
                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>Once your account is approved, you will receive a notification and be able to <a href='#' style='color: rgb(255, 214, 10);'>log in</a> and start using your account.</p>
                            <br>
                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for your patience.<br>Regards,<br>Admin Team</p>
                        </div>";

            // Set the body of the email
            $mail->Body = $message;

            //$mail->addAttachment('users_reports/credentials.pdf', "credentials $name.pdf"); 

            $mail->addReplyTo("johndoedummy79@gmail.com");
            // Send the email
            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: ".$mail->ErrorInfo;
        }

    }


    function forget_password_mail($name, $email, $password){

        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username = 'johndoedummy79@gmail.com';               // SMTP username
            $mail->Password = 'rzxgrdjhdhnyowfe';                       // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('johndoedummy79@gmail.com', 'Admin');
            $mail->addAddress($email, $name);        // Add a recipient (user)

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Account Password Recovery';

            // Here is your HTML content
            $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                 <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                            </div>
                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Hello $name</h2>
                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>Your account recovery credentials are:</p>
                           
                            <div style='padding: 15px; background-color:rgb(250, 250, 250); border: 1px solid rgb(250, 250, 250); border-radius: 8px; margin-bottom: 20px;'>

                                <p style='font-size: 16px; color: rgb(0, 0, 0);'><strong>Email: </strong>$email</p>
                                <p style='font-size: 16px; color: rgb(0, 0, 0);'><strong>Password: </strong>$password</p>
                            </div>
                            
                            <br />
                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for your patience. If you encounter any issues, feel free to contact us.<br>Regards,<br>Admin Team</p>
                        </div>";

            // Set the body of the email
            $mail->Body = $message;

            //$mail->addAttachment('users_reports/credentials.pdf', "credentials $name.pdf"); 

            $mail->addReplyTo("johndoedummy79@gmail.com");
            // Send the email
            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: ".$mail->ErrorInfo;
        }

    }


    function account_status_mail($name, $email, $status){

        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username = 'johndoedummy79@gmail.com';               // SMTP username
            $mail->Password = 'rzxgrdjhdhnyowfe';                       // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('johndoedummy79@gmail.com', 'Admin');
            $mail->addAddress($email, $name);        // Add a recipient (user)

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Account Status Update';


                    if($status == "Active"){
            
                             $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                                <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                                            </div>
                                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Hello $name,</h2>
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>We wanted to inform you that your account has been successfully <span style='border-radius: 10px; background-color: rgb(123, 221, 96); padding:4px'>Activated</span> and is now ready for use.</p>

                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>Please note that if your account was previously deactivated, it has now been reactivated. You can access all the features and benefits of your account once again.</p>

                                            <br />
                                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for being a part of BlogBeat. If you encounter any issues, feel free to contact us.<br>Regards,<br>Admin Team</p>
                                        </div>";

                        }elseif($status == "InActive"){
                        
                            $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                                <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                                            </div>
                                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Hello $name,</h2>
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>We regret to inform you that your account has been <span style='border-radius: 10px; background-color: rgb(240, 37, 34); padding:4px'>Deactivated</span> and is no longer active.</p>  
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>If you believe this is an error or if you wish to reactivate your account, please contact our support team for assistance.</p>

                                            <br />
                                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for being a part of BlogBeat. If you need any help, feel free to contact us.<br>Regards,<br>Admin Team</p>
                                        </div>";
                        
                        }elseif($status == "Approved"){
                        
                            $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                                <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                                            </div>
                                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Hello, $name!</h2>
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>We are pleased to inform you that your account registration has been successfully <span style='border-radius: 10px; background-color: rgb(50, 205, 50); padding:4px'>Approved</span>.</p>  
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>However, your account is still pending activation. Once activated, you'll be able to access all features. Please check your email for the activation link.</p>

                                            <br />
                                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for choosing BlogBeat. If you need any assistance, feel free to contact us.<br>Regards,<br>Admin Team</p>
                                        </div>";
                        
                        }elseif($status == "Rejected"){
                        
                            $message = "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #FFF4C2; border: 1px solid #E1E1E1; border-radius: 10px;'>
                                            <div style='text-align: center; font-size: 32px; font-weight: bold; background-color: rgb(255, 214, 10); padding:4px; border-radius: 10px;'>
                                                <span style='font-family: sans-serif; color: rgb(0, 0, 0);'>BlogBeat</span>
                                            </div>
                                            <h2 style='color: rgb(0, 0, 0); font-size: 24px;'>Hello $name,</h2>
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>We regret to inform you that your account registration has been <span style='border-radius: 10px; background-color: rgb(240, 37, 34); padding:4px'>Rejected</span>.</p>  
                                            <p style='font-size: 16px; color: rgb(0, 0, 0);'>If you have any questions or concerns regarding this decision, please feel free to reach out to our support team for clarification.</p>

                                            <br />
                                            <p style='font-size: 14px; color: rgb(85, 85, 85);'>Thank you for your understanding. If you need any assistance, feel free to contact us.<br>Regards,<br>Admin Team</p>
                                        </div>";
                        
                        }

            // Set the body of the email
            $mail->Body = $message;


            $mail->addReplyTo("johndoedummy79@gmail.com");
            // Send the email
            $mail->send();
            echo "Email has been sent to: ";

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: ".$mail->ErrorInfo;
        }

    }


    function notify_admins_feedback($name, $email, $message) {
        global $db;

        $admins = $db->fetch_all("SELECT email, CONCAT(first_name, ' ', last_name) AS full_name FROM user WHERE role_id = 1 AND is_active = 'Active' AND is_approved = 'Approved'");
        
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'johndoedummy79@gmail.com'; // replace
                $mail->Password   = 'rzxgrdjhdhnyowfe'; // replace
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('johndoedummy79@gmail.com', 'BlogBeat Notification');
                foreach ($admins as $admin) {
                    $mail->addAddress($admin['email'], $admin['full_name']);
                }

                $mail->isHTML(true);
                $mail->Subject = 'New Feedback Submitted';

                $mail->Body = "
                                <div style='font-family: Arial, sans-serif; padding: 25px; background-color: #FFF4C2; border: 1px solid #e0e0e0; border-radius: 12px;'>

                                    <!-- Header -->
                                    <div style='text-align: center; padding: 10px 0 20px 0; background-color: #ffd60a; border-radius: 10px;'>
                                      <h2 style='margin: 0; color: #000; font-weight: bold;'>📝 New Feedback Alert - BlogBeat</h2>
                                    </div>

                                    <!--  Content -->
                                    <div style='padding: 20px; background-color: #ffffff; border-radius: 10px; margin-top: 20px;'>
                                      <p style='font-size: 16px; color: #333;'><strong>Name:</strong> $name</p>
                                      <p style='font-size: 16px; color: #333;'><strong>Email:</strong> $email</p>
                                      <p style='font-size: 16px; color: #333;'><strong>Message:</strong><br><span style='background-color: #f7f7f7; display: block; padding: 10px; border-left: 4px solid #ffd60a;'> $message</span></p>
                                    </div>

                                    <!-- Footer -->
                                    <p style='text-align: center; font-size: 13px; color: #666; margin-top: 30px;'>
                                      This is an automated notification from <strong>BlogBeat</strong>.<br>
                                      Please do not reply directly to this message.
                                    </p>
                                </div>
                            ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Feedback email to admin failed: " . $e->getMessage());
            }
    }

    function blog_follower_notification($blog_id, $post_title, $post_summary){

        global $db;
        
        $blog = $db->fetch_one("SELECT blog_title FROM blog WHERE blog_id = " . (int)$blog_id);

        if (!$blog) return;

         $blog_title = $blog['blog_title'];

         $followers = $db->fetch_all("
             SELECT u.first_name, u.last_name, u.email 
             FROM following_blog fb
             JOIN user u ON fb.follower_id = u.user_id
             WHERE fb.blog_following_id = $blog_id AND fb.status = 'Followed'
         ");

         if (empty($followers)) return;


         try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'johndoedummy79@gmail.com'; // replace
                $mail->Password   = 'rzxgrdjhdhnyowfe'; // replace
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('johndoedummy79@gmail.com', 'BlogBeat Notification');

                foreach ($followers as $follower) {
                    $full_name = $follower['first_name'] . ' ' . $follower['last_name'];
                    $mail->addAddress($follower['email'], $full_name);
                }

                $mail->isHTML(true);
                $mail->Subject = "New Post Published in '$blog_title'";

                $mail->Body = "
                                <div style='font-family: sans-serif; background-color: #FFF9DC; border: 1px solid #FFDB58; border-radius: 12px; padding: 24px; color: #333;'>

                                    <!-- BlogBeat Header -->
                                    <div style='background-color: #FFD60A; padding: 10px 20px; border-radius: 10px 10px 0 0; text-align: center;'>
                                        <h1 style='margin: 0; font-size: 28px; font-weight: bold; color: #000;'>📣 BlogBeat</h1>
                                        <p style='margin: 0; font-size: 14px; color: #222;'>Where Ideas Find Their Voice</p>
                                    </div>

                                    <!-- Body -->
                                    <div style='padding: 20px;'>
                                        <h2 style='color: #111;'>New Post in <strong>$blog_title</strong></h2>

                                        <p style='font-size: 16px;'><strong>Title:</strong> $post_title</p>
                                        <p style='font-size: 16px;'><strong>Summary:</strong><br>$post_summary</p>
                                    </div>

                                    <!-- Footer -->
                                    <div style='border-top: 1px solid #eee; padding-top: 12px; font-size: 13px; color: #555; text-align: center;'>
                                        You're receiving this because you follow <strong>$blog_title</strong> on <strong>BlogBeat</strong>.<br>
                                        <i>If you no longer want to receive updates, you can unfollow the blog anytime.</i>
                                    </div>
                                </div>
                            ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Feedback email to admin failed: " . $e->getMessage());
            }




        
    }

?>


