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

    <div class="w-100 px-3" style="max-width: 500px;">
        <div class="card shadow-lg p-4 p-md-5">
            
            <!-- Branding Header -->
            
            <h1 class="fw-bolder text-dark text-center mb-1 fs-4">Reset Password</h1>
            <!-- <p class="text-muted text-center small fw-bold mb-4">IT SRupport Ticketing System</p> -->

            <!-- Login Form -->        
            <form action="../../controllers/auth_controller.php" method="POST" class="needs-validation" novalidate>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0" name="email" placeholder="Enter your email address" required>
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

            
                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-light bottom-btn me-3">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go to Login
                    </a>                
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="reset_password_submit">
                        <i class="bi bi-check-circle"></i> Reset Password
                    </button>                                           
                </div> 

            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 text-muted" style="font-size: 0.8rem;">
            &copy; 2026 IT Support Ticketing System.<br>All rights reserved.
        </div>
        
    </div>

    <!-- Bootstrap 5.1.3 JS -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    </body>
</html>