<?php
class StatusHistory {    

    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }    

    // Records an immutable audit log entry when a ticket transitions states
    public function recordTransition($ticket_id, $changed_by_user_id, $old_status, $new_status) {
        $query = "INSERT INTO status_history (ticket_id, changed_by_user_id, old_status, new_status) 
                  VALUES (:ticket_id, :changed_by, :old_status, :new_status)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->bindParam(':changed_by', $changed_by_user_id);
        $stmt->bindParam(':old_status', $old_status);
        $stmt->bindParam(':new_status', $new_status);
        
        return $stmt->execute();
    }

    // Fetches the timeline of status changes for audit views
    public function getHistoryByTicket($ticket_id) {
        $query = "SELECT status_history.*, u.first_name, u.last_name 
                  FROM status_history
                  LEFT JOIN users u ON status_history.changed_by_user_id = u.id
                  WHERE status_history.ticket_id = :ticket_id
                  ORDER BY status_history.changed_at DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

}