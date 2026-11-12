
<!-- Left Sidebar -->
<aside class="bg-white border-end shadow-sm d-flex flex-column position-fixed top-0 start-0 h-100" style="width: 280px; z-index: 1000;">
    <a href="#" class="d-flex align-items-center gap-3 p-4 py-3 text-decoration-none border-bottom text-dark">
        <div class="bg-success bg-gradient text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
            <i class="bi bi-headset fs-5"></i>
        </div>
        <div class="d-flex flex-column" style="line-height: 1.2;">
            <span class="fw-bolder fs-5">IT Support</span>
            <span class="text-muted fw-bold mt-2" style="font-size: 0.9rem;">
                Ticketing System 
            </span>
        </div>
    </a>
    
    <div class="p-4 py-1 overflow-auto flex-grow-1">

        <?php if ($_SESSION['role'] == 'Administrator'):?>
            <!-- Administrator Only -->
            <div class="sidebar-div" style="font-size: 0.75rem; letter-spacing: 1px;">Main</div>
            <a href="../dashboard/dashboard-admin.php" id="sb_dashboard" class="sidebar-link"><i class="bi bi-speedometer2 text-muted fs-5"></i> Dashboard</a>
        <?php endif ?>
        
        <div class="tsidebar-div">Tickets</div>
        <?php if ($_SESSION['role'] == 'Administrator'):?>
            <!-- Administrators Only -->
            <a href="../ticket/all-tickets.php" id="sb_all_tickets" class="sidebar-link"><i class="bi bi-inboxes text-muted fs-5"></i> View All Tickets</a>
        <?php endif ?>   
        <a href="../ticket/create-ticket.php" id="sb_create_ticket" class="sidebar-link"><i class="bi bi-plus-circle text-muted fs-5"></i> Create New Ticket</a>        
        <a href="../ticket/my-tickets.php" id="sb_my_tickets" class="sidebar-link"><i class="bi bi-list text-muted fs-5"></i> My Tickets</a>   

        <?php if ($_SESSION['role'] == 'Agent'):?>
            <!-- Agents Only -->
            <a href="../ticket/assigned-tickets.php" id="sb_assigned_tickets" class="sidebar-link"><i class="bi bi-list text-muted fs-5"></i> My Assigned Tickets</a>   
        <?php endif ?>  
        
        <?php if ($_SESSION['role'] == 'Administrator'):?>
            <div class="tsidebar-div">Administration</div>
            <a href="../user/all-users.php" id="sb_manage_users" class="sidebar-link"><i class="bi bi-people fs-5"></i> Manage Users</a>
            <a href="../category/manage-categories.php" id="sb_manage_categories" class="sidebar-link"><i class="bi bi-tags text-muted fs-5"></i> Manage Categories</a>
        <?php endif ?>  

        <div class="tsidebar-div">Settings</div>
        <a href="../user/my-profile.php" id="sb_profile" class="sidebar-link"><i class="bi bi-person-lines-fill text-muted fs-5"></i> My Profile</a>
        <a href="../user/change-password.php" id="sb_change_password" class="sidebar-link"><i class="bi bi-shield-lock text-muted fs-5"></i> Change Password</a>        
        <a href="../../controllers/auth_controller.php?logout=true" id="sb_logout" class="sidebar-link"><i class="bi bi-box-arrow-left text-muted fs-5"></i> Logout</a>
    </div>
</aside>