<?php
require_once "database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Added Successfully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/style/success.css">
</head>
<body>
    <div class="container">
        <div class="success-message">
            <h1>Product Added Successfully!</h1>
            <p>Your product has been added to the database. You can add another product or view the product list.</p>
        </div>

        <div class="text-center">
            <a href="addproduct.php" class="btn btn-success btn-custom">Add Another Product</a>
            <a href="allproducts.php" class="btn btn-primary btn-custom">View Product List</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>