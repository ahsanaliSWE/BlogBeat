<?php
    
    class General{

        public static function site_header($title, $is_admin = false, $show_on_mobile = false){
            ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title><?= $title ?></title>
                    <?php
                      if($is_admin){
                        ?>
                          <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
                        <?php
                        $header_image = "../images/png/logo-no-background.png";
                      }else{
                        ?>
                          <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
                        <?php
                      }
                    ?>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
                        <!-- head: below existing links -->
                  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">
                <script src="require/client_side_validation.js"></script>            

                <style>
                  html {
                    scroll-behavior: smooth;
                  }
                </style>
                </head>
                <body style="background-color: <?= $_SESSION['themes']['background_color'] ?? '#ffc107' ?>; font-family: <?= $_SESSION['themes']['font_style'] ?? 'Arial' ?>;">
                        
                    <?php
                        if($show_on_mobile){
                          ?>
                             <!-- header -->
                              <header class="container-fluid pt-5">

                                  <div class="row justify-content-center">
                                    <div class="col-12">
                                      <div class="text-center mb-4">
                                        <img src=<?= $header_image??"images/png/logo-no-background.png" ?> alt="BlogBeat" class="img-fluid" style="max-height: 80px;">
                                        <p class="text-muted mt-2 fw-bold"><i>Where Ideas Find Their Voice</i></p>
                                      </div>
                                    </div>
                                  </div>

                              </header>
                              <!-- header -->
                            
                          <?php
                        }else{
                          ?>
                              <!-- header -->
                              <header class="container-fluid pt-5 d-none d-md-block">

                                  <div class="row justify-content-center">
                                    <div class="col-12">
                                      <div class="text-center mb-4">
                                        <img src=<?= $header_image??"images/png/logo-no-background.png" ?> alt="BlogBeat" class="img-fluid" style="max-height: 80px;">
                                        <p class="text-muted mt-2 fw-bold"><i>Where Ideas Find Their Voice</i></p>
                                      </div>
                                    </div>
                                  </div>

                              </header>
                              <!-- header -->
                          <?php
                        }
   
        }

        public static function site_navbar($is_login = false, $first_name = null, $last_name = null, $profile_pic = null, $active_page = null){
            ?>
                <!-- navbar -->
                <div class="row sticky-top">
                  <div class="col-12 bg-primary-subtle px-0">
                      <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-lg ps-3">
                        <div class="container-fluid">
                          <a class="navbar-brand" href="home.php">
                            <img src="images/png/logo-no-background-white.png" alt="BlogBeat" height="50px" width="auto">
                          </a>
                          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                          </button>
                
                          <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center">
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "home"? "active": ""?>" aria-current="page" href="home.php">Home</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "blogs"? "active": ""?>" href="blogs.php">Blog</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "posts"? "active": ""?>" href="posts.php">Posts</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "about"? "active": ""?>" href="about_us.php">About</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "contact_us"? "active": ""?>" href="contact_us.php">Contact Us</a>
                              </li>
                            </ul>
                
                            <?php
                                if($is_login){
                                  ?>
                                      <div class="row me-1">

                                        <div class="dropdown text-end w-100">
                                            <a href="#" class="d-flex align-items-center justify-content-center justify-content-lg-end text-white text-decoration-none dropdown-toggle w-100 w-lg-auto"
                                              id="navbarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                              <img src="images/users/<?= $profile_pic??"user.jpg"?>" alt="Profile" width="40" height="40"
                                              class="rounded-circle me-2 shadow-sm" style="object-fit: cover;">
                                              <span class="fw-bold d-md-inline"><?= $first_name." ".$last_name ?></span>
                                            </a>
                                          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarUserDropdown">
                                            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                                            <li><a class="dropdown-item" href="following_blogs.php">Following blog</a></li>
                                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                          </ul>
                                        </div>
                                              
                                      </div>
                                              
                                  <?php
                                }elseif($active_page != "login"){

                                  ?>
                                      <div class="row gy-2 mx-0">
		        		                        <div class="col-lg-6 ps-0">
		        		                          <a href="login.php" class="btn btn-outline-light shadow-lg px-3 fw-bold w-100">Login</a>
		        		                        </div>
		        		                        <div class="col-lg-6 ps-0">
		        		                          <a href="register.php" class="btn btn-outline-light shadow-lg px-3 fw-bold w-100">Register</a>
		        		                        </div>
		        		                      </div>
                                  <?php
                                }
                            ?>
                            
                
                          </div>
                        </div>
                      </nav>
                
                    </div>
                </div>
                <!-- navbar -->

            <?php
        }

        public static function admin_navbar($first_name,$last_name,$profile_pic, $active_page = null){
          ?>    

                <!-- navbar -->
                <div class="row sticky-top">
                  <div class="col-12 bg-primary-subtle px-0">
                      <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-lg ps-3">
                        <div class="container-fluid">
                          <a class="navbar-brand fw-bold" href="<?= $active_page == "Edit" ? "admin/admin_dashboard.php" : "admin_dashboard.php"?>">
                            <i class="fas fa-cogs me-2"></i>Admin Panel
                          </a>
                          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#admin_navbar"
                            aria-controls="admin_navbar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                          </button>
                
                          <div class="collapse navbar-collapse" id="admin_navbar">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex align-items-center">
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "dashboard"? "active": ""?>" aria-current="page" href="<?= $active_page == "Edit" ? "admin/admin_dashboard.php" : "admin_dashboard.php"?>">Dashboard</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link <?= $active_page == "users"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_users.php": "manage_users.php" ?>">Users</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "blogs"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_blogs.php" : "manage_blogs.php"?>">Blogs</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "posts"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_posts.php" : "manage_posts.php"?>">Posts</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "categories"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_categories.php" : "manage_categories.php"?>">Categories</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "comments"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_comments.php" : "manage_comments.php"?>">Comments</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link <?= $active_page == "feedback"? "active": ""?>" href="<?= $active_page == "Edit" ? "admin/manage_feedback.php" : "manage_feedback.php"?>">Feedback</a>
                              </li>
                            </ul>
                
                                <div class="row me-1">
                                  <div class="dropdown text-end w-100">
                                      <a href="#" class="d-flex align-items-center justify-content-center justify-content-lg-end text-white text-decoration-none dropdown-toggle w-100 w-lg-auto"
                                        id="navbarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                          <?php if($active_page == "Edit"){ ?>
                                                  <img src="images/users/<?= $profile_pic?? "user.jpg" ?>" alt="Profile" width="40" height="40" class="rounded-circle me-2 shadow-sm" style="object-fit: cover;">
                                          <?php }else{ ?>
                                                <img src="../images/users/<?= $profile_pic?? "user.jpg"?>" alt="Profile" width="40" height="40" class="rounded-circle me-2 shadow-sm" style="object-fit: cover;">
                                          <?php } ?>
                                      
                                        <span class="fw-bold d-md-inline"><?= $first_name." ".$last_name ?></span>
                                      </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarUserDropdown">
                                      <li><a class="dropdown-item" href="../edit_profile.php">Edit Profile</a></li>
                                      <li><hr class="dropdown-divider"></li>
                                      <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                    </ul>
                                  </div>

                                </div>                         
                
                          </div>
                        </div>
                      </nav>
                
                    </div>
                </div>
                <!-- navbar -->
              
          <?php
      }
      

        public static function site_slider(){
          ?>
            <!-- slider -->
             <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-bs-ride="carousel">
               <div class="carousel-inner">
                 <div class="carousel-item active" data-bs-interval="3000">
                   <div class="ratio ratio-21x9">
                     <img src="images/slider/slider_img_1.jpg" class="d-block w-100 object-fit-cover" alt="Slider Image 1">
                   </div>
                 </div>
                 <div class="carousel-item" data-bs-interval="3000">
                   <div class="ratio ratio-21x9">
                     <img src="images/slider/slider_img_2.jpg" class="d-block w-100 object-fit-cover" alt="Slider Image 2">
                   </div>
                 </div>
                 <div class="carousel-item" data-bs-interval="3000">
                   <div class="ratio ratio-21x9">
                     <img src="images/slider/slider_img_3.jpg" class="d-block w-100 object-fit-cover" alt="Slider Image 3">
                   </div>
                 </div>
                 <div class="carousel-item" data-bs-interval="3000">
                   <div class="ratio ratio-21x9">
                     <img src="images/slider/slider_img_4.jpg" class="d-block w-100 object-fit-cover" alt="Slider Image 4">
                   </div>
                 </div>
               </div>
               <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                 <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                 <span class="visually-hidden">Previous</span>
               </button>
               <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                 <span class="carousel-control-next-icon" aria-hidden="true"></span>
                 <span class="visually-hidden">Next</span>
               </button>
             </div>
             <!-- slider -->
          <?php            
        }

        public static function home_page_post_cards($post_id,$title,$summary,$categories,$comments,$time,$image = null){
            ?>

            <!-- posts card 1 -->
            <div class="card border-0 shadow-sm rounded-4 hover-shadow mb-3">
              <div class="row g-0 align-items-stretch">
                <!-- Image -->
                <div class="col-md-3">
                  <img src="images/posts/<?= $image == null ? "blog.jpg": $image ?>" class="img-fluid h-100 rounded-start object-fit-cover" alt="Post Image">
                </div>
                <!-- Image -->                      
                 
                <!-- Content -->
                <div class="col-md-9">
                  <div class="card-body bg-light h-100 rounded-end d-flex flex-column justify-content-between">
                  <div>
                      <h4 class="fw-bold text-dark mb-1"><?= $title?></h4>
                      <p class="mb-1 text-muted small"><strong>Summary:</strong> <?= $summary ?>...</p>         
                      <?php
                            foreach($categories as $category){
                            ?>
                                <span class="badge bg-info mb-2"><?= $category ?></span>
                            <?php
                            }
                      ?>                   
                      
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                      <?php if($comments == 1){ ?>
                            <span class="badge bg-success">Comments Allowed</span>
                      <?php } else { ?>
                            <span class="badge bg-danger">Comments Disabled</span>
                      <?php } ?>
                      <span class="text-muted">Published: 2024-11-22</span>
                    </div>
                    <div class="text-center mt-3">
                      <a href="view_post.php?post_id=<?= $post_id ?>" class="btn btn-dark w-100 shadow-lg rounded-3 fw-semibold">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
                <!-- Content -->                    
               </div>
            </div>
            <!-- posts card 1 -->

            <?php

        }

      public static function blog_post_cards($post_id, $blog_id,$image, $title, $categories, $summary, $created_at ){
        ?>
            <div class="col-12">
              <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0 h-100">
                  <div class="col-md-4">
                    <img src="images/posts/<?= htmlspecialchars($image ?? 'blog.jpg') ?>" class="img-fluid h-100 w-100 object-fit-cover" alt="Post Image">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body d-flex flex-column h-100 bg-light">
                      <h5 class="card-title fw-bold"><?= htmlspecialchars($title) ?></h5>
                      <p class="text-muted small mb-1">
                        Categories: 
                        <?php foreach (explode(',', $categories) as $cat) { ?>
                          <span class="badge bg-primary"><?= htmlspecialchars($cat) ?></span>
                        <?php } ?>
                      </p>
                      <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($summary, 0, 200)) ?>...</p>
                      <div class="d-flex justify-content-between align-items-center pt-2 mt-3 border-top">
                        <small class="text-muted">
                          <i class="bi bi-calendar-event me-1"></i> <?= date('F j, Y', strtotime($created_at)) ?>
                        </small>
                        <a href="view_post.php?blog_id=<?= $blog_id ?>&post_id=<?= $post_id ?>" class="btn btn-sm btn-dark fw-semibold">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php
      }

        public static function overview_card($title,$icon,$count,$color){
            ?>
                <div class="col-md-6 col-lg-3">
                  <div class="card bg-dark text-white shadow-lg border-0 rounded-4 text-center">
                    <div class="card-body">
                      <i class="<?= $icon ?> fa-2x mb-2 <?= $color ?>"></i>
                      <h5 class="card-title"><?= $title ?></h5>
                      <p class="display-6 fw-bold"><?= $count ?></p>
                    </div>
                  </div>
                </div>
            <?php
        }

        public static function management_card($title,$icon,$text,$color,$btn,$link){
          ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card bg-dark text-white shadow-sm rounded-4 h-100">
                    <div class="card-body d-flex flex-column">
                      <div class="d-flex align-items-center mb-3">
                        <i class="<?= $icon ?> fa-2x <?= $color ?> me-3"></i>
                        <h5 class="card-title mb-0 fw-bold"><?= $title ?></h5>
                      </div>
                        <p class="flex-grow-1"><?= $text ?></p>
                        <a href="<?= $link ?>" class="btn btn-outline-light w-100 mt-2 fw-semibold"><?= $btn ?></a>
                    </div>
                  </div>
                </div>
          <?php
        }



        public static function guest_feedback(){
            ?>
                <section class="container py-5">
            <div class="row justify-content-center px-2">
                <div class="col-lg-8 bg-secondary-subtle rounded-4 shadow-lg px-0 mb-4 mb-lg-0">
                    
                    <div class="card shadow-lg border-0 rounded-4">
                      <div class="card-body bg-dark text-white rounded-4 shadow-lg">
                        
                        <h3 class="text-center mb-4 fw-bold">Contact Us</h3>
  
                            <div class="card-body bg-secondary-subtle text-dark rounded-4">
                                
                                <p class="text-muted mb-3">
                                  <span class="text-danger">*</span> Required fields
                                </p>

                                <form class="row g-3" method="POST" action="process/feedback_process.php" onsubmit="return validate_feedback(false)">
                                    <div class="col-md-12 mb-3 form-floating">
                                      <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
                                      <label for="name">
                                        Name<span class="text-danger">*</span>
                                      </label>
                                    </div>
                                    <div class="col-md-12 mb-3 form-floating">
                                      <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                                      <label for="email">
                                        Email<span class="text-danger">*</span>
                                      </label>
                                    </div>
                                    <div class="mb-3">
                                      <label for="feedback" class="form-label">
                                        Feedback<span class="text-danger">*</span>
                                    </label>
                                      <textarea class="form-control" id="feedback" name="feedback" rows="3" placeholder="Enter your Feedback" required></textarea>
                                    </div>
                                    
                                    <div class="col-12 text-center">
                                      <button class="btn btn-dark px-5 py-2 fw-bold w-100" type="submit" name="guest_feedback">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </section>
            <?php
        }

        public static function user_feedback($user_id, $email){
            ?>
                <section class="container py-5">
                    <div class="row justify-content-center px-2">
                        <div class="col-lg-8 bg-secondary-subtle rounded-4 shadow-lg px-0 mb-4 mb-lg-0">
                            
                            <div class="card shadow-lg border-0 rounded-4">
                              <div class="card-body bg-dark text-white rounded-4 shadow-lg">
                                
                                <h3 class="text-center mb-4 fw-bold">Contact Us</h3>
                
                                    <div class="card-body bg-secondary-subtle text-dark rounded-4">
                                        
                                        <form class="row g-3" method="POST" action="process/feedback_process.php" onsubmit="return validate_feedback(true)">
                                            <div class="mb-3">
                                              <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">
                                              <input type="hidden" id="email" name="email" value="<?= $email ?>">
                                              <label for="feedback" class="form-label">
                                                Feedback<span class="text-danger">*</span>
                                            </label>
                                              <textarea class="form-control" id="feedback" name="feedback" rows="3" placeholder="Enter your Feedback" required></textarea>
                                            </div>
                                            
                                            <div class="col-12 text-center">
                                              <button class="btn btn-dark px-5 py-2 fw-bold w-100" type="submit" name="user_feedback">Submit</button>
                                            </div>
                                        </form>
                
                                    </div>
                                </div>
                
                            </div>
                
                
                        </div>
                    </div>
                
                </section>
            <?php
        }

        public static function site_footer() {
          ?>
          <footer class="bg-dark text-light pt-5 pb-3">
              <div class="container">
                  <div class="row">

                    <!-- Quick Links Column -->
                    <div class="col-md-4 mb-4">
                          <h5 class="text-uppercase mb-3 text-center">Quick Links</h5>
                          <ul class="list-unstyled text-center">
                              <li><a href="home.php" class="text-light text-decoration-none">Home</a></li>
                              <li><a href="blogs.php" class="text-light text-decoration-none">Blogs</a></li>
                              <li><a href="posts.php" class="text-light text-decoration-none">Posts</a></li>
                              <li><a href="contact_us.php" class="text-light text-decoration-none">Contact Us</a></li>
                          </ul>
                      </div>

                      
      
                      <!-- About Column -->
                      <div class="col-md-4 mb-4">
                          <h5 class="text-uppercase mb-3 text-center">About BlogBeat</h5>
                          <p class="small">
                              BlogBeat is your daily dose of the latest in tech, lifestyle, and trends. 
                              We bring inspiring stories, expert opinions, and much more.
                          </p>
                      </div>
      
      
                      <!-- Social Media Column -->
                      <div class="col-md-4 mb-4">
                          <h5 class="text-uppercase mb-3 text-center">Follow Us</h5>
                          <div class="d-flex gap-3 justify-content-center">
                              <a href="#" class="text-light fs-4"><i class="fa-brands fa-facebook"></i></a>
                              <a href="#" class="text-light fs-4"><i class="fa-brands fa-x-twitter"></i></a>
                              <a href="#" class="text-light fs-4"><i class="fa-brands fa-instagram"></i></a>
                              <a href="#" class="text-light fs-4"><i class="fa-brands fa-youtube"></i></a>
                          </div>
                      </div>
      
                  </div>
      
                  <hr class="border-light">
      
                  <!-- Footer Bottom -->
                  <div class="text-center small">
                      &copy; 2025 BlogBeat. All rights reserved.
                  </div>
              </div>
          </footer>
          <?php
      }

      public static function pagination($page, $totalPages){ ?>

               <!-- AJAX Pagination Links -->
            <tr>
                <td colspan="8" class="text-center">
                    <div class="mt-3">
                        <nav>
                            <ul class="pagination justify-content-center rounded-3 p-2">
                                <!-- Previous -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link pagination-link <?= ($page > 1) ? 'bg-dark text-white' : '' ?>" data-page="<?= max(1, $page - 1) ?>">Previous</a>
                                </li>
          
                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a href="#" class="page-link pagination-link <?= ($i == $page) ? 'bg-dark text-white' : 'bg-secondary-subtle text-dark' ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                                
                                <!-- Next -->
                                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link pagination-link <?= ($page < $totalPages) ? 'bg-dark text-white' : '' ?>" data-page="<?= min($totalPages, $page + 1) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </td>
            </tr>
        
<?php }
      

        public static function site_script($is_admin = false){
            ?>
            <?php
               if($is_admin){
                   ?>
                       <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="../bootstrap/js/multi-select-tag.js"></script>

                   <?php
               }else{
                   ?>
                       <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
                       <script src="bootstrap/js/multi-select-tag.js"></script>
                   <?php
               }
            ?>    <!-- End of <body> -->

              <script>
                    var tagSelector = new MultiSelectTag('categories', {
                          
                        required: true,              
                        placeholder: 'Search category',  
                        onChange: function(selected) { 
                            console.log('Selection changed:', selected);
                        }
                    });
                </script>
                </body>
                </html>
            <?php
        }

    }
?>