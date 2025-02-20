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
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
//?_____________________________________select_____________________________________________________________

    public function select($table, $condition = "", $params = []) {
        try {
            $sql = "SELECT * FROM $table";
            if (!empty($condition)) {
                $sql .= " WHERE $condition";
            }
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
    
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Select failed: " . $e->getMessage();
            return [];
        }
    }

    public function getOrders($startDate = null, $endDate = null, $userId = null) {
        try {
            $sql = "SELECT 
                        o.order_id,
                        o.status,
                        u.name,
                        o.date,
                        SUM(op.quantity * p.product_price) AS total_price
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    JOIN order_products op ON o.order_id = op.order_id
                    JOIN products p ON op.product_id = p.product_id";
    
            $conditions = [];
            if ($startDate && $endDate) {
                $conditions[] = "o.date BETWEEN :start_date AND :end_date";
            }
            if ($userId) {
                $conditions[] = "o.user_id = :user_id";
            }
    
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
    
            $sql .= " GROUP BY o.order_id"; // Add grouping
            $sql .= " ORDER BY o.date DESC";
    
            $stmt = $this->conn->prepare($sql);
    
            if ($startDate && $endDate) {
                $stmt->bindParam(":start_date", $startDate);
                $stmt->bindParam(":end_date", $endDate);
            }
            if ($userId) {
                $stmt->bindParam(":user_id", $userId);
            }
    
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Failed to get orders: " . $e->getMessage());
            return [];
        }
    }
//?_____________________________________insert_____________________________________________________________

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
//?__________________________________________update and delete____________________________________________
    public function update($table, $data, $id) {
        try {
            
            $primaryKey = $this->getPrimaryKey($table);
    
            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "$key = :$key";
            }
    
            $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE $primaryKey = :id";
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
    
    public function delete($table, $id) {
        try {

            $primaryKey = $this->getPrimaryKey($table);
            
            $sql = "DELETE FROM $table WHERE $primaryKey = :id";
            $stmt = $this->conn->prepare($sql);
        
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Delete failed: " . $e->getMessage());
        }
    }

    private function getPrimaryKey($table) {
        $primaryKeys = [
            'users' => 'user_id',
            'products' => 'product_id',
            'orders' => 'order_id',
        ];
        return $primaryKeys[$table] ?? 'id'; 
    }
}