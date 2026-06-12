<?php
// 1. Establish the database connection
require_once __DIR__ . '/../db_connection.php';

try {
    // 2. Query the database, ensuring we explicitly select the 'id' column
    // Replace 'id' with your actual primary key column name if it differs (e.g., 'item_id')
    $sql = "SELECT id, name, description, image_url, category, price, oldPrice, rating, onSale 
            FROM hardware_items";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // 3. Fetch all products as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Return as clean JSON
    header('Content-Type: application/json');
    echo json_encode($products);

} catch (PDOException $e) {
    // 5. Handle errors silently or return an error message
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}

// // ... inside your PHP file
// $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Add this to verify the file is actually running:
// $products[] = ['name' => 'DEBUG_TEST_FILE_LOADED', 'id' => 999]; 

// header('Content-Type: application/json');
// echo json_encode($products);
exit;
?>