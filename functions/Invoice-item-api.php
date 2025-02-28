<?php
include '../connection.php'; // Database connection

header('Content-Type: application/json');

if (!isset($_GET['invoice_id'])) {
    echo json_encode(["success" => "fail", "status" => 400, "message" => "Missing invoice ID"]);
    exit;
}

$invoiceId = $_GET['invoice_id'];

try {
    $query = "SELECT item_code, description, qty, price FROM invoice_items WHERE inv_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode(["success" => "success", "status" => 200, "data" => $items]);
} catch (Exception $e) {
    echo json_encode(["success" => "fail", "status" => 500, "message" => "Internal Server Error"]);
}

$conn->close();
?>
