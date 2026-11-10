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
    require_once '../../models/Category.php';
    $category = new Category();
    $categories = $category->getCategories();
?>

    <!-- MAIN CONTENT: USER MANAGEMENT PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Page Header & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bolder mb-1 text-dark fs-3">Category Management</h2>
                <p class="text-muted mb-0 fw-medium">Add, edit, and manage categories for the system.</p>
            </div>
            <div>
                
                <button class="btn btn-default fw-bold shadow-sm px-4 rounded-pill" style="border: 1px solid #8888" onclick="location.reload();">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reload
                </button>             

                <a href="../category/create-category.php">
                <button class="btn btn-success fw-bold shadow-sm px-4 rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add New Category
                </button>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php include('../partials/alerts.php'); ?>

        <!-- Data Table Panel -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-white border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Name</th>
                            <th class="ps-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Description</th>
                            <th class="text-center pe-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">

                        <?php foreach($categories as $category): ?>
                        <tr>
                            <td class="ps-4 py-1 fw-medium"> <?= $category['name'] ?></td>
                            <td class="ps-4 py-1 text-muted"><?= $category['description'] ?></td>
                            <td class="text-center pe-4">
                                <a href="../category/view-category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-light text-success border" title="View Category"><i class="bi bi-eye"></i></a>
                                <a href="../category/edit-category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-light text-primary border me-1" title="Edit Category"><i class="bi bi-pencil"></i></a>
                                <a href="../category/delete-category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-light text-danger border" title="Delete Category"><i class="bi bi-trash"></i></a>
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
        document.getElementById("breadcrumb_title").innerHTML = "Manage Categories";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Category";
        document.getElementById("breadcrumb_item_2").innerHTML = "Manage Categories";

        //Activate Sidebar Link
        document.getElementById("sb_manage_categories").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>