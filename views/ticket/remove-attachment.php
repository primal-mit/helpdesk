<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}



require_once '../../models/Database.php';
require_once '../../models/Ticket.php';

$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
    echo "Ticket ID is missing.";
    exit();
}

// Fetch user details from the database
$ticket = new Ticket();
$ticket = $ticket->getTicket($ticket_id);
if (!$ticket) {
    echo "Ticket not found.";
    exit();
}

//Denie access to others tickets (Requester)
if ($ticket['requester_id'] != $_SESSION['user_id']) {  
    echo "Access Denied. You do not have permission to access this page.";
    exit();
}

?>

<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 964px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-trash fs-3 text-warning"></i> Remove Attachment</h2>
                <p class="card-panel-subtitle">Please confirm the deletion of the attachment below.</p>
            </div>

            <?php
                $extension = strtolower(pathinfo($ticket['attachment'], PATHINFO_EXTENSION));
                $imageExtensions = ['jpg', 'jpeg', 'png'];
                if (in_array($extension, $imageExtensions, true)):?>

                <div class="text-center mb-4">
                    <img src="../../uploads/tickets/<?= htmlspecialchars($ticket['attachment']) ?>" alt="" height="200px" width="250px">                          
                </div>
            <?php endif; ?>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/ticket_controller.php">  
                <input type="hidden" class="form-control" name="ticket_id" required value="<?php echo $ticket['id']; ?>">                      
                <input type="hidden" class="form-control" name="attachment" required value="<?php echo $ticket['attachment']; ?>">  
                      
                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-danger bottom-btn me-3" name="remove_attachment_submit">
                        <i class="bi bi-trash"></i> Remove Attachment
                    </button>                          
                </div>
                        
            </form>        
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Remove Attachment";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Ticket";
        document.getElementById("breadcrumb_item_2").innerHTML = "Remove Attachment";

        //Activate Sidebar Link
        document.getElementById("sb_manage_users").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>