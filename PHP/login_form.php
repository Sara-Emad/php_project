<?php
session_start();
require_once 'Database.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $db = new Database();
        $users = $db->select('users');

        $user = null;
        foreach ($users as $u) {
            if ($u['email'] === $email) {
                $user = $u;
                break;
            }
        }

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: welcome.php");
            exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                        <a class="nav-link" href="about.php">About</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark bg-opacity-75 p-4 rounded">
                    <div class="card-header">
                        <h3 class="text-center mb-4 text-warning">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p><?php echo $error; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form id="loginForm" class="text-white" action="login_form.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <a class="text-warning" href="adduser.php">create account?</a>
                            <br>
                            <br>
                            <input type="submit" class="btn btn-warning w-100" value="Login">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value.trim();
    var isValid = true;

    var errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(function(error) {
        error.remove();
    });

    if (email === '') {
        showError('email', 'Email is required.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email', 'Invalid email format.');
        isValid = false;
    }

    if (password === '') {
        showError('password', 'Password is required.');
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
});

function showError(fieldId, message) {
    var field = document.getElementById(fieldId);
    var error = document.createElement('div');
    error.className = 'error-message text-danger mt-1';
    error.innerText = message;
    field.parentNode.appendChild(error);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>