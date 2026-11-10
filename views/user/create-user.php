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

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 964px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-plus-circle fs-3 text-success"></i> Add New User</h2>
                <p class="card-panel-subtitle">Please provide the required details below.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/user_controller.php">                        
                        
                <div class="d-flex align-items-center text-dark fw-bold mb-4" style="font-size: 1.1rem;">
                    <i class="bi bi-info-circle me-2"></i> User Information
                    <div class="flex-grow-1 ms-3 border-top border-2"></div>
                </div>

                <div class="row mb-4">                                                 
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">
                            First Name<span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter the first name.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Last Name<span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter the last name.</div>
                    </div>
                </div>

                <div class="row mb-4">                                                 
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">
                            Username<span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Username" name="username" required minlength="4">
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Username must contain at least 4 characters.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Role <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <select class="form-select" name="role" required>
                            <option value="" selected disabled>Select Role</option>
                            <option value="requester">Requester</option>
                            <option value="agent">Agent</option>
                            <option value="administrator">Administrator</option>
                        </select>
                        <div class="valid-feedback">Role selected successfully!</div>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>
                </div>


                <div class="row mb-4"> 

                    <div class="col-md-6">
                        <label class="form-label">
                            Status <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <select class="form-select" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <div class="valid-feedback">Status selected successfully!</div>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>
                </div>

                
                <!--  Contact Details Section -->
                <div class="d-flex align-items-center text-dark fw-bold mb-4 mt-3" style="font-size: 1.1rem;">
                    <i class="bi bi-card-list me-2"></i> Contact Details
                    <div class="flex-grow-1 ms-3 border-top border-2"></div>
                </div>

                <div class="row mb-4">                                                 
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">
                            Phone Number <span class="text-danger fw-bolder ms-1">*</span>
                        </label>
                        <input type="text" class="form-control" name="phone_number" placeholder="Phone Number (eg: 0771234567)" required minlength="10" maxlength="10" pattern="0[0-9]{9}">
                        <div class="valid-feedback">Valid phone number!</div>
                        <div class="invalid-feedback">Please enter a valid 10-digit phone number starting with 0. <br>(Eg: 0771234567)</div>
                    </div>

                    <div class="col-md-6">                                
                        <label class="form-label">
                            Email Address
                        </label>
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">                                
                        <div class="invalid-feedback">Please enter a valid email address <br>(Eg: name@example.com).</div>
                    </div>
                </div>

                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="create_user_submit">
                        <i class="bi bi-check-circle"></i> Add User
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
        document.getElementById("breadcrumb_title").innerHTML = "New User";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "User";
        document.getElementById("breadcrumb_item_2").innerHTML = "Add New User";

        //Activate Sidebar Link
        document.getElementById("sb_manage_users").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>