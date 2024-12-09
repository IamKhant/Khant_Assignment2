<?php
session_name('Khant');
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$success_message = false;
$error_message = null;

// Get user email from session
$email = $_SESSION['email'];

// Database connection
$mysqli = new mysqli("localhost", "root", "", "PlantBiodiversity");
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Fetch user data
$stmt = $mysqli->prepare("SELECT first_name, last_name, dob, gender, hometown, student_id, profile_image, contact_number FROM user_table WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($firstName, $lastName, $dob, $gender, $hometown, $studentId, $profileImage, $contactNumber);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $updatedFirstName = htmlspecialchars(trim($_POST['first_name']));
    $updatedLastName = htmlspecialchars(trim($_POST['last_name']));
    $updatedDOB = $_POST['dob'];
    $updatedGender = $_POST['gender'];
    $updatedStudentId = htmlspecialchars(trim($_POST['student_id']));
    $updatedHometown = htmlspecialchars(trim($_POST['hometown']));
    $updatedEmail = htmlspecialchars(trim($_POST['email']));
    $updatedContactNumber = htmlspecialchars(trim($_POST['contact_number']));
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $profileImagePath = $profileImage;
    $resumePath = null;

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
        $imageType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $imageName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);

        if (in_array($imageType, ['jpg', 'jpeg', 'png']) && $_FILES['profile_image']['size'] <= 5 * 1024 * 1024) {
            $profileImagePath = 'img/profile_images/' . $imageName . '.' . $imageType;
            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath)) {
                $error_message = "Failed to upload profile image.";
            }
        } else {
            $error_message = "Invalid image format or file size exceeds 5MB.";
        }
    }

    // Handle resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['size'] > 0) {
        $resumeType = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        $resumeName = pathinfo($_FILES['resume']['name'], PATHINFO_FILENAME);

        if ($resumeType === 'pdf' && $_FILES['resume']['size'] <= 7 * 1024 * 1024) {
            $resumePath = 'resume/' . $resumeName  . '.pdf';
            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)) {
                $error_message = "Failed to upload resume.";
            }
        } else {
            $error_message = "Invalid resume format or file size exceeds 7MB.";
        }
    }

    // Validate and update password
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if ($newPassword !== $confirmPassword) {
            $error_message = "Passwords do not match.";
        } elseif (strlen($newPassword) < 6) {
            $error_message = "Password must be at least 6 characters.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE account_table SET hashed_password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Update user information in database
    if (!$error_message) {
        $stmt = $mysqli->prepare("UPDATE user_table SET first_name = ?, last_name = ?, dob = ?, gender = ?, hometown = ?, student_id = ?, profile_image = ?, resume_path = ?, contact_number = ? WHERE email = ?");
        $stmt->bind_param("ssssssssss", $updatedFirstName, $updatedLastName, $updatedDOB, $updatedGender, $updatedHometown, $updatedStudentId, $profileImagePath, $resumePath, $updatedContactNumber, $email);
        $stmt->execute();
        $stmt->close();

        // Update email in both tables if it has changed
        if ($updatedEmail !== $email) {
            $mysqli->begin_transaction();
            try {
                $stmt = $mysqli->prepare("UPDATE user_table SET email = ? WHERE email = ?");
                $stmt->bind_param("ss", $updatedEmail, $email);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare("UPDATE account_table SET email = ? WHERE email = ?");
                $stmt->bind_param("ss", $updatedEmail, $email);
                $stmt->execute();
                $stmt->close();

                $mysqli->commit();
                $_SESSION['email'] = $updatedEmail;
            } catch (Exception $e) {
                $mysqli->rollback();
                $error_message = "Error updating email: " . $e->getMessage();
            }
        }

        if (!$error_message) {
            $success_message = true;
        }
    }
}

?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
    <?php include 'head.php'; ?>
<body>
    <?php include 'header.php'; ?>

    <main class="container mt-5" id="update_profile_container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 shadow-lg">
                    <h1 class="text-center">Update Profile</h1>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success text-center">
                            Profile updated successfully!
                        </div>
                        <meta http-equiv="refresh" content="2;url=profile.php">
                    <?php elseif ($error_message): ?>
                        <div class="alert alert-danger text-center"><?= $error_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                    <div class="form-group text-center">
                                <?php
                                $displayImagePath = !empty($profileImage) ? $profileImage : (($gender === 'Male') ? 'img/profile_images/boys.jpg' : 'img/profile_images/girl.png');
                                ?>
                                <img src="<?= $displayImagePath ?>" alt="Profile Image" class="img-fluid rounded-circle mt-2 mb-2" id="update-profile-img">
                            </div>


                            <div class="form-group">
                                <label for="email" class="font-weight-bold">Email:</label>
                                <input type="email" name="email" value="<?= $email ?>" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="font-weight-bold">First Name:</label>
                                        <input type="text" name="first_name" value="<?= $firstName ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="font-weight-bold">Last Name:</label>
                                        <input type="text" name="last_name" value="<?= $lastName ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob" class="font-weight-bold">Date of Birth:</label>
                                        <input type="date" name="dob" value="<?= $dob ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="font-weight-bold">Gender:</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_id" class="font-weight-bold">Student ID:</label>
                                        <input type="text" name="student_id" value="<?= $studentId ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hometown" class="font-weight-bold">Hometown:</label>
                                        <input type="text" name="hometown" value="<?= $hometown ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Number Field -->
                            <div class="form-group">
                                <label for="contact_number" class="font-weight-bold">Contact Number:</label>
                                <input type="text" name="contact_number" value="<?= $contactNumber ?>" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password" class="font-weight-bold">New Password:</label>
                                        <input type="password" name="new_password" class="form-control" autocomplete="new-password" placeholder="Enter to reset password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="font-weight-bold">Confirm Password:</label>
                                        <input type="password" name="confirm_password" class="form-control" autocomplete="new-password" placeholder="Confirm new password">                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profile_image" class="font-weight-bold">Profile Image:</label>
                                <input type="file" name="profile_image" class="form-control-file mt-2" accept="image/jpeg, image/png">
                            </div>

                            <div class="form-group">
                                <label for="resume" class="font-weight-bold">Resume (PDF):</label>
                                <input type="file" name="resume" class="form-control-file mt-2" accept="application/pdf">
                            </div>

                            <div class="form-group d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-success btn-lg mx-2">Update Profile</button>
                                <a href="profile.php" class="btn btn-secondary btn-lg mx-2">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>