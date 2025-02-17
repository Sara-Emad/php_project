<?php
require_once '../php/database/config.php';

header('Content-Type: application/json');

if (isset($_GET['order_id'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get order details
    $query = "SELECT o.*, u.name as user_name,
              p.product_name, op.quantity
              FROM orders o
              LEFT JOIN users u ON o.user_id = u.user_id
              LEFT JOIN order_products op ON o.order_id = op.order_id
              LEFT JOIN products p ON op.product_id = p.product_id
              WHERE o.order_id = :order_id";
              
    $stmt = $db->prepare($query);
    $stmt->execute([':order_id' => $_GET['order_id']]);
    
    $order = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($order) {
        $response = [
            'order_id' => $order[0]['order_id'],
            'user_name' => $order[0]['user_name'],
            'room' => $order[0]['room'],
            'date' => $order[0]['date'],
            'products' => array_map(function($item) {
                return [
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity']
                ];
            }, $order)
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Order not found']);
    }
}