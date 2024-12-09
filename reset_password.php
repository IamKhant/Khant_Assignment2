<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_name('Khant');
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PlantBiodiversity";

// Establish a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$message = '';
$success_message = false;
$step = 1; // Step 1: Ask for email

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Step 1: Verify Email
        $email = trim($_POST['email']);
        $stmt = $conn->prepare("SELECT contact_number, hometown FROM user_table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, save data in session and proceed to step 2
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_data'] = $result->fetch_assoc();
            $step = 2;
        } else {
            $message = "No account found with this email address.";
        }
    } elseif (isset($_POST['contact_number']) && isset($_POST['hometown'])) {
        // Step 2: Verify Contact Number and Hometown
        $contact_number = trim($_POST['contact_number']);
        $hometown = trim($_POST['hometown']);

        if (
            $contact_number === $_SESSION['reset_data']['contact_number'] &&
            strtolower($hometown) === strtolower($_SESSION['reset_data']['hometown'])
        ) {
            // Details are correct, proceed to step 3
            $step = 3;
        } else {
            $message = "Contact number or hometown does not match.";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        // Step 3: Update Password
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($new_password === $confirm_password && strlen($new_password) >= 6) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE account_table SET hashed_password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $_SESSION['reset_email']);
            if ($stmt->execute()) {
                // Clear session data
                unset($_SESSION['reset_email'], $_SESSION['reset_data']); 
                $success_message = true;
            
                // Redirect to login page with success message
                // // header("Location: login.php?message=Password successfully updated! Please log in.");
                // exit();
            } else {
                $message = "Failed to update the password. Please try again.";
            }
        } else {
            $message = "Passwords do not match or do not meet the length requirement.";
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
    <title>Reset Password</title>
    <?php include 'head.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container reset-password-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Reset Password</h3>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success text-center">
                                Successfully reset the password!Please proceed to login.
                            </div>
                            <meta http-equiv="refresh" content="2;url=login.php">

                        <?php endif; ?>
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info text-center">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($step === 1): ?>
                            <!-- Step 1: Email Input -->
                            <form action="reset_password.php" method="post">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Enter Your Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </form>
                        <?php elseif ($step === 2): ?>
                            <!-- Step 2: Contact Number and Hometown -->
                            <form action="reset_password.php" method="post">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Enter Your Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="hometown" class="form-label">Enter Your Hometown</label>
                                    <input type="text" class="form-control" id="hometown" name="hometown" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </form>
                        <?php elseif ($step === 3): ?>
                            <!-- Step 3: New Password -->
                            <form action="reset_password.php" method="post">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Enter New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" placeholder="new password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password" placeholder="Confirm new password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>