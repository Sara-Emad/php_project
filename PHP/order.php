<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $order_id;
    public $user_id;
    public $status;
    public $date;
    public $notes;
    public $room;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
      try {
          $query = "INSERT INTO " . $this->table_name . "
                  (user_id, status, date, notes, room)
                  VALUES (:user_id, :status, :date, :notes, :room)";
          
          // Debug log
          error_log("Query: " . $query);
          error_log("Parameters: " . print_r([
              'user_id' => $this->user_id,
              'status' => $this->status,
              'date' => $this->date,
              'notes' => $this->notes,
              'room' => $this->room
          ], true));
          
          $stmt = $this->conn->prepare($query);
          
          $stmt->bindParam(":user_id", $this->user_id);
          $stmt->bindParam(":status", $this->status);
          $stmt->bindParam(":date", $this->date);
          $stmt->bindParam(":notes", $this->notes);
          $stmt->bindParam(":room", $this->room);
  
          if($stmt->execute()) {
              return $this->conn->lastInsertId();
          }
          
          // Log error if execution fails
          error_log("Execute failed: " . print_r($stmt->errorInfo(), true));
          return false;
      } catch (PDOException $e) {
          error_log("Order creation error: " . $e->getMessage());
          return false;
      }
  }

    public function addOrderProducts($order_id, $product_id, $quantity) {
        try {
            $query = "INSERT INTO order_products 
                    (order_id, product_id, quantity)
                    VALUES (:order_id, :product_id, :quantity)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->bindParam(":quantity", $quantity);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Add order products error: " . $e->getMessage());
            return false;
        }
    }
}