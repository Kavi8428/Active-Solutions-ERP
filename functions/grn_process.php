<?php
include '../connection.php';

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // General data
    $date = sanitize($_POST["date"]);
    $supplier = sanitize($_POST["supplier"]);
    $method = sanitize($_POST["method"]);

    // Insert into grn_data table
    $stmt = $conn->prepare("INSERT INTO grn_data (date, supplier, method) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $date, $supplier, $method);
    $stmt->execute();

    // Get the last inserted ID
    $grnId = $stmt->insert_id;

    // Items data
    $itemCodes = $_POST["itemCode"];
    $quantities = $_POST["quantity"];

    // Insert into grn_items table
    $stmtItems = $conn->prepare("INSERT INTO grn_items (grn_id, itemCode, quantity) VALUES (?, ?, ?)");

    for ($i = 0; $i < count($itemCodes); $i++) {
        $itemCode = sanitize($itemCodes[$i]);
        $quantity = sanitize($quantities[$i]);
        $stmtItems->bind_param("iss", $grnId, $itemCode, $quantity);
        $stmtItems->execute();

        // Get the last inserted item ID
        $itemId = $stmtItems->insert_id;

        // Decode serial numbers for the current item from JSON
        $itemsArrayString = $_POST["itemsArray"];
        $itemsArray = json_decode($itemsArrayString, true);

        // Check if the "serialNumbers" key is present in the current item's array
        if (isset($itemsArray[$i]['serialNumbers'])) {
            foreach ($itemsArray[$i]['serialNumbers'] as $serialNumber) {
                $serialNumber = sanitize($serialNumber);

                // Insert into grn_serial_numbers table
                $stmtSerial = $conn->prepare("INSERT INTO grn_serial_numbers (item_id, serialNumber) VALUES (?, ?)");
                $stmtSerial->bind_param("is", $itemId, $serialNumber);
                $stmtSerial->execute();
                $stmtSerial->close();
            }
        }
    }

    $stmtItems->close();

    // For testing purposes, we'll just print the data
    echo json_encode(['grnId' => $grnId, 'date' => $date, 'supplier' => $supplier, 'method' => $method, 'itemCodes' => $itemCodes, 'quantities' => $quantities, 'itemsArray' => $itemsArray]);
}
?>
