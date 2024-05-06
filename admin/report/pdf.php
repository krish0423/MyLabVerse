<?php
// Include the fpdf class
require('C:\xampp\htdocs\odlms\fpdf\fpdf.php');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "odlms_db";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create an fpdf object
$pdf = new FPDF();
$pdf->AddPage();

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);

// Add the title at the center of the PDF
$pdf->Cell(0, 10, 'MY LABVERSE', 0, 1, 'C');
$pdf->Ln(5); // Add some vertical space

// Draw a horizontal line
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5); // Add some vertical space

// Fetch data from the database
$result = $conn->query("SELECT * FROM hemogram_data ORDER BY id DESC LIMIT 1");

// Check if there is data
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Add patient name and today's date
    $pdf->SetFont('Arial', 'B', 12); // Set font to bold
    $pdf->Cell(40, 10, 'Patient Name: ' . $data['client_name']);
    $pdf->Cell(80); // Move to the center
    $pdf->Cell(40, 10, ' Date: ' . date('d-m-Y'));
    $pdf->Ln();

    // Draw an additional horizontal line
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(1); // Add some vertical space

    // Add the title "HAEMOGRAM REPORT" at the center
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'HAEMOGRAM REPORT', 0, 1, 'C');
    $pdf->Ln(1); // Add some vertical space

    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(1); // Add some vertical space

    // Add Test, Result, Units, and Normals headings as a table
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 10, 'TEST', 0);
    $pdf->Cell(40, 10, 'RESULT', 0);
    $pdf->Cell(40, 10, 'UNITS', 0);
    $pdf->Cell(40, 10, 'NORMALS', 0);
    $pdf->Ln();

    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(1); // Add some vertical space

    // Add Blood Counts row
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 10, 'Blood Counts:', 0);
    $pdf->Ln();

    // Add Hemoglobin row
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Hemoglobin:', 0);
    $pdf->Cell(40, 10, $data['hemoglobin'], 0);
    $pdf->Cell(40, 10, 'gm%', 0);
    $pdf->Cell(40, 10, 'M: 13.55-18 F: 12-16', 0);
    $pdf->Ln();

    // Add more rows for other tests and data

    // Example with additional row
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'RBC Count:', 0);
    $pdf->Cell(40, 10, $data['rbc_count'], 0);
    $pdf->Cell(40, 10, 'mill./cu.mm.', 0); // Placeholder for Units
    $pdf->Cell(40, 10, 'M:4.5-6.5 F:4.2-5.4', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'WBC Count:', 0);
    $pdf->Cell(40, 10, $data['wbc_count'], 0);
    $pdf->Cell(40, 10, '/cu.mm.', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '4000 to 10,000', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Platelet Count:', 0);
    $pdf->Cell(40, 10, $data['platelet_count'], 0);
    $pdf->Cell(40, 10, '/cu.mm.', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '1,50,000 to 4,50,000', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 10, 'Differential Count:');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Polymorphs:', 0);
    $pdf->Cell(40, 10, $data['polymorphs'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '50-70', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Lymphocytes:', 0);
    $pdf->Cell(40, 10, $data['lymphocytes'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '20-40', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Eosinophils:', 0);
    $pdf->Cell(40, 10, $data['eosinophils'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '01-06', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Monocytes:', 0);
    $pdf->Cell(40, 10, $data['monocytes'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '02-06', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Basophils:', 0);
    $pdf->Cell(40, 10, $data['basophils'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '00-01', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 10, 'Blood Indices:');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'P.C.V.:', 0);
    $pdf->Cell(40, 10, $data['pcv'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, 'M:40-54 F:36-45', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'M.C.V.:', 0);
    $pdf->Cell(40, 10, $data['mcv'], 0);
    $pdf->Cell(40, 10, 'fi', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '82-92', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'M.C.H.:', 0);
    $pdf->Cell(40, 10, $data['mch'], 0);
    $pdf->Cell(40, 10, 'pg', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '27-32', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'M.C.H.C.:', 0);
    $pdf->Cell(40, 10, $data['mchc'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '32-36', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'R.D.W.:', 0);
    $pdf->Cell(40, 10, $data['rdw'], 0);
    $pdf->Cell(40, 10, '%', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '11.6-14.6', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 10, 'Smear Study');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'RBCs:', 0);
    $pdf->Cell(40, 10, $data['rbcs'], 0);
    $pdf->Cell(40, 10, '', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'WBCs:', 0);
    $pdf->Cell(40, 10, $data['wbcs'], 0);
    $pdf->Cell(40, 10, '', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '', 0); // Placeholder for Normals
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Platelets:', 0);
    $pdf->Cell(40, 10, $data['platelet_option'], 0);
    $pdf->Cell(40, 10, '', 0); // Placeholder for Units
    $pdf->Cell(40, 10, '', 0); // Placeholder for Normals
    $pdf->Ln();









} else {
    $pdf->Cell(40, 10, 'No data available.');
}

// Output the PDF
$pdf->Output();
?>
