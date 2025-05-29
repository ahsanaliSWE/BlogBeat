<?php
    session_start();

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }

    include('require/database.php');
    include('require/general.php');

    $blog_id = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : 0;
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    if ($post_id <= 0) {
        header("Location: blogs.php?msg=Invalid Post ID!&color=alert-danger");
    }

    // Fetch post with blog info
    $post = $db->fetch_one("
        SELECT p.*, b.blog_title, u.first_name, u.last_name 
        FROM post p
        JOIN blog b ON p.blog_id = b.blog_id
        JOIN user u ON b.user_id = u.user_id
        WHERE p.post_id = $post_id AND p.post_status = 'Active'
    ");

  if (!$post) {
      header("Location: view_blog.php?blog_id=$blog_id&msg=Post not Found!&color=alert-danger");
      exit();
  }

  // Fetch categories
  $categories = $db->fetch_all("
      SELECT c.category_title 
      FROM post_category pc 
      JOIN category c ON pc.category_id = c.category_id 
      WHERE pc.post_id = $post_id
  ");

  // Fetch attachments
  $attachments = $db->fetch_all("
      SELECT * FROM post_atachment 
      WHERE post_id = $post_id AND is_active = 'Active'
  ");



  General::site_header(htmlspecialchars($post['post_title']));

  if (isset($_SESSION['user'])) {
      General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "posts");
  } else {
      General::site_navbar(false, null, null, null, "blogs");
  }
?>

<!-- Post Section -->
<section class="container my-5">
  <div class="card shadow-lg rounded-4 overflow-hidden border-0">
    
    <?php if($post['featured_image'] != null) { ?>
        <img src="images/posts/<?= htmlspecialchars($post['featured_image']) ?: 'blog.jpg' ?>" class="card-img object-fit-cover rounded-bottom-4" alt="Post Cover" style="height: 400px; width:auto">    
     <?php } ?>
    <div class="card-body bg-light p-4">
      <h2 class="card-title fw-bold text-dark"><?= htmlspecialchars($post['post_title']) ?></h2>

      <!-- Categories -->
      <div class="mb-3">
        <?php foreach ($categories as $cat){ ?>
          <span class="badge bg-info text-dark me-1"><?= htmlspecialchars($cat['category_title']) ?></span>
        <?php }?>
      </div>

      <!-- Summary -->
      <p class="card-text text-muted"><strong>Summary:</strong> <?= htmlspecialchars($post['post_summary']) ?></p>

      <!-- Full Description -->
      <p class="card-text text-muted"><strong>Description:</strong> <?= htmlspecialchars($post['post_description']) ?></p>

      <!-- Attachments -->
      <?php if ($attachments){ ?>
        <div class="mb-3">
          <h6 class="fw-bold text-dark">Attachments:</h6>
          <ul class="list-unstyled mb-0">
            <?php foreach ($attachments as $att){ 
                 $ext = pathinfo($att['post_attachment_title'], PATHINFO_EXTENSION);
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
              <?php if($iconClass == "fas fa-image text-success"){ ?>
                      <div class="col-md-6">
                      <img src="uploads/<?= $att['post_attachment_path'] ?>" class="mt-2" width="150" alt="Featured Image" />
                      </div>
             <?php }else{
                    ?>
                    <i class="<?= $iconClass ?> me-2"></i><?php } ?>
                    <a href="uploads/<?= $att['post_attachment_path'] ?>" target="_blank" class="link-primary text-decoration-none">
                      <?= $att['post_attachment_title'] ?>
                    </a>
                </li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>

      <div class="d-flex justify-content-between mt-4">
        <span class="text-muted">Author: <?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?></span>
        <small class="text-muted">Created: <?= date("Y-m-d", strtotime($post['created_at'])) ?></small>
        <small class="text-muted">Updated: <?= $post['updated_at'] ? date("Y-m-d", strtotime($post['updated_at'])) : 'N/A' ?></small>
      </div>
    </div>
  </div>
</section>


      <?php if($post['is_comment_allowed'] == 1){ ?>

        <!-- Comments -->
        <section class="container my-5">
          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body bg-dark text-white rounded-top-4 shadow-lg">
              <h3 class="text-center mb-0 fw-bold">Comments</h3>
            </div>

            <div class="px-4 pt-4 pb-0 bg-secondary-subtle text-dark rounded-bottom-4" id="comment_body" style="max-height: 500px; overflow-y: auto;">
                <!-- comments loaded here-->
            </div>

          </div>
            <!-- Comment Form -->
              <?php if (isset($_SESSION['user'])){ ?>
                <div class="my-4 bg-secondary-subtle p-4 rounded-4 shadow-sm mt-4">
                  <h5 class="fw-bold">Leave a Comment</h5>
                    <div class="mb-3">
                      <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Write your comment..." required></textarea>
                    </div>
                    <button name="action" value="add_comment" class="btn btn-dark fw-bold" onclick="add_comment(<?= $post_id ?>)">Submit</button>
                </div>
              <?php }else{ ?>
                <div class="alert alert-warning text-center fw-bold mt-4">
                  Please <a href="login.php" class="text-decoration-none">login</a> to leave a comment.
                </div>
              <?php } ?>
        </section>
      <?php }else{ ?>
        <div class="container alert alert-warning text-center fw-bold rounded-4 mt-4">
          Comments are disabled for this post.
        </div>
      <?php } ?>


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


<?php General::site_footer(); ?>

  <script>
      load_comments(<?= $post_id ?>);

      function load_comments(post_id){

            var comments_body = document.getElementById("comment_body");

            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
      
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    
                    comments_body.innerHTML = ajax_request.responseText;

                }
            };
          
            ajax_request.open("POST", "ajax/comment_process_user.php?action=load_comments", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("post_id=" + post_id);      
      }

      function add_comment(post_id){

          var comment = document.getElementById("comment").value;
  
          var ajax_request = null;


          if(comment == ""){
            alert("Comment Cannot be Empty");
            return;
          }


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
                    comment.value = "";
                    load_comments(post_id);
                }
            };
          
            ajax_request.open("POST", "ajax/comment_process_user.php?action=add_comment", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("post_id=" + post_id + "&comment=" + comment);

      }
  </script>
<?php General::site_script(); ?>
