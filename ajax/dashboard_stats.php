<?php
        include("../require/database.php");
        include("../require/general.php");

        // Fetch counts
        $user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE role_id = 2 ")['total'] ?? 0;
        $rejected_user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE is_approved = 'Rejected'")['total'] ?? 0;
        //$approved_user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE is_approved = 'Approved'")['total'] ?? 0;
        //$active_user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE is_active = 1")['total'] ?? 0;
        $inactive_user_count = $db->fetch_one("SELECT COUNT(*) as total FROM user WHERE is_active = 0")['total'] ?? 0;
        
        $blog_count = $db->fetch_one("SELECT COUNT(*) as total FROM blog")['total'] ?? 0;
        //$active_blog_count = $db->fetch_one("SELECT COUNT(*) as total FROM blog WHERE blog_status = 'active'")['total'] ?? 0;
        //$inactive_blog_count = $db->fetch_one("SELECT COUNT(*) as total FROM blog WHERE blog_status = 'inactive'")['total'] ?? 0;

        $post_count = $db->fetch_one("SELECT COUNT(*) as total FROM post")['total'] ?? 0;
        //$active_post_count = $db->fetch_one("SELECT COUNT(*) as total FROM post WHERE post_status = 'Active'")['total'] ?? 0;
        //$inactive_post_count = $db->fetch_one("SELECT COUNT(*) as total FROM post WHERE post_status = 'InActive'")['total'] ?? 0;

        $category_count = $db->fetch_one("SELECT COUNT(*) as total FROM category")['total'] ?? 0;
        
        $comment_count = $db->fetch_one("SELECT COUNT(*) as total FROM post_comment")['total'] ?? 0;
        //$active_comment_count = $db->fetch_one("SELECT COUNT(*) as total FROM post_comment WHERE is_active = 'Active'")['total'] ?? 0;
        //$inactive_comment_count = $db->fetch_one("SELECT COUNT(*) as total FROM post_comment WHERE is_active = 'InActive'")['total'] ?? 0;
        
        $feedback_count = $db->fetch_one("SELECT COUNT(*) as total FROM user_feedback")['total'] ?? 0;
        // Output pre-rendered cards
   
        General::overview_card("Users", "fas fa-users", $user_count, "bg-dark");
        General::overview_card("Rejected Users", "fas fa-user-times", $rejected_user_count, "bg-dark");
        General::overview_card("Inactive Users", "fas fa-user-slash", $inactive_user_count, "bg-dark");

        General::overview_card("Blogs", "fas fa-blog", $blog_count, "bg-dark");

        General::overview_card("Posts", "fas fa-file-alt", $post_count, "bg-dark");
        General::overview_card("Categories", "fas fa-tags", $category_count, "bg-dark");

        General::overview_card("Comments", "fas fa-comments", $comment_count, "bg-dark");
        General::overview_card("Feedback", "fas fa-envelope-open-text", $feedback_count, "bg-dark");
        

?>