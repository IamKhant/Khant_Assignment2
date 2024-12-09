<?php
session_name('Khant');
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate first name
    if (empty($_POST["first_name"])) {
        $errors['first_name'] = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["first_name"])) {
        $errors['first_name'] = "First name can only contain letters and spaces.";
    } else {
        $_SESSION['first_name'] = clean_input($_POST["first_name"]);
    }

    // Validate last name
    if (empty($_POST["last_name"])) {
        $errors['last_name'] = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["last_name"])) {
        $errors['last_name'] = "Last name can only contain letters and spaces.";
    } else {
        $_SESSION['last_name'] = clean_input($_POST["last_name"]);
    }

    // Validate student ID
    if (empty($_POST["student_id"])) {
        $errors['student_id'] = "Student ID is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $_POST["student_id"])) {
        $errors['student_id'] = "Student ID can only contain letters and numbers.";
    } else {
        $_SESSION['student_id'] = clean_input($_POST["student_id"]);
    }

    // Validate date of birth
    if (empty($_POST["dob"])) {
        $errors['dob'] = "Date of birth is required.";
    } else {
        $_SESSION['dob'] = clean_input($_POST["dob"]);
    }

    // Validate gender
    if (empty($_POST["gender"])) {
        $errors['gender'] = "Please choose your gender.";
    } else {
        $_SESSION['gender'] = clean_input($_POST["gender"]);
    }

    // Validate email and check if it already exists in the database
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        $_SESSION['email'] = clean_input($_POST["email"]);

        // Query to check if email exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM account_table WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count > 0) {
            $errors['email'] = "This email is already registered.";
        }
        $stmt->close();
    }

    // Validate hometown
    if (empty($_POST["hometown"])) {
        $errors['hometown'] = "Hometown is required.";
    } else {
        $_SESSION['hometown'] = clean_input($_POST["hometown"]);
    }

    // Validate contact number (new validation)
    if (empty($_POST["contact_number"])) {
        $errors['contact_number'] = "Contact number is required.";
    } elseif (!ctype_digit($_POST["contact_number"])) {
        $errors['contact_number'] = "Contact number must be a valid number.";
    } else {
        $_SESSION['contact_number'] = clean_input($_POST["contact_number"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($_POST["password"]) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    } else {
        $password = clean_input($_POST["password"]);
    }

    // Validate confirm password
    if (empty($_POST["confirm_password"])) {
        $errors['confirm_password'] = "Confirm password is required.";
    } elseif ($_POST["confirm_password"] !== $_POST["password"]) {
        $errors['confirm_password'] = "Passwords do not match.";
    } else {
        $confirm_password = clean_input($_POST["confirm_password"]);
    }

    if (empty($errors)) {
        $profileImage = ($_SESSION['gender'] == 'Male') ? 'img/profile_images/boys.jpg' : 'img/profile_images/girl.png';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the user_table
        $stmt = $conn->prepare("INSERT INTO user_table (first_name, last_name, dob, gender, hometown, student_id, profile_image, email, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['dob'], $_SESSION['gender'], $_SESSION['hometown'], $_SESSION['student_id'], $profileImage, $_SESSION['email'], $_SESSION['contact_number']);
        $stmt->execute();

        // Get the inserted user ID
        $user_id = $stmt->insert_id;
        $stmt->close();

        // Insert account data into account_table with the generated user_id
        // Set default user type to 'user' if not provided
        $user_type = "user";

        // Insert account data into account_table with the generated user_id
        $stmt = $conn->prepare("INSERT INTO account_table (email, hashed_password, type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_SESSION['email'], $hashed_password, $user_type);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        // Store errors in session
        $_SESSION['errors'] = $errors;

        // Check user type
        if ($_SESSION['type'] == 'user') {
            // Redirect to registration.php if the user type is 'user'
            header("Location: registration.php");
        } elseif ($_SESSION['type'] == 'admin') {
            // Redirect to add_user.php if the user type is 'admin'
            header("Location: add_user.php");
        } else {
            header("Location: registration.php");
        }
        exit();
    }
}

// Function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
