<?php
    session_start();

    include('../require/database.php');
    include('../require/general.php');


    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 2) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }

     if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_feedbacks'){

        
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
    
        // Optional search
        $search = trim($_GET['search'] ?? '');
        $search_condition = "";

        if (!empty($search)) {
            $search = htmlspecialchars($search); 
            $search_condition = "WHERE user_name LIKE '%$search%' 
                                OR user_email LIKE '%$search%'
                                OR feedback LIKE '%$search%'
                                ";
        } 

        // Count total records
        $count_query = "SELECT COUNT(*) AS total FROM user_feedback $search_condition";
        $total_feedback = $db->fetch_one($count_query)['total'] ?? 0;
        $total_pages = ceil($total_feedback / $limit);
    
        // Fetch categories
        $feedbacks = $db->fetch_all("
            SELECT * FROM user_feedback
            $search_condition 
            ORDER BY feedback_id DESC 
            LIMIT $limit OFFSET $offset
        ");

        if (!empty($feedbacks)) {
            $count = $offset + 1;
            foreach ($feedbacks as $feedback) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $feedback['user_name'] ?></td>
                    <td><?= $feedback['user_email'] ?></td>
                    <td><?= $feedback['feedback'] ?></td>
                    <td><?= $feedback['created_at'] ?></td>
                </tr>
            <?php $count++;
            } 

             General::pagination($page, $total_pages);

        } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No Feedback found.</td></tr>";
        }
        
    }
?>