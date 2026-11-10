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

require_once '../../models/Database.php';
require_once '../../models/Category.php';

$category_id = $_GET['id'] ?? null;
if (!$category_id) {
    echo "Category ID is missing.";
    exit();
}
    
// Fetch category details from the database
$category = new Category();
$category = $category->getCategoryById($category_id);
if (!$category) {
    echo "Category not found.";
    exit();
}

?>

<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 964px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-trash fs-3 text-warning"></i> Delete Category</h2>
                <p class="card-panel-subtitle">Please confirm the deletion of the category below.</p>
            </div>

            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i> Warning!</h4>
                <p>Deleting a category is a permanent action and cannot be undone. Please ensure that you have selected the correct category before proceeding.</p>
                <hr>
                <p class="mb-0">Category to be deleted: <strong><?php echo htmlspecialchars($category['name']); ?></strong></p>
            </div>


            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/category_controller.php">  
                <input type="hidden" class="form-control" name="category_id" required value="<?php echo $category['id']; ?>">                      
                      
                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-danger bottom-btn me-3" name="delete_category_submit">
                        <i class="bi bi-trash"></i> Delete Category
                    </button>                        
                </div>
                        
            </form>        
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Delete Category";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Category";
        document.getElementById("breadcrumb_item_2").innerHTML = "Delete Category";

        //Activate Sidebar Link
        document.getElementById("sb_manage_categories").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>