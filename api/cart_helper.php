<?php
// api/cart_helper.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getCartData($pdo = null) {
    // If PDO database handle is available, read freshest values from persistent tables
    if ($pdo !== null) {
        $session_id = session_id();
        $stmt = $pdo->prepare("
            SELECT ci.product_id as id, hi.name, hi.description, hi.image_url, ci.price_at_add as price, ci.quantity as qty
            FROM carts c
            JOIN cart_items ci ON c.id = ci.cart_id
            JOIN hardware_items hi ON ci.product_id = hi.id
            WHERE c.session_id = ?
        ");
        $stmt->execute([$session_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $subtotal = 0;
        $count = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['qty']);
            $count += $item['qty'];
        }
        
        // Refresh local session storage to match
        $_SESSION['cart'] = [];
        foreach($items as $i) {
            $_SESSION['cart'][$i['id']] = $i;
        }

        return ['items' => $items, 'subtotal' => $subtotal, 'count' => $count];
    }

    // Fallback if database handles are omitted
    $cart = $_SESSION['cart'] ?? [];
    $subtotal = 0;
    $count = 0;
    foreach ($cart as $item) {
        $subtotal += ($item['price'] * $item['qty']);
        $count += $item['qty'];
    }
    return ['items' => array_values($cart), 'subtotal' => $subtotal, 'count' => $count];
}
?>