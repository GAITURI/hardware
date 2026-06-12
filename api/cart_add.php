<?php
// api/cart_helper.php
if (session_status() === PHP_SESSION_NONE) session_start();

function getCartData() {
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