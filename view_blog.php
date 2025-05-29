<?php
    session_start();
    include('require/general.php');
    include('require/database.php');

    $blog_id = isset($_GET['blog_id']) ? (int)$_GET['blog_id'] : 0;
    if ($blog_id <= 0) {
        header("Location: blogs.php?msg=Invalid Blog ID..!");
        exit();
    }

    $blog = $db->fetch_one("SELECT * FROM blog WHERE blog_id = $blog_id");
    
    General::site_header($blog['blog_title']);
    

    if (isset($_SESSION['user'])) {
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "blogs");
    } else {
        General::site_navbar(false, null, null, null, "blogs");
    }
?>

    <!-- Blog Cover and Title -->
    <section class="pb-5 text-white">
      <div class="container-fluid p-0">
        <div class="card text-bg-dark w-100 overflow-hidden shadow-lg border-0">
          <img src="images/blogs/<?= htmlspecialchars($blog['blog_background_image'] ?? 'blog.jpg') ?>" class="card-img object-fit-cover" alt="Cover Image" style="height: 400px;">
          <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center bg-dark bg-opacity-50">
            <h1 class="display-4 fw-bold"><?= htmlspecialchars($blog['blog_title']) ?></h1>
            <p class="lead fst-italic"><?= htmlspecialchars($blog['blog_description'] ?? 'Discover posts and insights from this blog.') ?></p>
          </div>
        </div>
      </div>
    </section>

      <!-- Main Section -->
      <section class="container my-5">
        <div class="row g-5">
          <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Latest Blog Posts</h2>
            <div class="row g-4" id="blog_posts_container">

              

            </div>
          </div>
                  
        <!-- right side bar search and categories are not working -->
          <!-- Right: Sidebar -->
          <div class="col-lg-4">
            <!-- Search -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
              <div class="card-body">
                <h5 class="fw-bold mb-3">Search Posts</h5>
                <div>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by title..." id="search_input" onkeyup="load_posts(<?= $blog_id ?>, 1, this.value)">
                    <button class="btn btn-dark" type="submit">Go</button>
                  </div>
                </div>
              </div>
            </div>
                  
            <!-- Filter by Category -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
              <div class="card-body">
                <h5 class="fw-bold mb-3">Filter by Category</h5>
                <div class="d-flex flex-wrap gap-2">
                  <span class="badge bg-primary">Programming</span>
                  <span class="badge bg-success">AI</span>
                  <span class="badge bg-danger">Security</span>
                  <span class="badge bg-info text-dark">Design</span>
                  <span class="badge bg-warning text-dark">Business</span>
                </div>
              </div>
            </div>
                  
                  
          </div>
                  
        </div>
      </section>

    <script>

      load_posts(<?= $blog_id ?>, 1, "");

      function load_posts(blog_id, page = 1, search = ""){
        
            var ajax_request = null;         
            var posts = document.getElementById("blog_posts_container");

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }        
          
          ajax_request.onreadystatechange = function () {
              if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                  posts.innerHTML = ajax_request.responseText;
              }
          };         
        
          ajax_request.open("POST", "ajax/post_process_user.php?action=load_blog_posts", true);
          ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          ajax_request.send("blog_id=" + blog_id + "&page=" + page + "&search=" + encodeURIComponent(search));
      }

    
      document.addEventListener("click", function (e) {
        if (e.target.classList.contains("pagination-link")) {
          e.preventDefault();
          const page = e.target.getAttribute("data-page");
          const query = document.getElementById('search_input').value;
          load_posts(<?= $blog_id ?>, page, query);
          //document.getElementById("blogCardsContainer").scrollIntoView({ behavior: "smooth" });
        }
      });
        
      

    </script>

<?php
    General::site_script();
?>
