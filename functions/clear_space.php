<?php
$directory = '../assets/backup/'; // Specify the directory path
$monthsToKeep = 3;

// Get the current timestamp
$currentTimestamp = time();

// Loop through the files in the directory
$files = glob($directory . '*');
foreach ($files as $file) {
    // Get the file's last modified timestamp
    $fileTimestamp = filemtime($file);

    // Calculate the difference in seconds
    $difference = $currentTimestamp - $fileTimestamp;

    // Calculate the difference in months
    $differenceInMonths = floor($difference / (30 * 24 * 60 * 60));

    // Delete the file if it's older than 3 months
    if ($differenceInMonths >= $monthsToKeep) {
        unlink($file);
        echo 'File deleted: ' . $file . '<br>';
    }
}

echo 'Space cleared successfully.';
?>
