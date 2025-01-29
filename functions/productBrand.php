<?php
include '../connection.php';

// Use isset() to check if the parameters are set
if (isset($_GET['code'])) {
    $itemCode = $_GET['code'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT brand FROM product WHERE item_code = ?");
    $stmt->bind_param("s", $itemCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $brand = $row['brand'];

        // Log the values before sending the JSON response
        error_log('Fetched Brand: ' . $brand . '');

        // Return the brand as JSON
        echo json_encode(['brand' => $brand]);
    } else {
        echo json_encode(["error" => "Brand is not found for the selected item and price category."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing parameters."]);
}

$conn->close();
?>
