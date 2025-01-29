<?php
include '../connection.php'; // Include your database connection script

// SQL query to fetch data from gin_data table
$sql = "SELECT * FROM gin_items";

// Execute query
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Initialize an array to store the data
    $data = array();

    // Fetch associative array of each row
    while ($row = $result->fetch_assoc()) {
        // Append the row to the data array
        $data[] = $row;
    }

    // Convert the data array to JSON format
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // If no rows were returned
    echo json_encode(array("message" => "No data found"));
}

// Close connection
$conn->close();
?>
