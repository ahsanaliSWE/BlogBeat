<?php
    session_start();

    include('../require/database.php');

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_comments'){

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        // Fetch comments
        $comments = $db->fetch_all("
            SELECT c.*, u.first_name, u.last_name, u.user_image 
            FROM post_comment c 
            JOIN user u ON u.user_id = c.user_id 
            WHERE c.post_id = $post_id AND c.is_active = 'Active'
            ORDER BY c.post_comment_id DESC
        ");

        if (!empty($comments)){ 
?>
                <?php foreach ($comments as $comment){ ?>
                  <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body d-flex">
                      <img src="images/users/<?= htmlspecialchars($comment['user_image'] ?? 'user.jpg') ?>" class="rounded-circle me-3" width="60" height="60" alt="User Image" style="object-fit: cover;">
                      <div>
                        <h6 class="mb-1">
                          <?= htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']) ?>
                          <small class="text-muted ms-2">• <?= date("M d, Y", strtotime($comment['created_at'])) ?></small>
                        </h6>
                        <p class="mb-0"><?= htmlspecialchars($comment['comment']) ?></p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
          <?php }else{ ?>
              <p class="text-muted text-center">No comments yet.</p>
          <?php } ?>
              
<?php
              
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_comment'){

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        $comment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';

        if ($post_id <= 0 || empty($comment)) {
            header("location: ../blogs.php?msg=Invalid Post Id");
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];

         $inserted =  $db->insert('post_comment', [
                          'post_id' => $post_id,
                          'user_id' => $user_id,
                          'comment' => $comment,
                          'created_at' => date('Y-m-d H:i:s',time()),
                          'is_active' => 'InActive',
                      ]);

        //header("location: view_post.php?post_id=$post_id&msg=Comment Added Successfully");
      if($inserted){
          echo "Comment Added Sucessfully Wait for admin approval";
      }else{
          echo "Error cannot add comment";
      }
     
        exit();

    }else{
        header("Location: blogs.php?msg=Invalid Request!&color=alert-danger");
        exit();
    }


?>