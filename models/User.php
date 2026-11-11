<?php
class User {    

    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }    
    
    public function getUsers() {
        $query = "SELECT * FROM users";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    //Get User by ID
    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Create User
    public function create($first_name, $last_name, $username, $role, $status, $phone_number, $email) {
        // Check if username already exists
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists. Please choose a different username.";
            return false;
        }

        // Hash the password (username is the default password for new users)
        $hashed_password = password_hash($username, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO users (first_name, last_name, username, role, status,phone_number, email, password) 
                  VALUES (:first_name, :last_name, :username, :role, :status, :phone_number, :email, :password)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred. Please try again.";
            return false;
        }
    }

    public function update($id, $first_name, $last_name, $username, $role, $status, $phone_number, $email) {
        // Check if username already exists for another user
        $query = "SELECT * FROM users WHERE username = :username AND id != :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists. Please choose a different username.";
            return false;
        }

        // Update user details in the database
        $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, role = :role, status = :status, phone_number = :phone_number, email = :email WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while updating the user. Please try again.";
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while deleting the user. Please try again.";
            return false;
        }
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

    public function checkEmailExists($email) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }else {
            return false;
        }
    }   
    
    function updatePassword($email, $newPassword) {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while updating the password. Please try again.";
            return false;
        }
    }

    function changePassword($user_id, $current_password, $new_password) {
        // Fetch the current password hash from the database
        $query = "SELECT password FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION['error'] = "User not found.";
            return false;
        }

        // Verify the current password
        if (!password_verify($current_password, $row['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            return false;
        }

        // Hash the new password
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_query = "UPDATE users SET password = :password WHERE id = :id";
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':id', $user_id);

        if ($update_stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while changing the password. Please try again.";
            return false;
        }
    }

}
?>