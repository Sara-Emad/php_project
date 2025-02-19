<?php
require_once('my_orders(functions).php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="../assets/myorder.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#"><strong>Cafe</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="welcome(customer).php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" href="my_orders.php">My Orders</a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <span class="ms-2"><?php echo isset($name) ? $name : 'User'; ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">My Orders</h2>
                
                
                <form method="GET" action="my_orders.php" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($orders)) {
                                foreach ($orders as $order) {
                                    $orderId = $order['order_id'];
                                    $totalPrice = calculateTotalPrice($db, $orderId);
                                    echo "<tr>";
                                    echo "<td>" . date("Y/m/d h:i A", strtotime($order['date'])) . "</td>";
                                    echo "<td><span class='status-badge " . $order['status'] . "'>" . $order['status'] . "</span></td>";
                                    echo "<td>$" . $totalPrice . "</td>";
                                    echo "<td>";
                                    
                                    if ($order['status'] === "Pending" || $order['status'] === "Processing") {
                                        echo "<a href='#' class='btn btn-warning btn-sm cancel-order-btn' data-order-id='$orderId'>";
                                        echo "<i class='fa fa-xmark'></i> Cancel";
                                        echo "</a>";                                        
                                    }
                                    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center py-4'>No orders found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/javascript/myorder.js"></script>
</body>

</html>

