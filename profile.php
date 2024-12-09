<?php
session_name('Khant');
session_start(); // Start the session

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

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

// Retrieve user data from session
$email = $_SESSION['email'];

// Query to get user information
$sql = "
    SELECT u.first_name, u.last_name, u.student_id, a.email, a.type, u.profile_image, u.gender, u.contact_number
    FROM user_table AS u
    JOIN account_table AS a ON u.email = a.email
    WHERE a.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userInfo = $result->fetch_assoc();
    $firstName = htmlspecialchars($userInfo['first_name']);
    $lastName = htmlspecialchars($userInfo['last_name']);
    $studentId = htmlspecialchars($userInfo['student_id']);
    $gender = htmlspecialchars($userInfo['gender']);
    $profileImage = htmlspecialchars($userInfo['profile_image']);
} else {
    echo "<p>User not found.</p>";
    exit();
}

// Determine the profile image to display
$profileImagePath = !empty($profileImage) ? $profileImage : (($gender === 'Male') ? 'img/profile_images/boys.jpg' : 'img/profile_images/girl.png');
// Close the database connection
$conn->close();
?>

<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body class="d-flex flex-column min-vh-100" id="profileBody">
    <?php include_once 'header.php'; ?>

    <div class="container mt-7 pt-7 flex-grow-1" id="profileBox">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
            <img src="<?= $profileImagePath ?>" alt="Profile Image" class="square mb-3 img-fluid" id="profileImg">
            
                <h6><strong>Name:</strong> <?= $firstName . ' ' . $lastName ?></h6>
                <h6><strong>Student ID:</strong> <?= $studentId ?></h6>
                <h6><strong>Email:</strong> <a href="mailto:<?= $email ?>"><?= $email ?></a></h6>
                <div class="mt-4">
                    <p id="paragraph">
                        "I declare that this assignment is my individual work. I have not worked collaboratively nor have I copied from any other student's work or from any other source. I have not engaged another party to complete this assignment. I am aware of the University’s policy with regards to plagiarism. I have not allowed, and will not allow, anyone to copy my work with the intention of passing it off as his or her own work."
                    </p>
                </div>
                <div class="mt-4 pt-7 mb-3">
                    <a href="index.php" class="btn btn-primary mr-2">Home Page</a>
                    <a href="about.php" class="btn btn-secondary mr-2">About Page</a>
                    <a href="update_profile.php" class="btn btn-info">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>
</body>

</html>