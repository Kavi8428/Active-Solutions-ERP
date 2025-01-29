<?php
include '../connection.php';


// SQL query to fetch data from masterfile table
$sql = "SELECT * FROM grn_items";

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
    $json_data = json_encode($data);

    // Output the JSON data
    echo $json_data;
} else {
    // If no rows were returned
    echo "No data found";
}

// Close connection
$conn->close();
?>
