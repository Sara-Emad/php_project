<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $db = new Database();

    $product_name = trim($_POST['product_name'] ?? '');
    if (empty($product_name)) {
        $errors[] = "Product name is required";
    } elseif (strlen($product_name) < 3) {
        $errors[] = "Product name must be at least 3 characters";
    }

    $price = trim($_POST['product_price'] ?? '');
    if (empty($price)) {
        $errors[] = "Price is required";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive number";
    }

    $category = trim($_POST['category'] ?? '');
    if (empty($category)) {
        $errors[] = "Please select a category";
    }

    $quantity = trim($_POST['quantity'] ?? '');
    if (empty($quantity)) {
        $errors[] = "Quantity is required";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $errors[] = "Quantity must be a positive whole number";
    } elseif ($quantity > 1000) {
        $errors[] = "Quantity cannot exceed 1000 items";
    }

    if (empty($_FILES['image']['name'])) {
        $errors[] = "Image is required";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, and PNG images are allowed";
        }

        if ($file_size > 5 * 1024 * 1024) {
            $errors[] = "File size must be less than 5MB";
        }
    }

    if (empty($errors)) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $upload_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            $data = [
                'product_name' => $product_name,
                'product_price' => $price,
                'category_id' => $category,
                'quantity' => $quantity,
                'image' => $image_name
            ];

            if ($db->insert('products', $data)) {
                header('location: success.php');
                exit();
            } else {
                $errors[] = "Failed to add product to the database.";
            }
        } else {
            $errors[] = "Failed to upload image.";
        }
    }

    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        header('Location: addproduct.php');
        exit();
    }
}
?>