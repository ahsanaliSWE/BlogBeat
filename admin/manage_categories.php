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

    General::site_header("Manage Categories", true);
    General::admin_navbar($_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "categories");
  
?>  
        <div id="spinner" class="text-center my-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

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

        <div class="container py-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold mb-0"><i class="fa-solid fa-tags"></i> Manage Categories</h3>
                            <button class="btn btn-light btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#add_category_modal">
                                <i class="fas fa-plus me-1"></i> Add Category
                            </button>
                    </div>

                    <!-- Search -->
                    <div class="row my-3">
                        <div class="col-md-6 input-group w-50 mx-auto">
                            <span class="input-group-text fw-bold">Search</span>
                            <input type="text" id="category_search_input" name="category_search_input" class="form-control" placeholder="Search Category" value="<?= $search??"" ?>" onkeyup="load_categories(1, this.value)">
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
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="category_table">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
        </div>



        <!-- Add Category Modal -->
        <div class="modal fade" id="add_category_modal" tabindex="-1" aria-labelledby="add_category_modal_label" aria-hidden="true">
          <div class="modal-dialog">
            <form class="modal-content shadow-lg border-0 rounded-4" id="add_category_form" onsubmit="return validate_add_category()">

              <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addCategoryModalLabel">
                  <i class="fas fa-plus-circle me-2"></i>Add Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body bg-light">
                <div class="mb-3">
                  <label class="form-label"><span class="text-danger">*</span>Category Title</label>
                  <input type="text" class="form-control" name="category_title" required>
                </div>

                <div class="mb-3">
                  <label class="form-label"><span class="text-danger">*</span>Description</label>
                  <textarea class="form-control" name="category_description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label"><span class="text-danger">*</span>Status</label>
                  <select class="form-select" name="status" required>
                    <option value="Active">Active</option>
                    <option value="InActive">InActive</option>
                  </select>
                </div>
              </div>

              <div class="modal-footer bg-light">
                <button type="submit" class="btn btn-success w-100">Add Category</button>
              </div>

            </form>
          </div>
        </div>
        <!-- Add Category Modal -->



        <!-- Edit Category Modal -->
       <div id="edit_category_modal">
        
       </div>
        <!-- Edit Category Modal -->



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

            <div id="edit_modals">
              <!-- Load view modals -->
          </div>

                    


<?php General::site_footer(); ?>

<script>
    load_categories();
    load_edit_category_modals();
    
    function load_categories(page = 1, search = '') {

        var ajax_request = null;

        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest();
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById("spinner").style.display = "block";
           
        ajax_request.onload = function () {

            document.getElementById("spinner").style.display = "none";

            if (ajax_request.status === 200) {
                document.getElementById("category_table").innerHTML = ajax_request.responseText;
            }
        };

        ajax_request.open("GET", "../ajax/category_process.php?action=load_categories&page=" + page + "&search=" + encodeURIComponent(search), true);
        ajax_request.send();

    }

    function validate_add_category() {
        var form = document.getElementById('add_category_form');
        var formData = new FormData(form);

        var title = formData.get('category_title')?.trim();
        var desc = formData.get('category_description')?.trim();
        var status = formData.get('status');

        var ajax_request = null;

        if (!title || !desc || !status) {
          alert("All fields are required!");
          return false;
      }


        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest();
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }

        document.getElementById("spinner").style.display = "block";

        ajax_request.onload = function () {

            if (ajax_request.status === 200) {
                const message = ajax_request.responseText;
                        
                document.getElementById("alertModalBody").innerText = message;
            
                const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                const add_category_modal = bootstrap.Modal.getInstance(document.getElementById("add_category_modal"));
                

                document.getElementById("spinner").style.display = "none";

                add_category_modal.hide();
                alertModal.show();
                load_edit_category_modals()
                load_categories();
            }
            
        };

      ajax_request.open("POST", "../ajax/category_process.php?action=add_category", true);
      ajax_request.send(formData);

      return false;
    }

    function load_edit_category_modals(){

        var ajax_request = null;

        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest();
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }

        ajax_request.onload = function () {

            if (ajax_request.status === 200) {
                document.getElementById("edit_modals").innerHTML = ajax_request.responseText;                
            }
                      
        };

        ajax_request.open("GET", "../ajax/category_process.php?action=load_edit_category_modals", true);
        ajax_request.send();
    }

      function update_status(category_id, status) {
            var ajax_request = null;

            if (window.XMLHttpRequest) {
                ajax_request = new XMLHttpRequest();
            } else {
                ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            }
      
            ajax_request.onload = function () {
                if (ajax_request.status === 200) {
                    const message = ajax_request.responseText;

                    document.getElementById("alertModalBody").innerText = message;

                    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                    alertModal.show();
                    load_categories(1);
                }
            };
          
            ajax_request.open("POST", "../ajax/category_process.php?action=update_status", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("category_id=" + category_id + "&status=" + status);
        }



     function clear_search(){
        var search = document.getElementById("category_search_input");
        search.value = "";
        load_categories();
    }
    
     document.addEventListener("click", function (e) {
          if (e.target.classList.contains("pagination-link")) {
              e.preventDefault();
              const page = e.target.getAttribute("data-page");
              const search = document.querySelector("#category_search_input")?.value || "";
              load_categories(page, search);
              //document.getElementById("category_table").scrollIntoView({ behavior: "smooth" });
          }
        });
</script>

<?php General::site_script(true); ?>
