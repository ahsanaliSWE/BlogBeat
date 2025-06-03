<?php

    session_start();

    require_once("../require/database.php");
    require_once("../require/general.php");

    if(!isset($_SESSION['user'])){
        header("Location: login.php?msg=You must be logged in to access this page.");
        exit();
    }


if (isset($_REQUEST['action']) && $_REQUEST['action'] == "load_followers") {

    $limit = 5; // Records per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max($page, 1); // Ensure page is at least 1
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Build WHERE clause for search
    $where = '';
    if (!empty($search)) {
        $search = $db->escape_string($search);
        $where = "AND (CONCAT(u.first_name, ' ', u.last_name) LIKE '%$search%' OR b.blog_title LIKE '%$search%')";
    }

    // Count total records
    $total_query = "SELECT COUNT(*) AS total
                   FROM following_blog f
                   JOIN user u ON f.follower_id = u.user_id
                   JOIN blog b ON f.blog_following_id = b.blog_id
                   WHERE f.status = 1 $where";

    $total_result = $db->fetch_all($total_query);
    $total_records = $total_result[0]['total'];
    $total_pages = ceil($total_records / $limit);

    // Fetch paginated records
    $query = "SELECT f.follow_id, f.created_at, u.first_name, u.last_name, u.user_image, b.blog_title AS blog_title
              FROM following_blog f
              JOIN user u ON f.follower_id = u.user_id
              JOIN blog b ON f.blog_following_id = b.blog_id
              WHERE f.status = 1 $where
              ORDER BY f.created_at DESC
              LIMIT $limit OFFSET $offset";

    $followers = $db->fetch_all($query);

    if (!empty($followers)) {
        $count = $offset + 1;
        foreach ($followers as $row) { ?>
            <tr>
                <td><?= $count ?></td>
                <td><img src="../images/users/<?= $row['user_image'] ?? "user.jpg" ?>" width="50" height="50" class="rounded-circle object-fit-cover" alt="User"></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td><?= $row['blog_title'] ?></td>
                <td><span class="badge bg-success">Following</span></td>
                <td><?= date('M j, Y h:i A', strtotime($row['created_at'])) ?></td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="unfollow_user(<?= $row['follow_id'] ?>)">
                        <i class="fas fa-user-minus me-1"></i> Unfollow
                    </button>
                </td>
            </tr>
        <?php $count++;
        } 
        
        /* AJAX Pagination Links */

        General::pagination($page, $total_pages);
        ?>

        
        
    <?php } else { ?>
        <tr><td colspan="7" class="text-center text-warning fst-italic fw-bold">No followers found.</td></tr>
    <?php }

}elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "unfollow_user") {

    $follow_id = (int)$_REQUEST['follow_id'];
  
    $db->update("following_blog", [
        'status' => 'InActive',
        'updated_at' => date("Y-m-d h:i:s", time()),
    ], "follow_id = $follow_id");

    echo "Follow record with ID $follow_id has been (unfollowed).";

}
?>
