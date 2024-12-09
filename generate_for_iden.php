<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('lib/fpdf/fpdf.php');

// Enable output buffering to prevent premature output
ob_start();

// Get the plant details passed from the URL
$scientificName = isset($_GET['scientificName']) ? $_GET['scientificName'] : '';
$commonName = isset($_GET['commonName']) ? $_GET['commonName'] : '';
$family = isset($_GET['family']) ? $_GET['family'] : '';
$genus = isset($_GET['genus']) ? $_GET['genus'] : '';
$species = isset($_GET['species']) ? $_GET['species'] : '';
$confidence = isset($_GET['confidence']) ? $_GET['confidence'] : '';
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

// Handle images
$tempDir = sys_get_temp_dir(); // System temp directory

if (!empty($imageUrls)) {
    foreach ($imageUrls as $imageUrl) {
        $imagePath = htmlspecialchars($imageUrl);

        // Download image temporarily
        $tempImage = $tempDir . DIRECTORY_SEPARATOR . 'temp_' . uniqid() . '.jpg';
        if (@file_put_contents($tempImage, file_get_contents($imagePath)) === false) {
            $pdf->Cell(0, 10, "Explore this link for images", 0, 1);
            $pdf->Cell(0, 10, $imagePath, 0, 1);
        } else {
            $pdf->Image($tempImage, 10, $pdf->GetY(), 40); // Add the image
            $pdf->Ln(40);
            unlink($tempImage); // Clean up temporary file
        }
    }
} else {
    $pdf->Cell(0, 10, "No images available.", 0, 1);
}

// Sanitize the scientific name to create a valid file name for the PDF
$filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName) . '.pdf';

// Clear output buffer and send the PDF
ob_end_clean();
$pdf->Output('D', $filename);