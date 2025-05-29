<?php
  session_start();

  if (isset($_SESSION['user'])) {
      if ($_SESSION['user']['role_id'] == 1) {

          header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Pending'){

          header("Location: login.php?msg=Your Account is Pending for Approval by Admin. Please wait for admin approval.&color=alert-warning");
          exit();
          
      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Approved' && $_SESSION['user']['is_active'] == 'InActive'){

          header("Location: login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Approved'  && $_SESSION['user']['is_active'] == 'Active'){ 

          header("Location: home.php?msg=Already Logged In..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_approved'] == 'Rejected'){
          header("Location: login.php?msg=Account Request Rejected by Admin. Please contact admin for more information.&color=alert-danger");
          exit();
      }
  }

  include('require/general.php');
?>
  
        <?php 
          General::site_header("Login");
          General::site_navbar(false, null, null, null, "login"); 
        ?>

          <!-- Login Card -->
          <div class="card shadow-lg border-0 rounded-4 col-lg-6 mx-auto my-4">
            <div class="card-body bg-dark text-white rounded-4 p-4">

              <h3 class="text-center fw-bold mb-4">Login to BlogBeat</h3>

              <div class="card-body bg-secondary-subtle text-dark rounded-4 p-4">
                <form class="row g-3" action="process/login_register_process.php" method="POST">

                  <?php

                    if (isset($_GET['msg']) || isset($_GET['message'])) {
                        if (isset($_GET['msg'])) {
                          $msg = $_GET['msg'] ?? "Error!...";
                        } else {
                          $msg = urldecode($_GET['message']);
                        }
                      $color = $_GET['color'] ?? "alert-info";
                      echo "<div class='alert $color text-center'>$msg</div>";
                    }
                  ?>

                  <div class="col-12 form-floating">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email"  onblur="check_email()" value="<?= $_COOKIE['remember_email'] ?? '' ?>">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <span id="email_msg"></span>
                  </div>

                  <div class="col-12 form-floating">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="<?= $_COOKIE['remember_password'] ?? '' ?>">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <span id="password_msg"></span>
                  </div>

                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <input class="form-check-input border-dark" type="checkbox" id="remember_me" name="remember_me" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="remember_me">
                        Remember Me
                      </label>
                    </div>
                    <a href="forget_password.php" class="fw-semibold small link-dark link-offset-2 link-opacity-100 link-opacity-50-hover link-underline-opacity-0">Forgot Password?</a>
                  </div>

                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-dark w-100 fw-bold" name="login" value="login">Login</button>
                  </div>

                  <div class="col-12 text-center">
                    <p class="mt-3 mb-0 text-muted">
                      Don't have an account?
                      <a href="register.php" class="link-dark link-offset-2 link-opacity-100 link-opacity-50-hover link-underline-opacity-0 fw-bold">Register</a>
                    </p>
                  </div>

                </form>
              </div>

            </div>
          </div>
          <!-- Login Card -->

                  
        </div>
      </div>
    </div>

<?php
      General::site_footer();
      General::site_script();  
?>
