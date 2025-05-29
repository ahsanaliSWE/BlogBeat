<?php
   session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First..!&color=alert-danger");
        exit();
    } elseif ($_SESSION['user']['role_id'] != 1) {
        header("Location: ../home.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

    include('../require/database.php');

    include('../require/general.php');  

    $query = "SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
              FROM blog b 
              JOIN user u ON b.user_id = u.user_id
              ";

    $blogs = $db->fetch_all($query);

    General::site_header("Manage Blogs",true,true);

    General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'],"blogs");

?>
       <div class="container py-5">

            <?php
                  if(isset($_GET['msg'])){
                    $msg = $_GET['msg'] ?? "Error!...";
                    $color = $_GET['color'] ?? "alert-info";
                    echo "<div class='alert $color text-center'>$msg</div>";
                    ?> <!-- <script>window.alert(<?= json_encode($msg) ?>);</script> --><?php
                  }   
            ?>

            <div class="card shadow-lg border-0 rounded-4">
              <div class="card-body bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center">
                <h3 class="mb-0 fw-bold"><i class="fas fa-blog me-2"></i>Manage Blogs</h3>
                <button class="btn btn-light btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createBlogModal">
                  <i class="fas fa-plus me-1"></i> Add Blog
                </button>
              </div>

               <!-- Search -->
              <div class="row my-3">
                  <div class="col-md-6 input-group w-50 mx-auto">
                      <span class="input-group-text fw-bold">Search</span>
                      <input type="text" id="blogSearchInput" name="blogSearchInput" class="form-control" placeholder="Search blog title..." value="<?= $search??"" ?>" onkeyup="load_blogs(1, this.value)">
                      <button class="input-group-text" onclick="clear_search()"><i class="fa-solid fa-broom"></i></i></button>
                      <button class="btn btn-dark"><i class="fas fa-search"></i></button>
                  </div>
              </div>

            <!-- Blogs Table -->
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body bg-light rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Posts/Page</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                              <!--  all blogs -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>



            <!-- Create Blog Modal -->
            <div class="modal fade" id="createBlogModal" tabindex="-1" aria-labelledby="createBlogModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form class="modal-content shadow-lg border-0 rounded-4" 
                      action="../process/blog_process.php?action=add_blog" 
                      method="POST" 
                      enctype="multipart/form-data" onsubmit="return add_blog_validation()">

                  <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="createBlogModalLabel">
                      <i class="fas fa-plus-circle me-2"></i>Create Blog
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body bg-light">
                    <div class="mb-3">
                      <label class="form-label">Blog Title</label>
                      <input type="text" class="form-control" name="blog_title" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Posts Per Page</label>
                      <input type="number" class="form-control" name="post_per_page" min="1" max="20" value="10" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Background Image</label>
                      <input type="file" class="form-control" name="background_image" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status" required>
                        <option value="Active">Active</option>
                        <option value="InActive">InActive</option>
                      </select>
                    </div>
                  </div>

                  <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-dark w-100" name="submit">Create Blog</button>
                  </div>

                </form>
              </div>
            </div>


        <?php foreach ($blogs as $blog) { ?>
          <div class="modal fade" id="editBlogModal<?= $blog['blog_id'] ?>" tabindex="-1" aria-labelledby="editBlogModalLabel<?= $blog['blog_id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content shadow-lg border-0 rounded-4"
                    method="POST"
                    enctype="multipart/form-data"
                    action="../process/blog_process.php?action=update_blog">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Blog</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">
                  <div class="mb-3">
                    <label class="form-label">Blog Title</label>
                    <input type="text" class="form-control" name="blog_title" value="<?= htmlspecialchars($blog['blog_title']) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Posts Per Page</label>
                    <input type="number" class="form-control" name="post_per_page" min="1" max="20" value="<?= $blog['post_per_page'] ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Change Background Image</label>
                    <input type="file" class="form-control" name="background_image">
                    <input type="hidden" name="old_background_image" value="<?= $blog['blog_background_image'] ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                      <option value="Active" <?= $blog['blog_status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                      <option value="InActive" <?= $blog['blog_status'] == 'InActive' ? 'selected' : '' ?>>Disabled</option>
                    </select>
                  </div>

                  <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
                </div>

                <div class="modal-footer bg-light">
                  <button type="submit" class="btn btn-primary w-100">Update Blog</button>
                </div>
              </form>
            </div>
          </div>
        <?php } ?>



       
       
          <?php foreach ($blogs as $blog){ ?>
           <div class="modal fade" id="viewBlogModal<?= $blog['blog_id'] ?>" tabindex="-1" aria-labelledby="viewBlogModalLabel<?= $blog['blog_id'] ?>" aria-hidden="true">
             <div class="modal-dialog modal-lg">
               <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">         

                 <!-- Modal Header -->
                 <div class="modal-header bg-dark text-white">
                   <h5 class="modal-title" id="viewBlogModalLabel<?= $blog['blog_id'] ?>">
                     <i class="fas fa-eye me-2"></i>View Blog Details
                   </h5>
                   <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>         

                 <!-- Modal Body -->
                 <div class="modal-body p-4 bg-light">        

                   <!-- Blog Cover Image -->
                   <div class="mb-4 text-center">
                     <img src="../images/blogs/<?= $blog['blog_background_image'] ?? 'default.jpg' ?>" 
                          class="img-fluid rounded-3 shadow-sm" 
                          style="max-height: 250px; object-fit: cover;" 
                          alt="Blog Cover">
                   </div>         

                   <!-- Blog Info Table -->
                   <div class="table-responsive">
                     <table class="table table-borderless">
                       <tbody>
                         <tr>
                           <th class="text-muted">Blog ID:</th>
                           <td>#<?= $blog['blog_id'] ?></td>
                         </tr>
                         <tr>
                           <th class="text-muted">User ID:</th>
                           <td>#<?= $blog['user_id'] ?> 
                             (<?= htmlspecialchars($blog['author_name'] ?? 'Unknown') ?>)
                           </td>
                         </tr>
                         <tr>
                           <th class="text-muted">Blog Title:</th>
                           <td><?= htmlspecialchars($blog['blog_title']) ?></td>
                         </tr>
                         <tr>
                           <th class="text-muted">Posts Per Page:</th>
                           <td><?= $blog['post_per_page'] ?></td>
                         </tr>
                         <tr>
                           <th class="text-muted">Status:</th>
                           <td>
                             <span class="badge <?= $blog['blog_status'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                               <?= $blog['blog_status'] ?>
                             </span>
                           </td>
                         </tr>
                         <tr>
                           <th class="text-muted">Created At:</th>
                           <td><?= date('Y-m-d h:i:s A', strtotime($blog['created_at'])) ?></td>
                         </tr>
                         <tr>
                           <th class="text-muted">Last Updated:</th>
                           <td><?= $blog['updated_at'] == ("0000-00-00 00:00:00")? "Null" : date('Y-m-d h:i:s A', strtotime( $blog['updated_at'])) ?></td>
                         </tr>
                       </tbody>
                     </table>
                   </div>         

                 </div>         

                 <!-- Modal Footer -->
                 <div class="modal-footer bg-light">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 </div>         

               </div>
             </div>
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



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            load_blogs(1); // Load blogs on page load
        });

           
        function clear_search(){
            var search = document.getElementById("blogSearchInput");
            search.value = "";
            load_blogs(1);
        }

        function load_blogs(page = 1, search = "") {
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

            ajax_request.open("GET", "../ajax/blog_process.php?action=load_blogs&page=" + page + "&search=" + encodeURIComponent(search), true);
            ajax_request.send();
        }

        function add_blog_validation(){

             var blog_title = document.getElementById("blog_title").value;
             var post_per_page = document.getElementById("post_per_page").value;
             var background_image = document.getElementById("background_image").value;
             var status = document.getElementById("status").value;

             var add_blog_form = document.getElementById("add_blog_form");
             

            if (blog_title === "" || post_per_page === "" || background_image === "" || status === "") {
                alert("Please fill in all fields.");
                return;
            }

            if(post_per_page < 1){
                alert("Posts per page must be at least 1.");
                return;
            }

            if(post_per_page > 20){
                alert("Posts per page max limit is 20.");
                return;
            }

            if (background_image) {
                var fileExtension = background_image.split('.').pop().toLowerCase();
                var allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (allowedExtensions.indexOf(fileExtension) === -1) {
                    alert("Invalid file type. Please upload an image file (jpg, jpeg, png, gif).");
                    return;
                }
            }

            document.getElementById("blog_title").value = "";
            document.getElementById("post_per_page").value = "";
            document.getElementById("background_image").value = "";
            document.getElementById("status").value = "";
            document.getElementById("createBlogModal").modal('hide');
        }

        function update_blog(blog_id) {
            var form = document.getElementById("editBlogForm" + blog_id);
            var formData = new FormData(form);

            if (formData.get("blog_title" + blog_id) === "" || formData.get("post_per_page" + blog_id) === "" || formData.get("status" + blog_id) === "") {
                alert("Please fill in all fields.");
                return;
            }

            if (formData.get("post_per_page" + blog_id) < 1) {
                alert("Posts per page must be at least 1.");
                return;
            }

            if (formData.get("background_image" + blog_id)) {
                var fileExtension = background_image.split('.').pop().toLowerCase();
                var allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (allowedExtensions.indexOf(fileExtension) === -1) {
                    alert("Invalid file type. Please upload an image file (jpg, jpeg, png, gif).");
                    return;
                }
            }

        
        }

        function update_status(blog_id, status) {
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
                    load_blogs(1);
                }
            };
          
            ajax_request.open("POST", "../ajax/blog_process.php?action=update_status", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("blog_id=" + blog_id + "&status=" + status);
        }


        document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              const search = document.querySelector("#blogSearchInput")?.value || "";
              load_blogs(page, search);
              document.getElementById("table_body").scrollIntoView({ behavior: "smooth" });
          }
        });


</script>

<?php
    General::site_script(true);
?>