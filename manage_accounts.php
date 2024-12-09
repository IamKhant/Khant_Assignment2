<?php
session_name('Khant');
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$success_message = false;
$error_message = false;

// Check if user is logged in and user type is "admin"
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'admin') {
    header("Location: login.php");
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

// Handle user deletion
if (isset($_GET['delete_email'])) {
    $email = $_GET['delete_email'];

    // Delete from account_table first due to foreign key constraint
    $delete_account_sql = "DELETE FROM account_table WHERE email = ?";
    $stmt_account = $conn->prepare($delete_account_sql);
    $stmt_account->bind_param("s", $email);

    if ($stmt_account->execute()) {
        // Delete from user_table if account_table delete was successful
        $delete_user_sql = "DELETE FROM user_table WHERE email = ?";
        $stmt_user = $conn->prepare($delete_user_sql);
        $stmt_user->bind_param("s", $email);

        if ($stmt_user->execute()) {
            $success_message = true;
        } else {
            $error_message = true;
        }
        $stmt_user->close();
    } else {
        $error_message = true;
    }

    $stmt_account->close();
}

// Fetch all users from the database
$sql = "SELECT user_table.first_name, user_table.last_name, user_table.contact_number, account_table.email, account_table.type
        FROM user_table
        JOIN account_table ON user_table.email = account_table.email";
$result = $conn->query($sql);
?>


<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body id="manage_accounts_body">
    <?php include_once 'admin_header.php'; ?>

    <div class="container mt-4" id="manage_user_container">
        <!-- Header Section -->
        <div class="text-center mb-4">
            <h1 class="text-primary">Manage User Accounts</h1>
            <p class="text-secondary">Add, edit, or delete user accounts easily.</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center">
                <strong>Success!</strong> User deleted successfully.
            </div>
            <meta http-equiv="refresh" content="2;url=manage_accounts.php">
        <?php elseif ($error_message): ?>
            <div class="alert alert-danger text-center">
                <strong>Error!</strong> Failed to delete the user. Please try again.
            </div>
        <?php endif; ?>

        <!-- User Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4>User List</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?php echo $row['contact_number']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo ucfirst($row['type']); ?></td>
                                    <td>
                                        <a href="edit_user.php?email=<?php echo $row['email']; ?>"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage_accounts.php?delete_email=<?php echo $row['email']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center">
            <a href="add_user.php" class="btn btn-primary mr-2">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
            <a href="main_menu_admin.php" class="btn btn-success">
                <i class="fas fa-arrow-circle-left"></i> Back to Main Menu
            </a>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>
</body>

</html>