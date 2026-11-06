<?php
class User {
    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }     

    //Login
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check whether username exists
        if (!$row) {
            $_SESSION['error'] = "No user found with the provided username.";
            return false;
        }

        // Verify password
        if (!password_verify($password, $row['password'])) {
            $_SESSION['error'] = "Invalid username or password.";
            return false;
        }

        // Check account status
        if ($row['status'] == "Inactive") {
            $_SESSION['error'] = "Your account is inactive. Please contact the administrator.";
            return false;
        }

        // Remove password before returning session data
        unset($row['password']);

        return $row;
    }

}
?>