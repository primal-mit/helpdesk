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



    //Signup
    public function signup($first_name, $last_name, $username, $role, $phone_number, $email, $password) {
        // Check if username already exists
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists. Please choose a different username.";
            return false;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO users (first_name, last_name, username, role, phone_number, email, password) 
                  VALUES (:first_name, :last_name, :username, :role, :phone_number, :email, :password)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred during signup. Please try again.";
            return false;
        }
    }

    

}
?>