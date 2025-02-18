<?php

class Database {
    private $conn;

    public function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // public function insert($table, $data) {
    //     $columns = implode(", ", array_keys($data));
    //     $placeholders = ":" . implode(", :", array_keys($data));
    //     $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute($data);
    // }

    public function select($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // public function update($table, $id, $data) {
    //     $updates = [];
    //     foreach ($data as $key => $value) {
    //         $updates[] = "$key = :$key";
    //     }
    //     $updates_str = implode(", ", $updates);
        
    //     $sql = "UPDATE $table SET $updates_str WHERE id = :id";
    //     $stmt = $this->conn->prepare($sql);
    //     $data['id'] = $id;
    //     return $stmt->execute($data);
    // }

    // public function delete($table, $id) {
    //     $sql = "DELETE FROM $table WHERE id = :id";
    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute(['id' => $id]);
    // }
}
?>
