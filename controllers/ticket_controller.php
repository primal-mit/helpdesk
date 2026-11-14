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


//Create Ticket
if (isset($_POST['create_ticket_submit'])) {
 

}


?>