<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <title>Add Product</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
      }
      body {
        background-image: url("../images/page-title-bg.webp");
        background-size: cover;
        background-repeat: no-repeat;
        min-height: 100vh;
      }
    </style>
  </head>
  <body class="text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-75">
      <div class="container-fluid">
        <a class="navbar-brand" href="addproduct.html">Restaurant</a>
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
              <a class="nav-link" href="Home.php">Home</a>
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
              <a class="nav-link" href="Manual order.php">Manual Order</a>
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
            action="addproduct.php"
            method="post"
            enctype="multipart/form-data"
          >
            <h2 class="text-center mb-4 text-warning">Add Product</h2>
            <div class="mb-3">
              <label for="name" class="form-label">Name:</label>
              <input 
              type="text" 
              class="form-control" 
              id="name" 
              name="name" 
              required
              />
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">Price:</label>
              <input
                type="number"
                class="form-control"
                id="price"
                name="price"
                required
              />
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">category:</label>
              <select
                name="category"
                id="category"
                class="form-select shadow-sm"
                required
              >
                <option value="" selected disabled>Select Category</option>
                <option value="hot drinks">Hot Drinks</option>
                <option value="cold drinks">Cold Drinks</option>
                <option value="sweets">Sweets</option>
                <option value="snacks">Snacks</option>
              </select >
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
    <script src="../javascript/addproduct.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
