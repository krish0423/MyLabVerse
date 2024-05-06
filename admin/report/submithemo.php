<?php
// path_to_your_report_submission_script.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $clientName = isset($_POST['client_name']) ? $_POST['client_name'] : '';
    $hemoglobin = isset($_POST['hemoglobin']) ? $_POST['hemoglobin'] : '';
    $rbcCount = isset($_POST['rbc_count']) ? $_POST['rbc_count'] : '';
    $wbcCount = isset($_POST['wbc_count']) ? $_POST['wbc_count'] : '';
    $plateletCount = isset($_POST['platelet_count']) ? $_POST['platelet_count'] : '';
    $polymorphs = isset($_POST['polymorphs']) ? $_POST['polymorphs'] : '';
    $lymphocytes = isset($_POST['lymphocytes']) ? $_POST['lymphocytes'] : '';
    $eosinophils = isset($_POST['eosinophils']) ? $_POST['eosinophils'] : '';
    $monocytes = isset($_POST['monocytes']) ? $_POST['monocytes'] : '';
    $basophils = isset($_POST['basophils']) ? $_POST['basophils'] : '';
    $pcv = isset($_POST['pcv']) ? $_POST['pcv'] : '';
    $mcv = isset($_POST['mcv']) ? $_POST['mcv'] : '';
    $mch = isset($_POST['mch']) ? $_POST['mch'] : '';
    $mchc = isset($_POST['mchc']) ? $_POST['mchc'] : '';
    $rdw = isset($_POST['rdw']) ? $_POST['rdw'] : '';
    $rbcs = isset($_POST['rbcs']) ? $_POST['rbcs'] : '';
    $wbcs = isset($_POST['wbcs']) ? $_POST['wbcs'] : '';
    $plateletOption = isset($_POST['platelet_option']) ? $_POST['platelet_option'] : '';

    // Insert data into the hemogram_data table
    $sql = "INSERT INTO hemogram_data 
    (client_name, hemoglobin, rbc_count, wbc_count, platelet_count, polymorphs, 
     lymphocytes, eosinophils, monocytes, basophils, pcv, mcv, mch, mchc, rdw, 
     rbcs, wbcs, platelet_option) 
    VALUES 
    ('$clientName', '$hemoglobin', '$rbcCount', '$wbcCount', '$plateletCount', '$polymorphs', 
     '$lymphocytes', '$eosinophils', '$monocytes', '$basophils', '$pcv', '$mcv', '$mch', '$mchc', '$rdw', 
     '$rbcs', '$wbcs', '$plateletOption')";

$response = array(); // Initialize the response array

if ($conn->query($sql) === TRUE) {
    // If the query is successful, set the response status to success
    $response['status'] = 'success';
} else {
    // If there's an error, set the response status to error and include the error message
    $response['status'] = 'error';
    $response['message'] = $conn->error;
}
  header('Content-Type: application/json');

    // Echo the JSON-encoded response
    echo json_encode($response);

    // Close database connection
    $conn->close();
} else {
    // Invalid request method
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Method Not Allowed";
}
?>