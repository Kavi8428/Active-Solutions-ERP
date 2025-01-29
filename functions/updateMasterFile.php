<?php
include '../connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering for response logging
ob_start();

try {
    $pdo = $conn; 

    // Get JSON input and decode
    $inputData = file_get_contents("php://input");
    file_put_contents('php_errors_log.txt', "Received input: " . $inputData . "\n", FILE_APPEND);

    $summaryData = json_decode($inputData, true);
    if (!is_array($summaryData)) {
        throw new Exception("Invalid JSON format");
    }

    $sql = "INSERT INTO masterfile (
                `ivn`,
                `invDate`,
                `soldTo`,
                `totalInvValue`,
                `synology`,
                `bdcom`,
                `draytec`,
                `hardDrives`,
                `acronis`,
                `gaj`,
                `maintain`,
                `labour`,
                `other`
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
            ON DUPLICATE KEY UPDATE
                `invDate` = VALUES(`invDate`),
                `soldTo` = VALUES(`soldTo`),
                `totalInvValue` = VALUES(`totalInvValue`),
                `synology` = VALUES(`synology`),
                `bdcom` = VALUES(`bdcom`),
                `draytec` = VALUES(`draytec`),
                `hardDrives` = VALUES(`hardDrives`),
                `acronis` = VALUES(`acronis`),
                `gaj` = VALUES(`gaj`),
                `maintain` = VALUES(`maintain`),
                `labour` = VALUES(`labour`),
                `other` = VALUES(`other`)";

    $stmt = $pdo->prepare($sql);

    if (!$stmt) {
        $prepareError = $pdo->error;
        file_put_contents('php_errors_log.txt', "SQL Prepare Error: " . $prepareError . "\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "SQL preparation failed.", "error" => $prepareError]);
        exit;
    }

    $errorLogs = [];
    foreach ($summaryData as $inv => $data) {
        if (!isset($data['inv']) || !isset($data['soldTo'])) {
            file_put_contents('php_errors_log.txt', "Missing key in data for $inv: " . print_r($data, true), FILE_APPEND);
            continue;
        }

        $params = [
            $data['inv'],
            $data['InvDate'],
            $data['soldTo'],
            $data['totalInvValue'],
            $data['Synology'],
            $data['bdcom'],
            $data['Draytec'],
            $data['hardDrives'],
            $data['acronis'],
            $data['gaj'],
            $data['maintain'],
            $data['labour'],
            $data['other']
        ];

        // Bind parameters and execute
        $stmt->bind_param(
            'sssssssssssss',
            $params[0], $params[1], $params[2], $params[3], $params[4],
            $params[5], $params[6], $params[7], $params[8], $params[9],
            $params[10], $params[11], $params[12]
        );

        if (!$stmt->execute()) {
            $errorLogs[] = [
                'inv' => $data['inv'],
                'error' => $stmt->error,
                'params' => $params
            ];
            file_put_contents('php_errors_log.txt', "SQL Execution Error: " . $stmt->error . "\nParams: " . print_r($params, true), FILE_APPEND);
        }
    }

    if (empty($errorLogs)) {
        echo json_encode(["status" => "success", "message" => "Data inserted/updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Some errors occurred.", "errors" => $errorLogs]);
    }

} catch (Exception $e) {
    file_put_contents('php_errors_log.txt', "General Error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$output = ob_get_clean(); // Capture all output
file_put_contents('response_log.txt', $output, FILE_APPEND); // Log output to response_log.txt
echo $output; // Send response back to client
?>
