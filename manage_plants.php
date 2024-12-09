<?php
session_name('Khant');
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$success_message = false;
$error_message = false;

// Check if the user is logged in and has 'admin' access
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'admin') {
    header("location: login.php");
    exit;
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "PlantBiodiversity";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve and reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $plant_id = $_GET['id'];
    $action = $_GET['action'];
    $new_status = $action === 'approve' ? 'approved' : 'rejected';

    // Update plant status based on action
    $stmt = $conn->prepare("UPDATE plant_table SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $plant_id);

    if ($stmt->execute()) {
        $success_message = ($action === 'approve') ? "Plant approved successfully!" : "Plant rejected successfully!";
    } else {
        $error_message = "Error updating plant status.";
    }

    $stmt->close();
    header("Location: manage_plants.php");
    exit;
}

// Delete plant record
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM plant_table WHERE id=?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $success_message = "Plant deleted successfully!";
    } else {
        $error_message = "Error deleting plant.";
    }

    $stmt->close();
    header("Location: manage_plants.php");
    exit;
}

// Fetch all plants data with description field
$query = "SELECT id, scientific_name, common_name, family, genus, species, status, description FROM plant_table";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body id="manage_plants_body">
    <?php include_once 'header.php'; ?>

    <main class="container py-5" id="manage_plants_main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">Manage Plants</h1>
            <a href="main_menu_admin.php" class="btn btn-secondary">Back to Main Menu</a>
        </div>

        <!-- Display success or error messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Plant Records Table -->
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Plant ID</th>
                        <th>Scientific Name</th>
                        <th>Family</th>
                        <th>Genus</th>
                        <th>Species</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['scientific_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['family']); ?></td>
                                <td><?php echo htmlspecialchars($row['genus']); ?></td>
                                <td><?php echo htmlspecialchars($row['species']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php elseif ($row['status'] == 'rejected'): ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['description'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['description']); ?>" target="_blank" class="btn btn-link btn-sm">View</a>
                                    <?php else: ?>
                                        <span>No description</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php if ($row['status'] == 'pending'): ?>
                                            <a href="manage_plants.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                            <a href="manage_plants.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Reject</a>
                                        <?php elseif ($row['status'] == 'approved'): ?>
                                            <button class="btn btn-success btn-sm" disabled>Approved</button>
                                            <a href="manage_plants.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Reject</a>
                                        <?php else: ?>
                                            <a href="manage_plants.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                            <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                                        <?php endif; ?>
                                        <a href="manage_plants.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plant?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No plant records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Add New Plant Button -->
        <div class="text-center mt-4">
            <a href="add_new_plant.php" class="btn btn-primary">Add New Plant</a>
        </div>
    </main>

    <?php include_once 'footer.php'; ?>
</body>

</html>