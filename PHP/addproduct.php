<?php
require_once "database.php";
$db = new Database(); 
$categories = $db->select('categories');

session_start();
if (!empty($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
} else {
    $errors = [];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="../assets/style/addproduct.css">
    <title>Add Product</title>
    

  </head>
  <body class="text-white">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-75">
      <div class="container-fluid">
        <a class="navbar-brand" href="addproduct.php">Cafeteria</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="checks.php">Checks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="createorder.php">Manual Order</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form
            id="addProduct"
            class="bg-dark bg-opacity-75 p-4 rounded"
            action="save.php"
            method="POST"
            enctype="multipart/form-data"
          >
          <h2 class="text-center mb-4 text-warning">Add Product</h2>
          <?php if (!empty($errors)): ?>
            <div style="color: red;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
              </div>
          <?php endif; ?>
            <div class="mb-3">
              <label for="product_name" class="form-label">Product Name:</label>
              <input 
              type="text" 
              class="form-control" 
              id="product_name" 
              name="product_name"
              required
              />
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">Price:</label>
              <input
                type="number"
                class="form-control"
                id="product_price"
                name="product_price"
                required
              />
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">category:</label>
              <select
                id="category"
                name="category"
                class="form-select shadow-sm"
                required
              >
                <option value="" selected disabled>Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>">
                        <?php echo $category['category_name']; ?>
                    </option>
                <?php endforeach; ?>
              </select >
            </div>
            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity:</label>
              <input
                type="number"
                class="form-control"
                id="quantity"
                name="quantity"
                required
              />
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Image:</label>
              <input
                type="file"
                class="form-control"
                id="image"
                name="image"
                accept="image/*"
                required
              />
            </div>
            <input type="submit" class="btn btn-warning w-100" />
          </form>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/javascript/addproduct.js"></script>
  </body>
</html>
