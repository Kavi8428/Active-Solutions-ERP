<?php
// Assuming you have already established a database connection
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deal_id = $_POST["deal_id"];
    $approve_value = $_POST["approve_value"];

    // Validate $deal_id and $approve_value if necessary


    $sql = "UPDATE quotation SET status = ? WHERE deal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $approve_value, $deal_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Deal status updated successfully";
    } else {
        echo "Error updating deal status";
    }

    $stmt->close();
    $conn->close();
}
?>
