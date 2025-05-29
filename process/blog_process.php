<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("Location: ../login.php?msg=Access Denied..!&color=alert-danger");
    exit();
}

include('../require/database.php');



if(isset($_REQUEST['action']) && $_REQUEST['action'] == "update_blog"){

        $blog_id = $_POST['blog_id'];
        $title = trim($_POST['blog_title']);
        $posts = (int)$_POST['post_per_page'];
        $status = $_POST['status'] === 'Active' ? 'Active' : 'InActive';
        $existing_image = $_POST['old_background_image'] ?? null;

        $image = $_FILES['background_image'] ?? null;
        $image_name = $existing_image;

        if ($image && $image['error'] === 0) {
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $image_name = uniqid("blog_", true) . ".$ext";
            move_uploaded_file($image['tmp_name'], "../images/blogs/$image_name");
            if ($existing_image && file_exists("../images/blogs/$existing_image")) {
                unlink("../images/blogs/$existing_image"); // Remove old image
            }
        }

        $db->update("blog", [
            'blog_title' => $title,
            'post_per_page' => $posts,
            'blog_status' => $status,
            'blog_background_image' => $image_name,
            'updated_at' => date("Y-m-d h:i:s",time()),
        ], "blog_id = $blog_id");

        header("Location: ../admin/manage_blogs.php?msg=Blog $title successfully updated.&color=alert-success");

    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "add_blog"){

        $title = trim($_REQUEST['blog_title']);
        $posts = (int)$_REQUEST['post_per_page'];
        $status = $_REQUEST['status'] === 'Active' ? 'Active' : 'InActive';
        $user_id = $_SESSION['user']['user_id'];

        $image = $_FILES['background_image'] ?? null;
        $image_name = null;

        
        if ($image && $image['error'] === 0) {
            $allowed_exts = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

              if (in_array($ext, $allowed_exts)) {
                 $image_name = uniqid("blog_", true) . ".$ext";
                 $destination = "../images/blogs/" . $image_name;
                 
                 if (!is_dir("../images/blogs")) {
                     mkdir("../images/blogs", 0777, true);
                 }
                  
                 if (!move_uploaded_file($image['tmp_name'], $destination)) {
                     echo "Failed to upload image.";
                     exit;
                 }
             } else {
                 echo "Invalid image type. Allowed types: jpg, jpeg";
                 exit;
             }
        } else {
            echo "No image uploaded or upload error.";
            exit;
        }


        $db->insert("blog", [
            'user_id' => $user_id,
            'blog_title' => $title,
            'post_per_page' => $posts,
            'blog_status' => $status,
            'blog_background_image' => $image_name,
            'created_at' => date("Y-m-d h:i:s",time()),
            'updated_at' => null
        ]);

        header("Location: ../admin/manage_blogs.php?msg=Blog $title successfully added.&color=alert-success");
        exit;

    }
    ?>