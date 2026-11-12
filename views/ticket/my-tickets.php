<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}
?>

<?php
    require_once '../../models/Database.php';
    require_once '../../models/Ticket.php'; 
    $ticket = new Ticket();
    $tickets = $ticket->getMyTickets();       
?>


<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT: TICKET QUEUE PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Page Header & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bolder mb-1 text-dark fs-3">View My Tickets</h2>
                <p class="text-muted mb-0 fw-medium">View My tickets and view their current status.</p>
            </div>
            <div>
                <button class="btn btn-success fw-bold shadow-sm px-4 rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> New Ticket
                </button>
            </div>
        </div>

        <!-- Filters Panel -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 pb-3">
            <form class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Ticket ID</label>
                    <input type="text" class="form-control bg-light border-0 shadow-none" name="filter_ticket_id" placeholder="Ticket ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Status</label>
                    <select class="form-select bg-light border-0 shadow-none" name="filter_status">
                        <option value="all">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="assigned">Assigned</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Priority</label>
                    <select class="form-select bg-light border-0 shadow-none" name="filer_priority">
                        <option value="all">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Category</label>
                    <select class="form-select bg-light border-0 shadow-none" name="filter_category">
                        <option value="all">All Categories</option>
                        <option value="hardware">Hardware</option>
                        <option value="software">Software</option>
                        <option value="network">Network</option>
                        <option value="account">Account</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-outline-secondary me-2 fw-bold">Clear</button>
                    <button type="button" class="btn btn-success fw-bold px-4"><i class="bi bi-funnel me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <!-- Data Table Panel -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-0">
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
                                <a href="view-ticket.php?id=<?= $ticket['id'] ?>"><button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">View</button></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>

    </main>
    <!-- END MAIN CONTENT -->

<script>
    // Set Breadcrumb
    document.getElementById("breadcrumb_title").innerHTML = "View My Tickets";
    document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
    document.getElementById("breadcrumb_item_2").innerHTML = "View My Ticket";

    //Activate Sidebar Link
    document.getElementById("sb_my_tickets").classList.add("sidebar-active");
</script>

<?php include_once('../partials/footer.php'); ?>