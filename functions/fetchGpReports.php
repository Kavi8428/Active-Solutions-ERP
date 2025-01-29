<?php
// Database connection
include '../connection.php';
// Fetch data from the quotation table
$sql = "SELECT * FROM quotation";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
