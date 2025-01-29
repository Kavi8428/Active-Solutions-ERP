<?php
include '../connection.php';

$host = $hostname;
$db = $database; 
$user = $username;
$pass = $password; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    // Escape the input data
    $dealNo = isset($data['dealNo']) ? $pdo->quote($data['dealNo']) : null;
    $dealDate = $pdo->quote($data['dealDate']);
    $dealDescription = $pdo->quote($data['dealDescription']);
    $dealSalesRep = $pdo->quote($data['dealSalesRep']);
    $dealPartner = $pdo->quote($data['dealPartner']);
    $dealCustomer = $pdo->quote($data['dealCustomer']);
    $cusTel = $pdo->quote($data['cusTel']);
    $dealPartRep = $pdo->quote($data['dealPartRep']);
    $dealStage = $pdo->quote($data['dealStage']);
    $multyTender = $pdo->quote($data['multyTender']);

    // SQL query logic
    if (!empty($dealNo)) {
        // Check if the record exists
        $checkSql = "SELECT COUNT(*) FROM crmdata WHERE id = $dealNo AND salesRep = $dealSalesRep";
        $exists = $pdo->query($checkSql)->fetchColumn();

        if ($exists == 0) {
            // Record does not exist, INSERT
            $sql = "INSERT INTO crmdata (date, description, salesRep, partner, customer, cusTel, partnerRep, stage, multyTender) 
                    VALUES ($dealDate, $dealDescription, $dealSalesRep, $dealPartner, $dealCustomer,$cusTel, $dealPartRep, $dealStage, $multyTender)";
        } else {
            // Record exists, UPDATE
            $sql = "UPDATE crmdata SET 
                        date = $dealDate, 
                        description = $dealDescription, 
                        salesRep = $dealSalesRep, 
                        partner = $dealPartner, 
                        customer = $dealCustomer, 
                        cusTel = $cusTel, 
                        partnerRep = $dealPartRep, 
                        stage = $dealStage,
                        multyTender = $multyTender
                    WHERE id = $dealNo";
        }
    } else {
        // No ID provided, INSERT
        $sql = "INSERT INTO crmdata (date, description, salesRep, partner, customer,cusTel, partnerRep, stage, multyTender) 
                VALUES ($dealDate, $dealDescription, $dealSalesRep, $dealPartner, $dealCustomer, $cusTel, $dealPartRep, $dealStage, $multyTender)";
    }

    // Execute the SQL
    $pdo->exec($sql);

    // Success response
    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.']);
} catch (PDOException $e) {
    // Error handling
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>