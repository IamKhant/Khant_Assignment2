<?php
session_name('Khant');
session_start();

$success_message = false;
$error_message = false;
$errors = [
    'scientific_name' => '',
    'common_name' => '',
    'family' => '',
    'genus' => '',
    'species' => '',
    'plant_image' => '',
    'description' => ''
];

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in and user type is "admin"
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "PlantBiodiversity");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Initialize input variables with empty values or POST data
$scientific_name = $_POST['scientific_name'] ?? '';
$common_name = $_POST['common_name'] ?? '';
$family = $_POST['family'] ?? '';
$genus = $_POST['genus'] ?? '';
$species = $_POST['species'] ?? '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($scientific_name)) $errors['scientific_name'] = "Scientific Name is required.";
    if (empty($common_name)) $errors['common_name'] = "Common Name is required.";
    if (empty($family)) $errors['family'] = "Family is required.";
    if (empty($genus)) $errors['genus'] = "Genus is required.";
    if (empty($species)) $errors['species'] = "Species is required.";

    // Validate file uploads
    $plant_image_path = null;
    if (!empty($_FILES['plant_image']['name'])) {
        $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['plant_image']['type'], $allowed_image_types)) {
            $errors['plant_image'] = "Only JPG, PNG, and GIF images are allowed.";
        } else {
            $image_name = time() . '_' . $_FILES['plant_image']['name'];
            $target_directory = 'img/contributeImg/';
            $target_file = $target_directory . $image_name;
            if (move_uploaded_file($_FILES['plant_image']['tmp_name'], $target_file)) {
                $plant_image_path = $target_file;
            } else {
                $errors['plant_image'] = "Failed to upload plant image.";
            }
        }
    }

    $description_path = null;
    if (!empty($_FILES['description']['name'])) {
        if ($_FILES['description']['type'] !== 'application/pdf') {
            $errors['description'] = "Description must be a PDF file.";
        } else {
            $pdf_name = time() . '_' . $_FILES['description']['name'];
            $target_directory = 'plants_description/';
            $target_file = $target_directory . $pdf_name;
            if (move_uploaded_file($_FILES['description']['tmp_name'], $target_file)) {
                $description_path = $target_file;
            } else {
                $errors['description'] = "Failed to upload description PDF.";
            }
        }
    }
    $status = 'approved';
    // Insert data if no errors
    if (!array_filter($errors)) {
        $sql = "INSERT INTO plant_table (scientific_name, common_name, family, genus, species, plants_image, description,status) 
                VALUES ('$scientific_name', '$common_name', '$family', '$genus', '$species', '$image_name', '$description_path','$status')";

        if ($mysqli->query($sql)) {
            $success_message = "New plant added successfully!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }
}

$mysqli->close();
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body>
    <?php include_once 'header.php'; ?>

    <div class="container mt-5" id="newplantcontainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Add New Plant</h2>

                <?php if ($success_message): ?>
                    <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                    <meta http-equiv="refresh" content="2;url=manage_plants.php">
                <?php elseif ($error_message): ?>
                    <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="add_new_plant.php" method="post" enctype="multipart/form-data" class="border p-4 rounded shadow-sm" id="addPlantForm">
                    <div class="mb-3">
                        <label for="scientific_name" class="form-label">Scientific Name</label>
                        <input type="text" class="form-control" id="scientific_name" name="scientific_name" value="<?php echo htmlspecialchars($scientific_name); ?>">
                        <small class="text-danger"><?php echo $errors['scientific_name']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="common_name" class="form-label">Common Name</label>
                        <input type="text" class="form-control" id="common_name" name="common_name" value="<?php echo htmlspecialchars($common_name); ?>">
                        <small class="text-danger"><?php echo $errors['common_name']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="family" class="form-label">Family</label>
                        <input type="text" class="form-control" id="family" name="family" value="<?php echo htmlspecialchars($family); ?>">
                        <small class="text-danger"><?php echo $errors['family']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="genus" class="form-label">Genus</label>
                        <input type="text" class="form-control" id="genus" name="genus" value="<?php echo htmlspecialchars($genus); ?>">
                        <small class="text-danger"><?php echo $errors['genus']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="species" class="form-label">Species</label>
                        <input type="text" class="form-control" id="species" name="species" value="<?php echo htmlspecialchars($species); ?>">
                        <small class="text-danger"><?php echo $errors['species']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="plant_image" class="form-label">Plant Image</label>
                        <input type="file" class="form-control" id="plant_image" name="plant_image" accept="image/*">
                        <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF</small>
                        <small class="text-danger"><?php echo $errors['plant_image']; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description (PDF)</label>
                        <input type="file" class="form-control" id="description" name="description" accept="application/pdf">
                        <small class="form-text text-muted">Allowed format: PDF</small>
                        <small class="text-danger"><?php echo $errors['description']; ?></small>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Add Plant</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                        <a href="manage_plants.php" class="btn btn-success">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>
</body>

</html>