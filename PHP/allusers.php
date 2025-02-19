<?php
require 'database.php'; // Include your database connection
$db = new Database(); // Create a new instance of the Database class

// Initialize the $users variable
$users = $db->select("users"); // Fetch all users from the database

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    if ($db->delete("users", $userId)) {
        // Refresh the users list after deletion
        $users = $db->select("users");
        $successMessage = "User deleted successfully";
    } else {
        $errorMessage = "Failed to delete user";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'allusers.php?action=delete&user_id=' + userId;
            }
        }
    </script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Cafe System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="welcome(admin).php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adduser.php">Add User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="allproducts.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checks.php">checks</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Users List</h2>

        <!-- Display success or error messages -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Room</th>
                    <th>Extension</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['room']) ?></td>
                            <td><?= htmlspecialchars($user['ext']) ?></td>
                            <td>
                                <a href="update_user.php?id=<?= $user['user_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <button onclick="confirmDelete(<?= $user['user_id'] ?>)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>