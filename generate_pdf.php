<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('lib/fpdf/fpdf.php');

// Get the plant details passed from the URL
$scientificName = isset($_GET['scientificName']) ? $_GET['scientificName'] : '';
$commonName = isset($_GET['commonName']) ? $_GET['commonName'] : '';
$family = isset($_GET['family']) ? $_GET['family'] : '';
$genus = isset($_GET['genus']) ? $_GET['genus'] : '';
$species = isset($_GET['species']) ? $_GET['species'] : '';
$confidence = isset($_GET['confidence']) ? $_GET['confidence'] : '';
$species = isset($_GET['species']) ? $_GET['species'] : '';
$imageUrls = isset($_GET['imageUrl']) ? explode(',', $_GET['imageUrl']) : [];

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set the font for the title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Plant Details', 0, 1, 'C');
$pdf->Ln(10);

// Set the font for the content
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Scientific Name: " . $scientificName, 0, 1);
$pdf->Cell(0, 10, "Common Name: " . $commonName, 0, 1);
$pdf->Cell(0, 10, "Family: " . $family, 0, 1);
$pdf->Cell(0, 10, "Genus: " . $genus, 0, 1);
$pdf->Cell(0, 10, "Species: " . $species, 0, 1);
$pdf->Cell(0, 10, "Confidence: " . $confidence, 0, 1);

$pdf->Ln(10);

// Add images to the PDF if they exist
if (!empty($imageUrls)) {
    foreach ($imageUrls as $imageUrl) {
        $imagePath = htmlspecialchars($imageUrl);
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 10, $pdf->GetY(), 60);
        } else {
            $pdf->Cell(0, 10, "Image not found: " . $imagePath, 0, 1);
        }
    }
}

// Sanitize the scientific name to create a valid file name for the PDF
$filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName) . '.pdf';

// Output the PDF to the browser with the scientific name as the file name
$pdf->Output('D', $filename);
?>