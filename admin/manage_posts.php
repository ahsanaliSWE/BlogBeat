<?php

   session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First..!&color=alert-danger");
        exit();
    } elseif ($_SESSION['user']['role_id'] != 1) {
        header("Location: ../home.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

  include('../require/general.php'); 
  include('../require/database.php');
  
  General::site_header("Manage Posts",true,true);
  
  General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'],"posts");
?>

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center">
      <h3 class="mb-0 fw-bold"><i class="fas fa-envelope-open-text me-2"></i>Manage Posts</h3>
      <a href="add_edit_post.php?action=add_post" class="btn btn-light btn-sm fw-bold">
        <i class="fas fa-plus me-1"></i> Add Post
      </a>
    </div>

    <!-- Search -->
    <div class="row my-3">
        <div class="col-md-6 input-group w-50 mx-auto">
            <span class="input-group-text fw-bold">Search</span>
            <input type="text" id="postSearchInput" name="postSearchInput" class="form-control" placeholder="Search Post title..." value="<?= $search??"" ?>" onkeyup="load_posts(1, this.value)">
            <button class="input-group-text" onclick="clear_search()"><i class="fa-solid fa-broom"></i></i></button>
            <button class="btn btn-dark"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="card-body bg-secondary-subtle text-dark rounded-bottom-4">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center bg-white">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Blog</th>
              <th>Author</th>
              <th>Status</th>
              <th>Comments</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="table_body">
            <!-- Sample Row -->
            
            <!-- Add more rows dynamically -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

      <!-- Alert Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-dark shadow rounded-4">
                  <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="alertModalLabel"><i class="fa-light fa-circle-exclamation"></i> Alert</h5>
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




<?php
    $posts = $db->fetch_all("SELECT * FROM post");

    foreach ($posts as $post) {
     $post_id = (int)$post['post_id']; // cast to int for safety
    
      $post = $db->fetch_one("
        SELECT p.*, b.blog_title, b.user_id 
        FROM post p 
        JOIN blog b ON p.blog_id = b.blog_id 
        JOIN user u ON b.user_id = u.user_id 
        WHERE p.post_id = $post_id
    ");

    $categories = $db->fetch_all("
        SELECT c.category_title 
        FROM post_category pc 
        JOIN category c ON pc.category_id = c.category_id 
        WHERE pc.post_id = $post_id
    ");
    
    // Fetch attachments of this post
    $attachments = $db->fetch_all("
        SELECT * FROM post_atachment WHERE post_id = $post_id
    ");


    ?>
       <!-- View Post Modal -->
      <div class="modal fade" id="viewPostModal<?= $post['post_id'] ?>" tabindex="-1" aria-labelledby="viewPostModalLabel<?= $post['post_id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header bg-dark text-white">
              <h5 class="modal-title fw-bold">
                <i class="fas fa-eye me-2"></i> View Post Details
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light p-4">

              <div class="mb-3 text-center">
                <?php if (!empty($post['featured_image']) && file_exists("../images/posts/" . $post['featured_image'])): ?>
                  <img src="../images/posts/<?= $post['featured_image'] ?>" class="img-fluid rounded-3" alt="Post Image" style="max-height: 250px; object-fit: cover;">
                <?php else: ?>
                  <img src="../images/posts/blog.jpg" class="img-fluid rounded-3" alt="Default Post Image" style="max-height: 250px; object-fit: cover;">
                <?php endif; ?>
              </div>
                
              <h4 class="fw-bold"><?= $post['post_title'] ?></h4>
              <p class="text-muted mb-2"><strong>Blog:</strong> <?= $post['blog_title'] ?></p>
                
              <p class="text-muted"><strong>Summary:</strong> <?= $post['post_summary'] ?></p>
                
              <div class="mb-3">
                <label class="fw-bold text-dark">Full Description:</label>
                <p><?= $post['post_description'] ?></p>
              </div>
                
              <div class="mb-3">
                <label class="fw-bold text-dark">Categories:</label><br>
                <?php foreach ($categories as $cat){ ?>
                  <span class="badge bg-primary me-1"><?= $cat['category_title'] ?></span>
                <?php } ?>
              </div>
                
              <div class="row">
                <div class="col-md-4 mb-2">
                  <strong>Status:</strong>
                  <?php if ($post['post_status'] === 'Active'){ ?>
                    <span class="badge bg-success">Active</span>
                  <?php }else{ ?>
                    <span class="badge bg-secondary">Inactive</span>
                  <?php } ?>
                </div>
                <div class="col-md-4 mb-2">
                  <strong>Comments:</strong>
                  <?php if ($post['is_comment_allowed'] == 1){ ?>
                    <span class="badge bg-info">Allowed</span>
                  <?php }else{ ?>
                    <span class="badge bg-warning text-dark">Not Allowed</span>
                  <?php } ?>
                </div>
                <div class="col-md-4 mb-2">
                  <strong>Created:</strong>
                  <span class="text-muted"><?= date('Y-m-d', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="col-md-4 mb-2">
                  <strong>Updated:</strong>
                  <span class="text-muted"><?= $post['updated_at'] == null ? "Null" : date('Y-m-d', strtotime($post['updated_at'])) ?></span>
                </div>
              </div>
                  
              <div class="mt-4">
                <h6 class="fw-bold text-dark">Attachments:</h6>
                <?php if (!empty($attachments)){ ?>
                <ul class="list-group list-group-flush">
                  <?php foreach ($attachments as $attach){ 
                    $ext = pathinfo($attach['post_attachment_title'], PATHINFO_EXTENSION);
                    $iconClass = "fas fa-file"; 
                  
                    if (in_array(strtolower($ext), ['pdf'])) {
                      $iconClass = "fas fa-file-pdf text-danger";
                    } elseif (in_array(strtolower($ext), ['jpg','jpeg','png'])) {
                      $iconClass = "fas fa-image text-success";
                    } elseif (in_array(strtolower($ext), ['zip','rar','7z'])) {
                      $iconClass = "fas fa-file-archive text-warning";
                    }
                  ?>
                  <li class="list-group-item bg-light">
                    <i class="<?= $iconClass ?> me-2"></i>
                    <a href="<?= $attach['post_attachment_path'] ?>" target="_blank" class="link-primary text-decoration-none">
                      <?= $attach['post_attachment_title'] ?>
                    </a>
                  </li>
                  <?php } ?>
                </ul>
                <?php }else{ ?>
                  <p class="text-muted fst-italic">No attachments available.</p>
                <?php } ?>
              </div>
                
            </div>
          </div>
        </div>
      </div>
                
        <?php
    }


?>

        


<script>
      load_posts();

      function clear_search(){
           var search = document.getElementById("postSearchInput");
           search.value = "";
           load_posts(1);
       }

       function load_posts(page = 1, search = "") {
           var ajax_request = null;
           if (window.XMLHttpRequest) {
               ajax_request = new XMLHttpRequest();
           } else {
               ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
           }
          
           ajax_request.onload = function () {
               if (ajax_request.status === 200) {
                   document.getElementById("table_body").innerHTML = ajax_request.responseText;
               }
           };
           ajax_request.open("GET", "../ajax/post_process.php?action=load_posts&page=" + page + "&search=" + encodeURIComponent(search), true);
           ajax_request.send();
       }

       function update_status(post_id, status) {
            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
      
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    const message = ajax_request.responseText;

                    document.getElementById("alertModalBody").innerText = message;

                    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                    alertModal.show();
                    load_posts();
                }
            };
          
            ajax_request.open("POST", "../ajax/post_process.php?action=update_status", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("post_id=" + post_id + "&status=" + status);
        }

        function allow_comments(post_id, status) {
            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
      
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    const message = ajax_request.responseText;

                    document.getElementById("alertModalBody").innerText = message;

                    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                    alertModal.show();
                    load_posts();
                }
            };
          
            ajax_request.open("POST", "../ajax/post_process.php?action=allow_comments", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("post_id=" + post_id + "&status=" + status);
        }


     document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              const search = document.querySelector("#blogSearchInput")?.value || "";
              load_posts(page, search);
              document.getElementById("table_body").scrollIntoView({ behavior: "smooth" });
          }
        });

</script>




<?php
    General::site_script(true);
?>