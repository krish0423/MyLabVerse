<?php
include('./classes/DBConnection.php'); // Include your database connection file

// Check if the 'id' parameter is set
if (isset($_POST['id'])) {
    // Sanitize the input to prevent SQL injection
    $userId = mysqli_real_escape_string($conn, $_POST['id']);

    // Query to retrieve patient information
    $query = "SELECT * FROM `client_list` WHERE `id` = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch the data as an associative array
        $patientData = mysqli_fetch_assoc($result);

        // Return the data as HTML
        echo "
            <div>
                <h5>Patient Information</h5>
                <p>Name: {$patientData['firstname']} {$patientData['middlename']} {$patientData['lastname']}</p>
                <p>Email: {$patientData['email']}</p>
                <p>Contact: {$patientData['contact']}</p>
                <p>Gender: {$patientData['gender']}</p>
                <p>Address: {$patientData['address']}</p>
                <!-- Add more information fields as needed -->
            </div>
        ";
    } else {
        // Handle query error
        echo "<p>Error retrieving patient information.</p>";
    }
} else {
    // Handle missing 'id' parameter
    echo "<p>Missing user ID.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
