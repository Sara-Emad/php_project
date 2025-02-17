<?php
session_start();

require_once('../Database/config.php');
require_once('../Database/Database.php');


$db = new Database();
$db->connect();


$user_id = $_SESSION['user_id'] ?? 1;
$name = $_SESSION['name'] ?? '';
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');


$sql_orders = "SELECT * FROM orders 
               WHERE user_id = :user_id 
               AND date BETWEEN :date_from AND :date_to
               ORDER BY date DESC";
$stmt = $db->getConnection()->prepare($sql_orders); 
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':date_from', "$date_from 00:00:00", PDO::PARAM_STR);
$stmt->bindValue(':date_to', "$date_to 23:59:59", PDO::PARAM_STR);
$stmt->execute();
$result_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


function getOrderItems($db, $order_id) {
    $sql_items = "SELECT oi.*, p.name, p.price, p.category 
                 FROM order_items oi
                 JOIN products p ON oi.product_id = p.id
                 WHERE oi.order_id = :order_id";
    $stmt_items = $db->getConnection()->prepare($sql_items); 
    $stmt_items->bindValue(':order_id', $order_id, PDO::PARAM_INT);
    $stmt_items->execute();
    return $stmt_items->fetchAll(PDO::FETCH_ASSOC);
}


function getProductIcon($category) {
    switch (strtolower($category)) {
        case 'tea':
            return 'fa-mug-hot';
        case 'coffee':
        case 'nescafe':
            return 'fa-mug-saucer';
        case 'cola':
        case 'soda':
            return 'fa-bottle-water';
        default:
            return 'fa-glass-water';
    }
}


function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'processing':
            return 'status-processing';
        case 'out for delivery':
            return 'status-delivery';
        case 'done':
            return 'status-done';
        default:
            return 'status-processing';
    }
}


function canBeCancelled($status) {
    return strtolower($status) === 'processing';
}


$expanded_order_id = $_GET['expand'] ?? 0;

?>
