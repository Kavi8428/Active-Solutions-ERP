<?php
include '../connection.php';

// Use $conn (assumed from your connection script) to initialize $mysqli
$mysqli = $conn;

// Get the raw POST data
$data = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($data);

// Check if data is received
if ($data) {
    // Access the values
    $vat = $data->vat;
    $grandTotal = $data->grandTotal;
    $code = $data->code;

    // Update quotation_products table (assuming deal_id is used as a unique identifier)
    $updateProductsQuery = "UPDATE quotation_products SET vat = ? WHERE deal_id = ?";
    $updateProductsStmt = $mysqli->prepare($updateProductsQuery);
    $updateProductsStmt->bind_param('ss', $vat, $code);
    $updateProductsStmt->execute();

    // Update quotation table (assuming deal_id is used as a unique identifier)
    $updateQuotationQuery = "UPDATE quotation SET sum = ? WHERE deal_id = ?";
    $updateQuotationStmt = $mysqli->prepare($updateQuotationQuery);
    $updateQuotationStmt->bind_param('ss', $grandTotal, $code);
    $updateQuotationStmt->execute();

    // Output success message
    echo "Data successfully updated.";
} else {
    // Output an error message if data is not received
    echo "Error: No data received.";
}
?>
