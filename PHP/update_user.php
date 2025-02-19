<?php
require 'database.php'; // Include your database connection

$db = new Database(); // Create database instance

// Get user ID from the URL
if (!isset($_GET['id'])) {
    die("Invalid user ID.");
}
$userId = $_GET['id']; // Retrieve user_id

// Fetch user data
$user = $db->select("users WHERE user_id = $userId"); 

if (!$user) {
    die("User not found.");
}

$user = $user[0]; // Since select returns an array, get the first result

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $room = $_POST['room'];
    $ext = $_POST['ext'];

    $updateData = [
        'name' => $name,
        'room' => $room,
        'ext' => $ext
    ];

    if ($db->update("users", $updateData, $userId)) {
        header("Location: allusers.php?success=User updated successfully");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Update User</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Room</label>
                <input type="text" name="room" class="form-control" value="<?= htmlspecialchars($user['room']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Extension</label>
                <input type="text" name="ext" class="form-control" value="<?= htmlspecialchars($user['ext']) ?>" required>
            </div>
            <button type="submit" href="allusers.php" class="btn btn-success">Update User</button>
            <a href="allusers.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
