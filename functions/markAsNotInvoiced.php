<?php
// Database connection settings
include '../connection.php';

// Check if the deal_id parameter is set
if (isset($_POST['deal_id'])) {
    // Get the deal_id from the POST parameters
    $dealId = $_POST['deal_id'];

    // Sanitize the deal_id to prevent SQL injection
    $dealId = mysqli_real_escape_string($conn, $dealId);

    // Update the 'invoiced' field in the 'quotation' table for the specified deal_id
    $updateSql = "UPDATE quotation SET invoiced = 0 WHERE deal_id = '$dealId'";

    if ($conn->query($updateSql) === TRUE) {
        // Update successful
        echo "Marked as invoiced successfully.";
    } else {
        // Handle the case where update failed
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Handle the case where deal_id parameter is not set
    echo "No deal ID provided.";
}

// Close the database connection
$conn->close();
?>
