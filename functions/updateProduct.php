<?php
// Check if the itemCodeAndDescription data is received
if (isset($_POST['itemCodeAndDescription'])) {
   
    include '../connection.php';

    // Prepare and bind the SQL statement to update existing item codes
    $stmt = $conn->prepare("UPDATE product SET short_des = ? WHERE item_code = ?");
    $stmt->bind_param("ss", $description, $itemCode);

    // Update each item code and description in the product table if it already exists
    foreach ($_POST['itemCodeAndDescription'] as $item) {
        $itemCode = $item['ItemCode'];
        $description = $item['Description'];
        
        // Check if the item code exists in the database
        $checkStmt = $conn->prepare("SELECT item_code FROM product WHERE item_code = ?");
        $checkStmt->bind_param("s", $itemCode);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        // If the item code exists, then update the description
        if ($checkStmt->num_rows > 0) {
            $stmt->execute();
        }
        
        $checkStmt->close();
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();

    echo "Item codes and descriptions updated successfully";
} else {
    echo "No item codes and descriptions received";
}
?>
