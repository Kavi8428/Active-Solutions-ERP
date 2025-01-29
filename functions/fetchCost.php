<?php
include '../connection.php';

// Use isset() to check if the parameters are set
if (isset($_GET['code'],)) {
    $itemCode = $_GET['code'];
    $cost = 'cost';

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT $cost FROM pricing WHERE item_code = ?");
    $stmt->bind_param("s", $itemCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $row[$cost];

      

        // Check if the response is properly encoded
        if (mb_detect_encoding($price, 'UTF-8', true) === false) {
            echo "Error: Response is not properly encoded.";
            exit;
        }

        // Return the price as plain text
        echo $price;
    } else {
        echo "Error: Price not found for the",$itemCode;
    }

    $stmt->close();
} else {
    echo "Error: Missing parameters.";
}

$conn->close();
?>
