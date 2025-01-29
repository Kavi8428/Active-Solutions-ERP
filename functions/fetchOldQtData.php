<?php
include '../connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type header
header('Content-Type: application/json');

// Get the 'code' parameter from the URL
$code = $_GET['code'];

// Perform a database query to fetch data based on the code
$query = "SELECT item_code, description, quantity, price, total, checking FROM quotation_products WHERE deal_id = ?";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

// Bind the parameter
mysqli_stmt_bind_param($stmt, "i", $code);

// Execute the prepared statement
mysqli_stmt_execute($stmt);

// Get the results
$result = mysqli_stmt_get_result($stmt);

// Check if the query was successful
if ($result) {
    // Fetch the data as an associative array
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        // Validate each retrieved value
        foreach ($row as $key => $value) {
            $row[$key] = strip_tags($value); // Remove potential HTML tags
        }

        $data[] = $row;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Return the data in JSON format
    echo json_encode($data);
} else {
    // Handle the case when the query fails
    echo json_encode([
        'error' => 'Failed to fetch data',
        'query' => $query,
        'error_message' => mysqli_error($conn),
    ]);
}
?>
