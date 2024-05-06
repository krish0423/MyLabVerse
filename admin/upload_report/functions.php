<?php
require_once('C:\xampp\htdocs\odlms\config.php'); // Adjust the path accordingly

// Function to fetch clients from the database
function fetch_clients() {
    global $conn; // Assuming you have a database connection variable

    $clients = array();

    $query = $conn->query("SELECT * FROM `client_list` WHERE `delete_flag` = 0");
    while ($row = $query->fetch_assoc()) {
        $clients[] = $row;
    }

    return $clients;
}

// Function to fetch tests from the database
function fetch_tests() {
    global $conn;

    $tests = array();

    $query = $conn->query("SELECT * FROM `test_list` WHERE `delete_flag` = 0");
    while ($row = $query->fetch_assoc()) {
        $tests[] = $row;
    }

    return $tests;
}

// Function to save report to the database
function save_report($clientId, $testId, $pdfFile) {
    global $conn;

    // Example: Save to the database
    $pdfFilename = save_pdf_to_server($pdfFile); // Save PDF file to server and get the filename

    $stmt = $conn->prepare("INSERT INTO upload_reports (client_id, test_id, pdf_filename) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $clientId, $testId, $pdfFilename);
    $stmt->execute();
    $stmt->close();
}

// Function to save PDF file to the server and return the filename
function save_pdf_to_server($pdfFile) {
    $uploadDir = 'path_to_upload_directory/'; // Replace with your upload directory path
    $pdfFilename = uniqid() . '_' . $pdfFile['name'];
    $uploadPath = $uploadDir . $pdfFilename;

    move_uploaded_file($pdfFile['tmp_name'], $uploadPath);

    return $pdfFilename;
}

// Function to get client name by ID
function get_client_name_by_id($clientId) {
    global $conn;

    $stmt = $conn->prepare("SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) AS client_name FROM `client_list` WHERE `id` = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->bind_result($clientName);
    $stmt->fetch();
    $stmt->close();

    return $clientName;
}

// Function to get test name by ID
function get_test_name_by_id($testId) {
    global $conn;

    $stmt = $conn->prepare("SELECT `name` FROM `test_list` WHERE `id` = ?");
    $stmt->bind_param("i", $testId);
    $stmt->execute();
    $stmt->bind_result($testName);
    $stmt->fetch();
    $stmt->close();

    return $testName;
}

function get_client_id_by_name($clientName) {
    global $conn; // Assuming $conn is your database connection

    $clientName = mysqli_real_escape_string($conn, $clientName);

    $query = "SELECT id FROM client_list WHERE CONCAT(firstname, ' ', middlename, ' ', lastname) = '$clientName'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    } else {
        return null; // Client not found
    }
}

// Function to get test ID by name
function get_test_id_by_name($testName) {
    global $conn; // Assuming $conn is your database connection

    $testName = mysqli_real_escape_string($conn, $testName);

    $query = "SELECT id FROM test_list WHERE name = '$testName'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    } else {
        return null; // Test not found
    }
}

// Function to save report to the database with client and test names
// Function to save report to the database with client and test names<?php
// Function to save report to the database
function save_report_to_database($clientId, $clientName, $testId, $testName, $pdfContent, $pdfFilename) {
    global $conn;

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO upload_reports (client_id, client_name, test_id, test_name, pdf_content, pdf_filename) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisss", $clientId, $clientName, $testId, $testName, $pdfContent, $pdfFilename);

    // Execute the statement
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        $stmt->close(); // Close the statement
        return true; // Report successfully saved to the database
    } else {
        $stmt->close(); // Close the statement
        return false; // Failed to save the report
    }
}

?>
