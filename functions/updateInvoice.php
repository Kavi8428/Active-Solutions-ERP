<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response header to JSON
header('Content-Type: application/json');

// Include the database connection file
include '../connection.php';  // Adjust the path if necessary

// Function to return JSON error responses
function jsonError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

// Get the raw POST data from the AJAX request
$rawData = file_get_contents('php://input');

// Debug: Log the raw data
file_put_contents('debug_raw_data.log', print_r($rawData, true));

// Decode the JSON data into an associative array
$data = json_decode($rawData, true);

// Check if JSON decode was successful
if ($data === null) {
    file_put_contents('debug_json_error.log', "Error decoding JSON: " . json_last_error_msg());
    jsonError('Error decoding JSON.');
}

// Debug: Log the decoded data
file_put_contents('debug_decoded_data.log', print_r($data, true));

// Check if data is received
if ($data && isset($data['generalData']) && isset($data['invItems']) && isset($data['removedRows'])) {
    // Extract generalData and invItems
    $generalData = $data['generalData'];
    $invItems = $data['invItems'];
    $removedRows = $data['removedRows'];

    // Log removedRows for debugging
    file_put_contents('debug_removed_rows.log', print_r($removedRows, true));

    // Collect general data
    $id = $generalData['id'];
    $inv = $generalData['inv'];
    $customer = $generalData['customer'];
    $cusEmployee = $generalData['cusEmployee'];
    $invDate = $generalData['invDate'];
    $poNo = $generalData['poNo'];
    $rep = $generalData['rep'];
    $terms = $generalData['terms'];
    $shipping = $generalData['shipping'];
    $vat = $generalData['vat'];
    $discountValue = $generalData['discountValue'];
    $discountStatus = $generalData['discountType'];
    $object = $generalData['object'];
    $status = $generalData['status'];
    $inventory = $generalData['inventory'];
    $lInvNo = $generalData['lInvNo'];

    // Check if the invoice with the given ID already exists
    $checkQuery = "SELECT * FROM invoice WHERE id = '$id' AND inv = '$inv' ";
    $result = $conn->query($checkQuery);

    if ($result === false) {
        jsonError('Database error during invoice check: ' . $conn->error);
    }

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE invoice SET inv='$inv', customer = '$customer', inv_date = '$invDate', po_num = '$poNo', 
                        rep = '$rep', terms = '$terms', shipping_date = '$shipping', vat = '$vat' , discountValue = '$discountValue' , 
                        discountStatus = '$discountStatus', cusEmployee = '$cusEmployee', object = '$object',status = '$status',inventory = '$inventory',lInvNo = '$lInvNo'
                        WHERE id = '$id'";

        if ($conn->query($updateQuery) === TRUE) {
            $response['message'] = 'Invoice data updated successfully';
        } else {
            jsonError('Error updating invoice: ' . $conn->error);
        }

    } else {
        $insertQuery = "INSERT INTO invoice (id, inv, customer, inv_date, po_num, rep, terms, shipping_date, vat, discountValue,discountStatus, cusEmployee, object,status,inventory,lInvNo) 
                        VALUES ('$id', '$inv', '$customer', '$invDate', '$poNo', '$rep', '$terms', '$shipping', '$vat' , '$discountValue', '$discountStatus', '$cusEmployee', '$object', '$status', '$inventory', '$lInvNo')";

        if ($conn->query($insertQuery) === TRUE) {
            $response['message'] = 'Invoice data inserted successfully';
        } else {
            jsonError('Error inserting invoice: ' . $conn->error);
        }
    }

    foreach ($invItems as $item) {
        $itemCode = $item['ITEM'];
        $quantity = $item['QUANTITY'];
        $serials = $item['SERIALS'];
        $description = $item['DESCRIPTION'];
        $unitPrice = $item['UNIT PRICE'];
        $total = $item['TOTAL'];
        $vatValue = $item['VAT'];
        $gp = $item['GP'];
        $warranty = $item['WARRANTY'];
        

        // Check if the item already exists for this invoice number
        $itemCheckQuery = "SELECT * FROM invoice_items WHERE inv_no = '$id' AND item_code = '$itemCode' AND serials = '$serials' ";
        $itemResult = $conn->query($itemCheckQuery);

        if ($itemResult === false) {
            jsonError('Database error during item check: ' . $conn->error);
        }

        if ($itemResult->num_rows > 0) {
            // Item exists, so update it
            $itemUpdateQuery = "UPDATE invoice_items 
                                SET qt = '$quantity', serials = '$serials', description = '$description', unit_price = '$unitPrice', total = '$total', vat = '$vatValue', gp = '$gp', warranty = '$warranty'
                                WHERE inv_no = '$id' AND item_code = '$itemCode'";

            if ($conn->query($itemUpdateQuery) !== TRUE) {
                jsonError('Error updating invoice item: ' . $conn->error);
            }
        } else {
            // Item does not exist, so insert it
            $itemInsertQuery = "INSERT INTO invoice_items (inv_no, item_code, qt, serials, description, unit_price, total, vat, gp, warranty) 
            VALUES ('$id', '$itemCode', '$quantity', '$serials', '$description', '$unitPrice', '$total', '$vatValue', '$gp', '$warranty')";

            if ($conn->query($itemInsertQuery) !== TRUE) {
                jsonError('Error inserting invoice item: ' . $conn->error);
            }
        }
    }

    foreach ($removedRows as $removedItem) {
        $itemCode = $removedItem[0];  // ITEM (column index 0)
        $quantity = $removedItem[1];  // QUANTITY (column index 1)
        $serials = $removedItem[2];   // SERIALS (column index 2)
        $description = $removedItem[3];   // Description (column index 3)
        $unitPrice = $removedItem[4];   // U price (column index 4)
        $total = $removedItem[5];   // total (column index 5)

        // SQL query to delete the item from invoice_items table
        $deleteQuery = "DELETE FROM invoice_items WHERE inv_no = '$id' AND item_code = '$itemCode' AND serials = '$serials' AND description = '$description' AND unit_price = '$unitPrice'  AND total = '$total'";

        if ($conn->query($deleteQuery) !== TRUE) {
            jsonError('Error deleting invoice item: ' . $conn->error);
        }
    }

    $conn->close();
    $response['status'] = 'success';
    echo json_encode($response);

} else {
    file_put_contents('debug_invalid_data.log', "Invalid data received: " . print_r($data, true));
    jsonError('No data received or data is invalid.');
}
?>
