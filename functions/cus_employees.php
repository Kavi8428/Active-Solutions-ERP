<?php
include '../connection.php'; // Include the database connection script

$company = $_GET['company'];
$response = array(); // Initialize an array to hold the response data

$result = $conn->query("SELECT customer_id FROM customer WHERE company_name = '$company'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Add customer ID to the response array
    $response['customer_id'] = $row['customer_id'];
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
