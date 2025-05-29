<?php
require_once("../require/database.php");

if (isset($_REQUEST["action"]) && $_REQUEST["action"] === "check_email" && isset($_REQUEST["email"])) {


    $query = "SELECT * FROM user WHERE email = '".$_REQUEST["email"]."'";
    $exists = $db->fetch_one($query);

    if ($exists) {
        echo '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Email Already Exists!</span>';
    } else {
        echo '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid Email</span>';
    }
}
?>
