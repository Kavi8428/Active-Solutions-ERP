<?php
include '../connection.php';

// Use isset() to check if the parameters are set
if (isset($_GET['code'], $_GET['category'])) {
    $itemCode = $_GET['code'];
    $priceCategory = $_GET['category'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT $priceCategory, cost FROM pricing WHERE item_code = ?");
    $stmt->bind_param("s", $itemCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $row[$priceCategory];
        $cost = $row['cost'];

        // Check if the response is properly encoded
        if (mb_detect_encoding($price, 'UTF-8', true) === false || mb_detect_encoding($cost, 'UTF-8', true) === false) {
            echo "Error: Response is not properly encoded.";
            exit;
        }

        // Log the values before sending the JSON response
        error_log('Fetched Price: ' . $price . ', Fetched Cost: ' . $cost);

        // Return the price and cost as JSON
        echo json_encode(['price' => $price, 'cost' => $cost]);
    } else {
        echo "Error: Price and cost not found for the selected item and price category.";
    }

    $stmt->close();
} else {
    echo "Error: Missing parameters.";
}

$conn->close();
?>
