<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}




require_once '../../models/Database.php';
require_once '../../models/Ticket.php';

$ticket_id = $_GET['ticket_id'] ?? null;
if (!$ticket_id) {
    echo "Ticket ID is missing.";
    exit();
}

// Fetch ticket details from the database
$ticket = new Ticket();
$ticket = $ticket->getTicket($ticket_id);
if (!$ticket) {
    echo "Ticket not found.";
    exit();
}

if ($_SESSION['user_id'] !== $ticket['requester_id']) {
    echo "Access Denied. You do not have permission to add attachments to this ticket.";
    exit();
}


?>

<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 964px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-upload fs-3 text-success"></i> Add Attachment</h2>
                <p class="card-panel-subtitle">Please confirm the deletion of the user below.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/ticket_controller.php" enctype="multipart/form-data">  
                <input type="hidden" class="form-control" name="ticket_id" required value="<?php echo $ticket_id; ?>">     
                
                <div>
                <label class="form-label">
                    <i class="bi bi-paperclip text-success me-2"></i> Attachment
                </label>
                <input type="file" class="form-control" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
                <small class="text-muted">Allowed formats: JPG, PNG, PDF, DOC, DOCX. Maximum size: 5 MB. </small>
                    <div class="valid-feedback">Attachment looks good!</div>
                    <div class="invalid-feedback">Please chose a file to upload.</div>
                </div>
                      
                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="add_attachment_submit">
                        <i class="bi bi-upload"></i> Upload
                    </button>                          
                </div>
                        
            </form>        
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Add Attachment";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
        document.getElementById("breadcrumb_item_2").innerHTML = "Add Attachment";

        //Activate Sidebar Link
        document.getElementById("sb_manage_users").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>