<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Comment.php';

// Create Comment
if (isset($_POST['post_comment_submit'])) {

    $ticket_id = trim($_POST['ticket_id']);
    $user_id = $_SESSION['user_id'];
    $comment_text = trim($_POST['comment_text']);

    //Serverside validation
    if (empty($comment_text)){
        $_SESSION['error'] = "Comment cannot be empty.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    }

    $comment = new comment();
    $result = $comment->addComment($ticket_id, $user_id, $comment_text);

    if ($result) {
        $_SESSION['success'] = "Comment created successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while creating the comment. Please try again.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    }
}



// Create Category
if (isset($_POST['delete_comment_submit'])) {

    $ticket_id = trim($_POST['ticket_id']);
    $comment_id = trim($_POST['comment_id']);
    
    $comment = new comment();
    $result = $comment->deleteComment($comment_id);

    if ($result) {
        $_SESSION['success'] = "Comment deleted successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while deleting the comment. Please try again.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    }
}


if (isset($_POST['update_comment_submit'])) {

    $comment_id = trim($_POST['comment_id']);
    $comment_text = trim($_POST['comment_text']);
    $ticket_id = trim($_POST['ticket_id']);
    
    $comment = new comment();
    $result = $comment->update($comment_id, $comment_text);

    if ($result) {
        $_SESSION['success'] = "Comment updated successfully.";
        header("Location: ../views/ticket/view-ticket.php?id=".$ticket_id);
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while deleting the comment. Please try again.";
        header("Location: ../views/comment/edit-comment.php?id=".$comment_id);
        exit();
    }
}

?>