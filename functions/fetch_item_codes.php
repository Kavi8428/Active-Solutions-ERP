<?php
include '../connection.php';

// Connect to the database
$db = $conn;

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Fetch item codes from the database
$sql = "SELECT item_code FROM pricing";
$result = $db->query($sql);

// Create an empty array to store item codes
$itemCodes = [];

// Check if there are any item codes
if ($result->num_rows > 0) {
  // Loop through each row and add item code to the array
  while($row = $result->fetch_assoc()) {
    $itemCodes[] = $row['item_code'];
  }
}

// Close the database connection
$db->close();

// Generate JSON data
$jsonData = json_encode($itemCodes);

// Send JSON data as response
header('Content-Type: application/json');
echo $jsonData;
?>
