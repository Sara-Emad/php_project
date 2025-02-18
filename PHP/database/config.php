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
          $options = [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_EMULATE_PREPARES => false,
              PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
          ];
          
          $this->conn = new PDO(
              "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
              $this->username,
              $this->password,
              $options
          );
          
          return $this->conn;
          
      } catch(PDOException $e) {
          error_log("Connection Error: " . $e->getMessage());
          throw new Exception("Database connection failed");
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