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

<?php
    require_once '../../models/Database.php';
    require_once '../../models/Ticket.php'; 
    require_once '../../models/Category.php';
    require_once '../../models/User.php';
            
    $category = new Category();
    $categories = $category->getCategories();

    $user = new User();
    $requesters = $user->getRequesterUsers();
    $agents = $user->getAgentUsers();

    //Filters
    $filter_ticket_id = $_GET['filter_ticket_id'] ?? '';
    $filter_status = $_GET['filter_status'] ?? '';
    $filter_priority = $_GET['filer_priority'] ?? '';
    $filter_category_id = $_GET['filter_category_id'] ?? '';
    $filter_requester_id = $_GET['filter_requester_id'] ?? '';
    $filter_agent_id = $_GET['filter_agent_id'] ?? '';    

    $ticket = new Ticket(); 
    $tickets = $ticket->getTickets($filter_ticket_id, $filter_status, $filter_priority, $filter_category_id, $filter_requester_id, $filter_agent_id);     
    
?>


<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT: TICKET QUEUE PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Page Header & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bolder mb-1 text-dark fs-3">View All Tickets</h2>
                <p class="text-muted mb-0 fw-medium">Manage and track all support requests across the organization.</p>
            </div>
            <div>
                <button class="btn btn-default fw-bold shadow-sm px-4 rounded-pill" style="border: 1px solid #8888" onclick="location.reload();">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reload
                </button> 
                <a href="../../views/ticket/create-ticket.php">
                <button class="btn btn-success fw-bold shadow-sm px-4 rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> New Ticket
                </button>
                </a>
            </div>
        </div>

        
        <!-- Alerts -->
        <?php include('../partials/alerts.php'); ?>
        

        <!-- Filters Panel -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 pb-3">
            <form class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Ticket ID</label>
                    <input type="text" class="form-control" name="filter_ticket_id" placeholder="Ticket ID" value="<?= $filter_ticket_id; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Status</label>
                    <select class="form-select" name="filter_status">
                        <option value="">All Statuses</option>
                        <option value="Open" <?= ($filter_status == 'Open') ? 'selected' : '' ?>>Open</option>
                        <option value="Assigned"<?= ($filter_status == 'Assigned') ? 'selected' : '' ?>>Assigned</option>
                        <option value="In Progress"<?= ($filter_status == 'n Progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="Resolved"<?= ($filter_status == 'Resolved') ? 'selected' : '' ?>>Resolved</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Priority</label>
                    <select class="form-select" name="filer_priority">
                        <option value="">All Priorities</option>
                        <option value="Low" <?= ($filter_priority == 'Low') ? 'selected' : '' ?>>Low</option>
                        <option value="Medium" <?= ($filter_priority == 'Medium') ? 'selected' : '' ?>>Medium</option>
                        <option value="High" <?= ($filter_priority == 'High') ? 'selected' : '' ?>>High</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Category</label>
                    <select class="form-select " name="filter_category_id">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($category['id'] == $filter_category_id) ? 'selected': '' ?> ><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


                 <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Requester</label>
                    <select class="form-select" name="filter_requester_id">
                        <option value="">All Requesters</option>
                        <?php foreach($requesters as $requester): ?>
                        <option value="<?= $requester['id'] ?>" <?= ($requester['id'] == $filter_requester_id) ? 'selected': '' ?> ><?= $requester['first_name']." ". $requester['last_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                 <div class="col-md-3">
                    <label class="form-label text-dark fw-bold mb-1" style="font-size: 0.85rem;">Agent</label>
                    <select class="form-select" name="filter_agent_id">
                        <option value="">All Agents</option>
                        <?php foreach($agents as $agent): ?>
                        <option value="<?= $agent['id'] ?>" <?= ($agent['id'] == $filter_agent_id) ? 'selected': '' ?> ><?= $agent['first_name']." ". $agent['last_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 text-start">
                    <button type="reset" class="btn btn-outline-secondary me-2 fw-bold">Clear</button>
                    <button type="submit" class="btn btn-success fw-bold px-4"><i class="bi bi-funnel me-1"></i> Filter</button>
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
                            <td class="ps-4 fw-bold text-dark"><?= formatTicketNumber($ticket['id']) ?></td>
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
                            <td><span class="badge status-badge badge-<?= $status_badge; ?> py-1 px-2"><?= ucfirst($ticket['status']) ?></span></td>
                            <td>
                                <?php if ($ticket['agent_first_name']) : ?>
                                    <div class="d-flex align-items-center fw-medium text-dark"><?= $ticket['agent_first_name'] ?> <?= $ticket['agent_last_name'] ?></div>
                                <?php else : ?>
                                    <span class="text-muted fst-italic fw-medium">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted fw-medium" style="font-size: 0.9rem;"><?= date('M d, H:i', strtotime($ticket['created_at'])) ?></td>
                            <?php                            
                                switch (($ticket['priority'])) {
                                    case 'Low': $priority_badge = 'success'; break;
                                    case 'Medium': $priority_badge = 'warning'; break;
                                    case 'High': $priority_badge = 'danger'; break;
                                    default: $priority_badge = 'secondary'; break;
                            }
                            ?>
                            <td><span class="badge priority-badge badge-<?php echo $priority_badge; ?> py-1 px-2"><?= ucfirst($ticket['priority']) ?></span></td>  
                            <td class="text-center pe-4">
                                <a href="view-ticket.php?id=<?= $ticket['id'] ?>"><button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">View</button></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>  
                         
                    </tbody>
                </table>               
            </div>       
        </div>

        <?php if (count($tickets) == 0):  ?>
            <p>No Results Found!</p>
        <?php endif ?>

    </main>
    <!-- END MAIN CONTENT -->

<script>
    // Set Breadcrumb
    document.getElementById("breadcrumb_title").innerHTML = "View All Tickets";
    document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
    document.getElementById("breadcrumb_item_2").innerHTML = "View All Ticket";

    //Activate Sidebar Link
    document.getElementById("sb_all_tickets").classList.add("sidebar-active");
</script>

<?php include_once('../partials/footer.php'); ?>