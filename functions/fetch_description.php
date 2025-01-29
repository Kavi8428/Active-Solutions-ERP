
<?php
include '../connection.php'; // Include the database connection script

$itemCode = $_GET['code'];

$result = $conn->query("SELECT short_des,warrenty FROM product WHERE item_code = '$itemCode'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['short_des'];
    echo '       Warranty: '.$row['warrenty'];

}
$conn->close();
?>