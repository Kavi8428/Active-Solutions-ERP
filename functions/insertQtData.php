<?php
include '../connection.php';

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize $stmt (assuming $conn is your mysqli connection object)
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the raw POST data is present
    $postdata = file_get_contents("php://input");

    if ($postdata) {
        // Decode the JSON data
        $requestData = json_decode($postdata, true);

        // Check if "itemsArray" and "generalData" are set in the decoded data
        if (isset($requestData["itemsArray"]) && isset($requestData["generalData"])) {
            $itemsArray = $requestData["itemsArray"];
            $generalData = $requestData["generalData"];

            // Insert into quotation table
            $stmtQuotation = $conn->prepare("INSERT INTO quotation (deal_name, deal_date, partner_name, partner_employee, project_month, end_customer, comment, sum, pmanager, gp, discount,validity,vated,discounted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
            $stmtLog = $conn->prepare("INSERT INTO log(user, task, date_time, other, serverity) VALUES (?, ?, NOW(), ?, ?)");
            $userName = sanitize($generalData["username"]);
            $task = sanitize($generalData["task"]);
            $dealName = sanitize($generalData["dealName"]);
            $dealDate = sanitize($generalData["dealDate"]);
            $partnerName = sanitize($generalData["partnerName"]);
            $partnerEmployee = sanitize($generalData["partnerEmployee"]);
            $projectMonth = sanitize($generalData["projectMonth"]);
            $endCustomer = sanitize($generalData["endCustomer"]);
            $comment = sanitize($generalData["comment"]);
            $sum = sanitize($generalData["sum"]);
            $pManager = sanitize($generalData["pManager"]);
            $gp = sanitize($generalData["gp"]);
            $discount = sanitize($generalData["discount"]);
            $validity = sanitize($generalData["validity"]);
            $checkDiscount = sanitize($generalData["checkDiscount"]);
            $checkVat = sanitize($generalData["checkVat"]);


            $stmtQuotation->bind_param("sssssssdsdddii", $dealName, $dealDate, $partnerName, $partnerEmployee, $projectMonth, $endCustomer, $comment, $sum, $pManager, $gp, $discount,$validity,$checkVat,$checkDiscount);
            $stmtQuotation->execute();

            // Get the last inserted ID
            $lastInsertedId = $stmtQuotation->insert_id;
            $other= "Qt Number:".$lastInsertedId;
            $serverity = "2";
            $stmtLog->bind_param("sssi", $userName,$task,$other,$serverity);
            $stmtLog->execute();

            

            $stmtQuotation->close();

            // Insert into quotation_products table
            $stmtItems = $conn->prepare("INSERT INTO quotation_products (deal_id, item_code, description, quantity, vat, price, total, checking) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($itemsArray as $item) {
                $itemCode = sanitize($item["item_code"]);
                $description = sanitize($item["description"]);
                $quantity = sanitize($item["quantity"]);
                $vat = sanitize($item["vat"]);
                $price = sanitize($item["price"]);
                $total = sanitize($item["total"]);
                $checking = sanitize($item["checking"]);

                // Adjust the number of placeholders in the SQL query to match the number of variables
                $stmtItems->bind_param("issiiiii", $lastInsertedId, $itemCode, $description, $quantity, $vat, $price, $total, $checking);
                $stmtItems->execute();
            }

            $stmtItems->close();

            // For testing purposes, we'll just print the data
            echo json_encode([ $lastInsertedId]);
        } else {
            // Handle the case when "itemsArray" or "generalData" is not set in the decoded data
            echo json_encode(['error' => 'itemsArray or generalData is not set in the decoded data']);
        }
    } else {
        // Handle the case when raw POST data is not present
        echo json_encode(['error' => 'No raw POST data']);
    }
}
?>
