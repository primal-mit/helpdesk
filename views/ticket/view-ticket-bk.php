<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

require_once '../../models/Database.php';
require_once '../../models/Ticket.php'; 
$ticket = new Ticket();
$ticket_id = $_GET["id"];
$ticket = $ticket->getTicket($ticket_id);  

$current_status_index = 0;
switch($ticket['status']){
    case "Open": $current_status_index = 0; break;
    case "Assigned": $current_status_index = 1; break;
    case "In Progress": $current_status_index = 2; break;
    case "Resolved": $current_status_index = 3; break;
    case "Closed": $current_status_index = 4; break;
}

//Denie access to others tickets (Requester)
if ($_SESSION['role'] == 'Requester') {
    echo "Access Denied. You do not have permission to access this page.";
    exit();
}
?>


<?php include_once('../partials/header.php'); ?>


<style>
    .timeline-container {
            overflow-x: auto;
            padding-bottom: 10px;
        }
        .timeline-wrapper {
            position: relative;
            padding: 20px 0;
            min-width: 500px; /* Prevents squishing on very small screens */
        }
        
        /* The Connecting Line Behind Nodes */
        .timeline-progress-bg {
            position: absolute;
            top: 38px; /* Perfectly centers the 4px line behind the 40px circles */
            left: 10%; /* Inset to start from the first circle center */
            width: 80%; /* Spans only between the first and last circle */
            height: 4px;
            z-index: 1;
            background-color: #b4b4b4;
            border-radius: 2px;
        }
        
        #progress-bar {
            transition: width 0.5s ease-in-out;
        }

        /* Individual Step Nodes */
        .timeline-step {
            position: relative;
            z-index: 2; /* Keeps circles above the line */
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1; /* Distributes nodes evenly */
        }
        
        /* The Circular Indicator */
        .timeline-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 4px solid #fff; /* Creates a clean cut-out effect over the line */
            transition: all 0.4s ease-in-out;
            font-size: 1.1rem;
        }

        .step-text {
            transition: color 0.4s ease;
            margin-top: 8px;
        }

        /* Custom Orange for Active State */
        .bg-orange { background-color: #fd9914 !important; }
        .text-orange { color: #fd9914 !important; }

        /* Simulation Buttons Styles */
        .simulation-controls button {
            transition: transform 0.1s ease;
        }
        .simulation-controls button:active {
            transform: scale(0.95);
        }

        .timeline-step .bg-secondary{
            background-color: #888 !important;
        }
</style>

    <!-- MAIN CONTENT: TICKET DETAILS PAGE -->
    <main class="p-4 p-md-4 flex-grow-1">
        
        <!-- Ticket Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
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
            <div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fs-6 px-3 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-gear-wide-connected me-1"></i> <?= strtoupper($ticket['status'])     ?>
                </span>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left Column: Info & Tracking -->
            <div class="col-xl-8">
                
                <!-- Ticket Description Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
                    <div class="d-flex align-items-center border-bottom pb-3 mb-4">
                        <h3 class="fw-bolder text-dark fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-card-text text-success"></i> Issue Details</h3>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Requester</div>
                            <div class="text-dark fw-bold d-flex align-items-center">                                
                                <?php echo $ticket['requester_first_name'] . " ". $ticket['requester_last_name']; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Category</div>
                            <div class="text-dark fw-bold"><i class="bi bi-hdd-network text-muted me-1"></i> <?= $ticket['category_name'] ?></div>
                        </div>
                        <div class="col-md-6 mt-md-3">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Priority</div>
                              <?php                            
                                switch (strtolower($ticket['priority'])) {
                                    case 'low': $priority_badge = 'secondary'; break;
                                    case 'medium': $priority_badge = 'warning'; break;
                                    case 'high': $priority_badge = 'danger'; break;
                                    default: $priority_badge = 'secondary'; break;
                                }
                            ?>
                            <span class="badge priority-badge badge-<?php echo $priority_badge; ?> py-2 px-3"><?= ucfirst($ticket['priority']) ?></span>
                            <!-- <div class="text-dark fw-bold"><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i> High</span></div> -->
                        </div>
                        <div class="col-md-6 mt-md-3">
                            <div class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Submitted On</div>
                            <div class="text-muted fw-bold"><?= date("M d, Y - h:i A", strtotime($ticket['created_at'])) ?></div>
                            <!-- <div class="text-muted fw-bold">Sep 22, 2026 - 09:00 AM</div> -->
                        </div>
                    </div>
                    
                    <div class="p-3 rounded-3 bg-light border">
                        <p class="mb-0 text-dark fw-medium" style="line-height: 1.6; background-color: #ebf9f1;">
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
                <div class="card border-success border-opacity-50 bg-success bg-opacity-10 shadow-sm rounded-4 p-4 position-sticky" style="top: 100px;">
                    <div class="d-flex align-items-center border-bottom border-success border-opacity-25 pb-3 mb-3">
                        <h3 class="fw-bolder text-success fs-5 mb-0 d-flex align-items-center gap-2"><i class="bi bi-sliders"></i> Update Ticket</h3>
                    </div>
                    
                    <form class="mt-2">
                        <!-- Status Update -->
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold">Current Status</label>
                            <select class="form-select border-success border-opacity-50 shadow-sm py-2">
                                <option value="Open" <?php if($ticket['status'] == "Open"){echo 'select';} ?>>Open</option>
                                <option value="Assigned" <?php if($ticket['status'] == "Assigned"){echo 'select';} ?>>Assigned</option>
                                <option value="In Progress" <?php if($ticket['status'] == "In Progress"){echo 'select';} ?>>In Progress</option>
                                <option value="Resolved" <?php if($ticket['status'] == "Resolved"){echo 'select';} ?>>Resolved</option>
                                <option value="Closed" <?php if($ticket['status'] == "Closed"){echo 'select';} ?>>Closed</option>
                            </select>
                        </div>
                        
                        <!-- Priority Update -->
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold">Priority Level</label>
                            <select class="form-select border-0 shadow-sm py-2">
                                <option>Low</option>
                                <option>Medium</option>
                                <option selected>High</option>
                                <option>Critical</option>
                            </select>
                        </div>

                        <!-- Agent Assignment -->
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold">Assigned Agent</label>
                            <select class="form-select border-0 shadow-sm py-2">
                                <option>Unassigned</option>
                                <option selected>Kasun (Agent)</option>
                                <option>Michael (Agent)</option>
                                <option>Amanda (Admin)</option>
                            </select>
                        </div>
                        
                        <hr class="border-success border-opacity-25 my-4">
                        
                        <button type="button" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                            <i class="bi bi-save me-2"></i> Save Changes
                        </button>
                    </form>
                </div>
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
