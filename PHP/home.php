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
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login_form.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary mx-2" href="adduser.php">Sign up</a>
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
    
    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Cafe Street, Food City</li>
                        <li class="my-2"><i class="fas fa-phone me-2"></i>+20 1223697400</li>
                        <li><i class="fas fa-envelope me-2"></i>info@cafeteria.com</li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="home.php" class="text-white text-decoration-none">Home</a></li>
                        <li class="my-2"><a href="#about" class="text-white text-decoration-none">About</a></li>
                        <li><a href="login_form.php" class="text-white text-decoration-none">Login</a></li>
                        <li class="my-2"><a href="adduser.php" class="text-white text-decoration-none">Sign Up</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="col-md-4 mb-4">
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        <a href="https://www.linkedin.com/in/peter-mahfouz-8342bb166/" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="https://www.linkedin.com/in/mohamed-elshemy-aaa804184?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="https://www.linkedin.com/in/amro-h-farouq-44b8652a9/" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="https://www.linkedin.com/in/sara-3433a9287/?locale=ar_AE" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="https://www.linkedin.com/in/sherifabdelbast?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row pt-3 border-top">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2023 Cafeteria. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>