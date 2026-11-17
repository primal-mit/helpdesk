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
    require_once '../../models/Ticket.php'; 
    $ticket = new Ticket();
    $tickets = $ticket->getRecentTickets();       
?>

<?php include_once('../partials/header.php'); ?>

<link rel="stylesheet" href="../../assets/css/dashboard.css">
    <!-- MAIN CONTENT: ADMIN DASHBOARD -->
    <main class="p-4 p-md-4 flex-grow-1">
   
    <!-- Quick Actions Grid -->
    <div class="mb-5">
        <h4 class="fw-bold mb-4 text-dark text-uppercase fs-5">Admin Dashboard</h4>

        <div class="row mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card border-gray">
                    <div class="stat-content">
                        <span class="stat-label">Total Active</span>
                        <span class="stat-value">6</span>
                    </div>
                    <div class="icon-wrapper icon-gray">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card border-orange">
                    <div class="stat-content">
                        <span class="stat-label">Open</span>
                        <span class="stat-value">2</span>
                    </div>
                    <div class="icon-wrapper icon-orange">
                        <i class="bi bi-person-dash"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card border-blue">
                    <div class="stat-content">
                        <span class="stat-label">In Progress</span>
                        <span class="stat-value">2</span>
                    </div>
                    <div class="icon-wrapper icon-blue">
                        <i class="bi bi-gear"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card border-green">
                    <div class="stat-content">
                        <span class="stat-label">Resolved</span>
                        <span class="stat-value">2</span>
                    </div>
                    <div class="icon-wrapper icon-green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
        

    <!-- Recent Ticket Requests Table Section -->
    <div class="w-100">
        <h4 class="fw-bold mb-4 text-dark text-uppercase fs-5">Recent Ticket Requests</h4>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                    <thead class="border-bottom">
                        <tr>
                            <th class="table-th ps-4 ">Ticket ID</th>
                            <th class="table-th">Subject & Requester</th>
                            <th class="table-th">Category</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Assigned To</th>
                            <th class="table-th">Created</th>
                            <th class="table-th">Priority</th>
                            <th class="text-center pe-4 table-th">Action</th>
                        </tr>
                    </thead>


                    <tbody class="border-top-0">

                        <?php foreach($tickets as $ticket): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark"><?= $ticket['id'] ?></td>
                            <td>
                                <div class="fw-semibold mb-1"><?= $ticket['subject'] ?></div>
                                <div class="requester-name"><?= $ticket['requester_first_name'] . ' ' . $ticket['requester_last_name'] ?></div>
                            </td>
                            <td class="text-dark fw-medium"><?= $ticket['category_name'] ?></td>                            
                            <?php
                                $status_badge = "";
                                switch (strtolower($ticket['status'])) {
                                    case 'open': $status_badge = 'warning'; break;
                                    case 'assigned': $status_badge = 'info'; break;
                                    case 'in progress': $status_badge = 'primary'; break;
                                    case 'resolved': $status_badge = 'success'; break;
                                    case 'closed': $status_badge = 'secondary'; break;
                                    default: $status_badge = 'secondary'; break;
                                }
                            ?>
                            <td><span class="badge priority-badge badge-<?= $status_badge; ?>"><?= ucfirst($ticket['status']) ?></span></td>
                            <td>
                                <?php if ($ticket['agent_first_name']) : ?>
                                    <div class="d-flex align-items-center fw-medium text-dark"><?= $ticket['agent_first_name'] ?> <?= $ticket['agent_last_name'] ?></div>
                                <?php else : ?>
                                    <span class="text-muted fst-italic fw-medium">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted fw-medium" style="font-size: 0.9rem;"><?= date('M d, H:i', strtotime($ticket['created_at'])) ?></td>
                            <?php                            
                                switch (strtolower($ticket['priority'])) {
                                    case 'low': $priority_badge = 'secondary'; break;
                                    case 'medium': $priority_badge = 'warning'; break;
                                    case 'high': $priority_badge = 'danger'; break;
                                    default: $priority_badge = 'secondary'; break;
                                }
                            ?>
                            <td><span class="badge priority-badge badge-<?php echo $priority_badge; ?>"><?= ucfirst($ticket['priority']) ?></span></td>  
                            <td class="text-center pe-4">
                                <a href="../../views/ticket/view-ticket.php?id=<?= $ticket['id'] ?>"><button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">View</button></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
                </div>
            </div>
            <!-- Table Footer/Pagination -->
            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing 1 to 5 entries</span>
                <a href="../../views/ticket/view-tickets.php"><button class="btn btn-sm btn-outline-secondary">View All</button></a>
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