<?php
    
    session_start();

    require('require/database.php');
    require('require/general.php');

    if (isset($_SESSION['user'])) {
      if ($_SESSION['user']['role_id'] == 1) {

          header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Approved'  && $_SESSION['user']['is_active'] == 'Active'){ 

          header("Location: home.php?msg=Already Logged In..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Approved' && $_SESSION['user']['is_active'] == 'InActive'){

          header("Location: login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Pending'){

          header("Location: login.php?msg=Your Account is Pending for Approval by Admin. Please wait for admin approval.&color=alert-warning");
          exit();
      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Rejected'){
          header("Location: login.php?msg=Account Request Rejected by Admin. Please contact admin for more information.&color=alert-danger");
          exit();
      }
  }

    $form_data = $_SESSION['form_data'] ?? [];
    $form_msg  = $_SESSION['form_msg'] ?? [];

    unset($_SESSION['form_data'], $_SESSION['form_msg']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <script src="require/client_side_validation.js"></script>
</head>
<body class="bg-warning">



  <div class="container-fluid">
        <div class="col-12 my-4 mb-lg-0 d-flex d-lg-none justify-content-center text-center">
          <div>
            <img src="images/png/logo-no-background.png" alt="BlogBeat" class="img-fluid" style="max-height: 80px;" />
            <p class="text-muted mt-2 fw-bold"><i>Where Ideas Find Their Voice</i></p>
          </div>
        </div>

    <?php 
        General::site_navbar(false, null, null, null, "login"); 
    ?>

    <div class="container py-5">

    
    <div class="row justify-content-center">
        
          <div class="col-lg-4 mb-4 mb-lg-0 d-none d-lg-flex justify-content-center justify-content-lg-start align-items-center order-lg-0" >
            <img src="images/png/logo-no-background.png" alt="BlogBeat" class="img-fluid" />
          </div>


        <div class="col-lg-8">

          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body bg-dark text-white rounded-4 shadow-lg">
              <h3 class="text-center mb-4 fw-bold">Registration Form</h3>

              <div class="card-body bg-secondary-subtle text-dark rounded-4">
                  
                  <p class="text-muted mb-3">
                    <span class="text-danger">*</span> Required fields
                  </p>
                  
                  <form class="row g-3" action="process/login_register_process.php" method="POST" enctype="multipart/form-data" >

                    <div class="col-md-6 form-floating">
                      <input type="text" id="first_name" name="first_name" value="<?= $first_name??""; ?>" class="form-control" placeholder="First Name" onblur="first_name_check()">
                      <label for="first_name">
                        First Name<span class="text-danger">*</span>
                      </label>
                      <span id="first_name_msg" class="text-danger"><?= $form_msg['first_name_msg'] ?? "";  ?></span>
                    </div>

                    <div class="col-md-6 form-floating">
                      <input type="text" id="last_name" name="last_name" value="<?= $last_name??""; ?>" class="form-control" placeholder="Last Name" onblur="last_name_check()" >
                      <label for="last_name">
                        Last Name<span class="text-danger">*</span>
                      </label>
                      <span id="last_name_msg" class="text-danger"><?= $form_msg['last_name_msg'] ??  ""; ?></span>
                    </div>

                    <div class="col-12 form-floating">
                      <input type="email" id="email" name="email" value="<?= $email??""; ?>" class="form-control" placeholder="Email" onblur="check_email(true)" >
                      <label for="email">
                        Email<span class="text-danger">*</span>
                      </label>
                      <span id="email_msg" class="text-danger"><?= $form_msg['email_msg'] ??  ""; ?></span>
                    </div>

                    <div class="col-md-6 form-floating">                    
                        <input type="password" id="password" name="password" value="<?= $password??""; ?>" class="form-control" placeholder="Password" onblur="check_password()" >
                        <label for="password">
                          Password<span class="text-danger">*</span>
                        </label>
                        <span id="password_msg" class="text-danger"><?= $form_msg['password_msg'] ??  ""; ?></span>
                    </div>

                    <div class="col-md-6 form-floating">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" onblur="check_confirm_password()">
                        <label for="confirm_password">
                          Confirm Password<span class="text-danger">*</span>
                        </label>
                        <span id="confirm_password_msg" class="text-danger"><?= $form_msg['confirm_password_msg']  ??  ""; ?></span>
                    </div>

                    <div class="col-12">
                      <label class="form-label d-block fw-bold">
                        Gender<span class="text-danger">*</span>
                      </label>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input border-dark" type="radio" name="gender" id="gender" value="Male" <?php echo(isset($gender) && $gender == 'Male')?"checked":""; ?> oncheck="check_gender()">
                        <label class="form-check-label" for="male">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input border-dark" type="radio" name="gender" id="gender" value="Female" <?php echo(isset($gender) && $gender == 'Female')?"checked":"";  ?> oncheck="check_gender()">
                        <label class="form-check-label" for="female">Female</label>
                      </div>
                      <span id="gender_msg" class="text-danger"><?= $form_msg['gender_msg']  ??  ""; ?></span>
                    </div>

                    <div class="col-md-6">
                      <label for="dob" class="form-label fw-bold">
                        Date of Birth<span class="text-danger">*</span>
                      </label>
                      <input type="date" class="form-control" id="dob" name="dob" min="1920-01-01" onchange="check_dob()">
                      <span id="dob_msg" class="form-text text-danger" ><?= $form_msg['dob_msg']  ??  ""; ?></span>
                    </div>

                    <div class="col-md-6">
                      <label for="profile_picture" class="form-label fw-bold">
                        Profile Picture
                      </label>
                      <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept=".jpg, .jpeg, .png," onchange="validate_profile_pic()">
                      <span id="profile_pic_msg" class="text-danger"><?= $form_msg['image_msg'] ?? "" ?></span>
                    </div>

                    <div class="col-12">
                      <label for="address" class="form-label fw-bold">
                        Address
                      </label>
                      <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"></textarea>
                      <span id="address_msg" class="text-danger"><?= $form_msg['address_msg'] ?? "" ?></span>
                    </div>

                    <div class="col-12 text-center">
                      <button class="btn btn-dark px-5 py-2 fw-bold w-100" type="submit" name="register" value="register" id="registerbtn">Register</button>
                    </div>

                   </form>
                     <div class="col-12 text-center">
                      <p class="mt-3 mb-0 text-muted">
                        Already have an account?
                        <a href="login.php" class="link-dark link-offset-2 link-opacity-100 link-opacity-50-hover link-underline-opacity-0 fw-bold">Login</a>
                      </p>
                    </div>
                </div>
            </div>
          </div>

      </div>

    </div>
    </div>
  </div>

<?php 
    General::site_footer();
    General::site_script();
?>