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

//!_____________________________________________________________________
public function update($table, $data, $id) {
    try {
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = :$key";
        }
        
        $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE order_id = :id";  
        $stmt = $this->conn->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
        return false;
    }
}

//!______________________________________________________________________

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
//!______________________________________________________________________
public function delete($table, $id) {
    try {
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Delete failed: " . $e->getMessage();
        return false;
    }
}



}
?>