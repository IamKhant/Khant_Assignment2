<?php
session_name('Khant');
session_start();
$success_message = false;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and has 'admin' access
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['type'] !== 'admin') {
    header("location: login.php");
    exit;
}

// Check if email is provided
if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo "User email is required.";
    exit;
}

$email = $_GET['email'];

// Database connection
$mysqli = new mysqli("localhost", "root", '', "PlantBiodiversity");

// Load user data from the database
$stmt = $mysqli->prepare("
    SELECT user_table.first_name, user_table.last_name, user_table.dob, user_table.gender, 
           user_table.hometown, user_table.student_id, user_table.profile_image, 
           user_table.contact_number, account_table.type, account_table.hashed_password
    FROM user_table 
    INNER JOIN account_table ON user_table.email = account_table.email 
    WHERE user_table.email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($firstName, $lastName, $dob, $gender, $hometown, $studentId, $profileImage, $contactNumber, $type, $hashedPassword);
$stmt->fetch();
$stmt->close();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated data from the form
    $updatedFirstName = $_POST['first_name'];
    $updatedLastName = $_POST['last_name'];
    $updatedDOB = $_POST['dob'];
    $updatedGender = $_POST['gender'];
    $updatedStudentId = $_POST['student_id'];
    $updatedHometown = $_POST['hometown'];
    $updatedEmail = $_POST['email'];
    $updatedContactNumber = $_POST['contact_number'];
    $profileImagePath = $profileImage;
    $updatedType = $_POST['type']; // Get the updated user type
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];


    // Validate and upload profile image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
        $fileType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $originalFileName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);
        if (in_array($fileType, ['jpg', 'jpeg', 'png']) && $_FILES['profile_image']['size'] <= 5 * 1024 * 1024) {
            $profileImagePath = 'img/profile_images/' . $originalFileName . '.' . $fileType;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath)) {
                $stmt = $mysqli->prepare("UPDATE user_table SET profile_image=? WHERE email=?");
                $stmt->bind_param("ss", $profileImagePath, $email);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Validate and upload resume
    $resumePath = null; // Initialize resume path
    if (isset($_FILES['resume']) && $_FILES['resume']['size'] > 0) {
        $resumeType = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        $originalResumeName = pathinfo($_FILES['resume']['name'], PATHINFO_FILENAME);
        $resumeSize = $_FILES['resume']['size'];

        if ($resumeType === 'pdf' && $resumeSize <= 7 * 1024 * 1024) {
            $resumePath = 'resume/' . $originalResumeName . '.pdf';
            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)) {
                $error_message = "Failed to upload resume. Check directory permissions and ensure 'resume' folder exists.";
            }
        } else {
            $error_message = "Invalid resume format or file size exceeds 7MB.";
        }
    }

    // Validate and update password (only if both fields are filled)
    if (!empty($newPassword) && !empty($confirmPassword)) {
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
    } // Only update password if both fields are provided


    // Update user data in the database
    if (!isset($error_message)) {
        $stmt = $mysqli->prepare("UPDATE user_table SET first_name=?, last_name=?, dob=?, gender=?, hometown=?, student_id=?, profile_image=?, resume_path=?, contact_number=? WHERE email=?");
        $stmt->bind_param("ssssssssss", $updatedFirstName, $updatedLastName, $updatedDOB, $updatedGender, $updatedHometown, $updatedStudentId, $profileImagePath, $resumePath, $updatedContactNumber, $email);
        $stmt->execute();
        $stmt->close();

        // Update the "type" in the account_table
        $stmt = $mysqli->prepare("UPDATE account_table SET type=? WHERE email=?");
        $stmt->bind_param("ss", $updatedType, $email);
        $stmt->execute();
        $stmt->close();

        // Update email in both tables if changed
        if ($updatedEmail !== $email) {
            echo "Attempting to update email...";
            $mysqli->begin_transaction();
            try {
                // Update user_table first, then account_table
                $stmt = $mysqli->prepare("UPDATE user_table SET email=? WHERE email=?");
                $stmt->bind_param("ss", $updatedEmail, $email);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare("UPDATE account_table SET email=? WHERE email=?");
                $stmt->bind_param("ss", $updatedEmail, $email);
                $stmt->execute();
                $stmt->close();

                $mysqli->commit();
                $_SESSION['email'] = $updatedEmail;
            } catch (Exception $e) {
                $mysqli->rollback();
                echo "Error updating email: " . $e->getMessage();
            }
        }

        $success_message = true;
    }
}

?>

<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body id="updateProfileBody">
    <?php include_once 'header.php'; ?>

    <main id="main-mt">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg p-4">
                        <h1 class="text-center">Edit User Information</h1>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success text-center">
                                Profile updated successfully! Redirecting back to Manage Accounts page.
                            </div>
                            <meta http-equiv="refresh" content="2;url=manage_accounts.php">
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group text-center">
                                <?php
                                $displayImagePath = !empty($profileImage) ? $profileImage : (($gender === 'Male') ? 'img/profile_images/boys.jpg' : 'img/profile_images/girl.png');
                                ?>
                                <img src="<?= $displayImagePath ?>" alt="Profile Image" class="img-fluid rounded-circle mt-2 mb-2" id="update-profile-img">
                            </div>

                            <!-- Email Field (Read-only) -->
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
                                        <label for="new_password">New Password:</label>
                                        <input type="password" name="new_password" class="form-control" autocomplete="new-password" placeholder="Enter to reset password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password:</label>
                                        <input type="password" name="confirm_password" class="form-control" autocomplete="new-password" placeholder="Confirm new password">                                    </div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="font-weight-bold">User Type:</label>
                                        <select name="type" class="form-control" required>
                                            <option value="user" <?= $type == 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $type == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-success btn-lg mx-2">Submit changes</button>
                                <a href="manage_accounts.php" class="btn btn-secondary btn-lg mx-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once 'footer.php'; ?>
</body>

</html>