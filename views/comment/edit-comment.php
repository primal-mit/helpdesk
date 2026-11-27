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
require_once '../../models/Comment.php';

$comment_id = $_GET['id'] ?? null;
if (!$comment_id) {
    echo "Comment ID is missing.";
    exit();
}

// Fetch comment details from the database
$comment = new Comment();
$comment = $comment->getCommentById($comment_id);
if (!$comment) {
    echo "Comment not found.";
    exit();
}

?>

<?php include_once('../partials/header.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="p-3 p-md-4 mx-auto flex-grow-1" >

        <!-- Card Panel -->
        <div class="card card-panel"  style="max-width: 964px;">
            <div class="mb-3 text-center">
                <h2 class="card-panel-title"><i class="bi bi-pencil fs-3 text-warning"></i> Edit Comment</h2>
                <p class="card-panel-subtitle">Please update the comment below.</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="POST" action="../../controllers/comment_controller.php">  
                <input type="hidden" class="form-control" name="comment_id" required value="<?php echo $comment['id']; ?>">                     
                <input type="hidden" class="form-control" name="ticket_id" required value="<?php echo $comment['ticket_id']; ?>">    
       
                <div class="row mb-4">     
                    <div class="col-md-12">
                        <label class="form-label">
                            Comment<span class="text-danger fw-bolder ms-1">*</span>
                        </label>

                        <textarea class="form-control" name="comment_text" required><?php echo $comment['comment_text']; ?></textarea>
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter the comment.</div>
                    </div>
                </div>

                <!-- Alerts -->
                <?php include('../partials/alerts.php'); ?>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-light bottom-btn me-3" onclick="goBack()">              
                        <i class="bi bi-caret-left-fill me-1"></i> Go Back
                    </button>
                    <button type="submit" class="btn btn-success bottom-btn me-3" name="update_comment_submit">
                        <i class="bi bi-check-circle"></i> Update Comment
                    </button>
                    <button type="reset" class="btn btn-light bottom-btn">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>                            
                </div>
                        
            </form>        
            
        </div>

    </main>
    <!-- END MAIN CONTENT -->

    <script>
        // Set Breadcrumb
        document.getElementById("breadcrumb_title").innerHTML = "Edit Comment";
        document.getElementById("breadcrumb_item_1").querySelector('a').innerHTML = "Comment";
        document.getElementById("breadcrumb_item_2").innerHTML = "Edit Comment";

        //Activate Sidebar Link
        document.getElementById("sb_manage_categories").classList.add("sidebar-active");
    </script>

<?php include('../partials/footer.php'); ?>