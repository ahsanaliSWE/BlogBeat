<?php
    session_start();

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }
    
    include('require/general.php');

    General::site_header("Blogs");
    

    if (isset($_SESSION['user'])) {
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "blogs");
    } else {
        General::site_navbar(false, null, null, null, "blogs");
    }
    

?>

            <!-- spinner -->
            <div id="spinner" class="text-center my-3" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <!-- spinner -->


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

       <!-- Blog Listing Page -->
        <section class="container py-5">

            <!-- Heading and Search -->
            <div class="row mb-4">
              <div class="col-lg-8">
                <h2 class="fw-bold">Explore Blogs</h2>
                <p class="text-muted">Browse through all public blogs, discover ideas, or start your own!</p>
              </div>
              <div class="col-lg-4">
                <form id="searchForm" class="d-flex justify-content-end mb-4">
                  <input class="form-control me-2" id="searchInput" type="search" placeholder="Search blogs..." name="search" onkeyup="load_blogs(1, this.value)" aria-label="Search">
                  <button class="btn btn-dark" type="submit">Search</button>
                </form>
              </div>
            </div>
            <!-- Search and Heading -->


          <!-- Blog Cards -->
          <div class="row g-4" id="blog_cards_container">       

          </div>

        </section>

  <script>
        load_blogs();
        function load_blogs(page = 1, search = '') {
            var ajax_request = null;         
            var cards = document.getElementById("blog_cards_container");

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
              ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }        
          
          ajax_request.onreadystatechange = function () {
              if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                  cards.innerHTML = ajax_request.responseText;
              }
          };         
        
          ajax_request.open("GET", "ajax/blog_process_user.php?action=load_blogs_user&page=" + page + "&search=" + search, true);
          ajax_request.send();
      }

      function follow_unfollow_blog(blog_id, status) {
          var ajax_request = new XMLHttpRequest();

          if (window.XMLHttpRequest) {
              ajax_request = new XMLHttpRequest();
          } else {
              ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
          }

          ajax_request.onreadystatechange = function () {
              if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                  const message = ajax_request.responseText;

                  document.getElementById("alertModalBody").innerText = message;

                  const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                  alertModal.show();
                  load_blogs();
              }
          };

          ajax_request.open("POST", "ajax/blog_process_user.php?action=follow_unfollow_blog", true);
          ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          ajax_request.send("blog_id=" + blog_id + "&status=" + status);
      }

      document.addEventListener("click", function (e) {
        if (e.target.classList.contains("pagination-link")) {
          e.preventDefault();
          const page = e.target.getAttribute("data-page");
          const query = document.getElementById('searchInput').value;
          load_blogs(page, query);
          document.getElementById("blogCardsContainer").scrollIntoView({ behavior: "smooth" });
        }
      });
  </script>



<?php
    General::site_script();
    General::site_footer();
?>
