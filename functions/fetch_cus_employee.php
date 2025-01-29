<?php
include '../connection.php'; // Include the database connection script

$empId = $_GET['empId'];
$response = array(); // Initialize an array to hold the response data

$result = $conn->query("SELECT cus_em_name FROM customer_employee WHERE cus_em_id = '$empId'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Add employee name to the response array
    $response['cus_em_name'] = $row['cus_em_name'];
} else {
    // If no data found, return an error message
    $response['error'] = 'No data found for the given employee ID';
}

// Close database connection
$conn->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
