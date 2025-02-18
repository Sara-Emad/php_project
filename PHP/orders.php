<?php
require_once '../php/database/config.php';
require_once 'order.php';


session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.php');
    exit;
}


$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

// Get all orders with their details
$query = "SELECT o.*, u.name as user_name 
          FROM orders o 
          LEFT JOIN users u ON o.user_id = u.user_id 
          ORDER BY o.date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Orders Management</h2>
        
        <!-- Status Filter -->
        <div class="mb-3">
            <select class="form-select" id="statusFilter">
                <option value="">All Orders</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <!-- Orders Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['user_name']; ?></td>
                        <td><?php echo $order['room']; ?></td>
                        <td>
                            <select class="form-select form-select-sm status-select" 
                                    data-order-id="<?php echo $order['order_id']; ?>">
                                <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>
                                    Pending
                                </option>
                                <option value="In Progress" <?php echo $order['status'] == 'In Progress' ? 'selected' : ''; ?>>
                                    In Progress
                                </option>
                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>
                                    Completed
                                </option>
                            </select>
                        </td>
                        <td><?php echo date('Y-m-d H:i', strtotime($order['date'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info view-order" 
                                    data-order-id="<?php echo $order['order_id']; ?>">
                                View Details
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const orderId = this.dataset.orderId;
                const newStatus = this.value;
                
                fetch('update_order_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Order status updated successfully');
                    } else {
                        alert('Failed to update order status');
                    }
                });
            });
        });

        // Handle view details
        document.querySelectorAll('.view-order').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                
                fetch(`get_order_details.php?order_id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        const modal = document.querySelector('#orderModal');
                        const modalBody = modal.querySelector('.modal-body');
                        
                        // Display order details
                        modalBody.innerHTML = `
                            <p><strong>Order ID:</strong> ${data.order_id}</p>
                            <p><strong>Customer:</strong> ${data.user_name}</p>
                            <p><strong>Room:</strong> ${data.room}</p>
                            <p><strong>Date:</strong> ${data.date}</p>
                            <hr>
                            <h6>Products:</h6>
                            <ul>
                                ${data.products.map(product => `
                                    <li>${product.product_name} x ${product.quantity}</li>
                                `).join('')}
                            </ul>
                        `;
                        
                        new bootstrap.Modal(modal).show();
                    });
            });
        });

        // Handle status filtering
        document.querySelector('#statusFilter').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const statusCell = row.querySelector('.status-select').value;
                if (!status || statusCell === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>