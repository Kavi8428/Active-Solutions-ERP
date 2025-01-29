<?php
include '../connection.php';

// SQL query to fetch data from the table
$sql = "SELECT * FROM quotation_products";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // Push each row into the data array
        $data[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();

// Convert the data array to JSON
$data_json = json_encode($data);

// Return the JSON data
echo $data_json;
?>
