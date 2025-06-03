<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("Location: ../login.php?msg=Access Denied..!&color=alert-danger");
    exit();
}

include('../require/database.php');
include('../require/general.php');

General::site_header("Manage Followers", true, true);
General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "followers");
?>

<div id="spinner" class="text-center my-3" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<section class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body bg-dark text-white rounded-top-4">
            <h3 class="fw-bold text-center"><i class="fas fa-users me-2"></i>Manage Followers</h3>
        </div>

        <div class="card-body bg-light rounded-bottom-4">
            <div class="row mb-3">
                <div class="col-md-6 input-group w-50 mx-auto">
                    <span class="input-group-text fw-bold">Search</span>
                    <input type="text" name="search" class="form-control" placeholder="Search by user or blog title" onkeyup="load_followers(1)">
                    <button class="btn btn-dark"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>User Name</th>
                            <th>Blog Title</th>
                            <th>Status</th>
                            <th>Followed At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="followers_table">
                        <!-- Dynamic content will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


            <!-- Alert Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-dark shadow rounded-4">
                  <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="alertModalLabel"><i class="fa-light fa-circle-exclamation"></i> Alert</h5>
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

<script>
    load_followers(1);

function load_followers(page = 1) {
    var search = document.querySelector("input[name='search']").value;

    if(window.XMLHttpRequest) {
        ajax_request = new XMLHttpRequest();
    } else {
        ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    ajax_request.onload = function () {
        if (ajax_request.status === 200) {
            document.getElementById("followers_table").innerHTML = ajax_request.responseText;
        }
    };
    ajax_request.open("GET", "../ajax/followers_process.php?action=load_followers&page=" + page + "&search=" + encodeURIComponent(search), true);
    ajax_request.send();
}

function unfollow_user(follow_id) {

     if(window.XMLHttpRequest) {
        ajax_request = new XMLHttpRequest();
    } else {
        ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    document.getElementById("spinner").style.display = "block";

    ajax_request.onload = function () {
        const message = ajax_request.responseText;
        document.getElementById("spinner").style.display = "none";

        document.getElementById("alertModalBody").innerText = message;

        const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        alertModal.show();
        
        load_followers(1);
    };

    ajax_request.open("POST", "../ajax/followers_process.php?action=unfollow_user", true);
    ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax_request.send("follow_id=" + follow_id);
}

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("pagination-link")) {
            e.preventDefault();
            load_followers(e.target.dataset.page);
        }
    });

</script>

<?php General::site_script(true); ?>
