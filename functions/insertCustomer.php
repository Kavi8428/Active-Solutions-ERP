<?php
include '../connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data)) {
        // Collect data from the request
        $companyName = $data['companyName'];
        $address = $data['address'];
        $companyType = $data['companyType'];
        $creditLimit = $data['creditLimit'];
        $customerNotes = $data['customerNotes'];
        $paymentTerms = $data['paymentTerms'];
        $vatNumber = $data['vatNumber'];

        // Insert query
        $sql = "INSERT INTO customer (company_name, address, type, credit_limit, note, payment_terms, vat)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssisss', $companyName, $address, $companyType, $creditLimit, $customerNotes, $paymentTerms, $vatNumber);

            if ($stmt->execute()) {
                echo "Customer added successfully"; // Return plain text success message
            } else {
                http_response_code(500);
                echo "Failed to add customer: " . $stmt->error; // Return plain text error message
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo "Failed to prepare SQL query: " . $conn->error; // Return plain text error message
        }

        $conn->close();
    } else {
        http_response_code(400);
        echo "Invalid data"; // Return plain text error message
    }
} else {
    http_response_code(405);
    echo "Method not allowed"; // Return plain text error message
}
?>
