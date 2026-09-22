<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connection.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Return empty array if input is empty
if ($query === '') {
    echo json_encode([]);
    exit;
}

try {
    // Search matching products in name, description, or category
    $searchTerm = '%' . $query . '%';
    $stmt = $pdo->prepare("
        SELECT id, name, price, image_url, description 
        FROM products 
        WHERE name LIKE :term 
           OR description LIKE :term 
        LIMIT 8
    ");
    
    $stmt->execute(['term' => $searchTerm]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failure']);
}