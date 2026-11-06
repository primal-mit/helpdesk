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
            <div class="bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow" style="width: 60px; height: 60px; font-size: 2rem;">
                <i class="bi bi-headset"></i>
            </div>
            <h1 class="fw-bolder text-dark text-center mb-1 fs-4">Login</h1>
            <p class="text-muted text-center small fw-bold mb-4">IT Support Ticketing System</p>

            <!-- Login Form -->        
            <form action="../../controllers/auth_controller.php" method="POST" class="needs-validation" novalidate>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted mb-1">Username</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="username" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold text-muted mb-0">Password</label>
                        <a href="#" class="text-success text-decoration-none small fw-bold">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" class="form-control border-start-0 ps-0" name="password" value="password123" placeholder="Enter your password" required>
                    </div>
                </div>

                <!-- Display error message if login fails -->
                <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger py-1 small" role="alert">' . $_SESSION['error'] . '</div>';    
                        unset($_SESSION['error']);                
                    }
                ?>

                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label text-muted small fw-bold" for="rememberMe">Remember me on this device</label>
                </div>
            
                <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" name="login_submit">
                    Log In <i class="bi bi-arrow-right ms-1"></i>
                </button>

                <label class="form-check-label text-muted small- mt-1" for="rememberMe">Don't have an account? <a href="signup.php" class="text-success text-decoration-none small fw-bold">Sign Up</a></label>

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