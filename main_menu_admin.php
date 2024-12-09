<?php
session_name('Khant');
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in and user type is "admin"
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body id="admin_menu_body">
    <?php include_once 'admin_header.php'; ?>

    <div class="container-fluid admin-container text-center">
        <div class="mb-4">
            <h1>Admin Main Menu</h1>
            <p>Welcome, Admin! Choose an option below to manage the application.</p>
        </div>

        <div class="row justify-content-center">
    <!-- Manage Users Card -->
    <div class="col-md-6 col-lg-5 mb-4">
        <div class="menu-card">
            <img src="img/businessman-with-cloud-icons.jpg" alt="Manage Users" class="card-image">
            <h3>Manage Users</h3>
            <p>View, edit, or delete users registered on the platform.</p>
            <a href="manage_accounts.php" class="btn">Manage Users</a>
        </div>
    </div>

    <!-- Review Plant Contributions Card -->
    <div class="col-md-6 col-lg-5 mb-4">
        <div class="menu-card">
            <img src="img/woman-science-assistant-agricultural-officer-greenhouse-farm-research-melon.jpg" alt="Manage Plants" class="card-image">
            <h3>Review Plant Contributions</h3>
            <p>Review and approve plant contributions submitted by users.</p>
            <a href="manage_plants.php" class="btn">Manage Plants</a>
        </div>
    </div>
</div>
    </div>

    <?php include_once 'footer.php'; ?>
</body>

</html>