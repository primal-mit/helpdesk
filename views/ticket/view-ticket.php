<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}


require_once '../../models/Database.php';
require_once '../../models/Ticket.php'; 
require_once '../../models/User.php'; 
require_once '../../models/StatusHistory.php'; 

$ticket = new Ticket();
$ticket_id = $_GET["id"];
$ticket = $ticket->getTicket($ticket_id);  


//Denie access to others tickets (Agents)
if ($_SESSION['role'] == 'Agent' && $ticket['agent_id'] != $_SESSION['user_id']) {
    echo "Access Denied. You do not have permission to access this page.";
    exit();
}

//Denie access to others tickets (Requester)
if ($_SESSION['role'] == 'Requester' && $ticket['requester_id'] != $_SESSION['user_id']) {  
    echo "Access Denied. You do not have permission to access this page.";
    exit();
}




$user = new User();
$agents = $user->getAgentUsers();

$current_status_index = 0;
switch($ticket['status']){
    case "Open": $current_status_index = 0; break;
    case "Assigned": $current_status_index = 1; break;
    case "In Progress": $current_status_index = 2; break;
    case "Resolved": $current_status_index = 3; break;
    case "Closed": $current_status_index = 4; break;
}

?>


<?php include_once('../partials/header.php'); ?>

<link rel="stylesheet" type="text/css" href="../../assets/css/timeline.css"/>

    <!-- MAIN CONTENT: TICKET DETAILS PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Ticket Header -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div onclick="goBack()" class="text-decoration-none text-muted fw-bold mb-2 d-inline-block" style="font-size: 0.9rem;"><i class="bi bi-arrow-left me-1"></i> Back to Queue</div>
                <h2 class="fw-bolder mb-1 text-dark fs-3"><?= formatTicketNumber($ticket['id']) ?></h2>
                <h5 class="text-muted fw-medium fs-5"><?= $ticket['subject'] ?></h5>
            </div>

            <!-- Timeline -->
            <div class="timeline-container">
                    <div class="timeline-wrapper">
                        
                    <!-- Background Progress Line -->
                    <div class="progress timeline-progress-bg">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Timeline Nodes -->
                    <div class="d-flex justify-content-between position-relative w-100 text-center" id="timeline-nodes">
                        <!-- Nodes will be generated and updated by JavaScript -->
                    </div>
                    
                </div>
            </div>
            
            <!-- Right Status -->
            <div class="text-center">
                
                <span class="badge bg-default bg-opacity-10 fw-bolder text-success border border-success border-opacity-100 fs-6 px-4 py-2 rounded-pill shadow-sm- ">
                    <?php
                        switch($ticket['status']){
                            case "Open": $status_icon = 'bi-envelope-open';  break;
                            case "Assigned": $status_icon = 'bi-person-check-fill';  break;
                            case "In Progress": $status_icon = 'bi-gear-wide-connected';  break;
                            case "Resolved": $status_icon = 'bi-check-circle-fill';  break;
                            case "Closed": $status_icon = 'bi-lock-fill';  break;
                        }
                    ?>
                     <i class="bi <?= $status_icon; ?>"></i> <?= strtoupper($ticket['status']) ?>
                </span>

                <div class="text-secondary px-3 text-center">Current Status</div>
            </div>
        </div>

        <!-- <hr class="my-0 py-0 mb-3"> -->
        <div style="border:1px solid #04491977" class="mb-4" ></div>

        
        <!-- Alerts -->
        <?php include('../partials/alerts.php'); ?>

        <div class="row g-4">
            
            <!-- Left Column: Info & Tracking -->
            <div class="col-xl-8">
                
                <!-- Ticket Description Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                        <div>
                            <h3 class="fw-bolder text-dark fs-5 mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-card-text text-success"></i> Issue Details
                            </h3>
                        </div>

                        <div class="text-muted text-end">
                            <span>Submitted on </span>
                            <span class="fw-bold"><?= date("M d, Y - h:i A", strtotime($ticket['created_at'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;"><i class="bi bi-person-fill text-muted me-1"></i>Requester</div>
                            <div class="text-dark fw-semi-bold d-flex align-items-center">                                
                                <?php echo $ticket['requester_first_name'] . " ". $ticket['requester_last_name']; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;"><i class="bi bi-person-fill-check text-muted me-1"></i> Agent</div>
                            <?php if($ticket['agent_id'] != null): ?>
                            <div class="text-dark fw-semi-bold"> <?= $ticket['agent_first_name']." ".$ticket['agent_last_name'] ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic fw-medium">Unassigned</span>
                            <?php endif ?>
                        </div>

                        <div class="col-md-6 mt-md-4">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;"><i class="bi bi-grid-fill text-muted me-1"></i> Category</div>
                            <div class="text-dark fw-semi-bold"><?= $ticket['category_name'] ?></div>
                        </div>

                        <div class="col-md-6 mt-md-4">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;"><i class="bi bi-flag-fill text-muted me-1"></i> Priority</div>
                              <?php                            
                                switch (strtolower($ticket['priority'])) {
                                    case 'low': $priority_badge = 'success'; break;
                                    case 'medium': $priority_badge = 'warning'; break;
                                    case 'high': $priority_badge = 'danger'; break;
                                    default: $priority_badge = 'secondary'; break;
                                }
                            ?>
                            <span class="badge priority-badge badge-<?php echo $priority_badge; ?> py-2 px-3"><?= ucfirst($ticket['priority']) ?></span>
                        </div>
                    
                    </div>
                    
                    <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Description</div>
                    <div class="p-3 rounded-3 bg-light border">
                        <p class="mb-0 text-dark fw-medium" style="line-height: 1.6;">
                            <?= $ticket['description'] ?>
                        </p>
                    </div>
                </div>

                <!-- Activity & Comments Tracking -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
                    <div class="d-flex align-items-center border-bottom pb-3 mb-4">
                        <h3 class="fw-bolder text-dark fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-clock-history text-success"></i> Activity & Comments</h3>
                    </div>
                    
                    <div class="border-start ms-2 ps-4 position-relative border-2 mt-4">
                        
                        <!-- System Note -->
                        <div class="mb-4 position-relative">
                            <div class="position-absolute rounded-circle bg-secondary border border-3 border-white shadow-sm" style="width: 14px; height: 14px; left: -1.5rem; top: 0.25rem; margin-left: -6px;"></div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-dark">System Note</span>
                                <span class="text-muted" style="font-size: 0.8rem;">&bull; Sep 22, 09:15 AM</span>
                            </div>
                            <div class="text-muted fst-italic" style="font-size: 0.95rem;">
                                Ticket assigned to <span class="text-dark fw-bold fst-normal">Kasun (Agent)</span> and status changed to <span class="text-primary fw-bold fst-normal">Assigned</span>.
                            </div>
                        </div>

                        <!-- Agent Comment -->
                        <div class="mb-4 position-relative">
                            <div class="position-absolute rounded-circle bg-success border border-3 border-white shadow-sm" style="width: 14px; height: 14px; left: -1.5rem; top: 0.25rem; margin-left: -6px;"></div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-bold text-success">Agent Kasun</span>
                                <span class="text-muted" style="font-size: 0.8rem;">&bull; Sep 22, 09:30 AM</span>
                            </div>
                            <div class="bg-light border p-3 rounded-3 text-dark fw-medium" style="font-size: 0.95rem;">
                                I am heading to the second floor now to check the main switch and the wireless access points[cite: 4].
                            </div>
                        </div>

                        <!-- System Note -->
                        <div class="mb-0 position-relative">
                            <div class="position-absolute rounded-circle bg-secondary border border-3 border-white shadow-sm" style="width: 14px; height: 14px; left: -1.5rem; top: 0.25rem; margin-left: -6px;"></div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-dark">System Note</span>
                                <span class="text-muted" style="font-size: 0.8rem;">&bull; Sep 22, 09:31 AM</span>
                            </div>
                            <div class="text-muted fst-italic" style="font-size: 0.95rem;">
                                Status changed from Assigned to <span class="text-success fw-bold fst-normal">In Progress</span>.
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Add Comment Form -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold mb-2">Add a Comment / Update</label>
                            <textarea class="form-control bg-light border-0 shadow-none p-3" rows="3" placeholder="Type your update here to notify the requester..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-success fw-bold px-4 shadow-sm"><i class="bi bi-send me-2"></i> Post Comment</button>
                        </div>
                    </form>
                </div>

            </div>



            <!-- Right Column: Update Controls -->
            <div class="col-xl-4">
                <?php if($ticket['status'] != "Closed"): ?>

                <!-- Assign Agent -->
                <?php if ($_SESSION['role'] == 'Administrator' && $ticket['status'] == "Open") : ?>
                                    
                    <div class="card border-success border-opacity-50 bg-success- bg-opacity-10 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex align-items-center border-bottom border-success border-opacity-25 pb-3 mb-3">
                            <h3 class="fw-bolder text-success fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-sliders"></i> Assign an Agent</h3>
                        </div>
                        
                        <form class="mt-0 needs-validation" novalidate action="../../controllers/ticket_controller.php" method="POST">                                  
                            <input type="hidden" name="ticket_id" value="<?=  $ticket['id'] ?>">
                            <input type="hidden" name="current_status" value="<?=  $ticket['status'] ?>">
                            <div class="mb-2">
                                <label class="form-label text-dark fw-bold">Agent</label>
                                <select class="form-select shadow-sm py-2" name="agent_id" required>
                                    <option value="">Unassigned</option>
                                    <?php foreach($agents as $agent):?>
                                    <option value="<?= $agent['id'] ?>"><?= $agent['first_name'] ." ". $agent['last_name'] ?></option>
                                    <?php endforeach ?>
                                </select>                                

                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please select an agent</div>
                            </div>

                            <textarea class="form-control mb-2" name="notes" rows="2" placeholder="Additional Notes (optional)"></textarea>
                                                    
                            <button type="submit" name="assign_an_agent_submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-save me-2"></i> Assign an Agent
                            </button>
                        </form>
                    </div>

                <?php endif ?>


                <!-- Mark as In Progress -->
                <?php if ($_SESSION['role'] == 'Agent' && $ticket['status'] == "Assigned") : ?>
                <div class="card border-success border-opacity-50 bg-success- bg-opacity-10 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center border-bottom border-success border-opacity-25 pb-3 mb-3">
                        <h3 class="fw-bolder text-success fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-sliders"></i> Mark as In Progress</h3>
                    </div>
                    
                    <form class="mt-2 needs-validation" novalidate action="../../controllers/ticket_controller.php" method="POST">                                   
                        <input type="hidden" name="ticket_id" value="<?=  $ticket['id'] ?>">
                        <input type="hidden" name="current_status" value="<?=  $ticket['status'] ?>">
                        <textarea class="form-control mb-2" name="notes" rows="2" placeholder="Additional Notes (optional)"></textarea>
                        <button type="submit" name="mark_as_in_progress_submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                            <i class="bi bi-gear me-2"></i> Mark as In Progress
                        </button>
                    </form>
                </div>
                <?php endif ?>


                <!-- Mark as Resolved -->
                <?php if (($_SESSION['role'] == 'Agent' || $_SESSION['role'] == 'Administrator') && $ticket['status'] != "Resolved") : ?>
                    <div class="card border-success border-opacity-50 bg-success- bg-opacity-10 shadow-sm rounded-4 p-4 mb-4" >
                        <div class="d-flex align-items-center border-bottom border-success border-opacity-25 pb-3 mb-3">
                            <h3 class="fw-bolder text-success fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-sliders"></i> Mark as Resolved</h3>
                        </div>
                        
                        <form class="mt-2 needs-validation" novalidate action="../../controllers/ticket_controller.php" method="POST">                                  
                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                            <input type="hidden" name="current_status" value="<?=  $ticket['status'] ?>">
                            <textarea class="form-control mb-2" name="notes" rows="2" placeholder="Additional Notes (optional)"></textarea>
                            <button type="submit" name="mark_as_resolved_submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i> Mark as Resolved
                            </button>
                        </form>
                    </div>
                <?php endif ?>
            
                <!-- Close Ticket -->
                <?php if ($_SESSION['role'] == 'Requester' || $_SESSION['role'] == 'Administrator') : ?>
                    <div class="card border-danger border-opacity-50 bg-danger- bg-opacity-10 shadow-sm rounded-4 p-4 mb-4" >
                        <div class="d-flex align-items-center border-bottom border-danger border-opacity-25 pb-3 mb-3">
                            <h3 class="fw-bolder text-default fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-sliders"></i> Close the Ticket</h3>
                        </div>
                        
                        <form class="mt-2 needs-validation" novalidate action="../../controllers/ticket_controller.php" method="POST">                             
                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                            <input type="hidden" name="current_status" value="<?=  $ticket['status'] ?>">
                            <button type="submit" name="close_ticket_submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-x-circle me-2"></i> Close the Ticket
                            </button>
                        </form>
                    </div>
                <?php endif ?>
                
            <?php else :?>
                <div class="card border-secondary border-opacity-50 bg-secondary bg-opacity-10 shadow-sm rounded-4 py-4 pb-2 mb-4" >
                    <p class="fw-bold text-center">Ticket Closed!</p>
                </div>

            <?php endif ?>

            </div>


        </div>
    </main>
    <!-- END MAIN CONTENT -->

<script src="../../assets/js/timeline.js"></script>

<script>
    // Set Breadcrumb
    document.getElementById("breadcrumb_title").innerHTML = "View Ticket";
    document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
    document.getElementById("breadcrumb_item_2").innerHTML = "View Ticket";

    //Update Timeline
    updateTimeline(<?= $current_status_index  ?>); 

</script>



   
<?php include('../partials/footer.php'); ?> 
