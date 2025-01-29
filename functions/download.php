<?php

if (isset($_GET['file'])) {
    $requestedFile = $_GET['file'];
    $allowedPaths = [
        '../assets/',
        '../assets/',
        '../../assets/backup/folder3/',
        '../../assets/backup/folder4/',
        '../../assets/backup/folder5/'
    ];

    $found = false;
    foreach ($allowedPaths as $allowedPath) {
        $filePath = $allowedPath . $requestedFile;
        if (file_exists($filePath)) {
            $found = true;
            // Set the appropriate content type based on the file extension
            $contentType = mime_content_type($filePath);
            header('Content-Type: ' . $contentType);

            // Set content disposition to trigger download
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');

            // Read and output the file content
            readfile($filePath);
            exit;
        }
    }

    if (!$found) {
        echo 'File not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
