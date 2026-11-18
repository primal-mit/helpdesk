<?php

require_once('StatusHistory.php');

class Ticket {
    private $conn;
    private $statusHistory;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();    
        $this->statusHistory = new StatusHistory();
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
    public function getTickets($filter_ticket_id, $filter_status, $filter_priority, $filter_category_id, $filter_requester_id, $filter_agent_id) {
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
          WHERE 1 = 1 ";
                  
        $params = [];

        if ($filter_ticket_id != '') {
            $query .= " AND t.id = :ticket_id";
            $params[':ticket_id'] = $filter_ticket_id;
        }

        if ($filter_status != '') {
            $query .= " AND t.status = :status";
            $params[':status'] = $filter_status;
        }

        if ($filter_priority != '') {
            $query .= " AND t.priority = :priority";
            $params[':priority'] = $filter_priority;
        }

        if ($filter_category_id != '') {
            $query .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filter_category_id;
        }

        if ($filter_requester_id != '') {
            $query .= " AND t.requester_id = :requester_id";
            $params[':requester_id'] = $filter_requester_id;
        }

        if ($filter_agent_id != '') {
            $query .= " AND t.agent_id = :agent_id";
            $params[':agent_id'] = $filter_agent_id;
        }

        $query .= " ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function getTicket($ticket_id) {
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
          WHERE t.id = ".$ticket_id."
          LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
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

     public function getMyTicketsCount() {
        $query = "SELECT * FROM tickets WHERE requester_id = ".$_SESSION['user_id'];
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getCreatedTicketsCount($user_id) {
        $query = "SELECT * FROM tickets WHERE requester_id = ".$user_id;
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getAssignedTicketsCount($user_id) {
        $query = "SELECT * FROM tickets WHERE agent_id = ".$user_id;
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
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


    //Update Status
    public function updateStatus($ticket_id, $changed_by_user_id, $old_status, $new_status, $notes) {
        $query = "UPDATE tickets SET status=:status WHERE id=:id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $ticket_id);
        $stmt->bindParam(':status', $new_status);

        if ($stmt->execute()) {
            $status_transition = $this->statusHistory->recordTransition($ticket_id, $changed_by_user_id, $old_status, $new_status, $notes);

            if($status_transition){
                return true;
            }else{
            $_SESSION['error'] = "Update compelted but an error occurred while recording status history. Please try again.";
                return false;
            }

        } else {
            $_SESSION['error'] = "An error occurred while updating the ticket. Please try again.";
            return false;
        }
    }


    //Assign an Agent
    public function assignAnAgent($ticket_id, $agent_id, $changed_by_user_id, $old_status, $notes) {
        $query = "UPDATE tickets SET status='Assigned', agent_id=:agent_id WHERE id=:id";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':agent_id', $agent_id);
        $stmt->bindParam(':id', $ticket_id);

        if ($stmt->execute()) {
            $status_transition = $this->statusHistory->recordTransition($ticket_id, $changed_by_user_id, $old_status, "Assigned", $notes);

            if($status_transition){
                return true;
            }else{
            $_SESSION['error'] = "Update compelted but an error occurred while recording status history. Please try again.";
            return false;
            }

            return true;
        } else {
            $_SESSION['error'] = "An error occurred while updating the ticket. Please try again.";
            return false;
        }
    }

  

    public function getStatusCount($status){
        $query = "SELECT * FROM tickets WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            $_SESSION['error'] = "An error occurred while processing. Please try again.";
            return false;
        }
    }


}
?>