<?php
include '../connection.php';

if (isset($_POST['item_code'])) {
    $selectedItemCode = $_POST['item_code'];

    // Query the database to retrieve the short description based on the selected item code
    $sql = "SELECT short_des FROM product WHERE item_code = '$selectedItemCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shortDescription = $row['short_des'];
       
    } else {
        $shortDescription = "No data found for the selected item code.";
    }
} else {
    $shortDescription = ""; // Initialize with an empty value
}

// Close the database connection
$conn->close();

echo $shortDescription; // Return the short description as the response
?>
