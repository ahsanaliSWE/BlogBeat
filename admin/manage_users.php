<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First..!&color=alert-danger");
        exit();
    }elseif($_SESSION['user']['role_id'] != 1){
        header("Location: ../login.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

    include('../require/database.php');

    include('../require/general.php'); 


    General::site_header("Manage Users",true,true);

    General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'],"users");

?>

            <div id="spinner" class="text-center my-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

        <section class="container py-5">

            
          
            
            <div class="card shadow-lg border-0 rounded-4" id="all_users">
                <div class="card-body bg-dark text-white rounded-top-4">
                    <h3 class="fw-bold text-center"><i class="fas fa-users me-2"></i>Manage Users</h3>
                </div>
        
                <div class="card-body bg-light rounded-bottom-4">
        
                     <!-- Search -->
                    <div class="row mb-3">
                        <div class="col-md-6 input-group w-50 mx-auto">
                            <span class="input-group-text fw-bold">Search</span>
                            <input type="text" name="search" class="form-control" placeholder="Search users by name or email" value="<?= $search??"" ?>" onkeyup="load_users(1)">
                            <button class="btn btn-dark"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
        
                
                    <!-- User Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-hover text-center ">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users_table">
                                <!-- Dynamic content will load here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->

                </div>
            </div>
        </section>

        <div id="modal"></div>

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

      
     <script>

        load_users(1);
        load_modals();

        function load_users(page = 1) {
            var search = document.querySelector("input[name='search']").value;
            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
           
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    document.getElementById("users_table").innerHTML = ajax_request.responseText;
                }
            };

            ajax_request.open("GET", "../ajax/user_process.php?action=load_users&page=" + page + "&search=" + search, true);
            ajax_request.send();
        }

        function load_modals(){
            var ajax_request = new XMLHttpRequest();

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    document.getElementById("modal").innerHTML = ajax_request.responseText;
                }
            };

            ajax_request.open("GET", "../ajax/user_process.php?action=load_edit_models", true);
            ajax_request.send();
        }

        function update_status(action, user_id, name, email) {
            var ajax_request = new XMLHttpRequest();

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            document.getElementById("spinner").style.display = "block";

            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    const message = ajax_request.responseText;

                    document.getElementById("alertModalBody").innerText = message;

                    // Show the modal
                    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                    document.getElementById("spinner").style.display = "none";

                    alertModal.show();

                    load_users(1);
                }
            };

             var params = "user_id=" + encodeURIComponent(user_id) +
                 "&status=" + encodeURIComponent(action) +
                 "&name=" + encodeURIComponent(name) +
                 "&email=" + encodeURIComponent(email);

            ajax_request.open("POST", "../ajax/user_process.php?action=update_status", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send(params);
        }


        document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              load_users(page);
              document.getElementById("users_table").scrollIntoView({ behavior: "smooth" });
          }
      });

    </script>

<?php
   

    General::site_script(true);
?>