<?php
class Database {
    private $host = "localhost";
    private $db_name = "cafe1";
    private $username = "root";
    private $password = "";
    public $conn;
    
    private function logError($message, $error_type = 'ERROR') {
        $log_message = date('[Y-m-d H:i:s]') . " [{$error_type}] {$message}" . PHP_EOL;
        error_log($log_message, 3, dirname(__FILE__) . '/database_errors.log');
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Set connection options
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ];
            
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
            // Test the connection
            $this->conn->query("SELECT 1");
            
            return $this->conn;
            
        } catch(PDOException $e) {
            $error_message = "Connection Error: " . $e->getMessage();
            $this->logError($error_message);
            
            // In production, you might want to show a generic error message
            throw new Exception("Database connection failed. Please try again later.");
        }
    }
    
    public function closeConnection() {
        $this->conn = null;
    }
    
    public function beginTransaction() {
        if ($this->conn && !$this->conn->inTransaction()) {
            return $this->conn->beginTransaction();
        }
        return false;
    }
    
    public function commit() {
        if ($this->conn && $this->conn->inTransaction()) {
            return $this->conn->commit();
        }
        return false;
    }
    
    public function rollBack() {
        if ($this->conn && $this->conn->inTransaction()) {
            return $this->conn->rollBack();
        }
        return false;
    }
}