<?php

    session_start();
    require_once("../require/database.php");
    require_once("../require/general.php");
    
    date_default_timezone_set('Asia/Karachi');

    

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == "load_blogs_user"){

            
        $search = trim($_GET['search'] ?? '');
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;
            
        $condition = "WHERE blog_status = 'Active'";
        if ($search !== '') {
            $condition .= " AND blog_title LIKE '%$search%'";
        }
        
        $total_query = "SELECT COUNT(*) AS total FROM blog $condition";
        $total = $db->fetch_one($total_query)['total'] ?? 0;
        $total_pages = ceil($total / $limit);
        
        $query = "SELECT blog.*, CONCAT(user.first_name, ' ', user.last_name) AS author 
                  FROM blog 
                  JOIN user ON blog.user_id = user.user_id 
                  $condition 
                  ORDER BY blog_id DESC 
                  LIMIT $limit OFFSET $offset";
        
        $blogs = $db->fetch_all($query);
        
        if ($blogs) {
            foreach ($blogs as $blog) {

                $is_following = false;
                if (isset($_SESSION['user'])) {
                    $user_id = $_SESSION['user']['user_id'];
                    $check_follow = $db->fetch_one("SELECT * FROM following_blog WHERE follower_id = $user_id AND blog_following_id = " . $blog['blog_id'] . " AND status = 'Followed'");
                    $is_following = !empty($check_follow);
                }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card rounded-4 shadow-lg h-100 overflow-hidden">
                        <img src="images/blogs/<?= $blog['blog_background_image'] ?? 'blog.jpg' ?>" class="card-img-top object-fit-cover" style="height: 200px;" alt="Blog Cover">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= $blog['blog_title'] ?></h5>
                            <small class="text-muted mb-2">Author: <?= $blog['author'] ?></small>
                            <small class="text-muted mb-3">Last updated: <?= date('F j, Y', strtotime($blog['updated_at'])) ?></small>
                            <a href="view_blog.php?blog_id=<?= $blog['blog_id'] ?>" class="btn btn-dark mt-auto w-100">View Blog</a>
                             <?php if (isset($_SESSION['user'])){ ?>
                                <div class="w-100 mt-2">
                                   <button type="button"
                                            class="<?= $is_following ? 'btn btn-outline-secondary' : 'btn btn-outline-primary' ?> w-100"
                                            onclick="follow_unfollow_blog(<?= $blog['blog_id'] ?>, '<?= $is_following ? 'Unfollow' : 'Follow' ?>')">
                                        <?= $is_following ? 'Unfollow' : 'Follow' ?>
                                    </button>
                             </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }

            ?>
           
        <?php
        
           General::pagination($page, $total_pages);
               
        } else {
            echo "<div class='col-12 text-center text-muted fw-bold'>No blogs found.</div>";
        }

        
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'follow_unfollow_blog') {

        $blog_id = (int)$_POST['blog_id'];
        $user_id = $_SESSION['user']['user_id'];
        $status = trim($_POST['status']); 
        
        $check = $db->fetch_one("SELECT * FROM following_blog WHERE follower_id = $user_id AND blog_following_id = $blog_id");

        if ($status === 'Follow') {
            if ($check) {
               
                $db->update('following_blog', [
                    'status' => 'Followed',
                    'updated_at' => date('Y-m-d H:i:s'),
                ], "follower_id = $user_id AND blog_following_id = $blog_id");

            } else {
  
                $data = [
                    'follower_id' => $user_id,
                    'blog_following_id' => $blog_id,
                    'status' => 'Followed',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => '0000-00-00 00:00:00',
                ];
                $db->insert('following_blog', $data);
            }
        
            echo "Followed successfully";
        
        } elseif ($status === 'Unfollow') {

            if ($check) {
      
                $db->update('following_blog', [
                    'status' => 'Unfollowed',
                    'updated_at' => date('Y-m-d H:i:s'),
                ], "follower_id = $user_id AND blog_following_id = $blog_id");
            
                echo "Unfollowed successfully";
            } else {
                echo "Not following this blog.";
            }
        
        } else {
            echo "Invalid action.";
        }

        
    } elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_folowing_blogs') {

        $search = trim($_GET['search'] ?? '');
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $user_id = (int)$_SESSION['user']['user_id']; // Ensure safe integer casting

        // Prepare search condition
        $search_condition = '';
        if (!empty($search)) {
            $search_condition = " AND blog.blog_title LIKE '%$search%'";
        }

        $condition = "WHERE blog.blog_status = 'Active' 
                      AND fb.follower_id = $user_id 
                      AND fb.status = 'Followed'
                      $search_condition";

        $total_query = "SELECT COUNT(*) AS total
                        FROM blog
                        INNER JOIN following_blog fb ON fb.blog_following_id = blog.blog_id
                        $condition";

        $total = $db->fetch_one($total_query)['total'] ?? 0;
        $total_pages = ceil($total / $limit);

        // Final data query
        $query = "SELECT blog.*, CONCAT(u.first_name, ' ', u.last_name) AS author
                  FROM blog
                  INNER JOIN user u ON blog.user_id = u.user_id
                  INNER JOIN following_blog fb ON fb.blog_following_id = blog.blog_id
                  $condition
                  ORDER BY blog.blog_id DESC
                  LIMIT $limit OFFSET $offset";

        $blogs = $db->fetch_all($query);

        
        if ($blogs) {
            foreach ($blogs as $blog) {

                $is_following = false;
                if (isset($_SESSION['user'])) {
                    $user_id = $_SESSION['user']['user_id'];
                    $check_follow = $db->fetch_one("SELECT * FROM following_blog WHERE follower_id = $user_id AND blog_following_id = " . $blog['blog_id'] . " AND status = 'Followed'");
                    $is_following = !empty($check_follow);
                }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card rounded-4 shadow-lg h-100 overflow-hidden">
                        <img src="images/blogs/<?= $blog['blog_background_image'] ?? 'blog.jpg' ?>" class="card-img-top object-fit-cover" style="height: 200px;" alt="Blog Cover">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= $blog['blog_title'] ?></h5>
                            <small class="text-muted mb-2">Author: <?= $blog['author'] ?></small>
                            <small class="text-muted mb-3">Last updated: <?= date('F j, Y', strtotime($blog['updated_at'])) ?></small>
                            <a href="view_blog.php?blog_id=<?= $blog['blog_id'] ?>" class="btn btn-dark mt-auto w-100">View Blog</a>
                             <?php if (isset($_SESSION['user'])){ ?>
                                <div class="w-100 mt-2">
                                   <button type="button"
                                            class="<?= $is_following ? 'btn btn-outline-secondary' : 'btn btn-outline-primary' ?> w-100"
                                            onclick="follow_unfollow_blog(<?= $blog['blog_id'] ?>, '<?= $is_following ? 'Unfollow' : 'Follow' ?>')">
                                        <?= $is_following ? 'Unfollow' : 'Follow' ?>
                                    </button>
                             </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }

            ?>
           
        <?php
        
           General::pagination($page, $total_pages);
               
        } else {
            echo "<div class='col-12 text-center text-muted fw-bold'>No blogs found.</div>";
        }
    }
?>