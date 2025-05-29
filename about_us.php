<?php
session_start();
include('require/general.php');

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role_id'] == 1) {
        header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
        exit();
    }
}

General::site_header("About Us");

if (isset($_SESSION['user']) && $_SESSION['user']['is_active'] == 'Active') {
    General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "about");
} elseif (isset($_SESSION['user']) && $_SESSION['user']['is_active'] == 'InActive') {
    header("Location: login.php?msg=Your account is inactive. Please contact admin to activate your account.&color=alert-danger");
    exit();
} else {
    General::site_navbar(false, null, null, null, "about");
}
?>

<!-- Hero Section -->
<div class="bg-dark text-white text-center py-5" style="background: url('assets/images/about-hero.jpg') no-repeat center center/cover;">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to BlogBeat</h1>
        <p class="lead">Unleashing creativity, one post at a time</p>
    </div>
</div>

<!-- Grid Section -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-md-6">
            <img src="images/blogbeat_about.jpg" class="img-fluid rounded shadow-sm" alt="About BlogBeat">
        </div>
        <div class="col-md-6">
            <h2 class="mb-4"><i class="fas fa-feather-alt text-primary me-2"></i>About BlogBeat</h2>
            <p class="fs-5">BlogBeat is a vibrant platform where passionate writers and curious readers connect. We are committed to delivering thoughtful, diverse, and engaging content that informs and inspires.</p>
            <p class="fs-5"><i class="fas fa-users text-success me-2"></i><strong>Community Driven:</strong> From tech to travel, food to finance, we celebrate voices from all walks of life.</p>
            <p class="fs-5"><i class="fas fa-lightbulb text-secondary me-2"></i><strong>Ideas That Matter:</strong> Every post is a chance to share knowledge, spark dialogue, and make an impact.</p>
        </div>
    </div>
</div>

<?php
General::site_footer();
General::site_script();
?>
