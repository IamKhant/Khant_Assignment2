<?php
session_name('Khant');
session_start();
// Check if the user is logged in, otherwise redirect to the login page (index.php)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>
<?php
include_once 'session_timeout.php'; // Include session timeout logic
?>
<?php include_once 'head.php'; ?>

<body id="identify_result_body">
    <?php include_once 'header.php'; ?>
    <div class="container identify_container">
        <h1 class="text-center mb-4">Plant Details</h1>

        <?php
        if (isset($_GET['scientificName'], $_GET['commonName'], $_GET['family'], $_GET['genus'], $_GET['species'], $_GET['confidence'], $_GET['imageUrl'])) {
            // Get the passed data from the URL
            $scientificName = htmlspecialchars($_GET['scientificName']);
            $commonName = htmlspecialchars($_GET['commonName']);
            $family = htmlspecialchars($_GET['family']);
            $genus = htmlspecialchars($_GET['genus']);
            $confidence = htmlspecialchars($_GET['confidence']);
            $formattedSpecies = htmlspecialchars($_GET['species']);
            $imageUrls = explode(',', $_GET['imageUrl']);  // Split the image URLs into an array

            echo '<div class="card shadow-sm">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title"><strong>Scientific Name:</strong> ' . $scientificName . '</h5>';
            echo '<p class="card-text"><strong>Common Name:</strong> ' . $commonName . '</p>';
            echo '<p class="card-text"><strong>Family:</strong> ' . $family . '</p>';
            echo '<p class="card-text"><strong>Genus:</strong> ' . $genus . '</p>';
            echo '<p class="card-text"><strong>Species:</strong> ' . $formattedSpecies . '</p>';
            echo '<p class="card-text font-weight-bold">Confidence: ' . $confidence . '</p>';

            // Loop through the array of image URLs
            if (!empty($imageUrls)) {
                foreach ($imageUrls as $imageUrl) {
                    echo '<img src="' . htmlspecialchars($imageUrl) . '" class="img-fluid rounded" alt="Plant Image" style="max-width: 300px; margin-right: 10px;">';
                }
            } else {
                echo '<p class="text-warning">No image available.</p>';
            }
            echo '</div>';
            echo '</div>';


            // Buttons
            echo '<div class="mt-4 text-center">';

            echo '<a href="generate_for_iden.php?scientificName=' . urlencode($scientificName) . '&commonName=' . urlencode($commonName) . '&family=' . urlencode($family) . '&genus=' . urlencode($genus) . '&species=' . urlencode($formattedSpecies) . '&confidence=' . urlencode($confidence) . '&imageUrl=' . urlencode($imageUrl) . '" class="btn btn-primary">Generate PDF</a>';
            echo '&nbsp;';
            echo '<a href="identify.php" class="btn btn-secondary">Back</a>';
            echo '</div>';
        } else {

            echo '<div class="alert alert-danger">No results available. Please try again.</div>';
        }
        ?>
    </div>

    <?php include_once 'footer.php'; ?>
</body>

</html>