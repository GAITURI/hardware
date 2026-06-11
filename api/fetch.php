<?php
require_once('db_connection.php');
session_start();

// Fetch product details dynamically based on ID, fall back to mock data if not found
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

try {
    $stmt = $pdo->prepare("SELECT * FROM hardware_items WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $product = null;
}

// Fallback mock array matching your UI screenshots exactly if the database row isn't ready
if (!$product) {
    $product = [
        'id' => 1,
        'name' => 'AWEI POWER BANK WITH BUILT-IN CABLES',
        'price' => 2500,
        'oldPrice' => 2799,
        'description' => 'The Awei Power Bank is your perfect travel companion, featuring built-in cables for ultimate convenience. With its sleek design and robust build, this power bank ensures your devices stay charged on the go. Ideal for tech-savvy individuals in Kenya, it offers reliable power backup for smartphones and other gadgets. Experience the freedom of staying connected without the hassle of carrying extra cables.',
        'image_url' => 'dashboard/images/bowl1.jpg', // Map this to your specific power bank image asset path
        'category' => 'latest',
        'sku' => 'AWEI-PB-001',
        'brand' => 'Awei',
        'stock_status' => 'In Stock'
    ];
}

// Fetch related items for "You May Also Like"
try {
    $related_stmt = $pdo->prepare("SELECT * FROM hardware_items WHERE id != ? LIMIT 4");
    $related_stmt->execute([$product_id]);
    $related_products = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $related_products = [];
}
?>