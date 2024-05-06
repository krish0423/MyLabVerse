<?php

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


// Fetch data from the hemogram_data table
$sql = "SELECT * FROM hemogram_data";
$result = $conn->query($sql);

$response = array(); // Initialize the response array

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Close database connection
$conn->close();

// Echo the JSON-encoded response
header('Content-Type: application/json');
echo json_encode($response);
exit(); // Ensure that no additional content is sent
?>