<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive E-shop</title>

    <!-- Bootstrap CSS (latest version) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (latest version) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (required for Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS (latest version) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Font Awesome (latest version) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- AOS (Animate On Scroll library, latest version) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Local CSS -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/categories.css" rel="stylesheet">
    <link href="assets/css/carousel.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    <link href="assets/css/featured-products.css" rel="stylesheet">
</head>

<body>
    <!-- headers -->
    <?php include('partials/headers.php'); ?>

    <!-- carousel secton -->
    <div class="container carousels">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/images/banners/banner-1.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/banners/banner-2.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/banners/banner-3.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- categories section -->
    <?php include('partials/categories.php'); ?>

    <!-- Featured Products Section -->

    <?php include('partials/featured-products.php'); ?>

    <section class="container-fluid text-center my-5">
        <h2>Why choose us</h2>
        <div class="row mt-4">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h4 class="feature-title">Easy Navigation</h4>
                <p class="feature-text">Our website is user-friendly and easy to navigate, ensuring a seamless shopping experience.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h4 class="feature-title">Secure Payment</h4>
                <p class="feature-text">Your payment information is securely processed to ensure a safe and seamless transaction process.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 class="feature-title">Free Shipping</h4>
                <p class="feature-text">Enjoy the convenience of free shipping on all orders.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h4 class="feature-title">Easy Returns</h4>
                <p class="feature-text">Our straightforward return policy ensures that if you're not completely satisfied with your purchase, sending it back is easy and stress-free.</p>
            </div>
        </div>
    </section>
    

    <!-- footer section -->
    <?php include('partials/footers.php'); ?>

    <!-- back to top button -->
    <?php include('partials/back-to-top.php'); ?>

</body>
    <script src="assets/js/main.js"></script>
</html>