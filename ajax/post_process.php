<?php
    session_start();
    
    include('../require/database.php');
    include('../require/general.php');

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_posts') {
        
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $search = trim($_GET['search'] ?? '');
        $search_condition = "";


        if (!empty($search)) {
            $search = htmlspecialchars($search); 
            $search_condition = 'WHERE p.post_title LIKE "%' . $search . '%" AND u.user_id = "' . $_SESSION['user']['user_id'] . '"';
        } else {
            $search_condition = 'WHERE u.user_id = "' . $_SESSION['user']['user_id'] . '"';
        }

        $total_query = "SELECT COUNT(*) AS total FROM post p JOIN blog b on b.blog_id = p.blog_id JOIN user u on u.user_id = b.user_id $search_condition";
        $total = $db->fetch_one($total_query)['total'] ?? 0;
        $total_pages = ceil($total / $limit);

        $query = "SELECT p.*, b.blog_title, u.first_name, u.last_name FROM post p 
                  JOIN blog b ON p.blog_id = b.blog_id 
                  JOIN USER u ON b.user_id = u.user_id 
                  $search_condition 
                  ORDER BY post_id DESC
                  LIMIT $limit OFFSET $offset
        ";

        $posts = $db->fetch_all($query);

        if (!empty($posts)) {
            $count = $offset + 1;
            foreach ($posts as $post) {
                ?>
                <tr>
                    <td><?= $post['post_id'] ?></td>
                    <td><?= $post['post_title'] ?></td>
                    <td><?= $post['blog_title'] ?></td>
                    <td><?= $post['first_name'] . ' ' . $post['last_name'] ?></td>
                    <?php
                        if ($post['post_status'] == 'Active') {
                            echo $status = '<td><span class="badge bg-success">Active</span></td>';
                        } else {
                            echo $status = '<td><span class="badge bg-danger">Inactive</span></td>';
                        }

                        if ($post['is_comment_allowed'] == 1) {
                            echo $comment_status = '<td><span class="badge bg-primary">Allowed</span></td>';
                        } else {
                            echo $comment_status = '<td><span class="badge bg-secondary">Not Allowed</span></td>';
                        }
                    ?>
                    <td><?= $post['created_at'] ?></td>
                    <td>
                        <a href="add_edit_post.php?action=edit_post&post_id=<?= $post['post_id'] ?>" class="btn btn-sm btn-primary" title="Edit Post">
                            <i class="fas fa-edit"></i>
                        </a>

                      <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewPostModal<?= $post['post_id'] ?>"><i class="fas fa-eye"></i></button>
                        
                        <?php if($post['post_status'] == 'Active'){ ?>
                                <button class="btn btn-sm btn-dark" onclick="update_status(<?= $post['post_id'] ?>, 'InActive')"><i class="fas fa-toggle-off"></i></button>
                        <?php  }else{ ?>
                                <button class="btn btn-sm btn-secondary" onclick="update_status(<?= $post['post_id'] ?>, 'Active')"><i class="fas fa-toggle-on"></i></button>
                        <?php } ?>
                      
                     
                      <?php if($post['is_comment_allowed'] == 1){ ?>
                             <button class="btn btn-sm btn-danger" onclick="allow_comments(<?= $post['post_id'] ?>, 'InActive')"><i class="fa-solid fa-comment"></i></button>
                      <?php  }else{ ?>
                            <button class="btn btn-sm btn-success" onclick="allow_comments(<?= $post['post_id'] ?>, 'Active')"><i class="fa-solid fa-comment"></i></button>
                     <?php } ?>
                      
                       
                    <?php
                    ?>
                    </td>
                </tr>
                <?php
            }
                 General::pagination($page, $total_pages);

        } else {
            echo "<tr><td colspan='8'>No posts found.</td></tr>";
        }
        ?>
        
        <?php


    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_status') {

        $post_id = $_REQUEST['post_id'];
        $status = $_REQUEST['status'];

        if ($status === 'Active') {
             $db->update("post", [
                 'post_status' => 'Active',
                 'updated_at' => date("Y-m-d h:i:s",time()),
             ], "post_id = $post_id");

             echo "Post with ID $post_id has been set to Active.";

        } elseif ($status === 'InActive') {
             $db->update("post", [
                'post_status' => 'InActive',
                'updated_at' => date("Y-m-d h:i:s",time()),
            ], "post_id = $post_id");

            echo "Post with ID $post_id has been set to Inactive.";
        }
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'allow_comments') {

        $post_id = $_REQUEST['post_id'];
        $status = $_REQUEST['status'];

        if ($status === 'Active') {
             $db->update("post", [
                 'is_comment_allowed' => 1,
                 'updated_at' => date("Y-m-d h:i:s",time()),
             ], "post_id = $post_id");

             echo "Comments have been activated on post ID $post_id.";

        } elseif ($status === 'InActive') {
             $db->update("post", [
                'is_comment_allowed' => 0,
                'updated_at' => date("Y-m-d h:i:s",time()),
            ], "post_id = $post_id");

            echo "Comments have been deactivated on post ID $post_id.";
        }
    }else{
        header("Location: ../login.php?msg=Invalid Request!&color=alert-danger");
        exit();
    }

    


?>