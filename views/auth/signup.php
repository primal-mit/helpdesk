<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Support Ticketing System</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-icons/bootstrap-icons.min.css">    

    <style>
        body{
            background-color: #f0fffb;
        }
        .card{
            border-radius: 1.2rem;
            border: 1px solid #0001;
        }
    </style>
</head>
<?php session_start(); ?>
<body class="d-flex flex-column align-items-center justify-content-center min-vh-100" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

<div class="w-100 px-3" style="max-width: 820px;">
    <div class="card shadow-lg p-4 p-md-5">

        <div class="bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow" style="width: 60px; height: 60px; font-size: 1.8rem;">
            <i class="bi bi-headset" style="font-size: 2rem;"></i>
        </div>
    
        <h1 class="fw-bolder text-dark text-center mb-2 fs-4">Sign Up!</h1>
        <p class="text-muted text-center small fw-bold mb-4">IT Support Ticketing System</p>

        <!-- Sign Up Form -->  
            <form class="row g-3 needs-validation" novalidate action="../../controllers/auth_controller.php" method="POST">                        
                    
            <div class="d-flex align-items-center text-dark fw-bold mb-4" style="font-size: 1.1rem;">
                <i class="bi bi-info-circle me-2"></i> User Information
                <div class="flex-grow-1 ms-3 border-top border-2"></div>
            </div>

            <div class="row mb-4">                                                 
                <div class="col-md-6 mb-4 mb-md-0">
                    <label class="form-label">
                        First Name<span class="text-danger fw-bolder ms-1">*</span>
                    </label>
                    <input type="text" class="form-control" name="first-name" placeholder="First Name" required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Please enter the first name.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Last Name<span class="text-danger fw-bolder ms-1">*</span>
                    </label>
                    <input type="text" class="form-control" name="last-name" placeholder="Last Name" required>
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Please enter the last name.</div>
                </div>
            </div>

            <div class="row mb-4">                                                 
                <div class="col-md-6 mb-4 mb-md-0">
                    <label class="form-label">
                        Username<span class="text-danger fw-bolder ms-1">*</span>
                    </label>
                    <input type="text" class="form-control" name="username" placeholder="Username" required minlength="4">
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
                        Email Address <span class="text-danger fw-bolder ms-1">*</span>
                    </label>
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">                                
                    <div class="invalid-feedback">Please enter a valid email address <br>(Eg: name@example.com).</div>
                </div>
            </div>


              <!-- Display error message if login fails -->
                <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger py-1 small" role="alert">' . $_SESSION['error'] . '</div>';    
                        unset($_SESSION['error']);                
                    }
                ?>

                <!-- Display success message if login is successful -->
                <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success py-1 small" role="alert">' . $_SESSION['success'] . '</div>';    
                        unset($_SESSION['success']);              
                    }
                ?>  
            

            <!-- Submit Button -->
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-light bottom-btn me-3">              
                    <i class="bi bi-caret-left-fill me-1"></i> Go to Login
                </a>                
                <button type="submit" class="btn btn-success bottom-btn me-3" name="signup_submit">
                    <i class="bi bi-check-circle"></i> Sign Up
                </button>
                <button type="reset" class="btn btn-light bottom-btn">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>                            
            </div>                        
        </form>  
    </div>

    <!-- Footer -->
    <div class="text-center mt-4 text-muted" style="font-size: 0.8rem;">
        &copy; 2026 IT Support Ticketing System.<br>All rights reserved.
    </div>
</div>

<script>    
    (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
        }, false)
    })
    })()
</script>


<!-- Bootstrap 5.1.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>
</body>
</html>