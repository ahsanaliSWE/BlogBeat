<?php

  session_start();
  if (!isset($_SESSION['user'])) {
      header("Location: ../login.php?msg=Please login to access this page&color=alert-danger");
      exit();
  }elseif ($_SESSION['user']['role_id'] != 1) {
      header("Location: ../home.php?msg=You are not authorized to access this page&color=alert-danger");
      exit();
  }else{

        include('../require/database.php');

        include('../require/general.php'); 

        $blog_count = $db->fetch_one("SELECT COUNT(*) as total FROM blog");
        $post_count = $db->fetch_one("SELECT COUNT(*) as total FROM post WHERE post_status = 'active'");
        $user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE is_active = 1");
        $feedback_count = $db->fetch_one("SELECT COUNT(*) as total FROM user_feedback");


        

        General::site_header("Admin Dashboard",true,true);

        General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'],"dashboard");
?>

      
        <!-- Dashboard -->
        <section class="container py-5">

            <!--  Welcome to the Admin Dashboard! -->
            <div class="text-center mb-4">
                <h1 class="fw-bold">Welcome, <?php echo $_SESSION['user']['first_name']; ?>!</h1>
                <p class="lead">Manage your content and users efficiently.</p>
            </div>
            <!--  Welcome to the Admin Dashboard! -->                  

          <!-- Overview Section -->
          <h2 class="fw-bold mb-4"><i class="fas fa-chart-simple me-2"></i>Overview</h2>
            <div class="row g-4" id="dashboard_cards">
              
                <!-- overview cards inserted here -->

            </div>
          <!-- Overview Section -->

  
            <!-- Management Sections -->
            <div class="mt-5">
              <h4 class="fw-bold mb-4"><i class="fas fa-sliders-h me-2"></i>Manage Content</h4>
  
              <div class="row g-4">
  
                <?php
                      General::management_card("Users", "fas fa-users", "View, edit, or remove registered users. Manage roles and permissions.", 
                                              "text-info", "Manage Users", "manage_users.php");
  
                      General::management_card("Blogs", "fas fa-blog", "Create and manage blogs, customize settings, and assign authors.", 
                                              "text-warning", "Manage Blogs", "manage_blogs.php");
  
                      General::management_card("Posts", "fas fa-file-alt", "Add, update, delete, and publish posts with categories and tags.", 
                                              "text-success", "Manage Posts", "manage_posts.php");
                                        
                      General::management_card("Categories", "fas fa-tags", "Create, edit, and delete categories for better content organization.",
                                              "text-danger", "Manage Categories", "manage_categories.php");
  
                      General::management_card("Comments", "fas fa-comments", "Moderate user comments, allow or disable replies, and control spam.", 
                                              "text-primary", "Manage Comments", "manage_comments.php");
                      
                      General::management_card("Feedback", "fas fa-envelope-open-text", "View and respond to user feedback or support messages.", 
                                              "text-danger", "View Feedback", "manage_feedback.php");

                      General::management_card("Followers", "fas fa-users", "Manage followers, view their profiles, and control follow requests.",
                                              "text-light", "Manage Followers", "manage_followers.php");
                ?>
  
              </div>
            </div>
            <!-- Management Sections -->

                
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

            <div id="spinner" class="text-center my-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>


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

                
                <!-- New user Requests -->
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-5">
                  <!-- Header -->
                  <div class="card-header bg-dark text-white rounded-0">
                    <h4 class="mb-0 d-flex align-items-center">
                      <i class="fas fa-user-clock me-2 fw-bold fs-5"></i> New Registration Requests
                    </h4>
                  </div>
                  
                  <!-- Table -->
                  <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                      <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark text-center">
                          <tr>
                            <th>#</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody class="text-center" id="user_requests">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>                      
                  
                </div>
                <!-- New user Requests -->



        </section>
        <!-- Dashboard -->

      <script>
      
      loadDashboardStats()
      load_user_requests();

      setInterval(() => {
          loadDashboardStats();
          load_user_requests();
      }, 10000);
                      

      function loadDashboardStats() {
          var ajax_request = new XMLHttpRequest();
          var cards = document.getElementById("dashboard_cards")
            
          ajax_request.onreadystatechange = function () {
              if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                  cards.innerHTML = ajax_request.responseText;
              }
          }
        
          ajax_request.open("GET", "../ajax/dashboard_stats.php", true);
          ajax_request.send();
      }


      function load_user_requests(page = 1) {
          const ajax_request = new XMLHttpRequest();
          const table_body = document.getElementById("user_requests");
            
          ajax_request.onreadystatechange = function () {
              if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                  table_body.innerHTML = ajax_request.responseText;
              }
          }
        
          ajax_request.open("GET", "../ajax/user_process.php?action=load_user_requests&page=" + page);
          ajax_request.send();
      }
      
      
    function approve_disapprove(event, action, user_id) {
        event.preventDefault(); 

        var ajax_request = null;
        var name = event.target.querySelector("input[name='name']").value;
        var email = event.target.querySelector("input[name='email']").value;


        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest();
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById("spinner").style.display = "block";

        ajax_request.onreadystatechange = function() {


            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                const message = ajax_request.responseText;
                        
                // Set the modal message
                document.getElementById("alertModalBody").innerText = message;
                
                // Show the modal
                const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                document.getElementById("spinner").style.display = "none";

                alertModal.show();
                        
                // Refresh the table after approval/rejection
                load_user_requests();
            }
        };

        var url = "../ajax/user_process.php";
        var params = "action=" + action + "&user_id=" + user_id + "&name=" + name + "&email=" + email;
        ajax_request.open("POST", url, true);
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_request.send(params); 
    }

        document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              load_user_requests(page);
              document.getElementById("registration_requests").scrollIntoView({ behavior: "smooth" });
          }
      });


    </script>

        
<?php
    General::site_script(true);    
  }   
?>
