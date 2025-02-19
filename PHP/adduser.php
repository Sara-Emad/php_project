<?php
require_once 'Database.php';

$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $ext = trim($_POST['ext']);
    $room = trim($_POST['room']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = trim($_POST['role']);

    $users = $db->select('users');
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo "<div class='alert alert-danger text-center'>Email is already registered!</div>";
            exit;
        }
    }

    $data = [
        "name" => $name,
        "email" => $email,
        "room" => $room,
        "ext" => $ext,
        "password" => $password,
        "role" => $role
    ];

    if ($db->insert("users", $data)) {
        echo "<div class='alert alert-success text-center'>Registration successful!</div>";
        header('Location: login_form.php');
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>An error occurred during registration!</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/style/addproduct.css">
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
                        <a class="nav-link" href="my_orders.php">My order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form
                    id="userForm"
                    class="bg-dark text-white bg-opacity-75 p-4 rounded"
                    method="POST"
                    action="adduser.php"
                >
                    <h2 class="text-center mb-4 text-warning">Add New User</h2>
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
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room</label>
                        <input type="text" class="form-control" name="room">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extension</label>
                        <input type="text" class="form-control" name="ext">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <a href="login_form.php">Have an account?</a>
                    <br>
                    <br>
                    <div class="d-grid">
                        <input type="submit" class="btn btn-warning w-100" value="Create account">
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Creating user...';
    
    fetch('process_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert('User created successfully! Please login.');
            window.location.href = 'index.php'; // Redirect to login page
        } else {
            alert(data.message || 'Error creating user');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the user');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
});
    </script> -->
</body>
</html>