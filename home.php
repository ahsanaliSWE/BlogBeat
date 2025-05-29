<?php
    session_start();

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }elseif ($_SESSION['user']['role_id'] == 2 && $_SESSION['user']['is_active'] == 'InActive') {
            header("Location: login.php?msg=Account is Inactive..!");
            exit();
        }
    }

    include('require/general.php');

    include('require/database.php');

    mysqli_report(false);

    General::site_header("Home");

    if (isset($_SESSION['user'])) {
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "home");
    } else {
        General::site_navbar(false, null, null, null, "home");
    }

    General::site_slider();
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

         <!-- main content -->
        <section class="container-fluid py-5">
            <div class="row">

                <!-- all posts -->  
                <div  class="col-lg-4 order-1 order-lg-0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body bg-dark text-white rounded-4 shadow-lg">
                            <h3 class="text-center mb-4 fw-bold" id="all_posts">All Posts</h3>

                            <div class="card-body bg-secondary-subtle text-dark rounded-4" id="all_posts_container">
                                

                            </div>

                        </div>
                    </div>
                </div>
                <!-- all posts -->


                <!-- Featured Post -->
                <div class="col-lg-8 order-0 order-lg-1 bg-secondary-subtle rounded-4 shadow-lg px-0 mb-4 mb-lg-0">
                    
                  <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body bg-dark text-white rounded-4 shadow-lg">
                      
                      <h3 class="text-center mb-4 fw-bold">Recent Posts</h3>

                          <div class="card-body bg-secondary-subtle text-dark rounded-4" id="recent_posts_container">
                                
                               <!--  recent posts show here -->
                                
                                      
                          </div>
                          
                    </div>
                  </div>

                </div>
                <!-- Featured Post -->

            </div>
        </section>
        <!-- main content -->

    <script>

        load_recent_posts();
        load_all_posts();
        

        function load_recent_posts(page = 1) {
            var ajax_request = null;
            var container = document.getElementById("recent_posts_container");

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }

            ajax_request.onreadystatechange = function () {
                if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                    container.innerHTML = ajax_request.responseText;
                }
            };

            ajax_request.open("GET", "ajax/post_process_user.php?action=load_recent_posts&page=" + page, true);
            ajax_request.send();
        }

        function load_all_posts(page = 1) {
            var ajax_request = null;
            var container = document.getElementById("all_posts_container");

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }

            ajax_request.onreadystatechange = function () {
                if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                    container.innerHTML = ajax_request.responseText;
                }
            };

            ajax_request.open("GET", "ajax/post_process_user.php?action=load_all_posts_sidebar&page=" + page, true);
            ajax_request.send();
        }


        document.addEventListener("click", function (e) {
          if (e.target.classList.contains("recent-posts-page")) {
            e.preventDefault();
            const page = e.target.getAttribute("data-page");
            document.getElementById("recent_posts_container").scrollIntoView({ behavior: "smooth" });
            load_recent_posts(page);
          }

          if (e.target.classList.contains("all-posts-page")) {
            e.preventDefault();
            const page = e.target.getAttribute("data-page");
            //document.getElementById("all_posts_container").scrollIntoView({ behavior: "smooth" });
            load_all_posts(page);
          }
        });


    </script>
<?php

    General::site_footer();

    General::site_script();
?>

