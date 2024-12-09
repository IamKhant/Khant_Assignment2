<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('lib/fpdf/fpdf.php');

$scientificName = isset($_POST['scientificName']) ? $_POST['scientificName'] : '';
$commonName = isset($_POST['commonName']) ? $_POST['commonName'] : '';
$family = isset($_POST['family']) ? $_POST['family'] : '';
$genus = isset($_POST['genus']) ? $_POST['genus'] : '';
$species = isset($_POST['species']) ? $_POST['species'] : '';
$image = isset($_POST['image']) ? $_POST['image'] : '';

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Plant Details', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Scientific Name: " . $scientificName, 0, 1);
$pdf->Cell(0, 10, "Common Name: " . $commonName, 0, 1);
$pdf->Cell(0, 10, "Family: " . $family, 0, 1);
$pdf->Cell(0, 10, "Genus: " . $genus, 0, 1);
$pdf->Cell(0, 10, "Species: " . $species, 0, 1);

$pdf->Ln(10);
if (!empty($image)) {
    $imagePath = 'img/contributeImg/' . basename($image);

    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 10, $pdf->GetY(), 60);
        $pdf->Ln(40);
    } else {
        $pdf->Cell(0, 10, "Image not found: " . htmlspecialchars($image), 0, 1);
    }
}
// Sanitize the scientific name to create a valid file name
$filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName) . '.pdf';

// Output the PDF with the scientific name as the file name
$pdf->Output('D', $filename);
?>