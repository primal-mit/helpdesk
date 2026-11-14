<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}  

require_once '../../models/Database.php';
require_once '../../models/Category.php';
$category = new Category();
$categories = $category->getCategories();
?>

<?php include_once('../partials/header.php'); ?>


    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >        

        <!-- Data Table Panel -->
        <div class="card card-panel"  style="max-width: 864px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-plus-circle fs-3 text-success"></i> Create New Ticket</h2>
                <p class="card-panel-subtitle">Fill out all required fields so our team can assist you.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate action="../../controllers/ticket_controller.php" method="POST">
                        
                <!-- Ticket Information Section-->
                <div class="d-flex align-items-center text-dark fw-bold mb-4" style="font-size: 1.1rem;">
                    <i class="bi bi-info-circle me-2"></i> Ticket Information
                    <div class="flex-grow-1 ms-3 border-top border-2"></div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12 mb-4">
                        <label class="form-label">
                            <i class="bi bi-card-text text-success me-2"></i> Subject <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" name="subject" placeholder="Brief summary of the issue (e.g., Cannot connect to office Wi-Fi)" required>
                        <div class="valid-feedback">Subject looks good!</div>
                        <div class="invalid-feedback">Please enter a ticket subject.</div>
                    </div>
                    
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">
                            <i class="bi bi-grid text-success me-2"></i> Category <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <select class="form-select" name="category_id" required >
                            <option value="" selected disabled>Select Category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?=  $category['name'] ?></option>
                            <?php endforeach; ?>   
                            
                        </select>
                        <div class="valid-feedback">Category selected successfully!</div>
                        <div class="invalid-feedback">Please select a ticket category.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-exclamation-triangle text-success me-2"></i> Priority <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <select class="form-select" name="priority_id" required>
                            <option value="" selected disabled>Select Priority Level</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <div class="valid-feedback">Priority selected successfully!</div>
                        <div class="invalid-feedback">Please select a priority level.</div>
                    </div>
                </div>

                <!--  Issue Details Section -->
                <div class="d-flex align-items-center text-dark fw-bold mb-4 mt-3" style="font-size: 1.1rem;">
                    <i class="bi bi-card-list me-2"></i> Issue Details
                    <div class="flex-grow-1 ms-3 border-top border-2"></div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12 mb-4">
                        <label class="form-label">
                            <i class="bi bi-justify-left text-success me-2"></i> Detailed Description <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Please provide as much detail as possible to help us troubleshoot the issue..." required minlength="10"></textarea>
                        <div class="valid-feedback">Description looks good!</div>
                        <div class="invalid-feedback">Description must contain at least 10 characters.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="bi bi-paperclip text-success me-2"></i> Attachment (Optional)
                        </label>
                        <input type="file" class="form-control">
                    </div>
                </div> 

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                         <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>  
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="create_ticket_submit">
                        <i class="bi bi-check-circle me-1"></i> Submit Ticket
                    </button>
                    <button type="reset" class="btn btn-light bottom-btn ">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </button>   
                                             
                </div>
            </form>   
        </div>

    </main>
    <!-- END MAIN CONTENT -->


    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Create New Ticket";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
        document.getElementById("breadcrumb_item_2").innerHTML = "Create New Ticket";

        //Activate Sidebar Link
        document.getElementById("sb_create_ticket").classList.add("sidebar-active");
    </script>

   <?php include('../partials/footer.php'); ?>