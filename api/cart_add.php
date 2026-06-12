<?php
header('Content-Type:application/json');

// api/cart_helper.php
if (session_status() === PHP_SESSION_NONE) 
session_start();

require_once __DIR__ . '/../db_connection.php';

$input =json_decode(file_get_contents('php://input'), true);

if(!$input || !isset($input['product_id']) || !isset($input['quantity']) || !isset($input['price'])){
    echo json_encode(['status'=> 'error', 'message' =>'Invalid product parameters sent.']);
    exit;
}
$product_id= (int)$input['product_id'];
$quantity =(int)$input['quantity'];
$price = floatval($input['price']);
$session_id= session_id();


try{
    $pdo->beginTransaction();
// FETCH AND CREATE PERSISTENT CART MATCHING SESSION_ID
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id=?");
    $stmt->execute([$session_id]);
    $cart= $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$cart){
        $insertCart =$pdo->prepare("INSERT INTO carts(session_id) VALUES(?)");
        $insertCart->execute([$session_id]);
        $cart_id =$pdo->lastInsertId();

    }else{
        $cart_id =$cart['id'];

    }

    // Insert/ update item quantities using unique index constraints
    $checkItem =$pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id=? AND product_id=?");
    $checkItem->execute([$cart_id, $product_id]);
    $existingItem =$checkItem->fetch(PDO::FETCH_ASSOC);


    if ($existingItem){
        $newQty =$existingItem['quantity'] + $quantity;
        $updateItem =$pdo->prepare("UPDATE cart_items SET quantity=?, price_at_add=? WHERE id=?");
        $updateItem->execute([$newQty,$price,$existingItem['id']]);
    }else{
        $insertItem= $pdo->prepare("INSERT INTO cart_items(cart_id,product_id, quantity, price_at_add) VALUES(?,?,?,?)");
        $insertItem->execute([$cart_id, $product_id, $quantity, $price]);

    }
    // fallback sync to standard array structures for legacy views
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] =[];
    }

    // pull product details to store cleanly inside global helper sessions
    $prodStmt = $pdo->prepare("SELECT name, description, image_url FROM hardware_items WHERE id=?");
    $prodStmt->execute([$product_id]);
    $prodDetails = $prodStmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['cart'][$product_id] = [
        'id'          => $product_id,
        'name'        => $prodDetails['name'] ?? 'Hardware Item',
        'description' => $prodDetails['description'] ?? '',
        'image_url'   => $prodDetails['image_url'] ?? '',
        'price'       => $price,
        'qty'         => ($existingItem ? $existingItem['quantity'] + $quantity : $quantity)
    ];
    $pdo->commit();
    // calculate updated total quantities ti return back to frontent badge components
    $totalCount =0;
    foreach ($_SESSION['cart'] as $item){
        $totalCount += $item['qty'];

    }
    echo json_encode([
        'status' =>'success',
        'message'=>'Items registered successfully to your cart session.',
        'new_total_count'=>$totalCount
    ]);


}catch(Exception $e){
    if($pdo->inTransaction()){
        $pdo->rollBack();

    }
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

exit;
?>