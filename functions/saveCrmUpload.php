<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['id'])) {
    // Receive the ID from the POST data
    $id = $_POST['id'];

    // Ensure that the ID is valid (this is a simple validation, you can adjust as needed)
    if (empty($id) || !is_numeric($id)) {
        $response['message'] = 'Invalid ID.';
        echo json_encode($response);
        exit;
    }

    // Resolve the upload directory
    $uploadDir = realpath(__DIR__ . '/../assets/crmFiles') . '/';
    if ($uploadDir === false) {
        error_log('Failed to resolve the upload directory.');
        $response['message'] = 'Invalid upload directory path.';
        echo json_encode($response);
        exit;
    }

    $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    // Use the ID as the new file name with the original file extension
    $newFileName = $id . '.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;

    // Log resolved paths for debugging
    error_log('Resolved Upload Directory: ' . $uploadDir);
    error_log('Uploaded File Temp Path: ' . $_FILES['file']['tmp_name']);
    error_log('Target Upload Path: ' . $uploadPath);

    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $response['message'] = 'Failed to create the upload directory.';
            error_log('Failed to create upload directory: ' . $uploadDir);
            echo json_encode($response);
            exit;
        }
    }

    // Check if temp file exists
    if (file_exists($_FILES['file']['tmp_name'])) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
            $response['success'] = true;
            $response['message'] = 'File uploaded and renamed to ID successfully.';
        } else {
            $response['message'] = 'Failed to move the uploaded file.';
            error_log('Failed to move file to: ' . $uploadPath);
        }
    } else {
        $response['message'] = 'Temporary file not found.';
        error_log('Temp file missing: ' . $_FILES['file']['tmp_name']);
    }
} else {
    $response['message'] = 'No file uploaded or invalid request.';
}

echo json_encode($response);
?>
