<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: login.php?msg=Please login to access this page&color=alert-danger");
        exit();
    }

    include('require/general.php');
    include('require/database.php');

    $user_id = $_SESSION['user']['user_id'];

    $user = $db->fetch_one("SELECT * FROM user WHERE user_id = $user_id");

    if (!$user) {
        die("User not found");
    }

    General::site_header("Edit Profile");

    if ($_SESSION['user']['role_id'] == 1) {
        General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "Edit");
    } else {
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image']);
    }
?>

            <?php if (isset($_GET['msg'])){ ?>
                <script>
                    window.addEventListener('DOMContentLoaded', function () {
                        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                        var alertModalBody = document.getElementById('alertModalBody');
                        alertModalBody.innerText = "<?php echo htmlspecialchars($_GET['msg']); ?>";
                        alertModal.show();
                    });
                </script>
            <?php } ?>

            
            <!-- Alert Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-dark shadow rounded-4">
                  <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="alertModalLabel"><i class="fas fa-user"></i> Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center text-dark fs-6 px-4 py-3 fw-bold" id="alertModalBody">
                    <!-- Message will be injected here -->
                  </div>
                  <div class="modal-footer justify-content-center bg-light rounded-bottom">
                    <button type="button" class="btn btn-outline-dark px-4" data-bs-dismiss="modal">OK</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Alert Modal -->

        <section class="container my-5 rounded-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-dark text-white rounded-top-4">
                    <h3 class="text-center mb-0 fw-bold">Edit Profile</h3>
                </div>

                <div class="px-4 pt-4 pb-4 bg-light text-dark rounded-bottom-4">
                    <form method="POST" action="process/update_profile_process.php" enctype="multipart/form-data">

                        <div class="text-center mb-4">
                            <img src="<?= !empty($user['user_image']) ? "images/users/".$user['user_image'] : 'images/users/user.jpg' ?>"
                                 class="rounded-circle mb-3 shadow" width="120" height="120" alt="User Image" style="object-fit:cover;">
                            <div class="mb-3 w-50 mx-auto">
                                <label for="user_image" class="form-label fw-bold">Change Profile Picture</label>
                                <input type="file" class="form-control" id="user_image" name="user_image">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?=$user['first_name'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep unchanged">
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                       value="<?= $user['date_of_birth'] ?>" required>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= $user['address'] ?></textarea>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-dark rounded-4 px-4 py-2 w-100" name="action" value="update_profile">Update Profile</button>
                        </div> 
                    </form>
                </div>
            </div>
        </section>

<?php
    General::site_script();
    General::site_footer();
?>
