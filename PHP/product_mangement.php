<?php
require_once '../php/database/config.php';
require_once '../php/products.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Handle Delete
// Handle Delete
if (isset($_POST['delete_product'])) {
  try {
      $product->product_id = $_POST['product_id'];
      $product->delete();
      $success_message = "Product deleted successfully.";
  } catch (Exception $e) {
      $error_message = $e->getMessage();
  }
}

// Handle Edit/Update
if (isset($_POST['update_product'])) {
  try {
      $product->product_id = $_POST['product_id'];
      $product->product_name = $_POST['product_name'];
      $product->category_id = $_POST['category_id'];
      $product->product_price = $_POST['product_price'];
      $product->quantity = $_POST['quantity'];

      // Handle image upload for edit
      if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $upload_dir = 'uploads/products/';
          $file_tmp = $_FILES['image']['tmp_name'];
          $file_name = time() . '_' . $_FILES['image']['name'];
          $file_destination = $upload_dir . $file_name;

          if (move_uploaded_file($file_tmp, $file_destination)) {
              if (!empty($_POST['old_image'])) {
                  $old_image_path = $upload_dir . $_POST['old_image'];
                  if (file_exists($old_image_path)) {
                      unlink($old_image_path);
                  }
              }
              $product->image = $file_name;
          }
      }

      $product->update();
      $success_message = "Product updated successfully.";
  } catch (Exception $e) {
      $error_message = $e->getMessage();
  }
}

// Handle new product creation
if (isset($_POST['add_product'])) {
    $upload_dir = 'uploads/products/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $product->product_name = $_POST['product_name'];
    $product->category_id = $_POST['category_id'];
    $product->product_price = $_POST['product_price'];
    $product->quantity = $_POST['quantity'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = time() . '_' . $_FILES['image']['name'];
        $file_destination = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $file_destination)) {
            $product->image = $file_name;
        }
    }

    if ($product->create()) {
        $success_message = "Product created successfully.";
    } else {
        $error_message = "Failed to create product.";
    }
}

// Fetch categories for dropdown
$stmt = $db->query("SELECT * FROM categories ORDER BY category_name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing products
$stmt = $product->read();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);



  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Add Product Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Add New Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="add_product" value="1">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo $category['category_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="product_price" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*" id="imageInput" required>
                                <img id="imagePreview" class="preview-image">
                            </div>

                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Products List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products as $prod): ?>
                                        <tr>
                                            <td>
                                                <img src="uploads/products/<?php echo htmlspecialchars($prod['image']); ?>" 
                                                     class="product-image"
                                                     alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
                                            </td>
                                            <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                                            <td>EGP <?php echo number_format($prod['product_price'], 2); ?></td>
                                            <td><?php echo $prod['quantity']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" 
                                                        onclick="editProduct(<?php echo htmlspecialchars(json_encode($prod)); ?>)">
                                                    Edit
                                                </button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                                    <input type="hidden" name="delete_product" value="1">
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" id="editForm">
                        <input type="hidden" name="update_product" value="1">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <input type="hidden" name="old_image" id="edit_old_image">
                        
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" id="edit_product_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>">
                                        <?php echo $category['category_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" name="product_price" id="edit_product_price" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="edit_quantity" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <img id="currentImage" class="product-image d-block mb-2">
                            <label class="form-label">New Image (optional)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview for new products
        document.getElementById('imageInput').onchange = function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        };

        // Edit product function
        function editProduct(product) {
            document.getElementById('edit_product_id').value = product.product_id;
            document.getElementById('edit_product_name').value = product.product_name;
            document.getElementById('edit_category_id').value = product.category_id;
            document.getElementById('edit_product_price').value = product.product_price;
            document.getElementById('edit_quantity').value = product.quantity;
            document.getElementById('edit_old_image').value = product.image;
            document.getElementById('currentImage').src = 'uploads/products/' + product.image;
            
            // Show the modal
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
    </script>
</body>
</html>