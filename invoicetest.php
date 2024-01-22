<?php
ob_start();
require('vendor/tecnickcom/tcpdf/tcpdf.php');

// Check if the "invoice_id" parameter is set in the URL
if (isset($_GET['invoice_id'])) {
    $invoiceId = intval($_GET['invoice_id']); // Convert to integer for security
} else {
    die("Invoice ID is missing.");
}

// Create a new PDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

// Set document information (optional)
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('Invoice, PDF, PHP');

// Add a page
$pdf->AddPage();

// Fetch data from the database using the $invoiceId
$invoiceData = fetchDataFromDatabase($invoiceId); // Implement this function to fetch your data

// ... (rest of your code)
// Create the table header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(60, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
/* $pdf->Cell(30, 10, 'Unit Price', 1);
$pdf->Cell(30, 10, 'Total', 1); */
$pdf->Ln(); // Move to the next line

// Create the table rows
$pdf->SetFont('helvetica', '', 12);
if (isset($invoiceData['items']) && is_array($invoiceData['items'])) {
    foreach ($invoiceData['items'] as $item) {
        $pdf->Cell(60, 10, $item['description'], 1);
        $pdf->Cell(30, 10, $item['nat_heat'], 1);
        /*         $pdf->Cell(30, 10, '$' . number_format($item['carrat'], 2), 1);
        $pdf->Cell(30, 10, '$' . number_format($item['soldvalue'], 2), 1); */
        $pdf->Ln(); // Move to the next line
    }
}

// Define a function to fetch data from the database based on the provided invoice ID
function fetchDataFromDatabase($invoiceId)
{
    // Implement your database connection and data retrieval logic here
    // Example using PDO:
    $pdo = new PDO("mysql:host=localhost;dbname=projectgem", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM masterdata WHERE id = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT); // Bind the dynamic value
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
ob_end_clean();
// Output the PDF as a download
$pdf->Output('invoice.pdf', 'D');
