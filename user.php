<?php
class User {
    private $conn;
    private $table = "users";
    
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (first_name, last_name, email, password) 
                 VALUES (:first_name, :last_name, :email, :password)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind parameters
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Check if email exists
    public function emailExists() {
        $query = "SELECT id, first_name, last_name, password 
                 FROM " . $this->table . " 
                 WHERE email = :email 
                 LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->password = $row['password'];
            return true;
        }
        return false;
    }
    
    // Get user by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }
    
    // Update user
    public function update() {
        $query = "UPDATE " . $this->table . " 
                 SET first_name = :first_name, 
                     last_name = :last_name, 
                     email = :email 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind parameters
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Change password
    public function changePassword($new_password) {
        $query = "UPDATE " . $this->table . " 
                 SET password = :password 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>