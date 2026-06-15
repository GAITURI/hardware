<?php
// 1. Establish the database connection
require_once __DIR__ . '/../db_connection.php';

try {
    // 2. Query the database using the updated Mellar Outdoors schema layout
    // Maps item_id as 'id', old_price as 'oldPrice', and category_id/promotions for display tracking
    $sql = "SELECT
                item_id AS id,
                name,
                description,
                image_url,
                category_id AS category,
                price,
                old_price AS oldPrice,
                color,
                material,
                seating_capacity,
                is_latest,
                is_hot_deal AS onSale
            FROM items";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // 3. Fetch all products as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Clean Data Normalization Layer
    // Normalizes paths if image_url is missing, ensuring arrays conform perfectly
    foreach ($products as &$product) {
        // Enforce integer types for JS strict equality checks on the dashboard layout
        $product['id'] = (int)$product['id'];
        $product['price'] = (float)$product['price'];
        $product['oldPrice'] = $product['oldPrice'] !== null ? (float)$product['oldPrice'] : null;
        $product['seating_capacity'] = (int)$product['seating_capacity'];
        
        // Convert TinyInt flags from MySQL safely to true native Boolean flags for app.js
        $product['is_latest'] = (bool)$product['is_latest'];
        $product['onSale'] = (bool)$product['onSale'];

        // Path normalization rule: points to the root directory outside of dashboard
        if (empty($product['image_url'])) {
            $product['image_url'] = 'images/default_placeholder.jpg';
        }
    }
    unset($product); // Break reference pointer loop cleanly

    // 5. Return clean JSON to application workers
    header('Content-Type: application/json');
    echo json_encode($products);

} catch (PDOException $e) {
    // 6. Handle errors securely and notify asynchronous fetch instances
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'API Catalog Retrieval Failed: ' . $e->getMessage()]);
}
exit;
?>