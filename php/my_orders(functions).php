<?php
session_start();

require_once('../Database/Database.php');

$db = new Database();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $user = $db->select("users", "user_id = ?", [$userId]);

    if (!empty($user)) {
        $name = $user[0]['name'];
    }
}

$orders = $db->select('orders');
$products = $db->select('products');
$users = $db->select('users');

function calculateTotalPrice($db, $orderId)
{
    $totalPrice = 0;

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

    return number_format($totalPrice, 2);
}


if (isset($_GET['cancel_order_id'])) {
    $orderId = $_GET['cancel_order_id'];


    $order = $db->select("orders[$orderId]");

    if (!empty($order) && ($order[0]['status'] === "Pending" || $order[0]['status'] === "Processing")) {
        $db->update("orders", ["status" => "Canceled"], [$orderId]);
    }


    header("Location: my_orders.php");
    exit();
}
?>
