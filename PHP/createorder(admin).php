<?php
require_once 'database.php';

session_start();

$db = new Database();

$products = $db->select('products');
$users = $db->select('users'); // Fetch users from the database

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
    <title>Cafe Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style/createorder.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Cafe System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adduser.php">Add User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product_mangement.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>New Order</h5>
                    </div>
                    <div class="card-body">
                        <form id="orderForm" method="POST" action="process_order.php">
                            <div class="row">
                                <!-- Left side - Order Input -->
                                <div class="col-md-8">
                                    <!-- Add User Dropdown Here -->
                                    <div class="mb-3">
                                        <label class="form-label">Select User</label>
                                        <select id="user" name="user" class="form-select shadow-sm" required>
                                            <option value="" selected disabled>Select User</option>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?php echo $user['user_id']; ?>">
                                                    <?php echo htmlspecialchars($user['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Product</label>
                                            <select class="form-select" name="product_id" required>
                                                <option value="">Select Product</option>
                                                <?php foreach($products as $product): ?>
                                                    <option value="<?php echo $product['product_id']; ?>"
                                                            data-category="<?php echo htmlspecialchars($product['category_id']); ?>"
                                                            data-image="<?php echo htmlspecialchars($product['image']); ?>">
                                                        <?php echo $product['product_name']; ?> - 
                                                        EGP <?php echo $product['product_price']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="quantity" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-primary d-block w-100" onclick="addToOrder()">
                                                Add to Order
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Table</label>
                                        <select class="form-select" name="room">
                                            <option value="">Select Table</option>
                                            <option value="Room 1">Table 1</option>
                                            <option value="Room 2">Table 2</option>
                                            <option value="Room 3">Table 3</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right side - Order Summary -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Order Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="orderItems" class="mb-3">
                                                <!-- Order items will be displayed here -->
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <h6>Total:</h6>
                                                <h6 id="orderTotal">EGP 0.00</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success w-100">Confirm Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="search-container">
      <input 
        type="text" 
        id="productSearch" 
        class="search-input" 
        placeholder="Search for products..."
      >
    </div>
    
    <div id="productGrid" class="product-grid">
      <!-- Products will be displayed here -->
    </div>
  </div>
  <div id="orderItemsInputs" style="display: none;">
      <!-- Hidden inputs will be added here -->
  </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="createorder.js"></script>
</body>
</html>