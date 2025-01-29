<?php
// Include your database connection details (e.g., host, username, password, dbname)
include '../connection.php';

// Create a new connection
$db = $conn;


// Fetch data from the 'pricing' table
$sql = "SELECT * FROM stock_serials";
$result = $db->query($sql);

// Initialize an empty array to store product data
$products = [];

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each row and add product data to the array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    // Handle the case where no data is found (optional)
    $products = []; // Empty array if no results
}

// Close the database connection
$db->close();

// Generate JSON response
header('Content-Type: application/json');
echo json_encode($products);
?>
