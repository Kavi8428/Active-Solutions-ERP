<?php
include("../connection.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=admin_active", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['search'])) {
    $searchTerm = '%' . $_POST['search'] . '%';  // Add % for partial matching

    // Customize the query based on the specific table structure
    $stmt = $pdo->prepare("SELECT 'product' AS source, item_code FROM product WHERE item_code LIKE :searchTerm 
                          UNION 
                          SELECT 'product_synology' AS source, cpuModel AS item_code FROM product_synology WHERE cpuModel LIKE :searchTerm
                          UNION 
                          SELECT 'product' AS source, item_code FROM product WHERE item_code LIKE :searchTerm ");
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output results as JSON
    echo json_encode($results);
}

?>
