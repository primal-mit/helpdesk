<?php
class Ticket {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();    
    }

    public function createTicket($requester_id, $category_id, $subject, $description, $priority) {
        $query = "INSERT INTO tickets (requester_id, category_id, subject, description, priority, status) 
                  VALUES (:requester_id, :category_id, :subject, :description, :priority, 'Open')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':requester_id', $requester_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':priority', $priority);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId(); // Returns ID for StatusHistory creation
        }
        return false;
    }


    // Retrieves all tickets for the Admin/Agent dashboard
    public function getTickets() {
        $query = "SELECT 
            t.*,
            c.name AS category_name,
            requester.first_name AS requester_first_name,
            requester.last_name AS requester_last_name,
            agent.first_name AS agent_first_name,
            agent.last_name AS agent_last_name
          FROM tickets t
          LEFT JOIN categories c ON t.category_id = c.id
          LEFT JOIN users requester ON t.requester_id = requester.id
          LEFT JOIN users agent ON t.agent_id = agent.id
          ORDER BY t.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }


     public function getRecentTickets() {
        $query = "SELECT 
            t.*,
            c.name AS category_name,
            requester.first_name AS requester_first_name,
            requester.last_name AS requester_last_name,
            agent.first_name AS agent_first_name,
            agent.last_name AS agent_last_name
          FROM tickets t
          LEFT JOIN categories c ON t.category_id = c.id
          LEFT JOIN users requester ON t.requester_id = requester.id
          LEFT JOIN users agent ON t.agent_id = agent.id
          ORDER BY t.created_at DESC
          LIMIT 5";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMyTickets() {
        $query = "SELECT 
            t.*,
            c.name AS category_name,
            requester.first_name AS requester_first_name,
            requester.last_name AS requester_last_name,
            agent.first_name AS agent_first_name,
            agent.last_name AS agent_last_name
          FROM tickets t
          LEFT JOIN categories c ON t.category_id = c.id
          LEFT JOIN users requester ON t.requester_id = requester.id
          LEFT JOIN users agent ON t.agent_id = agent.id
          WHERE requester.id = ".$_SESSION['user_id']."
          ORDER BY t.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

     public function getMyAssignedTickets() {
        $query = "SELECT 
            t.*,
            c.name AS category_name,
            requester.first_name AS requester_first_name,
            requester.last_name AS requester_last_name,
            agent.first_name AS agent_first_name,
            agent.last_name AS agent_last_name
          FROM tickets t
          LEFT JOIN categories c ON t.category_id = c.id
          LEFT JOIN users requester ON t.requester_id = requester.id
          LEFT JOIN users agent ON t.agent_id = agent.id
          WHERE agent.id = ".$_SESSION['user_id']."
          ORDER BY t.created_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
?>