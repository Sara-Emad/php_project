<?php
class Database {
    private $host = "localhost";
    private $dbname = "cafe2";
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
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function select($table) {
        try {
            $sql = "SELECT * FROM $table";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Select failed: " . $e->getMessage());
        }
    }

    public function update($table, $data, $id) {
        try {
            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "$key = :$key";
            }
            
            // For orders table, use order_id instead of id
            $idField = ($table === 'orders') ? 'order_id' : 'id';
            
            $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE $idField = :id";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(":id", $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }

    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            $stmt = $this->conn->prepare($sql);
            if ($stmt->execute($data)) {
                // For tables with auto-increment ID
                if ($table !== 'order_products') {
                    return $this->conn->lastInsertId();
                }
                return true; // For composite key tables
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }

    public function delete($table, $id) {
        try {
            // For orders table, use order_id instead of id
            $idField = ($table === 'orders') ? 'order_id' : 'id';
            
            $sql = "DELETE FROM $table WHERE $idField = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Delete failed: " . $e->getMessage());
        }
    }
}