<?php
require 'database.php';

session_start();

$db = new Database();

// Get product ID from the URL
if (!isset($_GET['id'])) {
    die("Invalid product ID.");
}
$productId = $_GET['id'];

// Fetch product data
$product = $db->select("products WHERE product_id = $productId");

if (!$product) {
    die("Product not found.");
}

$product = $product[0]; // Get first result

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $quantity = $_POST['quantity'];
    
    $updateData = [
        'product_name' => $productName,
        'product_price' => $productPrice,
        'quantity' => $quantity
    ];

    // Handle image upload if a new image was provided
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $targetDir = "uploads/products/";
        $fileExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $fileExtension;
        $targetFile = $targetDir . $newFileName;

        // Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (5MB limit)
            if ($_FILES["image"]["size"] > 5000000) {
                die("File is too large. Maximum size is 5MB.");
            }
            
            // Allow certain file formats
            if ($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png") {
                die("Only JPG, JPEG & PNG files are allowed.");
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $updateData['image'] = $newFileName;
                
                // Delete old image if it exists
                if (!empty($product['image'])) {
                    $oldFile = $targetDir . $product['image'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            }
        }
    }

    if ($db->update("products", $updateData, $productId)) {
        header("Location: allproducts.php?success=Product updated successfully");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
        }
    </style>
</head>
<body class="bg-light">
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

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Update Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price (EGP)</label>
                <input type="number" step="0.01" name="product_price" class="form-control" value="<?= htmlspecialchars($product['product_price']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($product['quantity']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" class="product-image" alt="Current product image">
            </div>
            <div class="mb-3">
                <label class="form-label">New Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png">
                <small class="text-muted">Leave empty to keep current image. Only JPG, JPEG & PNG files are allowed (max 5MB).</small>
            </div>
            <button type="submit" class="btn btn-success">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>