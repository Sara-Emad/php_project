<?php
require_once 'Database.php';



$db = new Database();
$users = $db->select('users', [], null);
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
 <link rel="stylesheet" href="assets/css/style.css">
    

   
</head>
<body >
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Checks </h2>

        <!-- Filters Section -->
        <div class="card p-3 mb-4 shadow-sm">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">
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
    <script src="assets/js/script.js"></script>
</body>
</html>
