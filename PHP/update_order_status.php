<?php
require_once '../php/database/config.php';
require_once 'order.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order_id']) && isset($data['status'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE orders SET status = :status WHERE order_id = :order_id";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([
        ':status' => $data['status'],
        ':order_id' => $data['order_id']
    ])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}