<?php

    session_start();
    include('../require/database.php');

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_category'){

        if(!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1){
            header("Location: ../login.php?msg=Access Denied..!&color=alert-danger");
            exit();
        }


        $category_id = $_POST['category_id'] ?? null;
        $category_title = htmlspecialchars(trim($_POST['category_title']));
        $category_description = htmlspecialchars(trim($_POST['category_description']));
        $category_status = $_POST['category_status'] ?? 'Inactive';

        $update_result = $db->update('category',[
                            'category_title' => $category_title,
                            'category_description' => $category_description,
                            'category_status' => $category_status,
                            'updated_at' => date('Y-m-d H:i:s', time()),
                            ], "category_id = $category_id");

        if($update_result){
            header("Location: ../admin/manage_categories.php?msg=Category updated successfully..!");
            exit();
        } else {
            header("Location: ../admin/manage_categories.php?msg=Error updating category..!");
            exit();
        }

        
    }else {
        header("Location: ../admin/manage_categories.php?msg=Invalid Request..!");
        exit();
    }


?>