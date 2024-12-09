<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "PlantBiodiversity";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === false) {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($dbname);

// SQL to create user_table with email as primary key
$sql = "CREATE TABLE IF NOT EXISTS user_table (
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    dob DATE NULL,
    gender VARCHAR(6) NOT NULL,
    contact_number VARCHAR(15) NULL,
    hometown VARCHAR(50) NOT NULL,
    profile_image VARCHAR(100) NULL,
    resume_path VARCHAR(255) NULL
)";
if ($conn->query($sql) === false) {
    echo "Error creating user_table: " . $conn->error;
}

// SQL to create account_table
$sql = "CREATE TABLE IF NOT EXISTS account_table (
    email VARCHAR(50) NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    type VARCHAR(5) NOT NULL,
    FOREIGN KEY (email) REFERENCES user_table(email) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (email)
)";
if ($conn->query($sql) === false) {
    echo "Error creating account_table: " . $conn->error;
}

// SQL to create plant_table
$sql = "CREATE TABLE IF NOT EXISTS plant_table (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    scientific_name VARCHAR(50) NOT NULL,
    common_name VARCHAR(50) NOT NULL,
    family VARCHAR(100) NOT NULL,
    genus VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    plants_image VARCHAR(100) NULL,
    description VARCHAR(255) NULL,
    status ENUM('approved', 'pending', 'rejected') DEFAULT 'pending'
)";
if ($conn->query($sql) === false) {
    echo "Error creating plant_table: " . $conn->error;
}


// Insert dummy data for user_table if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM user_table");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $dummyUsers = [
        ['email' => 'admin@swin.edu.my', 'first_name' => 'Siwnburne', 'last_name' => 'Admin', 'student_id' => 'S0001', 'dob' => '1990-01-01', 'gender' => 'Male', 'contact_number' => '01122334455', 'hometown' => 'AdminCity', 'profile_image' => 'img/profile_images/boys.jpg'],
        ['email' => '104381819@students.swinburne.edu.my', 'first_name' => 'Khant Zin', 'last_name' => 'Ko', 'student_id' => '104381819', 'dob' => '2001-07-16', 'gender' => 'Male', 'contact_number' => '601126832970', 'hometown' => 'Mudon', 'profile_image' => 'img/profile_images/boys.jpg'],
        ['email' => 'user1@example.com', 'first_name' => 'John', 'last_name' => 'Doe', 'student_id' => 'S0002', 'dob' => '1995-02-02', 'gender' => 'Male', 'contact_number' => '01122334456', 'hometown' => 'User1Town', 'profile_image' => 'img/profile_images/boys.jpg'],
        ['email' => 'user2@example.com', 'first_name' => 'Jane', 'last_name' => 'Doe', 'student_id' => 'S0003', 'dob' => '1997-03-03', 'gender' => 'Female', 'contact_number' => '01122334457', 'hometown' => 'User2Town', 'profile_image' => 'img/profile_images/girl.png'],
    ];
    foreach ($dummyUsers as $user) {
        $stmt = $conn->prepare("INSERT INTO user_table (email, first_name, last_name, student_id, dob, gender, contact_number, hometown, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $user['email'], $user['first_name'], $user['last_name'], $user['student_id'], $user['dob'], $user['gender'], $user['contact_number'], $user['hometown'], $user['profile_image']);
        $stmt->execute();
    }
}

// Insert dummy data for account_table if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM account_table");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $dummyAccounts = [
        ['email' => 'admin@swin.edu.my', 'hashed_password' => password_hash('admin', PASSWORD_DEFAULT), 'type' => 'admin'],
        ['email' => '104381819@students.swinburne.edu.my', 'hashed_password' => password_hash('admin123', PASSWORD_DEFAULT), 'type' => 'user'],
        ['email' => 'user1@example.com', 'hashed_password' => password_hash('user1Pass', PASSWORD_DEFAULT), 'type' => 'user'],
        ['email' => 'user2@example.com', 'hashed_password' => password_hash('user2Pass', PASSWORD_DEFAULT), 'type' => 'user'],
    ];
    foreach ($dummyAccounts as $account) {
        $stmt = $conn->prepare("INSERT INTO account_table (email, hashed_password, type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $account['email'], $account['hashed_password'], $account['type']);
        $stmt->execute();
    }
}

// Insert dummy data for plant_table if empty
$result = $conn->query("SELECT COUNT(*) AS count FROM plant_table");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $dummyPlants = [
        ['scientific_name' => 'Torilis arvensis', 'common_name' => 'tall sock-destroyer', 'family' => 'Apiaceae', 'genus' => 'Torilis', 'species' => 'T. arvensis', 'plants_image' => 'CommonHedgeParsley.jpg', 'description' => 'plants_description/Torilis_arvensis_description.pdf', 'status' => 'approved'],
        ['scientific_name' => 'Bursera fagaroides', 'common_name' => 'torchwood copal', 'family' => 'Burseraceae', 'genus' => 'Bursera', 'species' => 'B. fagaroides', 'plants_image' => 'MexicanFrankincense.jpeg', 'description' => 'plants_description/Bursera_fagaroides_description.pdf', 'status' => 'approved'],
        ['scientific_name' => 'Boswellia samhaensis', 'common_name' => 'Indian frankincense', 'family' => 'Burseraceae', 'genus' => 'Boswellia', 'species' => 'B. samhaensis', 'plants_image' => 'BoswelliaSamhaensis.jpeg', 'description' => 'plants_description/Boswellia_samhaensis_description.pdf', 'status' => 'approved'],
        ['scientific_name' => 'Papaver somniferum L.', 'common_name' => 'Opium poppy', 'family' => 'Papaveraceae', 'genus' => 'Papaver', 'species' => 'P. somniferum', 'plants_image' => 'Papaver_somniferum_L.jpeg', 'description' => 'plants_description/Papaver_somniferum_L.pdf', 'status' => 'approved'],
    ];
    foreach ($dummyPlants as $plant) {
        $stmt = $conn->prepare("INSERT INTO plant_table (scientific_name, common_name, family, genus, species, plants_image, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $plant['scientific_name'], $plant['common_name'], $plant['family'], $plant['genus'], $plant['species'], $plant['plants_image'], $plant['description'], $plant['status']);
        $stmt->execute();
    }
}



// Return the connection object for use in other scripts (optional)
return $conn;
?>