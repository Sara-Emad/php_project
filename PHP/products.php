<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $product_id;
    public $product_name;
    public $category_id;
    public $product_price;
    public $image;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        try {
            $query = "SELECT p.*, c.category_name 
                    FROM " . $this->table_name . " p
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    ORDER BY p.product_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Error reading products: " . $e->getMessage());
            throw new Exception("Error fetching products");
        }
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                    SET product_name=:name, category_id=:category_id,
                        product_price=:price, image=:image, quantity=:quantity";
            
            $stmt = $this->conn->prepare($query);

            // Sanitize inputs
            $this->product_name = htmlspecialchars(strip_tags($this->product_name));
            $this->category_id = filter_var($this->category_id, FILTER_VALIDATE_INT);
            $this->product_price = filter_var($this->product_price, FILTER_VALIDATE_FLOAT);
            $this->quantity = filter_var($this->quantity, FILTER_VALIDATE_INT);
            
            // Bind values
            $stmt->bindParam(":name", $this->product_name);
            $stmt->bindParam(":category_id", $this->category_id);
            $stmt->bindParam(":price", $this->product_price);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":quantity", $this->quantity);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    public function getAvailableQuantity($product_id) {
        try {
            $query = "SELECT quantity FROM " . $this->table_name . " 
                     WHERE product_id = :product_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['quantity'] : 0;
        } catch(PDOException $e) {
            error_log("Error checking product quantity: " . $e->getMessage());
            return 0;
        }
    }

    public function updateQuantity($product_id, $new_quantity) {
        try {
            $query = "UPDATE " . $this->table_name . "
                     SET quantity = :quantity 
                     WHERE product_id = :product_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":quantity", $new_quantity);
            $stmt->bindParam(":product_id", $product_id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error updating product quantity: " . $e->getMessage());
            return false;
        }
    }
}