<?php
include '../connection.php';

// Check if the 'code' parameter is set in the URL
if (isset($_GET['company'])) {
    $itemCode = $_GET['company'];

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT vat FROM customer WHERE company_name = ?");
    $stmt->bind_param("s", $itemCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $vat = $row['vat'];
        echo json_encode(['vat' => $vat]);
    } else {
        echo json_encode(['error' => 'No data found for the given customer_id']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // 'code' parameter is not set in the URL
    echo json_encode(['error' => 'Parameter "code" is not set in the URL']);
}

// Close the database connection
$conn->close();
?>
