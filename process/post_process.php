<?php

    session_start();
    include('../require/database.php');
    include('mail_process.php');

    if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
        header("Location: ../login.php?msg=Unauthorized access&color=alert-danger");
        exit();
    }


    // Check if the form is submitted
if (isset($_POST['action']) && $_POST['action'] === 'add_post') {


    $post_title = $_POST['post_title'];
    $post_summary = $_POST['post_summary'];
    $post_description = $_POST['post_description'];
    $blog_id = $_POST['blog_id'];
    $post_status = $_POST['post_status'];
    $allow_comments = $_POST['allow_comments'];
    $user_id = $_SESSION['user']['user_id'];
    $created_at = date("Y-m-d h:i:s",time());

    // Upload featured image
    $featured_image = "";

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
    
        $upload_dir = "../images/posts/";
        // Check if the directory exists, if not create it
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // recursive creation with proper permissions
        }

        $filename = time() . "_" . basename($_FILES['featured_image']['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_path)) {
            $featured_image = $filename;
        }
    }


    // Insert into `post` table
    $post_data = [
        'post_title' => $post_title,
        'post_summary' => $post_summary,
        'post_description' => $post_description,
        'featured_image' => $featured_image,
        'blog_id' => $blog_id,
        'post_status' => $post_status,
        'is_comment_allowed' => $allow_comments,
        'created_at' => $created_at
    ];

    $inserted = $db->insert('post', $post_data);

    if ($inserted) {
        $post_id = $db->get_insert_id();

        // Insert post categories
        if (!empty($_POST['categories'])) {
            foreach ($_POST['categories'] as $cat_id) {
                $db->insert('post_category', [
                    'post_id' => $post_id,
                    'category_id' => (int)$cat_id
                ]);
            }
        }

        $attachments = [];
        $uploadDir = "../uploads/attachments/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($_FILES['attachments']['name'][0])) {
            foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
                $originalName = basename($_FILES['attachments']['name'][$key]);
                $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
                $targetPath = "../uploads/attachments/". $filename;
            
                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $attachments[] = $filename;
                
                    $db->insert("post_atachment", [
                        'post_id' => $post_id, 
                        'post_attachment_title' => $filename,
                        'post_attachment_path' => $targetPath,
                        'is_active' => 'InActive',
                        'created_at' => date("Y-m-d H:i:s",time()),
                    ]);
                }
            }
        }

        blog_follower_notification($blog_id, $post_title, $post_summary);
    }



        header("Location: ../admin/manage_posts.php?msg=Post added successfully");
        exit();
    }elseif (isset($_POST['action']) && $_POST['action'] === 'update_post') {

    $post_id = $_POST['post_id'];
    $post_title = $_POST['post_title'];
    $post_summary = $_POST['post_summary'];
    $post_description = $_POST['post_description'];
    $blog_id = $_POST['blog_id'];
    $post_status = $_POST['post_status'];
    $allow_comments = $_POST['allow_comments'];
    $updated_at = date("Y-m-d h:i:s", time());

    // Ensure post ID exists
    if (empty($post_id)) {
        header("Location: ../admin/manage_posts.php?msg=Invalid post ID&color=alert-danger");
        exit();
    }

    // Update the post details in the database
    $db->update("post", [
        'post_title' => $post_title,
        'post_summary' => $post_summary,
        'post_description' => $post_description,
        'blog_id' => $blog_id,
        'post_status' => $post_status,
        'is_comment_allowed' => $allow_comments,
        'updated_at' => $updated_at
    ], "post_id = $post_id");

    if (!empty($_POST['categories'])) {
        // First, remove the existing categories for the post
        $db->delete('post_category', "post_id = $post_id");

        // Insert new categories
        foreach ($_POST['categories'] as $cat_id) {
            $db->insert('post_category', [
                'post_id' => $post_id,
                'updated_at' => date("Y-m-d H:i:s", time()),
                'category_id' => (int)$cat_id
            ]);
        }
    }

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $upload_dir = "../images/posts/";

        // Ensure the directory exists, create it if it doesn't
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $filename = time() . "_" . basename($_FILES['featured_image']['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_path)) {
            $featured_image = $filename;

            // Update the post with the new featured image
            $db->update('post', [
                'featured_image' => $featured_image,
                'updated_at' => date("Y-m-d H:i:s", time()),
            ], "post_id = $post_id");
        }
    }

    if (!empty($_POST['rename_attachments'])) {
        foreach ($_POST['rename_attachments'] as $attachment_id => $new_name) {
            // Sanitize the new name
            $new_name = basename($new_name);  // To prevent directory traversal
            $target_path = "../uploads/attachments/" . $new_name;

            // Check if the new name is different and the file exists
            $attachment = $db->fetch_all("SELECT * FROM post_atachment WHERE post_atachment_id = $attachment_id");
            if ($attachment) {
                $current_name = $attachment[0]['post_attachment_title'];
                $current_path = "../uploads/attachments/" . $current_name;

                // Update the attachment in the database with the new name
                $db->update('post_atachment', [
                    'post_attachment_title' => $new_name,
                    'updated_at' => date("Y-m-d H:i:s", time()),
                ], "post_atachment_id = $attachment_id");
            }
        }
    }

    // Handle status updates for attachments
    if (!empty($_POST['status_attachments'])) {
        foreach ($_POST['status_attachments'] as $attachment_id => $status) {
            $db->update('post_atachment', [
                'is_active' => $status,
                'updated_at' => date("Y-m-d H:i:s", time()),
            ], "post_atachment_id = $attachment_id");
        }
    }

    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
        $uploadDir = "../uploads/attachments/";

        // Ensure the directory exists, create it if it doesn't
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            $originalName = basename($_FILES['attachments']['name'][$key]);
            $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($tmp_name, $targetPath)) {
                // Insert the new attachment into the database
                $db->insert("post_atachment", [
                    'post_id' => $post_id,
                    'post_attachment_title' => $filename,
                    'post_attachment_path' => $targetPath,
                    'is_active' => 'InActive',
                    'created_at' => date("Y-m-d H:i:s", time())
                ]);
            }
        }
    }

    // After updating the post, redirect to the manage posts page
    header("Location: ../admin/manage_posts.php?msg=Post updated successfully&color=alert-success");
    exit();

} else {
    header("Location: ../admin/manage_posts.php?msg=Post update failed&color=alert-danger");
    exit();
}


?>
