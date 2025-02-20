<?php
session_start();
require_once('database.php');

$db = new Database();

if (isset($_GET['cancel_order_id'])) {
    $cancelOrderId = intval($_GET['cancel_order_id']);

    $orders = $db->select('orders');

    $orderToCancel = null;
    foreach ($orders as $order) {
        if ($order['order_id'] == $cancelOrderId) {
            $orderToCancel = $order;
            break;
        }
    }

    if ($orderToCancel === null) {
        echo "Error: Order not found.";
        exit;
    }

    if ($orderToCancel['status'] !== "Pending" && $orderToCancel['status'] !== "Processing") {
        echo "Error: Order cannot be canceled.";
        exit;
    }

    $updateResult = $db->update("orders", ["status" => "Canceled"], $cancelOrderId);


    
    if ($updateResult) {
        echo "Success";
    } else {
        echo "Error: Update failed.";
    }
} else {
    echo "Error: Invalid request.";
}
?>
