<?php
session_start();
require_once 'database.php';

header('Content-Type: application/json');

try {
    // Decode the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['products']) || empty($data['products'])) {
        throw new Exception('Invalid order data');
    }

    $db = new Database();

    // Create the main order record
    $orderInsertData = [
        'user_id' => $_SESSION['user_id'] ?? 1, // Fallback to user 1 if not logged in
        'date' => date('Y-m-d'),
        'notes' => $data['notes'] ?? '',
        'room' => $data['room'] ?? '',
        'status' => 'Pending'
    ];

    // Insert the order and get the order ID
    $orderId = $db->insert('orders', $orderInsertData);

    if (!$orderId) {
        throw new Exception('Failed to create order');
    }

    // Process each product in the order
    foreach ($data['products'] as $product) {
        if (!isset($product['product_id']) || !isset($product['quantity'])) {
            throw new Exception('Invalid product data');
        }

        $orderProductData = [
            'order_id' => $orderId,
            'product_id' => $product['product_id'],
            'quantity' => $product['quantity']
        ];

        $result = $db->insert('order_products', $orderProductData);
        
        if ($result === false) {
            throw new Exception('Failed to add product to order');
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Order created successfully',
        'order_id' => $orderId
    ]);

} catch (Exception $e) {
    // If there's an error and an order was created, attempt to delete it
    if (isset($orderId) && isset($db)) {
        try {
            $db->delete('orders', $orderId);
        } catch (Exception $deleteError) {
            // Log the delete error but don't expose it to the user
            error_log("Failed to delete failed order: " . $deleteError->getMessage());
        }
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}