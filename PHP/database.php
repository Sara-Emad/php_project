<?php
class Database {

    private $host = "localhost";
    private $dbname = "cafe1";
    private $username = "root";
    private $password = "";
private $conn;
public function __construct() {
    try {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
        $this->conn = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}
//!________________________________________________________________________
    public function select($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
//!_____________________________________________________________________

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>