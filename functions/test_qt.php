<?php
include '../connection.php';
if (isset($_GET['code'])) {
    $redirection_code = $_GET['code'];

    // Use the $redirection_code to fetch data from the database
    $query = "SELECT * FROM quotation_products WHERE redirection_code = '$redirection_code'";
    $result = mysqli_query($conn, $query);

    // Fetch data and return it as JSON
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode($data);
}?>