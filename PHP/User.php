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

    public function create() {
        try {
            // Validate input data
            if (!$this->validateInputs()) {
                throw new Exception("Invalid input data");
            }

            $query = "INSERT INTO " . $this->table_name . "
                    SET name=:name, email=:email, room=:room, 
                        ext=:ext, password=:password, role=:role";
            
            $stmt = $this->conn->prepare($query);

            // Sanitize and validate inputs
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
            $this->room = htmlspecialchars(strip_tags($this->room));
            $this->ext = htmlspecialchars(strip_tags($this->ext));
            $this->role = $this->role === 'admin' ? 'admin' : 'user'; // Default to user if not admin

            // Hash password with better security
            $options = [
                'cost' => 12
            ];
            $hashed_password = password_hash($this->password, PASSWORD_BCRYPT, $options);
            
            // Bind values
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":room", $this->room);
            $stmt->bindParam(":ext", $this->ext);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":role", $this->role);

            if($stmt->execute()) {
                return true;
            }
            return false;

        } catch(PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    private function validateInputs() {
        // Validate email
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Validate password strength
        if (strlen($this->password) < 8) {
            return false;
        }

        // Validate name (non-empty and reasonable length)
        if (empty($this->name) || strlen($this->name) > 100) {
            return false;
        }

        return true;
    }

    public function emailExists() {
        try {
            $query = "SELECT user_id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize email
            $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
            $stmt->bindParam(":email", $this->email);
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
            
        } catch(PDOException $e) {
            error_log("Email check error: " . $e->getMessage());
            throw new Exception("Error checking email existence");
        }
    }

    public function search($keyword) {
        try {
            $query = "SELECT user_id, name, email, room, ext, role 
                     FROM " . $this->table_name . "
                     WHERE name LIKE :keyword 
                     OR email LIKE :keyword 
                     OR room LIKE :keyword
                     LIMIT 50"; // Add limit for security
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitize keyword
            $keyword = htmlspecialchars(strip_tags($keyword));
            $keyword = "%{$keyword}%";
            $stmt->bindParam(":keyword", $keyword);
            
            $stmt->execute();
            return $stmt;
            
        } catch(PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            throw new Exception("Error performing search");
        }
    }
}