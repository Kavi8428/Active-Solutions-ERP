<?php
// Database connection
include '../connection.php';

// Fetching the general data
$ginNo = $_POST['ginNo'];
$customer = $_POST['customer'];
$date = $_POST['date'];
$eCustomer = $_POST['eCustomer'];
$object = $_POST['object'];
$invNo = $_POST['invNo'];

// Fetching the items data
$itemsData = json_decode($_POST['itemsData'], true);

// Insert or update general data into gin_data table
$sql = "INSERT INTO gin_data (id, customer, end_customer, date, object, invNo)
        VALUES ('$ginNo', '$customer', '$eCustomer', '$date', '$object', '$invNo')
        ON DUPLICATE KEY UPDATE 
            customer = '$customer',
            end_customer = '$eCustomer',
            date = '$date',
            object = '$object',
            invNo = '$invNo'";
if ($conn->query($sql) === TRUE) {
    // Get the gin_id
    if ($conn->affected_rows > 0) {
        $gin_id = $conn->insert_id;
    } else {
        $result = $conn->query("SELECT id FROM gin_data WHERE id = '$ginNo'");
        $row = $result->fetch_assoc();
        $gin_id = $row['id'];
    }

    // Iterate through each item in the itemsData array
    foreach ($itemsData as $item) {
        $itemCode = $item['itemCode'];
        $quantity = $item['quantity'];
        $serialNumbers = $item['serialNumbers'];

        // Convert serial numbers array to a string
        $serialNumbersString = implode(',', $serialNumbers);

        // Debugging: Log the values before inserting/updating
        error_log("Processing item: gin_id=$gin_id, itemCode=$itemCode, quantity=$quantity, serials=$serialNumbersString");

        // Step 2: Check if the combination of gin_id and serial already exists in the gin_items table
        $query = "SELECT * FROM gin_items WHERE gin_id = ? AND serial LIKE ?";
        $stmt = $conn->prepare($query);
        $serialSearch = "%" . $serialNumbersString . "%";  // Use LIKE for partial matching
        $stmt->bind_param("is", $gin_id, $serialSearch);
        $stmt->execute();
        $result = $stmt->get_result();

        // Step 3: If the record exists, update it
        if ($result->num_rows > 0) {
            // Record found, update the existing record
            $updateQuery = "UPDATE gin_items SET quantity = ?, serial = ? WHERE gin_id = ? AND serial LIKE ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("isis", $quantity, $serialNumbersString, $gin_id, $serialSearch);
            $updateStmt->execute();

            echo "Record updated successfully.";
        } else {
            // Record not found, insert a new one
            $insertQuery = "INSERT INTO gin_items (gin_id, itemCode, quantity, serial) VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("isis", $gin_id, $itemCode, $quantity, $serialNumbersString);
            $insertStmt->execute();

            echo "Record inserted successfully.";
        }

        // Check if serialNumbers array is not empty
        if (empty($serialNumbers)) {
            echo json_encode(["status" => "error", "message" => "Serial numbers are empty for item code: $itemCode"]);
            $conn->close();
            exit();
        }

        // Insert new rows into masterinvoice table for each serial number
        foreach ($serialNumbers as $serial) {
            // Check if the data already exists in the masterinvoice table
            $checkSql = "SELECT * FROM masterinvoice 
                         WHERE inv='$invNo' AND gin='$gin_id' AND customer='$customer' 
                         AND itemCode='$itemCode' AND serial='$serial'";
            $result = $conn->query($checkSql);

            // If no rows are returned, insert the data
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO masterinvoice (inv, gin, customer, itemCode, serial, qty, object, status, date) 
                        VALUES ('$invNo', '$gin_id', '$customer', '$itemCode', '$serial', '1', '$object', 'GIN', '$date')";
                if (!$conn->query($sql)) {
                    error_log("Error inserting data for serial: $serial");
                    echo json_encode(["status" => "error", "message" => "Error inserting data for serial: $serial"]);
                    $conn->close();
                    exit();
                }
            }
        }
    }
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

error_log("Query: $sql");

$conn->close();
?>
