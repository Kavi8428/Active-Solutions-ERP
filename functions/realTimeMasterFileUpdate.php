<?php
include '../connection.php';

// Get the POST data
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'];
$column = $input['column']; // This should be the column name/header
$value = $input['value'];

// Escape the value to prevent SQL injection
$value = mysqli_real_escape_string($conn, $value);

// Construct the SQL query (note the backticks around $column)
$sql = "UPDATE masterfile SET `$column` = '$value' WHERE ivn = '$id'";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Update successful";
} else {
    echo "Update failed: " . $conn->error;
}

$conn->close();
?>
