<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php?msg=Login First..!&color=alert-danger");
        exit();
    } elseif ($_SESSION['user']['role_id'] != 1) {
        header("Location: ../home.php?msg=Access Denied..!&color=alert-danger");
        exit();
    }

    include("../require/general.php");
    include("../require/database.php");

    General::site_header("Manage Feedback", true);
    General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "feedback");

?>

     <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body bg-dark text-white rounded-top-4">
                <h3 class="fw-bold mb-0"><i class="fas fa-comments me-2"></i>Manage Feedback</h3>
            </div>

            <!-- Search -->
            <div class="row my-3">
                <div class="col-md-6 input-group w-50 mx-auto">
                    <span class="input-group-text fw-bold">Search</span>
                    <input type="text" id="feedback_search_input" name="feedback_search_input" class="form-control" placeholder="Search Feedback or by user name..." value="<?= $search??"" ?>" onkeyup="load_feedbacks(1, this.value)">
                    <button class="input-group-text" onclick="clear_search()"><i class="fa-solid fa-broom"></i></i></button>
                    <button class="btn btn-dark"><i class="fas fa-search"></i></button>
                </div>
            </div>


            <div class="card-body bg-light rounded-bottom-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center bg-white">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Feedback</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody id="feedback_table">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


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

    load_feedbacks();

    setInterval(() => {
        load_feedbacks();
    }, 10000);

     function load_feedbacks(page = 1, search = '') {

            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
           
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    document.getElementById("feedback_table").innerHTML = ajax_request.responseText;
                }
            };
               
            ajax_request.open("GET", "../ajax/feedback_process.php?action=load_feedbacks&page=" + page + "&search=" + encodeURIComponent(search), true);
            ajax_request.send();
    }

    function clear_search(){
        var search = document.getElementById("feedback_search_input");
        search.value = "";
        load_feedbacks();
    }


     document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              const search = document.querySelector("#feedback_search_input")?.value || "";
              load_feedbacks(page, search);
              document.getElementById("feedback_table").scrollIntoView({ behavior: "smooth" });
          }
        });
</script>

<?php
    General::site_script(true);
?>
