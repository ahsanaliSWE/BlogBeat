<?php
  session_start();

  if (isset($_SESSION['user'])) {
      if ($_SESSION['user']['role_id'] == 1) {

          header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['status'] == 'Active'){ 

          header("Location: home.php?msg=Already Logged In..!");
          exit();

      }elseif($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['status'] == 'InActive'){

          header("Location: login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
          exit();

      }
  }

  include('require/general.php');
?>
  
        <?php 
          General::site_header("Forget Password");
          General::site_navbar(false, null, null, null); 
        ?>

          <!-- Login Card -->
          <div class="card shadow-lg border-0 rounded-4 col-lg-6 mx-auto my-4">
            <div class="card-body bg-dark text-white rounded-4 p-4">

              <h3 class="text-center fw-bold mb-4">Forget Your Password..?</h3>

              <div class="card-body bg-secondary-subtle text-dark rounded-4 p-4">
                <form class="row g-3" action="process/login_register_process.php" method="POST">

                  <?php

                    if (isset($_GET['msg'])) {
                
                        $msg = $_GET['msg'] ?? "Error!...";
                        $color = $_GET['color'] ?? "alert-info";

                      echo "<div class='alert $color text-center'>$msg</div>";
                    }
                  ?>

                  <div class="col-12 form-floating">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Your Email"  onblur="check_email()" Required>
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <span id="email_msg"></span>
                  </div>


                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-dark w-100 fw-bold" name="forget_password" value="forget_password">Forget Password</button>
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
