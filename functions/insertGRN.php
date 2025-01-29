<?php
include '../connection.php';

// Get general data from the form
$id = $_POST['id'];
$supplier = $_POST['supplier'];
$date = $_POST['date'];
$method = $_POST['method'];
$invNo = $_POST['invNo'];
$object = $_POST['object'];

// Prepare the SQL query to check if the record exists
$checkSql = "SELECT id FROM grn_data WHERE id = '$id'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) {
    // If record exists, update it
    $updateSql = "UPDATE grn_data SET invNo = '$invNo', date = '$date', supplier = '$supplier', method = '$method', object = '$object' WHERE id = '$id'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Record updated successfully.<br>";
    } else {
        echo "Error updating record: " . $conn->error;
        exit();
    }
} else {
    // If record does not exist, insert new data
    $insertSql = "INSERT INTO grn_data (id, invNo, date, supplier, method, object) VALUES ('$id', '$invNo', '$date', '$supplier', '$method', '$object')";
    if ($conn->query($insertSql) === TRUE) {
        $grn_id = $conn->insert_id; // Get the last inserted ID for the grn_id
        echo "New record created successfully. Last inserted ID is: " . $grn_id . "<br>";
    } else {
        echo "Error inserting record: " . $conn->error;
        exit();
    }
}


// Decode the JSON strings into arrays
$columnId = json_decode($_POST['index'], true);
$itemCodes = json_decode($_POST['itemCode'], true);
$quantities = json_decode($_POST['quantity'], true);
$serialNumbers = json_decode($_POST['serialNumbers'], true);
$descriptions = json_decode($_POST['description'], true);

if (count($itemCodes) !== count($quantities) || count($itemCodes) !== count($serialNumbers) || count($itemCodes) !== count($columnId)) {
    echo "Error: Array lengths do not match.";
    exit();
}

foreach ($itemCodes as $index => $itemCode) {
    $quantity = $quantities[$index];
    $serials = $serialNumbers[$index]; // Already a comma-separated string
    $currentId = $columnId[$index]; // Use the correct id for this item
    $description = $descriptions[$index];

    $checkGrnItems = "SELECT grn_id FROM grn_items WHERE id = '$currentId'";
    $result = $conn->query($checkGrnItems);

    if ($result->num_rows > 0) {
        // If the record exists, update it
        $updateSql = "UPDATE grn_items 
                      SET itemCode = '$itemCode', quantity = '$quantity', serial = '$serials', description = '$description'
                      WHERE id = '$currentId'";
        if ($conn->query($updateSql) === TRUE) {
            echo "Item record updated successfully for grn_id: " . $currentId . "<br>";
        } else {
            echo "Error updating item record: " . $conn->error;
        }
    } else {
        // If the record does not exist, insert new data
        $insertSql = "INSERT INTO grn_items (grn_id, itemCode, quantity, serial,description) 
                      VALUES ('$id', '$itemCode', '$quantity', '$serials', '$description')";
        if ($conn->query($insertSql) === TRUE) {
            echo "New item record created successfully for grn_id: " . $id . "<br>";
        } else {
            echo "Error inserting item record: " . $conn->error;
        }
    }



    $query = "SELECT * FROM stock WHERE item_code = '$itemCode'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        // No matching item, insert new stock
        $insertStock = "INSERT INTO stock(item_code, qty) VALUES ('$itemCode', '$quantity')";
        if ($conn->query($insertStock) === TRUE) {
            $serialsArray = explode(',', $serials);
            foreach ($serialsArray as $serial) {
                $insertStockSerial = "INSERT INTO stock_serials(item_code, serial) VALUES('$itemCode', '$serial')";
                if ($conn->query($insertStockSerial) === TRUE) {
                    echo "Stock Inserted Successfully for item code: " . $itemCode . " with serial: " . $serial . "<br>";
                } else {
                    echo "Stock serials not inserted successfully: " . $insertStockSerial . "<br>" . $conn->error;
                }
            }
        } else {
            echo "Stock not inserted successfully: " . $insertStock . "<br>" . $conn->error;
        }
    } else {
        // Matching item found, update stock
        if($object == 'newStock' || $object == 'return'){
        $updateStock = "UPDATE stock SET qty = qty + '$quantity' WHERE item_code = '$itemCode'";
        if ($conn->query($updateStock) === TRUE) {
            $serialsArray = explode(',', $serials);
            foreach ($serialsArray as $serial) {
                $updateStockSerials = "INSERT INTO stock_serials(item_code, serial) VALUES('$itemCode', '$serial')";
                if ($conn->query($updateStockSerials) === TRUE) {
                    echo "Stock Updated Successfully for item code: " . $itemCode . " with serial: " . $serial . "<br>";
                } else {
                    echo "Stock serials not updated successfully: " . $updateStockSerials . "<br>" . $conn->error;
                }
            }
        } else {
            echo "Stock not updated successfully: " . $updateStock . "<br>" . $conn->error;
        }
        }else {
            echo "This is warranty in. so stock will not be updated: ". "<br>" . $conn->error;
        }
    }

    

    $serialsArray = explode(',', $serials);

    foreach ($serialsArray as $serial) {
        // Check if the row already exists in the database
        $checkSql = "SELECT * FROM masterinvoice 
                     WHERE inv = '$invNo' 
                     AND grn = '$id' 
                     AND customer = '$supplier' 
                     AND itemCode = '$itemCode' 
                     AND serial = '$serial' 
                     AND qty = '1' 
                     AND status = 'GRN' 
                     AND date = '$date' 
                     AND object = '$object'";
    
        $checkResult = $conn->query($checkSql);
    
        if ($checkResult->num_rows > 0) {
            // If the data already exists, do nothing
            echo "Row already exists for serial: $serial <br>";
        } else {
            // If the data does not exist, insert it as a new row
            $sqlInvoice = "INSERT INTO masterinvoice (inv, grn, customer, itemCode,itemDescription, serial, qty, status, date, object) 
                           VALUES ('$invNo', '$id', '$supplier', '$itemCode','$description', '$serial', '1', 'GRN', '$date', '$object')";
            
            if ($conn->query($sqlInvoice) === TRUE) {
                echo "Inserted successfully for serial: $serial <br>";
            } else {
                echo "Error: " . $sqlInvoice . "<br>" . $conn->error;
            }
        }
    }
    
}

$conn->close();
