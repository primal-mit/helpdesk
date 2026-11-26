<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Ticket.php';
require_once '../models/StatusHistory.php';

$ticket = new Ticket();


//Create Ticket
if (isset($_POST['create_ticket_submit'])) {
    $requester_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $subject = htmlspecialchars(strip_tags($_POST['subject']));
    $description = htmlspecialchars(strip_tags($_POST['description']));
    $priority_id = $_POST['priority_id'];

    //Attachment
    $attachment = "";

        if (isset($_FILES['attachment'])) 
        {
            $file = $_FILES['attachment'];

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

            $extension = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            if (!in_array($extension, $allowedExtensions, true)) {
                $_SESSION['error'] = "Invalid file format.";
                header("Location: ../views/ticket/create-ticket.php");  exit();
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = "Maximum file size is 5 MB.";
                header("Location: ../views/ticket/create-ticket.php");  exit();
            }

            $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $uploadPath = __DIR__ . '../../uploads/tickets/';

            if (move_uploaded_file(
                $file['tmp_name'],
                $uploadPath . $newFileName
            )) {
                $attachment =  $newFileName;                
            } else {
                $_SESSION['error'] = "File upload failed.";
                header("Location: ../views/ticket/create-ticket.php");  exit();
            }
        }

    $new_ticket_id = $ticket->createTicket($requester_id, $category_id, $subject, $description, $priority_id, $attachment);

    
    if ($new_ticket_id) {
        $_SESSION['success'] = "Ticket created successfully.";
        header("Location: ../views/ticket/my-tickets.php");
        exit();
    } else {
        header("Location: ../views/ticket/create-ticket.php");
        exit();
    }
}


// Assign an Agent
if (isset($_POST['assign_an_agent_submit'])) {
    $ticket_id = $_POST['ticket_id'];
    $agent_id = $_POST['agent_id'];
    $changed_by_user_id = $_SESSION['user_id'];
    $current_status = $_POST['current_status'];
    $notes = $_POST['notes'];

    if($agent_id == ""){
        $_SESSION['error'] = "Please select an agent.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }

    $result = $ticket->assignAnAgent($ticket_id, $agent_id, $changed_by_user_id, $current_status,$notes);

    
    if ($result) {
        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}


if (isset($_POST['mark_as_in_progress_submit'])) {
    $ticket_id = $_POST['ticket_id'];
    $changed_by_user_id = $_SESSION['user_id'];
    $current_status = $_POST['current_status'];
    $notes = $_POST['notes'];

    // $result = $ticket->markAsInProgress($ticket_id, $changed_by_user_id, $current_status);
    $result = $ticket->updateStatus($ticket_id, $changed_by_user_id, $current_status,"In Progress",$notes);

    if ($result) {
        $_SESSION['success'] = "Ticket marked as In Progress.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}

if (isset($_POST['mark_as_resolved_submit'])) {
    $ticket_id = $_POST['ticket_id'];
    $changed_by_user_id = $_SESSION['user_id'];
    $current_status = $_POST['current_status'];
    $notes = $_POST['notes'];

    // $result = $ticket->markAsResolived($ticket_id, $changed_by_user_id, $current_status);
    $result = $ticket->updateStatus($ticket_id, $changed_by_user_id, $current_status,"Resolved", $notes);

    if ($result) {
        $_SESSION['success'] = "Ticket marked as Resolved.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}

if (isset($_POST['close_ticket_submit'])) {
    $ticket_id = $_POST['ticket_id'];
    $changed_by_user_id = $_SESSION['user_id'];
    $current_status = $_POST['current_status'];
    $notes = $_POST['notes'];

    //$result = $ticket->closeTicket($ticket_id, $changed_by_user_id, $current_status);
    $result = $ticket->updateStatus($ticket_id, $changed_by_user_id, $current_status,"Closed", $notes);

    if ($result) {
        $_SESSION['success'] = "Ticket closed successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}


//Add Attachment
if (isset($_POST['add_attachment_submit'])) {

    if(!isset($_POST['ticket_id'])){
        $_SESSION['error'] = "No ticket id found.";
        header("Location: ../views/ticket/view-tickets.php");
        exit();
    }

    $ticket_id = $_POST['ticket_id'];  
    
    if (!isset($_FILES['attachment'])) 
    {
        $_SESSION['error'] = "No attachment found.";
        header("Location: ../views/ticket/add-attachment.php?ticket_id=".$ticket_id);  exit();
    }

    $attachment = "";
    
    //Physicaly Upload Attachment   
    $file = $_FILES['attachment'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowedExtensions, true)) {
        $_SESSION['error'] = "Invalid file format.";
        header("Location: ../views/ticket/add-attachment.php?ticket_id=".$ticket_id);  exit();
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['error'] = "Maximum file size is 5 MB.";
        header("Location: ../views/ticket/add-attachment.php?ticket_id=".$ticket_id);  exit();
    }

    $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;

    $uploadPath = __DIR__ . '../../uploads/tickets/';

    if (move_uploaded_file(
        $file['tmp_name'],
        $uploadPath . $newFileName
    )) {
        $attachment =  $newFileName;                
    } else {
        $_SESSION['error'] = "File upload failed.";
        header("Location: ../views/ticket/add-attachment.php?ticket_id=".$ticket_id);  exit();
    }

            
    //Update to the DB
    $result = $ticket->addAttachment($ticket_id, $attachment);

    if ($result) {
        $_SESSION['success'] = "Attachment upload successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/add-attachment.php?ticket_id=".$ticket_id);  exit();
        exit();
    }
    

}


//Remove Attachment
if (isset($_POST['remove_attachment_submit'])) {

    if(!isset($_POST['ticket_id'])){
        $_SESSION['error'] = "No ticket id found.";
        header("Location: ../views/ticket/view-tickets.php");
        exit();
    }

    $ticket_id = $_POST['ticket_id'];    
    $attachment = $_POST['attachment'];

    // Delete physical file
    $filePath = __DIR__ . '../../uploads/tickets/' . $attachment;

    if (is_file($filePath)) {
        unlink($filePath);
    }

    //Delete from DB
    $result = $ticket->removeAttachment($ticket_id);

    if ($result) {
        $_SESSION['success'] = "Attachment removed successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}





?>