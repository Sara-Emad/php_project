<?php
require 'database.php'; // Include your database connection
$db = new Database(); // Create a new instance of the Database class

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    if ($db->delete("users", $userId)) {
        // Refresh the users list
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
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Users List</h2>
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
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
