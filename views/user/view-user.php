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
require_once '../../models/User.php';
require_once '../../models/Ticket.php';

if(!isset($_GET['id']) || $_GET['id']==""){
    echo "No user id found!";
}
$user_id = $_GET['id'];

// Fetch user details from the database
$user = new User();
$user = $user->getUserById($user_id);
if (!$user) {
    echo "User not found.";
    exit();
}

$ticket = new Ticket();
?>

<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 800px;">
        
           
            <!-- Top Section -->
            <div class="row align-items-center mb-4">
                
                <!-- Left Side: Profile Info -->
                  <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">View User Profile</h2>
            <p class="text-muted mb-0">Manage your personal information.</p>
        </div>

                <div class="col-md-6 d-flex flex-column align-items-center align-items-md-start text-center text-md-start mb-4 mb-md-0 ps-md-4">
                    <div class="position-relative mb-3 mt-2">
                        <img src="https://ui-avatars.com/api/?name=<?= $user['first_name'] ." ". $user['last_name'] ?>&background=0fa968&color=fff&rounded=true&size=100" alt="Profile" class="avatar-xl">                          
                    </div>
                    <h4 class="fw-bold mb-1" style="color: var(--text-main);"><?= $user['first_name'] ." ". $user['last_name'] ?></h4>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 mb-2"><?= $user['role'] ?></span>
                    <p class="text-muted small mb-0"><i class="bi bi-envelope me-1"></i> <?= $user['email'] ?></p>
                    <p class="text-muted small"><i class="bi bi-c   ndar3 me-1"></i> Joined on <?= date('M d, Y', strtotime($user['created_at']))  ?></p>
                </div>

                <!-- Right Side: Ticket Stats & Action Buttons -->
                <div class="col-md-6 d-flex flex-column align-items-center align-items-md-end text-center text-md-end pe-md-4">
                    
                    <!-- Ticket Count -->
                    <div class="mb-4">
                    <h1 class="display-4 fw-bold text-success mb-0 lh-1"><?= $ticket->getCreatedTicketsCount($user_id); ?></h1>
                        <span class="text-secondary fw-semibold">Submitted Tickets</span>
                    </div>

                    <!-- Agent Only -->
                     <?php if($user['role'] == "Agent"): ?>
                        <div class="mb-4">
                            <h1 class="display-4 fw-bold text-success mb-0 lh-1"><?= $ticket->getAssignedTicketsCount($user_id); ?></h1>
                            <span class="text-secondary fw-semibold">Assigned Tickets</span>
                        </div>
                    <?php endif ?>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column gap-3 w-100 mb-2" style="max-width: 220px;">
                        <a href="../../views/ticket/view-tickets.php?filter_requester_id=<?= $user_id ?>" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 py-2">
                            <i class="bi bi-ticket-detailed"></i> View Submitted Tickets
                        </a>
                    </div>

                    <?php if($user['role'] == "Agent"): ?>
                    <div class="d-flex flex-column gap-3 w-100" style="max-width: 220px;">
                        <a href="../../views/ticket/view-tickets.php?filter_agent_id=<?= $user_id ?>" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 py-2">
                            <i class="bi bi-ticket-detailed"></i> View Assigned Tickets
                        </a>
                    </div>
                    <?php endif ?>
                    
                </div>
            </div>

            <!-- Horizontal Separator -->
            <hr class="my-2 border-secondary border-opacity-75">

            <!-- Bottom Section: Personal Information -->
            <div class="px-md-2">
                <h4 class="fw-normal text-dark mb-4 d-flex align-items-center gap-2" style="font-size: 1.5rem;">
                    <i class="bi bi-person-fill text-dark"></i> Personal Information
                </h4>
                
                
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>" disabled>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter the first name.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>" disabled>
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter the last name.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" disabled pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">     
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter a valid email address <br>(Eg: name@example.com).</div>                       
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number (Optional)</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= $user['phone_number'] ?>" disabled minlength="10" maxlength="10" pattern="0[0-9]{9}">
                            <div class="valid-feedback">Looks good!</div>
                            <div class="invalid-feedback">Please enter a valid 10-digit phone number starting with 0. <br>(Eg: 0771234567)</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= $user['username'] ?>" disabled>
                        </div>
                    </div>
                    
                    <!-- Alert -->
                    <?php include('../partials/alerts.php'); ?>

                    <!-- Save Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="buttun" name="save_my_profile_submit" class="btn btn-outline-secondary px-4 py-2 fw-semibold me-2" onclick="goBack()">
                            Go Back
                        </button>

                        <a href="../../views/user/edit-user.php?id=<?= $user_id ?>">
                        <button type="buttun" name="save_my_profile_submit" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                            Edit User
                        </button>
                        </a>
                    </div>
                
            </div>

        
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
         // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "View User Profile";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "User";
        document.getElementById("breadcrumb_item_2").innerHTML = "View User Profile";

        //Activate Sidebar Link
        document.getElementById("sb_manage_users").classList.add("sidebar-active");
    </script>




<?php include('../partials/footer.php'); ?>