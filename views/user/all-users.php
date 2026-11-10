<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}
if ($_SESSION['role'] !== 'Administrator') {
    echo "Access Denied. You do not have permission to access this page.";
    exit();
}
?>

<?php include_once('../partials/header.php'); ?>

<?php
    require_once '../../models/Database.php';
    require_once '../../models/User.php';
    $user = new User();
    $users = $user->getUsers();
?>

    <!-- MAIN CONTENT: USER MANAGEMENT PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Page Header & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bolder mb-1 text-dark fs-3">User Management</h2>
                <p class="text-muted mb-0 fw-medium">Add, edit, and manage system access for requesters, agents, and administrators.</p>
            </div>
            <div>
                <a href="../user/create-user.php">
                <button class="btn btn-success fw-bold shadow-sm px-4 rounded-pill">
                    <i class="bi bi-person-plus-fill me-1"></i> Add New User
                </button>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php include('../partials/alerts.php'); ?>

        <!-- Filters Panel -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 pb-3">
            <form class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Search User</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted shadow-none"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-0 ps-0 shadow-none" placeholder="Search by name or email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">System Role</label>
                    <select class="form-select bg-light border-0 shadow-none">
                        <option value="all">All Roles</option>
                        <option value="admin">Administrator</option>
                        <option value="agent">Agent</option>
                        <option value="requester">Requester</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Account Status</label>
                    <select class="form-select bg-light border-0 shadow-none">
                        <option value="all">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-secondary me-1 fw-bold px-3">Clear</button>
                    <button type="button" class="btn btn-success fw-bold px-3">Filter</button>
                </div>
            </form>
        </div>

        <!-- Data Table Panel -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-white border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Name</th>
                            <th class="py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Username</th>
                            <th class="py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Role</th>
                            <th class="py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Status</th>
                            <th class="py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Email</th>
                            <th class="text-center pe-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">

                        <?php foreach($users as $u): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">                                   
                                    <div>
                                        <a href="#" class="text-dark fw-bold text-decoration-none d-block mb-0"><?= ($u['first_name'] . ' ' . $u['last_name']) ?></a>                                        
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted fw-medium"><?= $u['username'] ?></td>
                            <td><?= $u['role'] ?></td>
                            <td class="text-dark fw-medium">
                                <?php if($u['status'] == 'Active'): ?>
                                    <span class="d-inline-block rounded-circle bg-success me-1" style="width: 8px; height: 8px;"></span> <?= $u['status'] ?>    
                                <?php else: ?>
                                    <span class="d-inline-block rounded-circle bg-danger me-1" style="width: 8px; height: 8px;"></span> <?= $u['status'] ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted fw-medium" style="font-size: 0.9rem;"><?= $u['email'] ?></td>
                            <td class="text-center pe-4">
                                <a href="../user/view-user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-light text-success border" title="View User"><i class="bi bi-eye"></i></a>
                                <a href="../user/edit-user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-light text-primary border me-1" title="Edit User"><i class="bi bi-pencil"></i></a>
                                <a href="../user/delete-user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-light text-danger border" title="Delete User"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>            
        </div>
    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Manage Users";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "User";
        document.getElementById("breadcrumb_item_2").innerHTML = "Manage Users";

        //Activate Sidebar Link
        document.getElementById("sb_manage_users").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>