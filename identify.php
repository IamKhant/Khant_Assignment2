<?php
session_name('Khant');
session_start();
// Check if the user is logged in, otherwise redirect to the login page (index.php)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body>
    <?php include_once 'header.php'; ?>
    <div class="container identify_container">
        <h1 class="text-center mb-4">Plant Identification</h1>

        <div class="card shadow-lg p-4">
            <form method="POST" action="process_identify.php" enctype="multipart/form-data">

                <!-- Project Selection -->
                <div class="form-group">
                    <label for="project"><strong>Project:</strong></label>
                    <select class="form-control" id="project" name="project" required>
                        <option value="the-plant-list">World Flora</option>
                        <option value="weurope">Western Europe</option>
                    </select>
                </div>

                <!-- Image Upload -->
                <div class="form-group">
                    <label for="image"><strong>Upload Image:</strong></label>
                    <input type="file" class="form-control-file" id="image" name="image" required>
                </div>

                <!-- Organ Selection -->
                <div class="form-group">
                    <label for="organ"><strong>Organ:</strong></label>
                    <select class="form-control" id="organ" name="organ" required>
                        <option value="flower">Flower</option>
                        <option value="leaf">Leaf</option>
                        <option value="fruit">Fruit</option>
                        <option value="bark">Bark</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">Identify!</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <?php include_once 'footer.php'; ?>
</body>

</html>