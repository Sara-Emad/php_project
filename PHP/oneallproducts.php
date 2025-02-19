<?php
require_once 'database.php';

session_start();

$db = new Database();
$products = $db->select('products');

if (!$products) {
    $products = [];
    error_log("Failed to fetch products from the database.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
        .availability {
            font-weight: bold;
        }
        .available {
            color: green;
        }
        .unavailable {
            color: red;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-75">
        <div class="container-fluid">
            <a class="navbar-brand" href="addproduct.php">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="checks.php">Checks</a></li>
                    <li class="nav-item"><a class="nav-link" href="createorder.php">Manual Order</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>All Products</h2>
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
                            <a href="editproduct.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>