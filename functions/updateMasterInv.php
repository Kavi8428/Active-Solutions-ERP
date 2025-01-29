<?php
header('Content-Type: application/json'); // Ensure the response is JSON

include '../connection.php';

// Sanitize inputs to avoid SQL syntax errors
$id = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
$inv = mysqli_real_escape_string($conn, $_POST['inv'] ?? '');
$grn = mysqli_real_escape_string($conn, $_POST['grn'] ?? '');
$gin = mysqli_real_escape_string($conn, $_POST['gin'] ?? '');
$customer = mysqli_real_escape_string($conn, $_POST['customer'] ?? '');
$itemCode = mysqli_real_escape_string($conn, $_POST['itemCode'] ?? '');
$item = mysqli_real_escape_string($conn, $_POST['item'] ?? '');
$itemDescription = mysqli_real_escape_string($conn, $_POST['itemDescription'] ?? '');
$brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
$serial = mysqli_real_escape_string($conn, $_POST['serial'] ?? '');
$value = mysqli_real_escape_string($conn, $_POST['value'] ?? '');
$qty = mysqli_real_escape_string($conn, $_POST['qty'] ?? '');
$status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');
$warranty = mysqli_real_escape_string($conn, $_POST['warranty'] ?? '');
$date = mysqli_real_escape_string($conn, $_POST['date'] ?? '');
$memo = mysqli_real_escape_string($conn, $_POST['memo'] ?? '');
$gp = mysqli_real_escape_string($conn, $_POST['gp'] ?? '');
$rep = mysqli_real_escape_string($conn, $_POST['rep'] ?? '');
$vat = mysqli_real_escape_string($conn, $_POST['vat'] ?? '');
$object = mysqli_real_escape_string($conn, $_POST['object'] ?? '');
$cusEmployee = mysqli_real_escape_string($conn, $_POST['cusEmployee'] ?? '');
$lInvNo = mysqli_real_escape_string($conn, $_POST['lInvNo'] ?? '');


$response = [];

// Debug: Log received data
error_log("Received data: " . json_encode($_POST));

// Split serials into an array
$serials = explode(',', $serial);

// Iterate over each serial
// Split serials into an array
$serials = explode(',', $serial);

// Iterate over each serial
foreach ($serials as $serial) {
    $serial = trim(mysqli_real_escape_string($conn, $serial)); // Trim and sanitize each serial

    // Assign a quantity of 1 for each individual serial
    $individualQty = 1;
    $individualValue = $value;

    // Check if the invoice (INV) already exists for this serial
    $sql = "SELECT * FROM masterinvoice WHERE inv = '$inv' AND itemCode = '$itemCode' AND serial = '$serial' AND status = '$status'";
    error_log("Executing query: $sql");
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update existing row
        $sql = "UPDATE masterinvoice 
                SET grn = '$grn', gin = '$gin', customer = '$customer', item = '$item', itemDescription = '$itemDescription', 
                    brand = '$brand', value = '$individualValue', qty = '$individualQty', object = '$object',
                    warranty = '$warranty', rep = '$rep', gp = '$gp', date = '$date', memo = '$memo', 
                    cusEmployee = '$cusEmployee', vat = '$vat', lInvNo = '$lInvNo' 
                WHERE inv = '$inv' AND itemCode = '$itemCode' AND serial = '$serial' AND status = '$status'";
        error_log("Executing query: $sql");
        if ($conn->query($sql) === TRUE) {
            $response['messages'][] = "Row updated successfully for serial: $serial";
        } else {
            $response['errors'][] = "Error updating row for serial $serial: " . $conn->error;
        }
    } else {
        // Insert new row
        $sql = "INSERT INTO masterinvoice (inv, grn, gin, customer, itemCode, item, itemDescription, brand, serial, value, qty, object, status, warranty, rep, gp, date, memo, cusEmployee, vat,lInvNo) 
                VALUES ('$inv', '$grn', '$gin', '$customer', '$itemCode', '$item', '$itemDescription', '$brand', '$serial', '$individualValue', '$individualQty', '$object', '$status', '$warranty', '$rep', '$gp', '$date', '$memo', '$cusEmployee', '$vat', '$lInvNo')";
        error_log("Executing query: $sql");
        if ($conn->query($sql) === TRUE) {
            $response['messages'][] = "New row inserted successfully for serial: $serial";
        } else {
            $response['errors'][] = "Error inserting row for serial $serial: " . $conn->error;
        }
    }
}


$conn->close();
echo json_encode($response); // Return response as JSON
?>