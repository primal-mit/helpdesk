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
$category_details = $category->getCategoryById($category_id);
if (!$category_details) {
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
                <h2 class="card-panel-title"><i class="bi bi-pencil fs-3 text-warning"></i> Edit Category</h2>
                <p class="card-panel-subtitle">Please update the required details below.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/category_controller.php">  
                <input type="hidden" class="form-control" name="category_id" required value="<?php echo $category_details['id']; ?>">                      
                        
                <div class="d-flex align-items-center text-dark fw-bold mb-4" style="font-size: 1.1rem;">
                    <i class="bi bi-info-circle me-2"></i> Category Information
                    <div class="flex-grow-1 ms-3 border-top border-2"></div>
                </div>

                <div class="row mb-4">                                                 
                    <div class="col-md-12 mb-4 mb-md-0">
                        <label class="form-label">
                            Category Name<span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Category Name" name="name" value="<?php echo $category_details['name']; ?>" required>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter the category name.</div>
                    </div>
                </div>

                <div class="row mb-4">     
                    <div class="col-md-12">
                        <label class="form-label">
                            Description<span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Description" name="description" value="<?php echo $category_details['description']; ?>" required>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter the description.</div>
                    </div>
                </div>

                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="update_category_submit">
                        <i class="bi bi-check-circle"></i> Update Category
                    </button>
                    <button type="reset" class="btn btn-light bottom-btn">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>                            
                </div>
                        
            </form>        
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Edit Category";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Category";
        document.getElementById("breadcrumb_item_2").innerHTML = "Edit Category";

        //Activate Sidebar Link
        document.getElementById("sb_manage_categories").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>