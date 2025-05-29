<?php

    include("../require/database.php");
    include("../process/mail_process.php");


    if(isset($_REQUEST['action']) && $_REQUEST['action'] == "load_user_requests"){

        $limit = 5; // Records per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max($page, 1); // Ensure page is at least 1
          
        $offset = ($page - 1) * $limit;
          
        // Count total records
        $totalQuery = "SELECT COUNT(*) AS total FROM user WHERE is_approved = 'pending'";
        $totalResult = $db->fetch_all($totalQuery);
        $totalRecords = $totalResult[0]['total'];
        $totalPages = ceil($totalRecords / $limit);
          
        // Fetch paginated records
        $query = "SELECT * FROM user WHERE is_approved = 'pending' ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $requests = $db->fetch_all($query);
        
        if (!empty($requests)) {
            $count = $offset + 1;
            foreach ($requests as $row) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><img src="../images/users/<?= $row['user_image']?? "user.jpg" ?>" width="50" height="50" class="rounded-circle object-fit-cover" alt="User"></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['gender'] ?></td>
                    <td><?= $row['date_of_birth'] ?></td>
                    <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <form method="POST" class="d-inline" onsubmit="approve_disapprove(event, 'accept', <?= $row['user_id'] ?>)">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <input type="hidden" name="name" value="<?= $row['first_name'] . " " . $row['last_name'] ?>">
                                <input type="hidden" name="email" value="<?= $row['email'] ?>">
                                <button name="action" value="accept" class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i> Accept</button>
                            </form>
                            <form method="POST" class="d-inline" onsubmit="approve_disapprove(event, 'reject', <?= $row['user_id'] ?>)">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <input type="hidden" name="name" value="<?= $row['first_name'] . " " . $row['last_name'] ?>">
                                <input type="hidden" name="email" value="<?= $row['email'] ?>">
                                <button name="action" value="reject" class="btn btn-sm btn-danger"><i class="fas fa-times me-1"></i> Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php $count++;
            } ?>
        
            <!-- AJAX Pagination Links -->
            <tr>
                <td colspan="8" class="text-center">
                    <div class="mt-3">
                        <nav>
                            <ul class="pagination justify-content-center rounded-3 p-2">
                                <!-- Previous -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link pagination-link <?= ($page > 1) ? 'bg-dark text-white' : '' ?>" data-page="<?= max(1, $page - 1) ?>">Previous</a>
                                </li>
          
                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a href="#" class="page-link pagination-link <?= ($i == $page) ? 'bg-dark text-white' : 'bg-secondary-subtle text-dark' ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php } ?>
                                
                                <!-- Next -->
                                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                    <a href="#" class="page-link pagination-link <?= ($page < $totalPages) ? 'bg-dark text-white' : '' ?>" data-page="<?= min($totalPages, $page + 1) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </td>
            </tr>
                                
        <?php } else { ?>
            <tr><td colspan="8" class="text-center text-warning fst-italic fw-bold">No users awaiting approval. You're all caught up, Admin!</td></tr>
        <?php }

              

    }else if (isset($_REQUEST['user_id']) && isset($_REQUEST['action']) && ($_REQUEST['action'] === 'accept' || $_REQUEST['action'] === 'reject')) {
        
        $user_id = (int)$_POST['user_id'];
        $name = $_POST['name'];
        $action = $_POST['action'];
        $email = $_POST['email'];

        if ($action === 'accept') {
            $db->execute_query("UPDATE user SET is_approved = 'Approved' WHERE user_id = $user_id");
            account_status_mail($name, $email, 'Approved');

            echo $msg= "User $name with $email ID $user_id has been approved.";
        } elseif ($action === 'reject') {
            $db->execute_query("UPDATE user SET is_approved = 'Rejected' WHERE user_id = $user_id");
            account_status_mail($name, $email, 'Rejected');
            echo $msg= "User $name with $email ID $user_id has been rejected.";
        }

    }else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "load_users") {

            $limit = 5;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max($page, 1);
            $offset = ($page - 1) * $limit;


            $search = trim($_GET['search'] ?? '');
            $condition = "WHERE role_id != 1";
            if (!empty($search)) {
                $condition .= " AND (CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR email LIKE '%$search%')";
            }

            $count_query = "SELECT COUNT(*) AS total FROM user $condition";
            $total_users = $db->fetch_one($count_query)['total'] ?? 0;
            $total_pages = ceil($total_users / $limit);

            $query = "SELECT * FROM user $condition ORDER BY user_id DESC LIMIT $limit OFFSET $offset";
            $users = $db->fetch_all($query);

            if (!empty($users)) {
                $count = $offset + 1;
                foreach ($users as $user) {
                ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><img src="../images/users/<?= $user['user_image']?? "user.jpg" ?>" width="50" height="50" class="rounded-circle object-fit-cover" /></td>
                    <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <?php if ($user['is_active'] == 'Active') { ?>
                            <span class="badge bg-success">Active</span>
                        <?php } else { ?>
                            <span class="badge bg-secondary">InActive</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['user_id'] ?>"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm <?= $user['is_active'] == 'Active' ? 'btn-danger' : 'btn-success' ?>" 
                              onclick="update_status('<?= $user['is_active'] == 'Active' ? 'InActive' : 'Active' ?>', <?= $user['user_id'] ?> , '<?= $user['first_name'] ?> <?= $user['last_name'] ?>', '<?= $user['email'] ?>')">
                            <i class="fas <?= $user['is_active'] == 'Active' ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                        </button>

                    </td>
                </tr>
                <?php
                }?>

                 <!-- AJAX Pagination Links -->
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="mt-3">
                            <nav>
                                <ul class="pagination justify-content-center rounded-3 p-2">
                                    <!-- Previous -->
                                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                        <a href="#" class="page-link pagination-link <?= ($page > 1) ? 'bg-dark text-white' : '' ?>" data-page="<?= max(1, $page - 1) ?>">Previous</a>
                                    </li>
              
                                    <!-- Page Numbers -->
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a href="#" class="page-link pagination-link <?= ($i == $page) ? 'bg-dark text-white' : 'bg-secondary-subtle text-dark' ?>" data-page="<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php } ?>
                                    
                                    <!-- Next -->
                                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                        <a href="#" class="page-link pagination-link <?= ($page < $total_pages) ? 'bg-dark text-white' : '' ?>" data-page="<?= min($total_pages, $page + 1) ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </td>
                </tr>
                <?php

            } elseif (empty($users)) {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }

        } elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "load_edit_models") {

            $query = "SELECT * FROM user WHERE role_id != 1";
            $users = $db->fetch_all($query);

            if (!empty($users)) {

                 foreach ($users as $user){ ?>
                    <!-- Modal -->
                    <div class="modal fade" id="editUserModal<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $user['user_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title fw-bold" id="editUserModalLabel<?= $user['user_id'] ?>"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="../process/user_process.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body bg-light">
                                        <div class="row g-3">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">First Name</label>
                                                <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Email</label>
                                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Password</label>
                                                <input type="password" class="form-control" name="password" placeholder="Enter new password">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Gender</label>
                                                <select class="form-select" name="gender">
                                                    <option <?= ($user['gender'] == "Male") ? "selected" : "" ?>>Male</option>
                                                    <option <?= ($user['gender'] == "Female") ? "selected" : "" ?>>Female</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Date of Birth</label>
                                                <input type="date" class="form-control" name="date_of_birth" value="<?= $user['date_of_birth'] ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Profile Image</label>
                                                <input type="file" class="form-control" name="user_image">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Current Image</label><br>
                                                <img src="../images/users/<?= $user['user_image'] ?>" width="60" height="60" class="rounded-circle object-fit-cover" alt="User Image">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Address</label>
                                                <textarea class="form-control" name="address" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="action" value="update_user" class="btn btn-dark fw-bold">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Edit User Modal -->
                    <?php } 

                    }else {
                        echo "<p class='text-center text-warning'>No users found.</p>";
                    }
                  
        
      }elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == "update_status") {
        
        $user_id = $_REQUEST['user_id'];
        $name = $_REQUEST['name'];
        $email = $_REQUEST['email'];
        $status = $_REQUEST['status'];

        if ($status == 'Active') {
            $db->execute_query("UPDATE user SET is_active = 'Active' WHERE user_id = $user_id");
            account_status_mail($name, $email, $status);
            echo $msg= "User $name with ID $user_id has been activated.";

        } elseif ($status == 'InActive') {

            $db->execute_query("UPDATE user SET is_active = 'InActive' WHERE user_id = $user_id");
            account_status_mail($name, $email, $status);
            echo $msg= "User $name with ID $user_id has been deactivated.";

        }
    }else {
        echo "<p class='text-center text-danger'>Invalid action.</p>";
    }
?>


