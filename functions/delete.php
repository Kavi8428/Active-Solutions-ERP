<?php
if (isset($_POST['file'])) {
    $requestedFile = $_POST['file'];

    $allowedPaths = [
        '../assets/',
        '../assets/backup/folder2/',
        '../assets/backup/folder3/',
        '../assets/backup/folder4/',
        '../assets/backup/folder5/'
    ];

    $found = false;
    foreach ($allowedPaths as $allowedPath) {
        $filePath = $allowedPath . $requestedFile;
        if (file_exists($filePath)) {
            $found = true;
            if (unlink($filePath)) {
                echo 'File deleted successfully.';
            } else {
                echo 'Error deleting file.';
            }
            break; // Exit the loop after successful deletion
        }
    }

    if (!$found) {
        echo 'File not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
