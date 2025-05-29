<?php
    session_start();

    if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
        header("Location: ../login.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

    require_once("../require/database.php");
    require_once("../require/general.php");

    date_default_timezone_set('Asia/Karachi');

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "load_blogs") {
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
    
        // Optional search
        $search = trim($_GET['search'] ?? '');
        $search_condition = "";

        if (!empty($search)) {
            $search = htmlspecialchars($search); 
            $search_condition = 'WHERE b.blog_title LIKE "%' . $search . '%" AND u.user_id = "' . $_SESSION['user']['user_id'] . '"';
        } else {
            $search_condition = 'WHERE u.user_id = "' . $_SESSION['user']['user_id'] . '"';
        }
    
        // Count total records
        $count_query = "SELECT COUNT(*) AS total FROM blog b JOIN user u ON u.user_id = b.user_id $search_condition";
        $total_blogs = $db->fetch_one($count_query)['total'] ?? 0;
        $total_pages = ceil($total_blogs / $limit);
    
        // Fetch blogs
        $query = "
            SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
            FROM blog b 
            JOIN user u ON u.user_id = b.user_id 
            $search_condition 
            ORDER BY b.blog_id DESC 
            LIMIT $limit OFFSET $offset
        ";
        $blogs = $db->fetch_all($query);
    
        if (!empty($blogs)) {
            $count = $offset + 1;
            foreach ($blogs as $blog) {
                ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= $blog['blog_title'] ?></td>
                    <td><?= $blog['author_name'] ?></td>
                    <td><?= $blog['post_per_page'] ?></td>
                    <td>
                        <?php if ($blog['blog_status'] === 'Active') { ?>
                            <span class="badge bg-success">Active</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php } ?>
                    </td>
                    <td><?= $blog['created_at'] ?></td>
                    <td><?= $blog['updated_at'] == ("0000-00-00 00:00:00")? "Null": $blog['updated_at'] ?></td>
                    <td>
                        <div class="btn-group w-100">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBlogModal<?= $blog['blog_id'] ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#viewBlogModal<?= $blog['blog_id'] ?>"><i class="fas fa-eye"></i></button>
                            <?php if ($blog['blog_status'] === 'Active') { ?>
                                <button class="btn btn-sm btn-danger" onclick="update_status(<?= $blog['blog_id'] ?>, 'InActive')"><i class="fas fa-ban"></i></button>
                            <?php } else { ?>
                                <button class="btn btn-sm btn-success" onclick="update_status(<?= $blog['blog_id'] ?>, 'Active')"><i class="fas fa-check"></i></button>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            <?php
                General::pagination($page, $total_pages);
        } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No blogs found.</td></tr>";
        }

    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "update_status"){

        $blog_id = (int)$_REQUEST['blog_id'];
        $status = $_REQUEST['status'];


             if ($status === 'Active') {
                $db->update("blog", [
                    'blog_status' => 'Active',
                    'updated_at' => date("Y-m-d h:i:s",time()),
                ], "blog_id = $blog_id");

                echo "Blog with ID $blog_id has been set to Active.";

            } elseif ($status === 'InActive') {
                 $db->update("blog", [
                    'blog_status' => 'InActive',
                    'updated_at' => date("Y-m-d h:i:s",time()),
                ], "blog_id = $blog_id");

                echo "Blog with ID $blog_id has been set to Inactive.";
            }
        
    }
?>
