<?php

include '../connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $ivn = $_POST['inv'];
    $table = $_POST['table'];
    $primaryKey = $_POST['primaryKey'];

    // Prepare the SQL DELETE statement
    $sqlDelete = "DELETE FROM $table WHERE $primaryKey = ?";

    // Prepare and bind
    if ($stmt = $conn->prepare($sqlDelete)) {
        $stmt->bind_param('s', $ivn);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Record deleted successfully']);
        } else {
            echo json_encode(['error' => 'Error deleting record: ' . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing the statement: ' . $conn->error]);
    }
} else {
    // Invalid request method
    echo json_encode(['error' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
