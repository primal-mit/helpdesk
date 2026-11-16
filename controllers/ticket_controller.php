<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Ticket.php';

$ticket = new Ticket();


//Create Ticket
if (isset($_POST['create_ticket_submit'])) {
    $requester_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $subject = htmlspecialchars(strip_tags($_POST['subject']));
    $description = htmlspecialchars(strip_tags($_POST['description']));
    $priority_id = $_POST['priority_id'];

    $new_ticket_id = $ticket->createTicket($requester_id, $category_id, $subject, $description, $priority_id);

    
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

    if($agent_id == ""){
        $_SESSION['error'] = "Please select an agent.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }

    $result = $ticket->assignAnAgent($ticket_id, $agent_id);

    
    if ($result) {
        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}



if (isset($_POST['mark_as_inprogress_submit'])) {
    $ticket_id = $_POST['ticket_id'];

    $result = $ticket->markAsInProgress($ticket_id);

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

    $result = $ticket->markAsInProgress($ticket_id);

    if ($result) {
        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}

if (isset($_POST['mark_as_resolved_submit'])) {
    $ticket_id = $_POST['ticket_id'];

    $result = $ticket->markAsResolived($ticket_id);

    if ($result) {
        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}

if (isset($_POST['close_ticket_submit'])) {
    $ticket_id = $_POST['ticket_id'];

    $result = $ticket->closeTicket($ticket_id);

    if ($result) {
        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    } else {
        header("Location: ../views/ticket/view-ticket.php?id=". $ticket_id);
        exit();
    }
}




?>