<?php
// Invoice-api.php
include '../connection.php'; // Include DB connection

header('Content-Type: application/json');

try {
    $query = "
        SELECT i.id, i.inv, i.customer, i.inv_date, i.po_num, i.rep, i.terms, i.shipping_date, i.vat, i.discountValue, i.discountStatus, i.cusEmployee, i.object, i.status, i.inventory, i.lInvNo,
               it.item_code, it.qt, it.serials, it.description, it.unit_price, it.total, it.vat AS item_vat, it.warranty, it.gp
        FROM invoice i
        LEFT JOIN invoice_items it ON i.id = it.inv_no
        ORDER BY i.id DESC";
    
    $result = $conn->query($query);
    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }

    echo json_encode(["success" => "success", "status" => 200, "data" => $invoices]);
} catch (Exception $e) {
    echo json_encode(["success" => "fail", "status" => 500, "message" => "Internal Server Error"]);
}

$conn->close();
?>
