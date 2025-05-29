<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First&color=alert-danger");
        exit();
    }elseif($_SESSION['user']['role_id'] != 1){
      header("Location: ../home.php?msg=Unauthorized Access&color=alert-danger");
        exit();
    }

  include('../require/database.php');
  include('../require/general.php');


  if(isset($_REQUEST) && $_REQUEST['action'] == 'add_post'){

    

    $blogs = $db->fetch_all("
      SELECT * FROM blog b 
      JOIN user u on b.user_id = u.user_id 
      WHERE u.user_id =  '".$_SESSION['user']['user_id']."'
    ");

    $categories = $db->fetch_all("SELECT * FROM category WHERE category_status = 'Active'");

    General::site_header("Add Post", true, true);

    General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "posts");

   
?>
  
    <section class="container py-5">
      <div class="card shadow-lg rounded-4">
        <div class="card-header bg-dark text-white">
          <h5 class="fw-bold"><i class="fas fa-plus-circle me-2"></i>Add New Post</h5>
        </div>
        <form action="../process/post_process.php" method="POST" enctype="multipart/form-data" class="card-body bg-light" onsubmit="return validate_post_form()">
          
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Post Title</label>
              <input type="text" id="post_title" name="post_title" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Select Blog</label>
              <select id="blog_id" name="blog_id" class="form-select" required>
                <option value="choose_blog" disabled selected>Choose blog</option>
                <?php foreach ($blogs as $blog){ ?>
                  <option value="<?= $blog['blog_id'] ?>"><?= $blog['blog_title'] ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Summary</label>
              <textarea id="post_summary" name="post_summary" class="form-control" rows="2" required></textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea id="post_description" name="post_description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Featured Image</label>
              <input type="file" id="featured_image" name="featured_image" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select id="post_status" name="post_status" class="form-select">
                <option value="InActive">InActive</option>
                <option value="Active">Active</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Allow Comments</label>
              <select id="allow_comments" name="allow_comments" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Categories</label>
              <select name="categories[]" id="categories" multiple>
              <?php foreach ($categories as $cat){ ?>
                      <option value="<?= $cat['category_id'] ?>"><?= $cat['category_title'] ?></option>
              <?php } ?>
              </select>
            </div>

            <div class="col-12">
              <label for="attachments" class="form-label">Attachments</label>
              <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
              <div id="fileList" class="mt-2 small text-muted"></div>
            </div>

          <div class="mt-4 d-flex justify-content-end">
            <button type="submit" name="action" value="add_post" class="btn btn-success">Add Post</button>
          </div>
          
        </form>
      </div>
    </section>

  <?php
  
  }elseif(isset($_REQUEST) && $_REQUEST['action'] == 'edit_post'){

  General::site_header("Edit Post", true, true);

  General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "posts");


    $post_id = intval($_GET['post_id'] ?? 0);

// Fetch post info
$post = $db->fetch_one("SELECT p.*, b.blog_title, b.blog_id, b.user_id FROM post p JOIN blog b ON p.blog_id = b.blog_id WHERE p.post_id = $post_id");

if (!$post || $post['user_id'] != $_SESSION['user']['user_id']) {
    die("Post not found or unauthorized");
}

// Fetch all blogs owned by user
$blogs = $db->fetch_all("SELECT * FROM blog WHERE user_id = '" . $_SESSION['user']['user_id'] . "'");

// Fetch categories and post's selected categories
$categories = $db->fetch_all("SELECT * FROM category WHERE category_status = 'Active'");
$selectedCategories = $db->fetch_all("SELECT category_id FROM post_category WHERE post_id = $post_id");
$post_category_ids = array_column($selectedCategories, 'category_id');

// Fetch attachments
$attachments = $db->fetch_all("SELECT * FROM post_atachment WHERE post_id = $post_id");

?>

<section class="container py-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-dark text-white">
      <h5 class="fw-bold"><i class="fas fa-edit me-2"></i>Edit Post</h5>
    </div>

    <form action="../process/post_process.php" method="POST" enctype="multipart/form-data" class="card-body bg-light" >
      <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Post Title</label>
          <input type="text" name="post_title" class="form-control" value="<?= htmlspecialchars($post['post_title']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Select Blog</label>
          <select name="blog_id" class="form-select" required>
            <option disabled>Choose blog</option>
            <?php foreach ($blogs as $blog){ ?>
              <option value="<?= $blog['blog_id'] ?>" <?= $blog['blog_id'] == $post['blog_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($blog['blog_title']) ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Summary</label>
          <textarea name="post_summary" class="form-control" rows="2" required><?= htmlspecialchars($post['post_summary']) ?></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="post_description" class="form-control" rows="5" required><?= htmlspecialchars($post['post_description']) ?></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Featured Image</label>
          <input type="file" name="featured_image" class="form-control">
          <?php if (!empty($post['featured_image'])){ ?>
            <img src="../images/posts/<?= htmlspecialchars($post['featured_image']) ?>" class="mt-2" width="150" alt="Featured Image">
          <?php } ?>
        </div>

        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select name="post_status" class="form-select">
            <option value="InActive" <?= $post['post_status'] == 'InActive' ? 'selected' : '' ?>>InActive</option>
            <option value="Active" <?= $post['post_status'] == 'Active' ? 'selected' : '' ?>>Active</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Allow Comments</label>
          <select name="allow_comments" class="form-select">
            <option value="1" <?= $post['is_comment_allowed'] ? 'selected' : '' ?>>Yes</option>
            <option value="0" <?= !$post['is_comment_allowed'] ? 'selected' : '' ?>>No</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Categories</label>
          <select name="categories[]" id="categories" multiple>
            <?php foreach ($categories as $cat){ ?>
              <option value="<?= $cat['category_id'] ?>" <?= in_array($cat['category_id'], $post_category_ids) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['category_title']) ?>
              </option>
            <?php } ?>
          </select>
        </div>


        <div class="col-12">
          <label class="form-label fw-bold">Existing Attachments</label>
          <?php if (!empty($attachments)){ ?>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Change Name</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attachments as $index => $att){ ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td>
                        <a href="<?= htmlspecialchars($att['post_attachment_path']) ?>" target="_blank">
                          <?= htmlspecialchars($att['post_attachment_title']) ?>
                        </a>
                      </td>
                      <td>
                        <input type="text" name="rename_attachments[<?= $att['post_atachment_id'] ?>]" class="form-control"
                               value="<?= htmlspecialchars($att['post_attachment_title']) ?>">
                      </td>
                      <td>
                        <select name="status_attachments[<?= $att['post_atachment_id'] ?>]" class="form-select">
                          <option value="Active" <?= $att['is_active'] == 'Active' ? 'selected' : '' ?>>Active</option>
                          <option value="InActive" <?= $att['is_active'] == 'InActive' ? 'selected' : '' ?>>InActive</option>
                        </select>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php }else{ ?>
            <p class="text-muted fst-italic">No attachments available.</p>
          <?php } ?>
        </div>


        <div class="col-12">
          <label class="form-label">Add More Attachments</label>
          <input type="file" name="attachments[]" multiple class="form-control">
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end">
        <button type="submit" name="action" value="update_post" class="btn btn-primary">Update Post</button>
      </div>
    </form>
  </div>
</section>
<?php
    }

    General::site_footer();
?>
  
  <script>
  function validate_post_form() {
    var title = document.getElementById("post_title").value.trim();
    var blog = document.getElementById("blog_id").value;
    var summary = document.getElementById("post_summary").value.trim();
    var description = document.getElementById("post_description").value.trim();
    var featuredImage = document.getElementById("featured_image").value;
    var status = document.getElementById("post_status").value;
    var comments = document.getElementById("allow_comments").value;
    var categories = document.getElementById("categories");

    // Validate required fields
    if (title === "" || blog === "" || description === "" || status === "" || comments === "") {
      alert("Please fill in all required fields.");
      return false;
    }

     if (blog === "choose_blog") {
        alert("Please select a blog.");
        return false;
      }

    // Validate image file type
    if (featuredImage) {
      var ext = featuredImage.split('.').pop().toLowerCase();
      var allowed = ['jpg', 'jpeg', 'png'];
      if (!allowed.includes(ext)) {
        alert("Invalid image file type. Allowed: jpg, jpeg, png.");
        return false;
      }
    }

    // Validate at least one category selected
    let categorySelected = false;
    for (let i = 0; i < categories.options.length; i++) {
      if (categories.options[i].selected) {
        categorySelected = true;
        break;
      }
    }

    if (!categorySelected) {
      alert("Please select at least one category.");
      return false;
    }

    return true;
  }

   document.getElementById("attachments").addEventListener("change", function () {
    const fileList = document.getElementById("fileList");
    fileList.innerHTML = "";

    for (let i = 0; i < this.files.length; i++) {
      const file = this.files[i];
      const item = document.createElement("div");
      item.textContent = `${i + 1}. ${file.name}`;
      fileList.appendChild(item);
    }
  });
</script>
<?php 
      
     
      General::site_script(true); 
?>
