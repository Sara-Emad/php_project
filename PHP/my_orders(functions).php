<?php
session_start();
require_once('database.php');

$db = new Database();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $db->select("users", "user_id = ?", [$userId]);

    if (!empty($user)) {
        $name = $user[0]['name'];
    }
}

$orders = $db->select('orders');

//?$orders = $db->getOrders();

$orderId = null;
if (!empty($orders)) {
    $orderId = $orders[0]['order_id']; 
}

$products = $db->select('products');
$users = $db->select('users');

function calculateTotalPrice($db, $orderId)
{
    $totalPrice = 0;

    if ($orderId !== null) {
        $orderProducts = $db->select("order_products", "order_id = ?", [$orderId]);

        if (!empty($orderProducts)) {
            foreach ($orderProducts as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                $product = $db->select("products", "product_id = ?", [$productId]);

                if (!empty($product)) {
                    $totalPrice += $product[0]['product_price'] * $quantity;
                }
            }
        }
    }

    return number_format($totalPrice, 2);
}


// Inside your PHP logic where you handle the filter
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'] . ' 23:59:59'; // Include time

    // Fetch filtered orders
    $orders = $db->select(
        "orders", 
        "date >= ? AND date <= ?", 
        [$startDate, $endDate]
    );
}
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

    if (!empty($orderToCancel) && ($orderToCancel['status'] === "Pending" || $orderToCancel['status'] === "Processing")) {
        $db->delete("orders", [$cancelOrderId]);
        echo "Success";
    } else {
        echo "Failed";
    }
}
?>
