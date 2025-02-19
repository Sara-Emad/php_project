<?php
require_once('../Database/Database.php');


$db = new Database();
$orders = $db->select('orders');

function calculateTotalPrice($db, $orderId) {
    $items = $db->select("orders", "order_id = ?", [$orderId]);
    $totalPrice = 0;

    if (!empty($items)) {
        foreach ($items as $item) {
            if (isset($item['product_id']) && isset($item['quantity'])) {  
                $product = $db->select("products", "product_id = ?", [$item['product_id']]);
                
                if (!empty($product) && isset($product[0]['product_price'])) { 
                    $totalPrice += $product[0]['product_price'] * $item['quantity'];
                }
            }
        }
    }

    return $totalPrice;
}
?>
