<?php
    session_start();

    include('../require/database.php');
    include('../require/general.php');


    if (isset($_SESSION['user'])) {
        if ($_SESSION['user']['role_id'] == 2) {
            header("Location: admin/admin_dashboard.php?msg=Access Denied..!");
            exit();
        }
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_categories'){

        
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
    
        // Optional search
        $search = trim($_GET['search'] ?? '');
        $search_condition = "";

        if (!empty($search)) {
            $search = htmlspecialchars($search); 
            $search_condition = "WHERE category_title LIKE '%$search%' 
                                OR category_description LIKE '%$search%'";
        } 

        // Count total records
        $count_query = "SELECT COUNT(*) AS total FROM category $search_condition";
        $total_categories = $db->fetch_one($count_query)['total'] ?? 0;
        $total_pages = ceil($total_categories / $limit);
    
        // Fetch categories
        $categories = $db->fetch_all("
            SELECT * FROM category
            $search_condition 
            ORDER BY category_id DESC 
            LIMIT $limit OFFSET $offset
        ");

        if (!empty($categories)) {
            $count = $offset + 1;
            foreach ($categories as $category) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $category['category_title'] ?></td>
                    <td><?= $category['category_description'] ?></td>
                    <td>
                        <?php if ($category['category_status'] == 'Active') { ?>
                                <span class="badge bg-success">Active</span>
                        <?php }else{ ?>
                                <span class="badge bg-secondary">InActive</span>
                        <?php } ?> 
                    </td>
                    <td><?= $category['created_at'] ?></td>
                    <td><?= $category['updated_at']  == ("0000-00-00 00:00:00") ? "Null": $category['updated_at'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit_category_modal<?= $category['category_id'] ?>"><i class="fas fa-edit"></i></button>
                        <?php if ($category['category_status'] == 'Active') { ?>
                                    <button name="action" class="btn btn-danger btn-sm" onclick="update_status(<?= $category['category_id'] ?>, 'InActive')"><i class="fas fa-toggle-off"></i></button>
                        <?php } else { ?>
                                    <button name="action" class="btn btn-success btn-sm" onclick="update_status(<?= $category['category_id'] ?>, 'Active')"><i class="fas fa-toggle-on"></i></button>
                        <?php } ?> 
                    </td>
                </tr>
            <?php $count++;
            } 

             General::pagination($page, $total_pages);

        } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No Category found.</td></tr>";
        }
        
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_category'){

        $category_title = htmlspecialchars(trim($_POST['category_title']));
        $category_description = htmlspecialchars(trim($_POST['category_description']));
        $status = htmlspecialchars(trim($_POST['status']));

        $data = [
            "category_title" => $category_title,
            "category_description" => $category_description,
            "category_status" => $status,
            "created_at" => date('Y-m-d H:i:s', time()),
            "updated_at" => "0000-00-00 00:00:00",
        ];

        $result = $db->insert("category", $data);

        if($result){
            echo "Category: ".$category_title." is added.";
            exit();
        }else{
            echo "Error in adding category..!";
            exit();
        }
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_edit_category_modals'){

        $categories = $db->fetch_all("SELECT * FROM category");

        foreach ($categories as $category) {
?>
        
         <div class="modal fade" id="edit_category_modal<?= $category['category_id'] ?>" tabindex="-1" aria-labelledby="edit_category_modal_label<?= $category['category_id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <form class="modal-content" id="edit_category_form" action="../process/category_process.php?action=update_category" method="POST">
              <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="category_id" id="category_id" value="<?= $category['category_id'] ?>">
                <div class="mb-3">
                  <label class="form-label">Category Title</label>
                  <input type="text" class="form-control" name="category_title" id="edit_category_title" value="<?= $category['category_title'] ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" name="category_description" id="edit_category_description"><?= $category['category_description'] ?></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status" id="edit_category_status">
                    <option value="Active" <?= $category['category_status'] == 'Active' ? "selected" : "" ?> >Active</option>
                    <option value="InActive" <?= $category['category_status'] == 'InActive' ? "selected" : "" ?>>InActive</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">Update Category</button>
              </div>
            </form>
          </div>
        </div>   
<?php
        }
        
    }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_status'){

        $category_id = (int)$_REQUEST['category_id'];
        $status = $_REQUEST['status'];


             if ($status === 'Active') {
                $db->update("category", [
                    'category_status' => 'Active',
                    'updated_at' => date("Y-m-d h:i:s",time()),
                ], "category_id = $category_id");

                echo "Category with ID $category_id has been set to Active.";

            } elseif ($status === 'InActive') {
                 $db->update("category", [
                    'category_status' => 'InActive',
                    'updated_at' => date("Y-m-d h:i:s",time()),
                ], "category_id = $category_id");

                echo "Category with ID $category_id has been set to Inactive.";
            }
    }
?>