<?php
class Database {
    private $host = "localhost";
    private $dbname = "cafe1";
    private $username = "root";
    private $password = "";
    private $conn = null;

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

    public function select($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->conn->prepare($sql);
            
            // Using bindParam() instead of bindValue()
            foreach ($data as $key => &$value) {
                // The & symbol is crucial here - it passes by reference
                $stmt->bindParam(":$key", $value);
            }
            
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public function update($table, $data, $id) {
        try {
            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "$key = :$key";
            }
            
            $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            
            // Using bindParam() instead of bindValue()
            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            // We need to bind the ID parameter by reference too
            $stmt->bindParam(":id", $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
            return false;
        }
    }

    public function delete($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            // Using bindParam() instead of bindValue()
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
            return false;
        }
    }

    // Special method for getting orders with details
    public function getOrders($startDate, $endDate, $userId = null) {
        try {
            $sql = "SELECT 
                        u.name,
                        o.date,
                        p.product_name as order_name,
                        op.quantity,
                        (op.quantity * p.product_price) as total_price
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    JOIN order_products op ON o.order_id = op.order_id
                    JOIN products p ON op.product_id = p.product_id
                    WHERE o.date BETWEEN :start_date AND :end_date";
            
            if ($userId) {
                $sql .= " AND o.user_id = :user_id";
            }
            
            $sql .= " ORDER BY o.date DESC";
            
            $stmt = $this->conn->prepare($sql);
            // Using bindParam() instead of bindValue()
            $stmt->bindParam(":start_date", $startDate);
            $stmt->bindParam(":end_date", $endDate);
            
            if ($userId) {
                $stmt->bindParam(":user_id", $userId);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Failed to get orders: " . $e->getMessage();
            return false;
        }
    }
}