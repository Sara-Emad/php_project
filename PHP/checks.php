<?php
require_once 'database.php';



$db = new Database();

$users = $db->select('users');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/style/checks.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active " href="index.php">Home</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="allproducts.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="checks.php">Checks</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="d-flex">
                        <a class="btn btn-outline-light" href="logout.php">Logout</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4 text-center" id="checks">Checks </h2>

        <!-- Filters Section -->
        <div class="card p-3 mb-4 shadow-sm">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select id="user_filter" class="form-select">
                        <option value="">All Users</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['user_id']; ?>">
                                <?php echo htmlspecialchars($user['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" onclick="searchOrders()">Search</button>
                </div>
            </div>
        </div>

        <!-- Orders List Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Orders List</h5>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Order Name</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="orders_list">
                        <!-- Orders will be dynamically loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom JavaScript -->
        <script src="../assets/javascript/ckecks.js"></script>
</body>

</html>