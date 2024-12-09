<?php
session_name('Khant');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_GET['descriptionFile'])) {
    $filePath = "plants_description/" . basename($_GET['descriptionFile']);
    
    // Check if file exists
    if (file_exists($filePath)) {
        // Set headers to prompt file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Description file not found.";
    }
} else {
    echo "No description file specified.";
}
?>