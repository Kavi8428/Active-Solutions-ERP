<?php

header('Content-Type: application/json');

// Target directory where the files are stored
$targetDir = realpath(__DIR__ . '/../assets/crmFiles') . '/';

// Check if the directory exists
if (!is_dir($targetDir)) {
    echo json_encode(['success' => false, 'message' => 'Directory not found.']);
    exit;
}

// Check if the ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input
    
    // Construct the file path based on the ID
    $filePath = $targetDir . $id . '.pdf'; // Assuming .pdf extension

    // Check if the file exists
    if (file_exists($filePath)) {
        // Serve the file securely
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        // File not found
        echo json_encode(['success' => false, 'message' => 'I dont have any file for the this customer. Just try to insert file and save it']);
    }
} else {
    // Invalid or missing ID
    echo json_encode(['success' => false, 'message' => 'Invalid or missing ID provided.']);
}

?>
