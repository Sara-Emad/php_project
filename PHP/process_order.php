<?php
session_start();
require_once '../php/database/config.php';
require_once 'order.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'order_errors.log');

try {
    // Debug logging
    error_log("POST data received: " . print_r($_POST, true));
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $database = new Database();
    $db = $database->getConnection();
    
    // // Hardcode user_id for testing
    // $user_id = 1; // Replace with $_SESSION['user_id'] in production
    // ====================================================================================
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User not authenticated"
    ]);
    exit;
}

// Use the actual logged-in user's ID
$user_id = $_SESSION['user_id'];
    // Debug products data
    error_log("Products data: " . print_r($_POST['products'], true));

    if (empty($_POST['products'])) {
        throw new Exception("No products provided in the order");
    }

    $order = new Order($db);
    
    // Set order properties
    $order->user_id = $user_id;
    $order->status = "Pending";
    $order->date = date('Y-m-d H:i:s');
    $order->notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $order->room = isset($_POST['room']) ? $_POST['room'] : '';

    // Start transaction
    $db->beginTransaction();
    
    // Create order and get ID
    $order_id = $order->create();
    
    if (!$order_id) {
        throw new Exception("Order creation failed in database");
    }

    // Process each product
    foreach ($_POST['products'] as $product) {
        $product_id = (int)$product['product_id'];
        $quantity = (int)$product['quantity'];
        
        if ($product_id <= 0 || $quantity <= 0) {
            throw new Exception("Invalid product ID or quantity");
        }
        
        if (!$order->addOrderProducts($order_id, $product_id, $quantity)) {
            throw new Exception("Failed to add product {$product_id} to order");
        }
    }

    // Commit transaction
    $db->commit();
    
    echo json_encode([
        "status" => "success",
        "message" => "Order created successfully",
        "order_id" => $order_id
    ]);

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    error_log("Order Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "debug" => [
            "post_data" => $_POST,
            "error" => $e->getMessage(),
            "trace" => $e->getTraceAsString()
        ]
    ]);
}