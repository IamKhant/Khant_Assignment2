<?php
session_name('Khant');
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$plantId = isset($_GET['id']) ? $_GET['id'] : null;

// Database connection details
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "PlantBiodiversity";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get plant details, including description
$sql = "SELECT scientific_name, common_name, family, genus, species, plants_image, description FROM plant_table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plantId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $plantData = $result->fetch_assoc();
} else {
    echo "Plant not found.";
    exit;
}

// Close the database connection
$conn->close();
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php' ?>

<body>
    <?php include_once 'header.php' ?>
    <div class="container plantDetailContainer">
        <h1 class="text-center mb-5"><?= htmlspecialchars($plantData['scientific_name']) ?> - <em><?= htmlspecialchars($plantData['common_name']) ?></em></h1>
        <div class="row">
            <div class="col-md-12" id="detailsImage">
                <p><strong>Family:</strong> <?= htmlspecialchars($plantData['family']) ?></p>
                <p><strong>Genus:</strong> <?= htmlspecialchars($plantData['genus']) ?></p>
                <p><strong>Species:</strong> <?= htmlspecialchars($plantData['species']) ?></p>

                <!-- Display the image or a placeholder if image is not available -->
                <img src="img/contributeImg/<?= htmlspecialchars($plantData['plants_image'] ?? 'placeholder.jpg') ?>"
                    class="detail-img"
                    alt="<?= htmlspecialchars($plantData['scientific_name']) ?>">


            </div>
        </div>

        <!-- this is generate pdf part -->
        <form action="download_pdf.php" method="POST" class="mt-3">
            <input type="hidden" name="scientificName" value="<?= htmlspecialchars($plantData['scientific_name']) ?>">
            <input type="hidden" name="commonName" value="<?= htmlspecialchars($plantData['common_name']) ?>">
            <input type="hidden" name="family" value="<?= htmlspecialchars($plantData['family']) ?>">
            <input type="hidden" name="genus" value="<?= htmlspecialchars($plantData['genus']) ?>">
            <input type="hidden" name="species" value="<?= htmlspecialchars($plantData['species']) ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($plantData['plants_image']) ?>">
            <input type="hidden" name="description" value="<?= htmlspecialchars($plantData['description']) ?>">
            <button type="submit" class="btn btn-success downloadButton">Genereate Pdf</button>
            
        </form>

        <!-- Display the plant description -->
        <p class="mt-3"><strong>Description:</strong> <?= htmlspecialchars($plantData['description'] ?? 'No description available.') ?></p>

        <?php if (!empty($plantData['description']) && $plantData['description'] !== 'No description available.'): ?>
            <!-- Form to download plant description file -->
            <form action="download_description.php" method="GET" class="mt-3">
                <input type="hidden" name="descriptionFile" value="<?= htmlspecialchars($plantData['description']) ?>">
                <button type="submit" class="btn btn-warning downloadButton">Download Description</button>
            </form>
        <?php endif; ?>
        <br>
        <a href="contribute.php" class="btn btn-primary">Back to Contribution</a>
    </div>
    <br><br><br>
    <?php include_once 'footer.php' ?>
</body>

</html>