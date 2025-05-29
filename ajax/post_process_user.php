<?php

    session_start();
    include('../require/database.php');
    include('../require/general.php');

    if(isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 1) {
        header("Location: ../admin/admin_dashboard.php?msg=Access Denied..!");
        exit();
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_all_posts') {

        $search = trim($_REQUEST['search'] ?? '');
        $author = trim($_REQUEST['author'] ?? '');
        $month = trim($_REQUEST['month'] ?? '');
        $date = trim($_REQUEST['date'] ?? '');
        $page = isset($_REQUEST['page']) ? max(1, (int)$_REQUEST['page']) : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $condition = "WHERE p.post_status = 'Active'";

        if ($search !== '') {
            $condition .= " AND p.post_title LIKE '%" . $search . "%'";
        }

        if ($author !== '') {
            $condition .= " AND CONCAT(u.first_name, ' ', u.last_name) LIKE '%" . $author . "%'";
        }

        if ($month !== '') {
            $condition .= " AND MONTH(p.created_at) = " . (int)$month;
        }

        if ($date !== '') {
            $condition .= " AND DATE(p.created_at) = '" . $date . "'";
        }

        $total_query = "SELECT COUNT(*) AS total
                        FROM post p
                        JOIN blog b ON p.blog_id = b.blog_id
                        JOIN user u ON b.user_id = u.user_id
                        $condition";
        $total = $db->fetch_one($total_query)['total'] ?? 0;
        $total_pages = ceil($total / $limit);

        $query = "SELECT p.*, b.blog_title, CONCAT(u.first_name, ' ', u.last_name) AS author_name
                  FROM post p
                  JOIN blog b ON p.blog_id = b.blog_id
                  JOIN user u ON b.user_id = u.user_id
                  $condition
                  ORDER BY p.created_at DESC
                  LIMIT $limit OFFSET $offset";

        $posts = $db->fetch_all($query);

         if (!empty($posts)){ 
    ?>
          <?php foreach ($posts as $post){ ?>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 shadow-sm rounded-4">
                <img src="images/posts/<?= $post['featured_image'] ?? 'blog.jpg' ?>" class="card-img-top object-fit-cover" style="height: 200px;" alt="Post Image">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title fw-bold"><?= htmlspecialchars($post['post_title']) ?></h5>
                  <p class="text-muted small mb-1"><strong>Author:</strong> <?= htmlspecialchars($post['author_name']) ?></p>
                  <p class="text-muted small mb-1"><strong>Blog:</strong> <?= htmlspecialchars($post['blog_title']) ?></p>
                  <p class="text-muted small mb-3"><strong>Date:</strong> <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                  <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($post['post_summary'], 0, 100)) ?>...</p>
                  <a href="view_post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-outline-dark mt-auto">Read More</a>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php }else{ ?>
          <div class="col-12 text-center text-muted fw-bold">No posts found.</div>
        <?php } ?>
      </div>

    <?php
            General::pagination($page, $total_pages);
    
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_recent_posts') {


        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $count_query = "SELECT COUNT(*) as total FROM post WHERE post_status='active'";
        $total_posts = $db->fetch_one($count_query)['total'] ?? 0;
        $total_pages = ceil($total_posts/ $limit);

        $recent_post_query = "
          SELECT * FROM post p
          WHERE p.post_status='active'
          ORDER BY p.created_at DESC
          LIMIT $limit OFFSET $offset
        ";

        $recent_posts = $db->fetch_all($recent_post_query);
?>      
        <div class="row mb-3 justify-content-center g-3 rounded-4" style="max-height: 600px; overflow-y: auto;">
            <div class="col-12" >
<?php
        if(!empty($recent_posts)) {
            foreach ($recent_posts as $post) {
               
                $cat_query = "
                  SELECT c.category_title 
                  FROM post_category pc
                  JOIN category c ON c.category_id = pc.category_id
                  WHERE pc.post_id = '" . $post['post_id'] . "' 
                    AND c.category_status = 'active'
                ";
                                        
                  $categories = [];
                  $catResults = $db->fetch_all($cat_query);
                  if ($catResults) {
                    foreach ($catResults as $cat) {
                      $categories[] = $cat['category_title'];
                    }
                  }
                                        
                 General::home_page_post_cards(
                        $post['post_id'],
                        $post['post_title'],
                        substr($post['post_summary'],0,250),
                        $categories,
                        $post['is_comment_allowed'] == 1,
                        $post['created_at'],
                        $post['featured_image']
                 );
            }
?>
            </div>
        </div>
<?php
             if ($total_pages > 1) {
                ?>
                    <div class="mt-3">
                        <nav>
                            <ul class="pagination justify-content-center rounded-3 p-2">
                                <!-- Previous -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link recent-posts-page <?= ($page > 1) ? 'bg-dark text-white' : '' ?>" data-page="<?= max(1, $page - 1) ?>">Previous</a>
                                </li>
          
                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a href="#" class="page-link recent-posts-page <?= ($i == $page) ? 'bg-dark text-white' : 'bg-secondary-subtle text-dark' ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                                
                                <!-- Next -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link recent-posts-page <?= ($page < $total_pages) ? 'bg-dark text-white' : '' ?>" data-page="<?= min($total_pages, $page + 1) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php
            }

        } else {
          echo '<p class="text-center text-muted">No posts found.</p>';
        }         
        
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_all_posts_sidebar') {

        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $count_query = "SELECT COUNT(*) as total FROM post WHERE post_status='active'";
        $total_posts = $db->fetch_one($count_query)['total'] ?? 0;
        $total_pages = ceil($total_posts/ $limit);

        $all_post_query = "
          SELECT * FROM post p
          WHERE p.post_status='active'
          LIMIT $limit OFFSET $offset
        ";

        $all_posts = $db->fetch_all($all_post_query);

?>
            <p class="text-muted mb-3">Check out posts and articles.</p>
                <ul class="list-group">
                  <?php
                      if($all_posts){
                        foreach($all_posts as $posts){
                    ?>
                          <a href="view_post.php?post_id=<?= $posts['post_id'] ?>" class="link-underline-light"><li class="list-group-item list-group-item-action"><?= $posts['post_title']." : ".substr($posts['post_summary'],0 , 100)."..." ?></li></a>
                        
                    <?php
                        }
                        }else {
                         echo '<p class="text-center text-muted">No posts found.</p>';
                     }
                    ?>
                </ul>
<?php
            if ($total_pages > 1) {
                ?>
                    <div class="mt-3">
                        <nav>
                            <ul class="pagination justify-content-center rounded-3 p-2">
                                <!-- Previous -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link all-posts-page <?= ($page > 1) ? 'bg-dark text-white' : '' ?>" data-page="<?= max(1, $page - 1) ?>">Previous</a>
                                </li>
          
                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a href="#" class="page-link all-posts-page <?= ($i == $page) ? 'bg-dark text-white' : 'bg-secondary-subtle text-dark' ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                                
                                <!-- Next -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link all-posts-page <?= ($page < $total_pages) ? 'bg-dark text-white' : '' ?>" data-page="<?= min($total_pages, $page + 1) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php
            }

    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_blog_posts'){

            $page = isset($_REQUEST['page']) ? max(1, (int)$_REQUEST['page']) : 1;
            $blog_id = isset($_REQUEST['blog_id']) ? (int)$_REQUEST['blog_id'] : 0;
            $limit = (int)($blog['post_per_page'] ?? 5); 
            $offset = ($page - 1) * $limit;
            $search = trim($_REQUEST['search'] ?? '');

            $search_condition = '';
            if (!empty($search)) {
                $search_condition = " AND p.post_title LIKE '%$search%'";
            }

            $condition = "WHERE p.blog_id = $blog_id 
                        AND p.post_status = 'Active' 
                        $search_condition
                    ";

            $count_query = "
                SELECT COUNT(*) AS total
                FROM post p $condition
            ";

            $total_posts = $db->fetch_one($count_query)['total'] ?? 0;
            $total_pages = ceil($total_posts / $limit);


            $posts = $db->fetch_all("
                SELECT p.*, GROUP_CONCAT(c.category_title) AS categories 
                FROM post p 
                LEFT JOIN post_category pc ON p.post_id = pc.post_id
                LEFT JOIN category c ON pc.category_id = c.category_id
                $condition
                GROUP BY p.post_id
                ORDER BY p.post_id DESC
                LIMIT $limit OFFSET $offset
            ");

         if (!empty($posts)){ 
            foreach ($posts as $post) { 
                
                    General::blog_post_cards($post['post_id'], $post['blog_id'],$post['featured_image'], $post['post_title'], $post['categories'], $post['post_summary'], $post['created_at'] );
?>

        <?php } ?>
    <?php }else { ?>
            <p class="text-muted">No posts available in this blog.</p>
    <?php } 

        if($total_pages > 1) {
            General::pagination($page, $total_pages);
        }
              
    }
?>

                                  
                                  