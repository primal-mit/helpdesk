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

<link rel="stylesheet" href="../../assets/css/dashboard.css">
    <!-- MAIN CONTENT: ADMIN DASHBOARD -->
    <main class="p-4 p-md-4 flex-grow-1">
   
    <!-- Quick Actions Grid -->
    <div class="mb-5">
        <h4 class="fw-bold mb-4 text-dark text-uppercase fs-5">Quick Actions</h4>
        <div class="row g-3">
            
            <!-- All Tickets Queue -->
            <a href="../../views/tickets/tickets-all.php" class="col-12 col-sm-6 col-md-4 col-xl-3">                
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-inbox"></i>
                    <span>All Tickets</span>
                </button>            
            </a>
            
            <!-- Create New Ticket -->
            <a href="../../views/tickets/create-ticket.php" class="col-12 col-sm-6 col-md-4 col-xl-3">
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-plus-circle"></i>
                    <span>Create New Ticket</span>
                </button>     
            </a>

            <!-- Create New Ticket -->
            <a href="../../views/tickets/my-tickets.php" class="col-12 col-sm-6 col-md-4 col-xl-3">
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-list"></i>
                    <span>My Tickets</span>
                </button>     
            </a>
            
            <!-- Manage Users -->
            <a href="../../views/user/all-users.php" class="col-12 col-sm-6 col-md-4 col-xl-3">            
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-people"></i>
                    <span>Manage Users</span>
                </button>
            </a>

               
            <!-- Manage Categories -->
            <a href="../../views/category/manage-categories.php" class="col-12 col-sm-6 col-md-4 col-xl-3">
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-tags"></i>
                    <span>Manage Categories</span>
                </button>
            </a>
            
            <!-- My Profile -->
            <a href="../../views/user/my-profile.php" class="col-12 col-sm-6 col-md-4 col-xl-3"> 
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-person-badge"></i>
                    <span>My Profile</span>
                </button>
            </a>
            
            <!-- Change Password -->
            <a href="../../views/user/change-password.php" class="col-12 col-sm-6 col-md-4 col-xl-3">
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-shield-lock"></i>
                    <span>Change Password</span>
                </button>
            </a>
            
            <!-- Logout -->
            <a href="../../controllers/auth_controller.php?logout=true" class="col-12 col-sm-6 col-md-4 col-xl-3">
                <button class="btn btn-quick-action shadow-md">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </a>
        </div>
    </div>
        

    <!-- Recent Ticket Requests Table Section -->
    <div class="w-100">
        <h4 class="fw-bold mb-4 text-dark text-uppercase fs-5">Recent Ticket Requests</h4>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle recent-tickets-table">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>Requester</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Row 1: Open -->
                            <tr>
                                <td class="fw-semibold">#TCK-1045</td>
                                <td class="text-dark">Printer requires maintenance</td>
                                <td>Kasun Silva</td>
                                <td class="status-column"><span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Open</span></td>
                                <td class="priority-column"><span class="badge bg-secondary px-3 py-2 rounded-pill">Low</span></td>
                                <td class="text-muted">Oct 20, 2026</td>
                            </tr>
                            <!-- Sample Row 2: Assigned -->
                            <tr>
                                <td class="fw-semibold">#TCK-1044</td>
                                <td class="text-dark">Need Adobe Creative Cloud license</td>
                                <td>Ashan Fernando</td>
                                <td class="status-column"><span class="badge text-white px-3 py-2 rounded-pill" style="background-color: #6f42c1;">Assigned</span></td>
                                <td class="priority-column"><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Medium</span></td>
                                <td class="text-muted">Oct 19, 2026</td>
                            </tr>
                            <!-- Sample Row 3: In Progress -->
                            <tr>
                                <td class="fw-semibold">#TCK-1043</td>
                                <td class="text-dark">Database migration error on staging</td>
                                <td>Nipun Perera</td>
                                <td class="status-column"><span class="badge bg-primary px-3 py-2 rounded-pill">In Progress</span></td>
                                <td class="priority-column"><span class="badge bg-danger px-3 py-2 rounded-pill">High</span></td>
                                <td class="text-muted">Oct 19, 2026</td>
                            </tr>
                            <!-- Sample Row 4: Resolved -->
                            <tr>
                                <td class="fw-semibold">#TCK-1042</td>
                                <td class="text-dark">Cannot connect to office VPN</td>
                                <td>Supun Dilantha</td>
                                <td class="status-column"><span class="badge bg-success px-3 py-2 rounded-pill">Resolved</span></td>
                                <td class="priority-column"><span class="badge bg-danger px-3 py-2 rounded-pill">High</span></td>
                                <td class="text-muted">Oct 18, 2026</td>
                            </tr>
                            <!-- Sample Row 5: Closed -->
                            <tr>
                                <td class="fw-semibold">#TCK-1041</td>
                                <td class="text-dark">Requesting new dual monitors</td>
                                <td>Kapila Gunawardana</td>
                                <td class="status-column"><span class="badge bg-secondary px-3 py-2 rounded-pill">Closed</span></td>
                                <td class="priority-column"><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Medium</span></td>
                                <td class="text-muted">Oct 15, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table Footer/Pagination -->
            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing 1 to 5 of 42 entries</span>
                <button class="btn btn-sm btn-outline-secondary">View All</button>
            </div>
        </div>
    </div>

    </main>
    <!-- END MAIN CONTENT -->

<script>
    // Set Breadcrumb
    document.getElementById("breadcrumb_title").innerHTML = "Dashboard";
    document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Dashboard";
    document.getElementById("breadcrumb_item_2").innerHTML = "Dashboard - Admin";

    //Activate Sidebar Link
    document.getElementById("sb_dashboard").classList.add("sidebar-active");
</script>

<?php include_once('../partials/footer.php'); ?>