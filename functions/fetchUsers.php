<?php
include '../connection.php';

// Connect to the database
$db = $conn;

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all data from the system_user table
$sql = "SELECT * FROM system_user";
$result = $db->query($sql);

// Create an empty array to store user data
$users = [];

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each row and add user data to the array
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Close the database connection
$db->close();

// Generate JSON data
$jsonData = json_encode($users);

// Send JSON data as response
header('Content-Type: application/json');
echo $jsonData;
?>