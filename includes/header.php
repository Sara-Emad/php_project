<!DOCTYPE html>
<html>
<head>
    <title>Cafe Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                <a href="checks.php">Checks</a>
            <?php endif; ?>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </nav>