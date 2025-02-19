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
            displayError("Connection failed: " . $e->getMessage());
            die();
        }
    }

    public function select($table) {
        try {
            $select_query = "SELECT * FROM $table";
            $stmt = $this->conn->prepare($select_query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }

    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $insert_query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            $stmt = $this->conn->prepare($insert_query);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            if ($this->conn->lastInsertId()) {
                displaySuccess("Insert Successful {$this->conn->lastInsertId()}");
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }

    public function update($table, $data, $id) {
        try {
            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "$key = :$key";
            }
            
            $update_query = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE id = :id";
            $stmt = $this->conn->prepare($update_query);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(":id", $id);
            
            $stmt->execute();
            if ($stmt->rowCount()) {
                displaySuccess("Update Successful");
                return true;
            }
            displayError("Update Failed");
            return false;
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }

    public function delete($table, $id) {
        try {
            $delete_query = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->conn->prepare($delete_query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            
            if ($stmt->rowCount()) {
                displaySuccess("Delete Successful");
                return true;
            }
            displayError("Delete Failed");
            return false;
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }

    public function selectOne($table, $id) {
        try {
            $select_query = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->conn->prepare($select_query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }
}

// Helper functions for displaying messages
function displaySuccess($message) {
    echo "<div class='alert alert-success'>$message</div>";
}

function displayError($message) {
    echo "<div class='alert alert-danger'>$message</div>";
}
?>
