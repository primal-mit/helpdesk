<?php
class StatusHistory {    

    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }    

    // Records an immutable audit log entry when a ticket transitions states
    public function recordTransition($ticket_id, $changed_by_user_id, $old_status, $new_status, $notes) {
        $query = "INSERT INTO status_history (ticket_id, changed_by_user_id, old_status, new_status, notes) 
                  VALUES (:ticket_id, :changed_by, :old_status, :new_status, :notes)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->bindParam(':changed_by', $changed_by_user_id);
        $stmt->bindParam(':old_status', $old_status);
        $stmt->bindParam(':new_status', $new_status);
        $stmt->bindParam(':notes', $notes);
        
        return $stmt->execute();
    }

    // Fetches the timeline of status changes for audit views
    public function getHistoryByTicket($ticket_id) {
        $query = "SELECT history.*, user.first_name, user.last_name,user.role 
                  FROM status_history as history
                  LEFT JOIN users user ON history.changed_by_user_id = user.id
                  WHERE history.ticket_id = :ticket_id
                  ORDER BY history.changed_at ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

}