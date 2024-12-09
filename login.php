<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_name('Khant');
session_start();

// Redirect if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['type'] === 'admin') {
        // If the user is an admin, redirect to the admin menu page
        header("Location: main_menu_admin.php");
    } else {
        // If the user is a regular user, redirect to the user menu page
        header("Location: main_menu.php");
    }
    exit();
}
require_once 'main.php';
// Initialize messages
$message = '';
$success_message_user = false;
$success_message_admin = false;


// // Database connection details
// $servername = "localhost";
// $username = "root";
// $password = '';
// $dbname = "PlantBiodiversity";

// // Establish a database connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check for connection errors
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Email validation function
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Retrieve user by email function
function get_user_by_email($email, $conn) {
    $sql = "
        SELECT a.email, a.hashed_password, a.type, u.first_name, u.last_name
        FROM account_table AS a
        JOIN user_table AS u ON a.email = u.email
        WHERE a.email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!validate_email($email)) {
        $message = 'Invalid email format.';
    } else {
        $user = get_user_by_email($email, $conn);

        if ($user) {
            if (password_verify($password, $user['hashed_password'])) {
                // Store user information in session
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $user['email'];
                $_SESSION['type'] = $user['type'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];

                // Redirect based on user type
                if ($user['type'] === 'admin') {
                    $success_message_admin = true;
                } else {
                    $success_message_user = true;
                }
            } else {
                $message = 'Incorrect password. Please try again.';
            }
        } else {
            $message = 'No account found with that email address.';
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php include 'head.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Please Log In</h3>

                        <?php if ($success_message_user): ?>
                            <div class="alert alert-success text-center">
                                Successfully logged in as a user! Redirecting to main menu.
                            </div>
                            <meta http-equiv="refresh" content="2;url=main_menu.php">

                        <?php endif; ?>

                        <?php if ($success_message_admin): ?>
                            <div class="alert alert-success text-center">
                                Successfully logged in as an admin! Redirecting to admin main menu.
                            </div>
                            <meta http-equiv="refresh" content="2;url=main_menu_admin.php">

                        <?php endif; ?>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Forgot you password? <a href="reset_password.php" class="text-primary">Reset Password</a></p>
                        </div>
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="registration.php" class="text-primary">Register</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>