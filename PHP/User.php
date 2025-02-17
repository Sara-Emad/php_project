<?php

class User {
    private $conn;
    private $table_name = "users";

    public $user_id;
    public $name;
    public $email;
    public $room;
    public $ext;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function search($keyword) {
        try {
            // Validate input
            if (empty($keyword) || strlen($keyword) < 2) {
                throw new Exception('Search keyword must be at least 2 characters long');
            }

            // Log the connection status
            if (!$this->conn) {
                error_log("Database connection is null in search method");
                throw new Exception("Database connection error");
            }
  
            // Prepare the search query with proper error handling
            $query = "SELECT user_id, name, email, room, ext, role 
                     FROM " . $this->table_name . "
                     WHERE name LIKE :keyword 
                     OR email LIKE :keyword 
                     OR room LIKE :keyword
                     ORDER BY name ASC
                     LIMIT 50";
            
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . print_r($this->conn->errorInfo(), true));
                throw new Exception("Database query preparation failed");
            }
            
            // Add wildcards for partial matching
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(":keyword", $searchTerm, PDO::PARAM_STR);
            
            // Execute with error checking
            $success = $stmt->execute();
            if (!$success) {
                error_log("Failed to execute statement: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Database query execution failed");
            }
            
            return $stmt;
            
        } catch(PDOException $e) {
            error_log("PDO Error in search: " . $e->getMessage());
            throw new Exception("Database error occurred");
        } catch(Exception $e) {
            error_log("General Error in search: " . $e->getMessage());
            throw $e;
        }
    }

    // ... rest of the User class methods ...
}