<?php
    session_start();

    include('require/general.php');

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }

    General::site_header("Contact Us");

?>
         <!--  get alert -->
        <?php if (isset($_GET['msg'])){ ?>
                <script>
                    window.addEventListener('DOMContentLoaded', function () {
                        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                        var alertModalBody = document.getElementById('alertModalBody');
                        alertModalBody.innerText = "<?php echo htmlspecialchars($_GET['msg']); ?>";
                        alertModal.show();
                    });
                </script>
            <?php } ?>
          <!--  get alert -->

          <!-- Alert Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-dark shadow rounded-4">
                  <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="alertModalLabel"><i class="fas fa-user"></i> Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center text-dark fs-6 px-4 py-3 fw-bold" id="alertModalBody">
                    <!-- Message will be injected here -->
                  </div>
                  <div class="modal-footer justify-content-center bg-light rounded-bottom">
                    <button type="button" class="btn btn-outline-dark px-4" data-bs-dismiss="modal">OK</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Alert Modal -->
<?php

    if (isset($_SESSION['user']) && $_SESSION['user']['is_active'] == 'Active') {
    
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "contact_us");
        General::user_feedback($_SESSION['user']['user_id'], $_SESSION['user']['first_name'] ." ". $_SESSION['user']['last_name'], $_SESSION['user']['email']);

    }elseif(isset($_SESSION['user']) && $_SESSION['user']['is_active'] == 'InActive'){
        
        header("Location: login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
        exit();

    } else {
        
        General::site_navbar(false, null, null, null,"contact_us");
        General::guest_feedback();
    }
?>
    <script>
        function validate_feedback(is_login = false) {

            if (is_login) {
                var feedback = document.getElementById("feedback").value;

                if (feedback.trim() === "") {
                    alert("Please enter your feedback.");
                    return false;
                }

            } else {

                var name = document.getElementById("name").value;
                var email = document.getElementById("email").value;
                var feedback = document.getElementById("feedback").value;
                var email_pattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;

                if (name.trim() === "") {
                    alert("Please enter your name.");
                    return false;
                }

                if (email.trim() === "") {
                    alert("Please enter your email.");
                    return false;
                }

                if (!email_pattern.test(email)) {
			        alert("Please enter a valid email address.");
                    return false;
		        }

            }
    
            return true;
        }

    </script>
<?php

    General::site_script();

    General::site_footer();
?>