<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}
?>
<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 800px;">
            <div class="mb-3 text-center border-bottom pb-4 mb-4">
                <h2 class="card-panel-title"><i class="bi bi-shield-lock fs-3 text-success"></i> Change Password</h2>
                <p class="card-panel-subtitle">Ensure your account is using a long, random password to stay secure.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/user_controller.php">   
                                                                    
                    <div class="mb-1">
                        <label class="form-label fw-bold text-secondary mb-2" style="font-size: 0.9rem;">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="current_password" placeholder="Enter your current password" required>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter your current password.</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-bold text-secondary mb-2" style="font-size: 0.9rem;">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="Enter your new password" required minlength="8">
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter your new password. <br>(Minimum 8 characters)</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-bold text-secondary mb-2" style="font-size: 0.9rem;">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" id="confirmPassword" placeholder="Enter new password" required minlength="8">
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback" id="confirmPasswordFeedback">Please re-enter your current password.</div>
                    </div>       
                    
                    <!-- Alert -->
                    <?php include('../partials/alerts.php'); ?>

                    <!-- Submit Button -->
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-light rounded-pill fw-bold px-5 py-2 shadow-sm me-3" onclick="goBack()">              
                            <i class="bi bi-caret-left-fill me-1"></i> Go Back
                        </button>
                        <button type="submit" name="change_password_submit" class="btn btn-success rounded-pill fw-bold px-5 py-2 shadow-sm me-3">
                            <i class="bi bi-check-circle"></i> Change Password
                        </button>
                        <button type="reset" class="btn btn-light rounded-pill fw-bold px-5 py-2 shadow-sm">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>                            
                    </div>
                        
            </form>          
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Change Password";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "User";
        document.getElementById("breadcrumb_item_2").innerHTML = "Change Password";

        //Activate Sidebar Link
        document.getElementById("sb_change_password").classList.add("sidebar-active");
    </script>




    <script>
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        const newPassword = document.getElementById('newPassword');
                        const confirmPassword = document.getElementById('confirmPassword');
                        const confirmFeedback = document.getElementById('confirmPasswordFeedback');

                        // Custom validation: check if passwords match
                        if (newPassword.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('Passwords do not match');
                            confirmFeedback.textContent = 'Passwords do not match.';
                        } else {
                            confirmPassword.setCustomValidity(''); // Reset error
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })

            // Optional: Real-time validation as the user types in the confirm password field
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');

            function validatePasswordMatch() {
                if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                    document.getElementById('confirmPasswordFeedback').textContent = 'Passwords do not match.';
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            if (newPassword && confirmPassword) {
                newPassword.addEventListener('input', validatePasswordMatch);
                confirmPassword.addEventListener('input', validatePasswordMatch);
            }
        })();
    </script>

<?php include('../partials/footer.php'); ?>