<?php

    session_start();

    require_once("../require/database.php");

    if(!isset($_SESSION['user'])){
        header("Location: login.php?msg=You must be logged in to access this page.");
        exit();
    }

    if(isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 1){
        header("Location: admin/admin_dashboard.php?msg=You do not have permission to access this page.");
        exit();
    }

    if(isset($_REQUEST['save_settings'])){

        $font_style = $_REQUEST['font_style'] ?? 'Arial';
        $background_color = $_REQUEST['background_color'] ?? '#ffffff';
        $user_id = $_SESSION['user']['user_id'];

        $settings = [
        'font_style' => $font_style,
        'background_color' => $background_color,
    ];

    foreach ($settings as $key => $value) {
        // Check if setting already exists for this user
        $check_query = "SELECT * FROM setting WHERE user_id = $user_id AND setting_key = '$key'";
        $exists = $db->count_rows($check_query);

         $exists = $db->count_rows("SELECT * FROM setting WHERE user_id = $user_id AND setting_key = '$key'");
    
            if ($exists > 0) {
                // Update existing setting
                $db->update("setting", [
                    "setting_value" => $value,
                    "updated_at" => date("Y-m-d H:i:s")
                ], "user_id = $user_id AND setting_key = '$key'");

            } else {
                // Insert new setting
                $db->insert("setting", [
                    "user_id" => $user_id,
                    "setting_key" => $key,
                    "setting_value" => $value,
                    "setting_status" => 'active',
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            }

        unset($_SESSION['themes']);

        foreach ($settings as $key => $value) {
            $_SESSION['themes'][$key] = $value;
        }
    }

    header("Location: ../settings.php?msg=Theme settings saved successfully.");
    exit();

    }elseif(isset($_REQUEST['reset_settings'])){

        $user_id = $_SESSION['user']['user_id'];

        // Reset to default settings
        $default_settings = [
            'font_style' => 'Arial',
            'background_color' => '#ffc107'
        ];

        foreach ($default_settings as $key => $value) {
            // Update existing setting
            $db->update("setting", [
                "setting_value" => $value,
                "updated_at" => date("Y-m-d H:i:s")
            ], "user_id = $user_id AND setting_key = '$key'");
        }

        unset($_SESSION['themes']);
        $_SESSION['themes'] = $default_settings;

        header("Location: ../settings.php?msg=Theme settings reset to default.");
        exit();
    }
?>