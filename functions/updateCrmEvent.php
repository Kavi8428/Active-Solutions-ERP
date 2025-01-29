<?php
include '../connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

if (isset($data['id']) && isset($data['trnNo']) && isset($data['salesRep'])) {
    $id = $data['id'];
    $trnNo = $data['trnNo'];
    $salesRep = $data['salesRep'];

    // Check if the record exists based on 'id' and 'salesRep'
    $sql_check = "SELECT id FROM crmitems WHERE id = ? AND salesRep = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $id, $salesRep);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Record exists, update it
        $sql = "UPDATE crmitems SET 
                    date = ?, 
                    action = ?, 
                    type = ?, 
                    followUp = ?, 
                    fupUser = ?, 
                    fupAction = ?, 
                    brand = ?, 
                    model = ?, 
                    inv = ?, 
                    qtNo = ?, 
                    media = ?,
                    supTicket = ?,
                    gp = ?,
                    gpMonth = ?
                WHERE id = ? AND salesRep = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssis", 
            $data['date'], 
            $data['action'], 
            $data['type'], 
            $data['followup'], 
            $data['fup'], 
            $data['fupAction'], 
            $data['brand'], 
            $data['model'], 
            $data['inv'], 
            $data['quote'], 
            $data['media'], 
            $data['supTicket'], 
            $data['gp'], 
            $data['gpMonth'], 
            $id,
            $salesRep
        );

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Data updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to execute update.']);
        }
    } else {
        // Record does not exist, insert new record
        // Ensure that the trnNo exists in crmdata table
        $sql_check_trn = "SELECT id FROM crmdata WHERE id = ?";
        $stmt_check_trn = $conn->prepare($sql_check_trn);
        $stmt_check_trn->bind_param("i", $trnNo);
        $stmt_check_trn->execute();
        $stmt_check_trn->store_result();

        if ($stmt_check_trn->num_rows > 0) {
            // trnNo exists, insert new record
            $sql = "INSERT INTO crmitems (trnNo, date, action, salesRep, type, media, followUp, fupUser,fupAction, brand, model, inv, qtNo,supTicket,gp,gpMonth) 
                    VALUES (?, ?, ?, ?, ?, ?,?,?, ?, ?, ?, ?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssssssssss", 
                $trnNo, 
                $data['date'], 
                $data['action'], 
                $salesRep, 
                $data['type'], 
                $data['media'], 
                $data['followup'], 
                $data['fup'], 
                $data['fupAction'], 
                $data['brand'], 
                $data['model'], 
                $data['inv'], 
                $data['quote'],
                $data['supTicket'],
                $data['gp'],
                $data['gpMonth']
            );

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Data inserted successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to execute insert.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid trnNo. The trnNo does not exist in crmdata table.']);
        }

        $stmt_check_trn->close();
    }

    $stmt_check->close();
} else {
    echo json_encode(['error' => 'Missing id, trnNo, or salesRep field.']);
}

// Close the connection
$conn->close();
?>