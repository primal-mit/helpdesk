<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support - Ticketing System</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">   
</head>
<body class="bg-light- d-flex">


<!-- Sidebar -->
<?php include_once('../partials/sidebar.php'); ?>    


<!-- Right Content Wrapper -->
<div class="d-flex flex-column flex-grow-1 min-vh-100" style="margin-left: 280px; width: calc(100% - 280px);">
    
    <!-- Top Navbar -->
    <nav class="bg-white border-bottom shadow-sm px-4 py-3 d-flex justify-content-between align-items-center sticky-top" style="z-index: 900;">
        
        <!-- Breadcrumbs Area -->
        <div class="d-flex align-items-center gap-3">
            
            <!-- Title -->
            <h5 id="breadcrumb_title" class="mb-0 fw-bolder text-dark d-none d-md-block">TITLE</h5>

            <div class="vr d-none d-md-block bg-secondary opacity-25"></div>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb mb-0 fw-medium" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li id="breadcrumb_item_1" class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Item 1</a></li>
                    <li id="breadcrumb_item_2" class="breadcrumb-item active fw-bold text-dark" aria-current="page">Item 2</li>
                </ol>
            </nav>
        </div>
        
        <!-- Right Side Actions & Profile -->
        <div class="d-flex align-items-center gap-4">
                  
            <div class="d-flex align-items-center gap-3">
                                
                <div class="vr bg-secondary opacity-25"></div>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=Primal+Perera&background=0fa968&color=fff&rounded=true" alt="User" class="rounded-circle border" width="35" height="35">
                        <div class="d-none d-md-flex flex-column text-start" style="line-height: 1.2;">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                <?php echo $_SESSION['name']; ?>
                            </span>
                            <span class="text-success fw-bold" style="font-size: 0.75rem;">
                                <?php echo $_SESSION['role']; ?>
                            </span>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.8re\m;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm border-0 rounded-3">
                        <li><a class="dropdown-item fw-medium py-2" href=""><i class="bi bi-person me-2 text-muted"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-medium py-2" href="../../controllers/auth_controller.php?logout=true"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>