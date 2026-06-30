<?php
// api/cart_helper.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db_connection.php';

function getCartData($pdo = null) {
    $items = [];
    $subtotal = 0;
    $count = 0;

    // 1. If PDO database handle is available, read freshest values from persistent tables
    if ($pdo !== null) {
        $session_token = session_id();
        try { // FIXED: Added missing try block opener
            $stmt = $pdo->prepare("
                SELECT
                ci.item_id as id,
                hi.name,
                hi.description,
                hi.image_url,
                ci.price as price,
                ci.quantity as qty
                FROM carts c
                JOIN cart_items ci ON c.cart_id = ci.cart_id
                JOIN items hi ON ci.item_id = hi.item_id
                WHERE c.session_token = ? AND c.status= 'active'
            ");
            $stmt->execute([$session_token]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $items = [];
        } 
    } // FIXED: Correctly matching the if ($pdo !== null) block scope

    // 2. SMART FALLBACK: If DB is empty, check if items exist in $_SESSION instead
    if (empty($items) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $sessionItem) {
            $items[] = [
                'id'          => $sessionItem['id'] ?? $sessionItem['item_id'],
                'name'        => $sessionItem['name'],
                'description' => $sessionItem['description'] ?? '',
                'image_url'   => $sessionItem['image_url'] ?? '',
                'price'       => floatval($sessionItem['price']),
                'qty'         => (int)($sessionItem['qty'] ?? $sessionItem['quantity'] ?? 1)
            ];
        }
    }
       
    // 3. Process calculations
    foreach ($items as $item) {
        $subtotal += ($item['price'] * $item['qty']);
        $count += $item['qty'];
    }
    
    // 4. Refresh local session storage to match perfectly
    $_SESSION['cart'] = [];
    foreach($items as $i) {
        $_SESSION['cart'][$i['id']] = $i;
    }

    return [
    'items' => $items,
    'subtotal' => $subtotal,
    'count' => $count
];
} // FIXED: Cleanly closes the getCartData function definition
?>