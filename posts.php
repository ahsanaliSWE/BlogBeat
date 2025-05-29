<?php
    session_start();
    include('require/database.php');
    include('require/general.php');

    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 1) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    } 

    General::site_header("Posts");

    if (isset($_SESSION['user'])) {
        General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "posts");
    } else {
        General::site_navbar(false, null, null, null, "posts");
    }

    
?>

    <section class="container my-5">
        <h2 class="fw-bold mb-4">Browse Posts</h2>

        <div class="row g-2 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by Title" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="author" class="form-control" placeholder="Search by Author" value="<?= htmlspecialchars($_GET['author'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <select name="month" class="form-select">
                    <option value="">Filter by Month</option>
                    <?php for($m = 1; $m <= 12; $m++){ ?>
                      <option value="<?= $m ?>" <?= (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                      </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            </div>
            <div class="col-md-1">
                <button class="btn btn-secondary w-100" onclick="clear_search()">Reset</button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-dark w-100" onclick="load_posts()">Filter</button>
            </div>
        </div>


        <div class="row g-4" id="post_cards_container">
         <!--  posts show here -->
        </div>

    
    </section>

    <script>
        load_posts();

        function load_posts(page = 1) {
            var ajax_request = new XMLHttpRequest();
            var cards = document.getElementById("post_cards_container");
            var search = document.querySelector('input[name="search"]').value;
            var author = document.querySelector('input[name="author"]').value;
            var month = document.querySelector('select[name="month"]').value;
            var date = document.querySelector('input[name="date"]').value;
    
            ajax_request.onreadystatechange = function () {
                if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                    cards.innerHTML = ajax_request.responseText;
                }
            };
    
            ajax_request.open("POST", "ajax/post_process_user.php?action=load_all_posts", true);
            ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_request.send("page=" + page + "&search=" + search + "&author=" + author + "&month=" + month + "&date=" + date);
        }

        function clear_search() {
            document.querySelector('input[name="search"]').value = '';
            document.querySelector('input[name="author"]').value = '';
            document.querySelector('select[name="month"]').value = '';
            document.querySelector('input[name="date"]').value = '';
            load_posts();
        }
        
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("pagination-link")) {
                e.preventDefault();
                const page = e.target.getAttribute("data-page");
                load_posts(page);
                document.getElementById("post_cards_container").scrollIntoView({ behavior: "smooth" });
            }
        });
        
    </script>

<?php
General::site_footer();
General::site_script();
?>
