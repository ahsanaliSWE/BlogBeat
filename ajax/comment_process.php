<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First..!&color=alert-danger");
        exit();
    } elseif ($_SESSION['user']['role_id'] != 1) {
        header("Location: ../home.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

    include("../require/general.php");
    include("../require/database.php");

    if($_REQUEST['action'] && $_REQUEST['action'] == 'load_comments'){
        
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $search = trim($_GET['search'] ?? '');
        $search_condition = "";


        if (!empty($search)) {
            $search = htmlspecialchars($search); 
            $search_condition = "WHERE c.comment LIKE '%$search%' OR p.post_title LIKE '%$search%' OR c.is_active LIKE '%$search%'";
        } 

        $total_query = "SELECT COUNT(*) AS total FROM post_comment c 
                    JOIN post p ON c.post_id = p.post_id 
                    $search_condition";
        $total = $db->fetch_one($total_query)['total'] ?? 0;
        $total_pages = ceil($total / $limit);

        

        $query = "SELECT c.*, p.post_title, u.first_name, u.last_name 
              FROM post_comment c 
              JOIN post p ON c.post_id = p.post_id 
              JOIN user u ON c.user_id = u.user_id 
              $search_condition 
              ORDER BY c.post_comment_id DESC 
              LIMIT $limit OFFSET $offset";

        $comments = $db->fetch_all($query);

         if (!empty($comments)) {
            $count = $offset + 1;
            foreach ($comments as $comment) {
                ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= substr($comment['comment'], 0, 50) ?>...</td>
                    <td><?= htmlspecialchars($comment['post_title']) ?></td>
                    <td><?= htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']) ?></td>
                    <td>
                        <?php if ($comment['is_active'] == 'Active') { ?>
                            <span class="badge bg-success">Active</span>
                        <?php } else { ?>
                            <span class="badge bg-secondary">InActive</span>
                        <?php } ?>
                    </td>
                    <td><?= date("Y-m-d", strtotime($comment['created_at'])) ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewCommentModal<?= $comment['post_comment_id'] ?>"><i class="fas fa-eye"></i></button>

                        <?php if ($comment['is_active'] == 'Active') { ?>
                                <button name="action" class="btn btn-danger btn-sm" onclick="update_status(<?= $comment['post_comment_id'] ?>, 'InActive')"><i class="fas fa-times"></i></button>
                        <?php } else { ?>
                                <button name="action" class="btn btn-success btn-sm" onclick="update_status(<?= $comment['post_comment_id'] ?>, 'Active')"><i class="fas fa-check"></i></button>
                        <?php } ?> 
                    </td>
                </tr>
                
                <?php
            }
            General::pagination($page, $total_pages);
        }else {
            ?>
            <tr>
                <td colspan="7" class="text-center">No comments found.</td>
            </tr>
            <?php
        }
    } elseif($_REQUEST['action'] && $_REQUEST['action'] == 'load_view_comment_modal') {

            $query = "SELECT pc.*, p.post_title, u.first_name, u.last_name 
                    FROM post_comment pc 
                    JOIN post p ON pc.post_id = p.post_id 
                    JOIN user u ON pc.user_id = u.user_id 
                    WHERE pc.is_active != 'Deleted' 
                    ORDER BY pc.post_comment_id DESC
            ";

            $comments = $db->fetch_all($query);

            foreach($comments as $comment){ ?>
                <div class="modal fade" id="viewCommentModal<?= $comment['post_comment_id'] ?>" tabindex="-1" aria-labelledby="viewCommentLabel<?= $comment['post_comment_id'] ?>" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                      <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="viewCommentLabel<?= $comment['post_comment_id'] ?>"><i class="fas fa-comment-alt me-2"></i>View Comment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body bg-light p-4">
                        <p><strong>Post Title:</strong> <?= htmlspecialchars($comment['post_title']) ?></p>
                        <p><strong>Commented By:</strong> <?= htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']) ?></p>
                        <p><strong>Status:</strong>
                          <?php if ($comment['is_active'] === 'Active') { ?>
                            <span class="badge bg-success">Approved</span>
                          <?php } else { ?>
                            <span class="badge bg-secondary">Rejected</span>
                          <?php } ?>
                        </p>
                        <hr>
                        <p><strong>Comment:</strong></p>
                        <div class="p-3 bg-white border rounded shadow-sm">
                          <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                        </div>
                      </div>
                      <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
            <?php }


    }elseif ($_REQUEST['action'] && $_REQUEST['action'] == 'update_status') {
        
        $comment_id = (int)$_REQUEST['comment_id'];
        $status = $_REQUEST['status'];


             if ($status === 'Active') {
                $db->update("post_comment", [
                    'is_active' => 'Active',
                ], "post_comment_id = $comment_id");

                echo "Comment with ID $comment_id has been set to Active.";

            } elseif ($status === 'InActive') {
                 $db->update("post_comment", [
                    'is_active' => 'InActive',
                ], "post_comment_id = $comment_id");

                echo "Comment with ID $comment_id has been set to InActive.";
            }
    }
?>