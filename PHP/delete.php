<?php


require_once "database.php";

$db = new Database();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($db->delete("products", $id)) {
        echo "<script>alert('Product deleted successfully.'); window.location.href='allproducts.php';</script>";
    } else {
        echo "<script>alert('Failed to delete product.'); window.location.href='allproducts.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='allproducts.php';</script>";
}
?>



