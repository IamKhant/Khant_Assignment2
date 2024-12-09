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

<body>
    <?php include_once 'header.php'; ?>
    <div class="container identify_container">
        <h1 class="text-center mb-4">Plant Identification Results</h1>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['project']) && !empty($_POST['organ']) && !empty($_FILES['image']['tmp_name'])) {
            require 'vendor/autoload.php';

            $apiKey = '2b104lZOZgmfG2WxqyaBxx8hOO';
            $project = $_POST['project'];
            $organ = $_POST['organ'];
            $imagePath = $_FILES['image']['tmp_name'];
            $apiUrl = "https://my-api.plantnet.org/v2/identify/$project?api-key=$apiKey&include-related-images=true&lang=en";

            $client = new GuzzleHttp\Client();
            try {
                $response = $client->request('POST', $apiUrl, [
                    'multipart' => [
                        ['name' => 'images', 'contents' => fopen($imagePath, 'r')],
                        ['name' => 'organs', 'contents' => $organ]
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (!empty($data['results'])) {
                    echo '<div class="alert alert-success text-center" role="alert">Results Found</div>';
                    echo '<div class="row">';

                    foreach (array_slice($data['results'], 0, 4) as $result) {
                        $scientificName = htmlspecialchars($result['species']['scientificNameWithoutAuthor']);
                        $commonName = htmlspecialchars($result['species']['commonNames'][0] ?? 'Unknown');
                        $familyName = htmlspecialchars($result['species']['family']['scientificNameWithoutAuthor'] ?? 'Unknown');
                        $genus = htmlspecialchars($result['species']['genus']['scientificNameWithoutAuthor'] ?? 'Unknown');
                        $confidence = htmlspecialchars(round($result['score'] * 100, 1)) . '%';

                        // Format species as "T. arvensis"
                        $nameParts = explode(' ', $scientificName);
                        $genusInitial = strtoupper(substr($nameParts[0], 0, 1));  // Get first letter of genus
                        $species = $nameParts[1] ?? '';  // Get species part (if exists)
                        $formattedSpecies = $genusInitial . '. ' . $species;


                        echo '<div class="col-md-6 mb-4">';
                        echo '<div class="card shadow-sm">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $scientificName . '</h5>';
                        echo '<p class="card-text"><strong>Common Name:</strong> ' . $commonName . '</p>';
                        // echo '<p class="card-text"><strong>Family:</strong> ' . $familyName . '</p>';
                        // echo '<p class="card-text"><strong>Genus:</strong> ' . $genus . '</p>';
                        // echo '<p class="card-text"><strong>Species:</strong> ' . $formattedSpecies . '</p>';
                        echo '<p class="card-text font-weight-bold">Confidence: ' . $confidence . '</p>';

                        if (!empty($result['images'])) {
                            echo '<div class="d-flex flex-wrap">';
                            $imageCount = 0;
                            foreach ($result['images'] as $img) {
                                if ($imageCount >= 3) break;
                                $imageUrl = isset($img['url']['s']) ? $img['url']['s'] : $img['url'];
                                echo '<div class="p-1">';
                                echo '<img src="' . htmlspecialchars($imageUrl) . '" class="img-fluid rounded" alt="Plant Image" style="width: 100%; max-width: 150px;">';
                                echo '</div>';
                                $imageCount++;
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="text-warning">No images available for this result.</p>';
                        }

                        // Add a "View Details" button
                        echo '<a href="identify_result.php?scientificName=' . urlencode($scientificName) . '&commonName=' . urlencode($commonName) . '&family=' . urlencode($familyName) . '&genus=' . urlencode($genus) . '&species=' . urlencode($formattedSpecies) . '&confidence=' . urlencode($confidence) . '&imageUrl=' . urlencode($imageUrl) .  '" class="btn btn-primary btn-sm mt-3">View Details</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning text-center" role="alert">No results found. Try a different image.</div>';
                }
            } catch (Exception $e) {
                // Save error in session for display on identify_result.php
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
                header("Location: identify_result.php");
                exit();
            }
        } else {
            // Redirect back with error if required fields are missing
            $_SESSION['error'] = 'All fields are required.';
            header("Location: identify_result.php");
            exit();
        }
        ?>
    </div>
    <?php include_once 'footer.php'; ?>
</body>

</html>