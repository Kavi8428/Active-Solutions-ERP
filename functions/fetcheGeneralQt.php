<?php
include '../connection.php';

// Check if any parameters are set in the GET request
if (!empty($_GET)) {
    // Check if the 'code' parameter is set
    if (isset($_GET['code'])) {
        // Sanitize and store the 'code' parameter
        $code = htmlspecialchars($_GET['code']);
    
        // Prepare and execute a MySQL query to fetch data based on the 'code'
        $sql = "SELECT * FROM quotation WHERE deal_id = $code";
        $result = $conn->query($sql);
    
        // Check if the query was successful
        if ($result) {
            // Fetch the data as an associative array
            $row = $result->fetch_assoc();
    
            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            // If the query fails, display an error message
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("error" => "Error executing query: " . $conn->error));
        }
    } else {
        // If 'code' parameter is not set, display an error message
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array("error" => "Error: 'code' parameter is not set in the request."));
    }
} else {
    // If no parameters are set, display a general message
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("error" => "No parameters received in the request."));
}
?>
