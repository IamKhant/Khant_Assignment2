<?php
session_name('Khant');
session_start();
// Check if the user is logged in, otherwise redirect to the login page (index.php)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'user') {
    header("location: login.php");
    exit;
}
?>

<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>

<?php include 'head.php'; ?>

<body>

    <!-- Navigation Bar -->
    <?php include 'header.php'; ?>
    <!-- Main Content -->
    <div class="mainmenu_container">
        <h2 class="text-center mt-4 mb-4">Main Menu</h2>
        <div class="row">
            <!-- Plants Classification Card -->
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <img src="img/plantClassifi.jpg" class="card-img-top" alt="Plants Classification Image">
                    <div class="card-body">
                        <h5 class="card-title">Plants Classification</h5>
                        <p class="card-text">This page will help you to learn about plant family, genus, and species classifications.</p>
                        <a href="classify.php" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Tutorial Card -->
            <div class="col-md-6">
                <div class="card shadow">
                    <img src="img/tutorial.jpg" class="card-img-top" alt="Tutorial Image">
                    <div class="card-body">
                        <h5 class="card-title">Tutorial</h5>
                        <p class="card-text">In this page, you can learn how to transfer a fresh leaf into herbarium specimens.</p>
                        <a href="tutorial.php" class="btn btn-primary">Start Tutorial</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Identify Card -->
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <img src="img/plantt_identify.webp" class="card-img-top" alt="Identify Plant Image">
                    <div class="card-body">
                        <h5 class="card-title">Identify</h5>
                        <p class="card-text">The page where you can upload a plant photo to identify the plant type and details.</p>
                        <a href="identify.php" class="btn btn-primary">Identify Plant</a>
                    </div>
                </div>
            </div>

            <!-- Contribution Card -->
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <img src="img/plant_Contribute.png" class="card-img-top" alt="Contribution Image">
                    <div class="card-body">
                        <h5 class="card-title">Contribution</h5>
                        <p class="card-text">Contribute plant data to the database by uploading images and plant specifications.</p>
                        <a href="contribute.php" class="btn btn-primary">Contribute Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'; ?>

</html>