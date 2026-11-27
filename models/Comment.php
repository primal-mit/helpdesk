<?php
class Comment {    

    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }   
    
    public function addComment($ticket_id, $user_id, $comment_text) {
        $query = "INSERT INTO comments (ticket_id, user_id, comment_text) VALUES (:ticket_id, :user_id, :comment_text)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':comment_text', $comment_text);
        
        return $stmt->execute();
    }

    public function getCommentsByTicket($ticket_id) {
        $query = "SELECT comment.*, user.first_name, user.last_name, user.role, user.id as user_id 
                  FROM comments comment
                  LEFT JOIN users user ON comment.user_id = user.id
                  WHERE comment.ticket_id = :ticket_id
                  ORDER BY comment.id ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function deleteComment($id) {
        $query = "DELETE FROM comments WHERE id = :id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
         if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while deleting the comment. Please try again.";
            return false;
        }
    }
}
?>