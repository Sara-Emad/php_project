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
    public function delete() {
      try {
          $this->conn->beginTransaction();

          // First check if the product is referenced in order_products
          $check_query = "SELECT COUNT(*) FROM order_products WHERE product_id = ?";
          $check_stmt = $this->conn->prepare($check_query);
          $check_stmt->execute([$this->product_id]);
          $count = $check_stmt->fetchColumn();

          if ($count > 0) {
              throw new Exception("Cannot delete product as it is referenced in orders");
          }

          // Get the image filename
          $query = "SELECT image FROM " . $this->table_name . " WHERE product_id = ?";
          $stmt = $this->conn->prepare($query);
          $stmt->execute([$this->product_id]);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          
          // Delete the product record first
          $delete_query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";
          $delete_stmt = $this->conn->prepare($delete_query);
          $result = $delete_stmt->execute([$this->product_id]);
          
          if ($result && $row && $row['image']) {
              // Only attempt to delete the file if product was successfully deleted from database
              $image_path = dirname(__FILE__) . '/uploads/products/' . $row['image'];
              if (file_exists($image_path)) {
                  unlink($image_path);
              }
          }

          $this->conn->commit();
          return true;
      } catch(Exception $e) {
          $this->conn->rollBack();
          error_log("Error deleting product: " . $e->getMessage());
          throw new Exception($e->getMessage());
      }
  }

  public function update() {
      try {
          $this->conn->beginTransaction();

          // Validate inputs
          if (empty($this->product_name) || $this->product_price <= 0 || $this->quantity < 0) {
              throw new Exception("Invalid input values");
          }

          $query = "UPDATE " . $this->table_name . "
                  SET product_name = :name,
                      category_id = :category_id,
                      product_price = :price,
                      quantity = :quantity";
          
          // Only include image in update if a new one is provided
          if ($this->image) {
              $query .= ", image = :image";
          }
          
          $query .= " WHERE product_id = :product_id";
          
          $stmt = $this->conn->prepare($query);
          
          // Sanitize and bind values
          $this->product_name = htmlspecialchars(strip_tags($this->product_name));
          $this->category_id = filter_var($this->category_id, FILTER_VALIDATE_INT);
          $this->product_price = filter_var($this->product_price, FILTER_VALIDATE_FLOAT);
          $this->quantity = filter_var($this->quantity, FILTER_VALIDATE_INT);
          
          $stmt->bindParam(":name", $this->product_name);
          $stmt->bindParam(":category_id", $this->category_id);
          $stmt->bindParam(":price", $this->product_price);
          $stmt->bindParam(":quantity", $this->quantity);
          $stmt->bindParam(":product_id", $this->product_id);
          
          if ($this->image) {
              $stmt->bindParam(":image", $this->image);
          }
          
          $result = $stmt->execute();
          
          if (!$result) {
              throw new Exception("Failed to update product");
          }

          $this->conn->commit();
          return true;
      } catch(Exception $e) {
          $this->conn->rollBack();
          error_log("Error updating product: " . $e->getMessage());
          throw new Exception($e->getMessage());
      }
  }
  
  public function readOne() {
      try {
          $query = "SELECT p.*, c.category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.product_id = ?";
          
          $stmt = $this->conn->prepare($query);
          $stmt->execute([$this->product_id]);
          
          return $stmt->fetch(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
          error_log("Error reading product: " . $e->getMessage());
          return false;
      }
  }
}