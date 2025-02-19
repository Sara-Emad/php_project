<?php
require_once('my_orders(functions).php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="../css/style.css">
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
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" href="#">My Orders</a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <span class="ms-2"><?php echo isset($users['user_id']) ? $users['name'] : 'User'; ?></span>
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
                                    $status = $order['status'];
                                    $totalPrice = calculateTotalPrice($db, $orderId);

                                    echo "<tr>";
                                    echo "<td>" . date("Y/m/d h:i A", strtotime($order['date'])) . "</td>";
                                    echo "<td><span class='status-badge " . $status . "'>" . htmlspecialchars($status) . "</span></td>";
                                    echo "<td>$" . $totalPrice . "</td>";
                                    echo "<td>";

                                    // Edit button
                                    echo "<a href='edit_order.php?order_id=$orderId' class='btn btn-primary btn-sm me-2'>
                                            <i class='fa fa-edit'></i> Edit
                                          </a>";

                                    // Delete button with confirmation
                                    echo "<a href='delete_order.php?order_id=$orderId' class='btn btn-danger btn-sm me-2'
                                            onclick='return confirm(\"Are you sure you want to delete this order?\");'>
                                            <i class='fa fa-trash'></i> Delete
                                          </a>";

                                    // Cancel button (only for Pending & Processing orders)
                                    if ($status === "Pending" || $status === "Processing") {
                                        echo "<a href='cancel_order.php?order_id=$orderId' class='btn btn-warning btn-sm'>
                                                <i class='fa fa-times'></i> Cancel
                                              </a>";
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
</body>

</html>
