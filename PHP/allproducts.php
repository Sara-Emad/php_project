<?php
require 'database.php'; // Include your database connection
session_start();

$db = new Database(); // Create a new instance of the Database class
// Initialize the $users variable
$products = $db->select("products"); // Fetch all users from the database

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    if ($db->delete("products", $productId)) {
        // Refresh the users list after deletion
        $products = $db->select("products");
        $successMessage = "product deleted successfully";
    } else {
        $errorMessage = "Failed to delete product";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/javascript/allproducts.js"></script>
    <link rel="stylesheet" href="../assets/style/allproducts.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="addproduct.html">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="welcome(admin).php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="allproducts.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="allusers.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="checks.php">Checks</a></li>
                    <li class="nav-item"><a class="btn btn-success" href="createorder(admin).php">create Order</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>All Products</h2>
        <!-- Add Product Button -->
        <div class="mb-3">
            <a href="addproduct.php" class="btn btn-success">Add New Product</a>
        </div>

        <!-- Display success or error messages -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td>EGP <?php echo number_format($product['product_price'], 2); ?></td>
                        <td>
                            <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>"
                                class="product-image" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        </td>
                        <td>
                            <span class="availability <?php echo ($product['quantity'] > 0) ? 'available' : 'unavailable'; ?>">
                                <?php echo ($product['quantity'] > 0) ? 'Available' : 'Unavailable'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="update_product.php?id=<?= $product['product_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <button onclick="confirmDelete(<?= $product['product_id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>