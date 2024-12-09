<?php
session_name('Khant');
session_start();

$success_message = false;
$error_message = false;
$errors = [];

// Enable error reporting
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Check if user is logged in and user type is "user"
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "PlantBiodiversity");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve approved plants data from the database
$query = "SELECT id, scientific_name, common_name, family, genus, species, plants_image FROM plant_table WHERE status = 'approved'";
$result = $mysqli->query($query);

// Check if form is submitted
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scientificName = isset($_POST['scientificName']) ? trim($_POST['scientificName']) : '';
    $commonName = isset($_POST['commonName']) ? trim($_POST['commonName']) : '';
    $family = isset($_POST['family']) ? trim($_POST['family']) : '';
    $genus = isset($_POST['genus']) ? trim($_POST['genus']) : '';
    $species = isset($_POST['species']) ? trim($_POST['species']) : '';

    // Validation checks
    if (empty($scientificName)) {
        $errors['scientificName'] = "Scientific Name is required.";
    }
    if (empty($commonName)) {
        $errors['commonName'] = "Common Name is required.";
    }
    if (empty($family)) {
        $errors['family'] = "Family is required.";
    }
    if (empty($genus)) {
        $errors['genus'] = "Genus is required.";
    }
    if (empty($species)) {
        $errors['species'] = "Species is required.";
    }

    // Validate plant image upload
    if (!empty($_FILES['plantImage']['name'])) {
        $imageName = basename($_FILES["plantImage"]["name"]);
        $targetDirImg = "img/contributeImg/";
        $targetFilePathImg = $targetDirImg . $imageName;

        if (!move_uploaded_file($_FILES["plantImage"]["tmp_name"], $targetFilePathImg)) {
            $errors['plantImage'] = "Error: Plant image upload failed.";
        }
    } else {
        $errors['plantImage'] = "Plant image is required.";
    }

    // Validate description file
    if (!empty($_FILES['descriptionFile']['name'])) {
        $descriptionFileName = basename($_FILES['descriptionFile']["name"]);
        $fileExtension = strtolower(pathinfo($descriptionFileName, PATHINFO_EXTENSION));
        $targetFilePathDesc = "plants_description/" . $descriptionFileName;

        if ($fileExtension !== 'pdf') {
            $errors['descriptionFile'] = "Description file must be a PDF.";
        } elseif ($_FILES['descriptionFile']['size'] > 7 * 1024 * 1024) {
            $errors['descriptionFile'] = "Description file must not exceed 7MB.";
        } elseif (!move_uploaded_file($_FILES["descriptionFile"]["tmp_name"], $targetFilePathDesc)) {
            $errors['descriptionFile'] = "Error: Description file upload failed.";
        }
    } else {
        $errors['descriptionFile'] = "Description file is required.";
    }

    // Insert into database if there are no errors
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO plant_table (scientific_name, common_name, family, genus, species, plants_image, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        if ($stmt) {
            $stmt->bind_param("sssssss", $scientificName, $commonName, $family, $genus, $species, $imageName, $targetFilePathDesc);

            if ($stmt->execute()) {
                $_SESSION['status'] = "Plant contribution successful! It will be reviewed soon.";
                $success_message = true;
            } else {
                $_SESSION['status'] = "Error: Could not save the contribution. " . $stmt->error;
            }
            $stmt->close();

            // Redirect to the same page after successful submission
            header("Location: contribute.php");
            exit;
        } else {
            die("Prepare failed: " . $mysqli->error);
        }
    } else {
        $error_message = true;
    }
}

// Display status message if available
// if (isset($_SESSION['status'])) {
//     echo "<p>" . $_SESSION['status'] . "</p>";
//     unset($_SESSION['status']); // Clear the status message after displaying it
// }
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include 'head.php'; ?>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5" id="contributeBody">
        <h1 class="text-center mb-3">Contributed Plants</h1>

        <div class="text-center mb-4">
            <button id="contributeBtn" class="btn btn-primary" data-toggle="modal" data-target="#contributeModal">Contribute Now</button>
        </div>
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center">
                Successfully submitted the plants! Admin will check and approve the plant. Please wait for admin approval.
            </div>
            <meta http-equiv="refresh" content="4;url=contribute.php" exit>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center">
                Error uploading plant. Please try again!
            </div>
            <meta http-equiv="refresh" content="3;url=contribute.php">
        <?php endif; ?>

        <!-- Plant Contribution Form Modal -->
        <div class="modal fade" id="contributeModal" tabindex="-1" aria-labelledby="contributeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contributeModalLabel">Contribute a New Plant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" id="contributeForm">
                            <div class="form-group">
                                <label for="scientificName">Scientific Name</label>
                                <input type="text" name="scientificName" class="form-control" value="<?= htmlspecialchars($scientificName ?? '') ?>" required>
                                <?php if (!empty($errors['scientificName'])): ?>
                                    <small class="text-danger"><?= $errors['scientificName'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="commonName">Common Name</label>
                                <input type="text" name="commonName" class="form-control" value="<?= htmlspecialchars($commonName ?? '') ?>" required>
                                <?php if (!empty($errors['commonName'])): ?>
                                    <small class="text-danger"><?= $errors['commonName'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="family">Family</label>
                                <input type="text" name="family" class="form-control" value="<?= htmlspecialchars($family ?? '') ?>" required>
                                <?php if (!empty($errors['family'])): ?>
                                    <small class="text-danger"><?= $errors['family'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="genus">Genus</label>
                                <input type="text" name="genus" class="form-control" value="<?= htmlspecialchars($genus ?? '') ?>" required>
                                <?php if (!empty($errors['genus'])): ?>
                                    <small class="text-danger"><?= $errors['genus'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="species">Species</label>
                                <input type="text" name="species" class="form-control" value="<?= htmlspecialchars($species ?? '') ?>" required>
                                <?php if (!empty($errors['species'])): ?>
                                    <small class="text-danger"><?= $errors['species'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="plantImage">Upload Plant Image</label>
                                <input type="file" name="plantImage" class="form-control-file" required>
                                <?php if (!empty($errors['plantImage'])): ?>
                                    <small class="text-danger"><?= $errors['plantImage'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="descriptionFile">Upload Description File (PDF only)</label>
                                <input type="file" name="descriptionFile" class="form-control-file" required>
                                <?php if (!empty($errors['descriptionFile'])): ?>
                                    <small class="text-danger"><?= $errors['descriptionFile'] ?></small>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-success" id="submitPlantBtn">Submit Plant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display approved contributed plants from the database -->
        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($plant = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card contriCard">
                            <img src="img/contributeImg/<?= htmlspecialchars($plant['plants_image'] ?? '') ?>" class="img-fluid mt-4 mb-4" alt="<?= htmlspecialchars($plant['scientific_name'] ?? 'Plant Image') ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($plant['scientific_name'] ?? 'No Scientific Name') ?></h5>
                                <p class="card-text"><em><?= htmlspecialchars($plant['common_name'] ?? 'No Common Name') ?></em></p>
                                <a href="plant_detail.php?id=<?= htmlspecialchars($plant['id'] ?? '') ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No approved plants contributed yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php' ?>
</body>

</html>

<?php
$mysqli->close();
?>