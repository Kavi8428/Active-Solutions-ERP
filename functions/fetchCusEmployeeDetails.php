<?php
include '../connection.php';

// Retrieve the company value from the query string
$company = $_GET['company'];

// Basic validation to ensure the company value is not empty
if (empty($company)) {
    echo json_encode(array('error' => 'Company name is required'));
    exit;
}

// Fetch customer ID based on the company name
$customerID = $conn->query("SELECT customer_id FROM customer WHERE company_name = '$company'");
$cusID = '';
if ($customerID->num_rows > 0) {
    $row = $customerID->fetch_assoc();
    $cusID = $row['customer_id'];
} else {
    $response['error'] = 'No data found for the given company name';
    echo json_encode($response);
    exit;
}

// Fetch employee IDs associated with the customer ID
$cusEmpID = $conn->query("SELECT cus_em_id FROM cus_emp_junc WHERE customer_id = '$cusID'");
$empIDs = [];
while ($row = $cusEmpID->fetch_assoc()) {
    $empIDs[] = $row['cus_em_id'];
}

// Fetch employee details based on employee IDs
$data = [];
if (!empty($empIDs)) {
    $empIDList = implode(",", $empIDs);
    $cusEmpName = $conn->query("SELECT * FROM customer_employee WHERE cus_em_id IN ($empIDList)");

    if ($cusEmpName->num_rows > 0) {
        while ($row = $cusEmpName->fetch_assoc()) {
            $data[] = $row; // Add the entire row to the $data array
        }
    } else {
        $response['error'] = 'No data found for the given employee IDs';
        echo json_encode($response);
        exit;
    }
} else {
    $response['error'] = 'No employee IDs found for the given customer ID';
    echo json_encode($response);
    exit;
}

// Prepare the response data as an associative array
$responseData = array(
    'cusEmpDetails' => $data // Keep $data as an array of employee details
);

// Send the response back as JSON
header('Content-Type: application/json');
echo json_encode($responseData);
?>
