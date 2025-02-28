<?php
include '../connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !is_array($data)) {
    echo json_encode(["error" => "Invalid data format"]);
    exit;
}

$conn->begin_transaction(); // Start transaction

try {
    foreach ($data as $invoice) {
        // Check if invoice exists
        $stmt_check_inv = $conn->prepare("SELECT id FROM invoice WHERE inv = ?");
        $stmt_check_inv->bind_param("s", $invoice['inv']);
        $stmt_check_inv->execute();
        $result = $stmt_check_inv->get_result();
        $existingInvoice = $result->fetch_assoc();

        if ($existingInvoice) {
            // Update existing invoice
            $invoice_id = $existingInvoice['id'];
            $stmt_update_inv = $conn->prepare("UPDATE invoice SET customer = ?, inv_date = ?, po_num = ?, rep = ?, terms = ?, shipping_date = ?, vat = ?, discountValue = ?, discountStatus = ?, cusEmployee = ?, object = ?, status = ?, inventory = ?, lInvNo = ? WHERE id = ?");
            $stmt_update_inv->bind_param("ssssssssssssssi",
                $invoice['customer'],
                $invoice['inv_date'],
                $invoice['po_num'],
                $invoice['rep'],
                $invoice['terms'],
                $invoice['shipping_date'],
                $invoice['vat'],
                $invoice['discountValue'],
                $invoice['discountStatus'],
                $invoice['cusEmployee'],
                $invoice['object'],
                $invoice['status'],
                $invoice['inventory'],
                $invoice['lInvNo'],
                $invoice_id
            );
            $stmt_update_inv->execute();
        } else {
            // Insert new invoice
            $stmt_insert_inv = $conn->prepare("INSERT INTO invoice (inv, customer, inv_date, po_num, rep, terms, shipping_date, vat, discountValue, discountStatus, cusEmployee, object, status, inventory, lInvNo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert_inv->bind_param("sssssssssssssss",
                $invoice['inv'],
                $invoice['customer'],
                $invoice['inv_date'],
                $invoice['po_num'],
                $invoice['rep'],
                $invoice['terms'],
                $invoice['shipping_date'],
                $invoice['vat'],
                $invoice['discountValue'],
                $invoice['discountStatus'],
                $invoice['cusEmployee'],
                $invoice['object'],
                $invoice['status'],
                $invoice['inventory'],
                $invoice['lInvNo']
            );
            $stmt_insert_inv->execute();
            $invoice_id = $conn->insert_id; // Get new invoice ID
        }

        // Check if invoice item exists
        $item = $invoice['invoiceItems'];
        $stmt_check_item = $conn->prepare("SELECT id FROM invoice_items WHERE inv_no = ? AND item_code = ?");
        $stmt_check_item->bind_param("is", $invoice_id, $item['item_code']);
        $stmt_check_item->execute();
        $result_item = $stmt_check_item->get_result();
        $existingItem = $result_item->fetch_assoc();

        if ($existingItem) {
            // Update existing item
            $stmt_update_item = $conn->prepare("UPDATE invoice_items SET qt = ?, serials = ?, description = ?, unit_price = ?, total = ?, vat = ?, warranty = ?, gp = ? WHERE id = ?");
            $stmt_update_item->bind_param("ssssssssi",
                $item['qt'],
                $item['serials'],
                $item['description'],
                $item['unit_price'],
                $item['total'],
                $item['vat'],
                $item['warranty'],
                $item['gp'],
                $existingItem['id']
            );
            $stmt_update_item->execute();
        } else {
            // Insert new item
            $stmt_insert_item = $conn->prepare("INSERT INTO invoice_items (inv_no, item_code, qt, serials, description, unit_price, total, vat, warranty, gp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert_item->bind_param("isssssssss",
                $invoice_id,
                $item['item_code'],
                $item['qt'],
                $item['serials'],
                $item['description'],
                $item['unit_price'],
                $item['total'],
                $item['vat'],
                $item['warranty'],
                $item['gp']
            );
            $stmt_insert_item->execute();
        }
    }

    $conn->commit(); // Commit transaction
    echo json_encode(["success" => "Data updated successfully"]);
} catch (Exception $e) {
    $conn->rollback(); // Rollback on error
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
