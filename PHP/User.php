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

    public function emailExists() {
        try {
            // Validate email
            if (empty($this->email)) {
                throw new Exception("Email cannot be empty");
            }

            // Prepare query
            $query = "SELECT user_id, name, email, password, role 
                     FROM " . $this->table_name . " 
                     WHERE email = :email
                     LIMIT 1";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . print_r($this->conn->errorInfo(), true));
                throw new Exception("Database query preparation failed");
            }

            // Sanitize and bind
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(":email", $this->email);

            // Execute query
            $stmt->execute();

            // Check if email exists
            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;

        } catch(PDOException $e) {
            error_log("PDO Error in emailExists: " . $e->getMessage());
            throw new Exception("Database error occurred");
        } catch(Exception $e) {
            error_log("General Error in emailExists: " . $e->getMessage());
            throw $e;
        }
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

    // Add create method since it's being used
    public function create() {
        try {
            // Prepare query
            $query = "INSERT INTO " . $this->table_name . "
                    (name, email, room, ext, password, role)
                    VALUES
                    (:name, :email, :room, :ext, :password, :role)";

            $stmt = $this->conn->prepare($query);

            // Sanitize and hash password
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->room = htmlspecialchars(strip_tags($this->room));
            $this->ext = htmlspecialchars(strip_tags($this->ext));
            $this->role = htmlspecialchars(strip_tags($this->role));
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

            // Bind values
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":room", $this->room);
            $stmt->bindParam(":ext", $this->ext);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":role", $this->role);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            return false;

        } catch(PDOException $e) {
            error_log("PDO Error in create: " . $e->getMessage());
            throw new Exception("Database error occurred");
        }
    }
  
    // In User.php, update the login method:
    
    public function login($email, $password) {
        try {
            // Sanitize inputs
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            
            $query = "SELECT user_id, name, email, password, role 
                     FROM " . $this->table_name . " 
                     WHERE email = :email
                     LIMIT 1";
    
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Database query failed");
            }
            
            $stmt->bindParam(":email", $email);
            
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed");
            }
    
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if(password_verify($password, $row['password'])) {
                    // Set properties
                    $this->user_id = $row['user_id'];
                    $this->name = $row['name'];
                    $this->email = $row['email'];
                    $this->role = $row['role'];
                    
                    // Set session variables
                    $_SESSION['user_id'] = $this->user_id;
                    $_SESSION['user_name'] = $this->name;
                    $_SESSION['user_role'] = $this->role;
                    
                    return true;
                }
            }
            
            return false;
            
        } catch(PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            throw new Exception("Database error occurred");
        } catch(Exception $e) {
            error_log("General Error: " . $e->getMessage());
            throw $e;
        }
    }
    
}