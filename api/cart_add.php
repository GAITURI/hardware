<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

require_once __DIR__ . '/db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['product_id']) || !isset($input['quantity']) || !isset($input['price'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product parameters sent.']);
    exit;
}

$product_id = (int)$input['product_id'];
$quantity   = (int)$input['quantity'];
$price      = floatval($input['price']);
$session_token = session_id();

try {
    $pdo->beginTransaction();

    // 1. VERIFY ITEM EXISTS IN THE CATALOG FIRST (Prevents Foreign Key Crashing)
    $prodStmt = $pdo->prepare("SELECT name, description, image_url FROM items WHERE item_id = ?");
    $prodStmt->execute([$product_id]);
    $prodDetails = $prodStmt->fetch(PDO::FETCH_ASSOC);

    if (!$prodDetails) {
        throw new Exception("Product ID " . $product_id . " does not exist in the 'items' table catalog.");
    }

    // 2. FETCH OR CREATE THE ACTIVE CART FOR THIS SESSION
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE session_token = ?");
    $stmt->execute([$session_token]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart) {
        $insertCart = $pdo->prepare("INSERT INTO carts (session_token,status) VALUES (?,'active')");
        $insertCart->execute([$session_token]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // 3. INSERT OR UPDATE ITEM QUANTITIES
    $checkItem = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND item_id = ?");
    $checkItem->execute([$cart_id, $product_id]);
    $existingItem = $checkItem->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        $newQty = $existingItem['quantity'] + $quantity;
        $updateItem = $pdo->prepare("UPDATE cart_items SET quantity = ?, price = ? WHERE cart_item_id = ?");
        $updateItem->execute([$newQty, $price, $existingItem['cart_item_id']]);
    } else {
        $insertItem = $pdo->prepare("INSERT INTO cart_items (cart_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
        $insertItem->execute([$cart_id, $product_id, $quantity, $price]);
    }

    // 4. SYNC TO PHP SESSION FOR THE FRONTEND
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $currentQtyInCart = $existingItem ? $existingItem['quantity'] + $quantity : $quantity;
    
    $_SESSION['cart'][$product_id] = [
        'id'          => $product_id,
        'name'        => $prodDetails['name'] ?? 'Product Item',
        'description' => $prodDetails['description'] ?? '',
        'image_url'   => $prodDetails['image_url'] ?? '',
        'price'       => $price,
        'qty'         => $currentQtyInCart
    ];
    
    $pdo->commit();

    // Calculate total item count for the navbar header badge
    $totalCount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalCount += $item['qty'];
    }
    
    echo json_encode([
        'status'          => 'success',
        'message'         => 'Items registered successfully to your cart session.',
        'new_total_count' => $totalCount
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
exit;
?>