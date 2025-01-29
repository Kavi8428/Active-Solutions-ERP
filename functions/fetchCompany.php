<?php
include '../connection.php';

// Connect to the database
$db = $conn;

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Fetch item codes from the database
$sql = "SELECT company_name FROM customer";
$result = $db->query($sql);

// Create an empty array to store item codes
$companies = [];

// Check if there are any item codes
if ($result->num_rows > 0) {
  // Loop through each row and add item code to the array
  while($row = $result->fetch_assoc()) {
    $companies[] = $row['company_name'];
  }
}

// Close the database connection
$db->close();

// Generate JSON data
$jsonData = json_encode($companies);

// Send JSON data as response
header('Content-Type: application/json');
echo $jsonData;
?>
