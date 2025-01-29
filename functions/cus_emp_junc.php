<?php
//cus_emp_junc.php
include '../connection.php'; // Include the database connection script

$customer_id = $_GET['cusId'];
$response = array(); // Initialize an array to hold the response data

$result = $conn->query("SELECT * FROM cus_emp_junc WHERE customer_id = '$customer_id'");

if ($result->num_rows > 0) {
    // Initialize an array to hold all the fetched rows
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        // Append each row to the $rows array
        $rows[] = $row;
    }
    // Add the array of rows to the response
    $response['data'] = $rows;
} else {
    // If no data found, return an error message
    $response['error'] = 'No data found for the given company';
}

// Close database connection
$conn->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
