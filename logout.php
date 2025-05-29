<?php
    session_start();

    unset($_SESSION);

    session_destroy();

    header("Location: login.php?msg=Logout Success&color=alert-success");
    exit();
?>