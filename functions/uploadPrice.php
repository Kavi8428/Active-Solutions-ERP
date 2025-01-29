<?php
// Establish database connection
include '../connection.php';

// Receive data from AJAX request
$jsonData = $_POST['jsonData'];

// Prepare and bind parameters
$stmt = $conn->prepare("INSERT INTO pricing (item_code, lr, r, rt, cost) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE lr = VALUES(lr), r = VALUES(r), rt = VALUES(rt), cost = VALUES(cost)");

$stmt->bind_param("sdddi", $itemCode, $lr, $r, $rt, $cost);

// Iterate through each row of JSON data
foreach ($jsonData as $row) {
    $itemCode = $row['ItemCode'];
    $lr = $row['LowReseller'];
    $r = $row['Reseller'];
    $rt = $row['Retail'];
    $cost = $row['Cost'];

    // Execute the prepared statement
    $stmt->execute();
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
