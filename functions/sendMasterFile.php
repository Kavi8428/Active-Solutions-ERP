<?php
include '../connection.php';

// Helper function to get a value from $_POST or return an empty string if it's not set
function getPostValue($key) {
    return isset($_POST[$key]) ? $_POST[$key] : "";
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request or set to empty string if not present
    $ivn = getPostValue('ivn');
    $sale = getPostValue('sale');
    $gpMonth = getPostValue('gpMonth');
    $invDate = getPostValue('invDate');
    $soldTo = getPostValue('soldTo');
    $endCustomer = getPostValue('endCustomer');
    $directPartner = getPostValue('directPartner');
    $salesPerson = getPostValue('salesPerson');
    $rep = getPostValue('rep');
    $totalInvValue = getPostValue('totalInvValue');
    $vat = getPostValue('vat');
    $nbt = getPostValue('nbt');
    $salesExcluTax = getPostValue('salesExcluTax');
    $total = getPostValue('total');
    $synology = getPostValue('Synology');
    $bdcom = getPostValue('bdcom');
    $draytec = getPostValue('Draytec');
    $zyxel = getPostValue('zyxel');
    $hardDrives = getPostValue('hardDrives');
    $acronis = getPostValue('acronis');
    $gaj = getPostValue('gaj');
    $network = getPostValue('network');
    $maintain = getPostValue('maintain');
    $labour = getPostValue('labour');
    $other = getPostValue('other');
    $product = getPostValue('product');
    $lb4 = getPostValue('lb4');
    $other5 = getPostValue('other5');
    $totalExclud = getPostValue('totalExclud');
    $pVat = getPostValue('pVat');
    $total6 = getPostValue('total6');
    $cost = getPostValue('cost');
    $sales = getPostValue('sales');
    $gp = getPostValue('gp');
    $gpp = getPostValue('gpp');
    $totalAmount = getPostValue('totalAmount');
    $paid = getPostValue('paid');
    $balance = getPostValue('balance');
    $days = getPostValue('days');
    $viable = getPostValue('viable');
    $datePaid = getPostValue('datePaid');
    $salesType = getPostValue('salesType');
    $warrantyEx = getPostValue('warrantyEx');
    $nextInv = getPostValue('nextInv');
    $contract = getPostValue('contract');
    $nextSalesPerson = getPostValue('nextSalesPerson');

    // Check if the invoice number exists
    $sql_check = "SELECT * FROM masterfile WHERE ivn = '$ivn'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Update the record if the invoice number matches
        $sqlUpdate = "
            UPDATE masterfile SET
                sale = '$sale',
                gpMonth = '$gpMonth',
                invDate = '$invDate',
                soldTo = '$soldTo',
                endCustomer = '$endCustomer',
                directPartner = '$directPartner',
                salesPerson = '$salesPerson',
                rep = '$rep',
                totalInvValue = '$totalInvValue',
                vat = '$vat',
                nbt = '$nbt',
                salesExcluTax = '$salesExcluTax',
                total = '$total',
                synology = '$synology',
                bdcom = '$bdcom',
                draytec = '$draytec',
                zyxel = '$zyxel',
                hardDrives = '$hardDrives',
                acronis = '$acronis',
                gaj = '$gaj',
                network = '$network',
                maintain = '$maintain',
                labour = '$labour',
                other = '$other',
                product = '$product',
                lb4 = '$lb4',
                other5 = '$other5',
                totalExclud = '$totalExclud',
                pVat = '$pVat',
                total6 = '$total6',
                cost = '$cost',
                sales = '$sales',
                gp = '$gp',
                gpp = '$gpp',
                totalAmount = '$totalAmount',
                paid = '$paid',
                balance = '$balance',
                days = '$days',
                viable = '$viable',
                datePaid = '$datePaid',
                salesType = '$salesType',
                warrantyEx = '$warrantyEx',
                nextInv = '$nextInv',
                contract = '$contract',
                nextSalesPerson = '$nextSalesPerson'
            WHERE ivn = '$ivn';
        ";

        if ($conn->query($sqlUpdate) === TRUE) {
            echo json_encode(['success' => 'Record updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating record: ' . $conn->error]);
        }
    } else {
        // Insert a new record if the invoice number does not exist
        $sqlInsert = "
            INSERT INTO masterfile (
                ivn, sale, gpMonth, invDate, soldTo, endCustomer, directPartner, salesPerson, rep, totalInvValue, vat, nbt, salesExcluTax, total, synology, bdcom, draytec, zyxel, hardDrives, acronis, gaj, network, maintain, labour, other, product, lb4, other5, totalExclud, pVat, total6, cost, sales, gp, gpp, totalAmount, paid, balance, days, viable, datePaid, salesType, warrantyEx, nextInv, contract, nextSalesPerson
            ) VALUES (
                '$ivn', '$sale', '$gpMonth', '$invDate', '$soldTo', '$endCustomer', '$directPartner', '$salesPerson', '$rep', '$totalInvValue', '$vat', '$nbt', '$salesExcluTax', '$total', '$synology', '$bdcom', '$draytec', '$zyxel', '$hardDrives', '$acronis', '$gaj', '$network', '$maintain', '$labour', '$other', '$product', '$lb4', '$other5', '$totalExclud', '$pVat', '$total6', '$cost', '$sales', '$gp', '$gpp', '$totalAmount', '$paid', '$balance', '$days', '$viable', '$datePaid', '$salesType', '$warrantyEx', '$nextInv', '$contract', '$nextSalesPerson'
            );
        ";

        if ($conn->query($sqlInsert) === TRUE) {
            echo json_encode(['success' => 'Record inserted successfully']);
        } else {
            echo json_encode(['error' => 'Error inserting record: ' . $conn->error]);
        }
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
