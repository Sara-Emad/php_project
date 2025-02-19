<?php

// session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login_form.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/style/home.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">Cafeteria</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="allusers.php">All Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success me-2" href="createorder(admin).php">Create Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1 class="text-warning">Welcome to Cafeteria</h1>
        </div>
    </section>

    <section id="about" class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img src="../assets/images/about.jpg" alt="About Us" class="img-fluid rounded">
                </div>
                <div class="col-lg-6">
                    <h2>About Us</h2>
                    <p>We are a passionate team dedicated to providing high-quality products and exceptional
                        services. Our mission is to [Your Mission]. We believe in [Your Values]. We've been serving
                        the community since [Year Established] and have built a reputation for
                        [Your Achievements/Strengths]. Learn more about our team and our commitment to
                        excellence.</p>
                    <a href="#" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="advantages">
        <div class="container">
            <h2 class="text-center">Why Choose Us?</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body text-center"> <i class="fas fa-award fa-3x mb-3"></i>
                            <h5 class="card-title">Quality Service</h5>
                            <p class="card-text">We provide top-notch services that meet your expectations and exceed
                                them.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5 class="card-title">Expert Team</h5>
                            <p class="card-text">Our team consists of highly experienced and skilled professionals.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-headset fa-3x mb-3"></i>
                            <h5 class="card-title">Customer Support</h5>
                            <p class="card-text">We offer 24/7 customer support to assist you with any questions or
                                concerns.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
