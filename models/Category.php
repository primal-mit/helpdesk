<?php
class Category {    

    private $conn;

    public function __construct() {     
        $database = new Database();
        $this->conn = $database->connect();        
    }    
    
    public function getCategories() {
        $query = "SELECT * FROM categories";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    //Get Category by ID
    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Create Category
    public function create($name, $description) {
        // Check if category already exists
        $query = "SELECT * FROM categories WHERE name = :name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Category already exists. Please choose a different name.";
            return false;
        }

        // Insert new category into the database
        $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred. Please try again.";
            return false;
        }
    }

   
    public function update($id, $name, $description) {
        // Check if category name already exists for another category
        $query = "SELECT * FROM categories WHERE name = :name AND id != :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Category name already exists. Please choose a different name.";
            return false;
        }

        // Update category details in the database
        $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while updating the category. Please try again.";
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $_SESSION['error'] = "An error occurred while deleting the category. Please try again.";
            return false;
        }
    }   
}
?>