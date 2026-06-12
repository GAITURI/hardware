<?php
/**
 * cart/index.php  — Standalone cart / checkout page
 * Reads from the same PHP session written by the API endpoints.
 */
session_start();
$cart     = array_values($_SESSION['cart'] ?? []);
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
$count    = array_sum(array_column($cart, 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart — Mambo Hardware</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Barlow+Condensed:wght@700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root {
      --crimson:      #d32f2f;
      --crimson-dark: #b71c1c;
      --charcoal:     #1e293b;
      --slate:        #64748b;
      --light-bg:     #f8fafc;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--light-bg); color: var(--charcoal); }

    /* ── TOPBAR ── */
    .cart-topbar {
      background: #0f172a;
      padding: 14px 0;
      display: flex; align-items: center;
    }
    .back-link {
      color: rgba(255,255,255,0.65); font-size: 13px; font-weight: 600;
      text-decoration: none; display: flex; align-items: center; gap: 8px;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--crimson); }
    .cart-topbar-title {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 22px; font-weight: 800; color: #fff; text-transform: uppercase;
    }
    .cart-count-pill {
      background: var(--crimson); color: #fff;
      font-size: 11px; font-weight: 700;
      padding: 3px 10px; border-radius: 50px; margin-left: 8px;
    }

    /* ── LAYOUT ── */
    .cart-page { padding: 40px 0 80px; }
    .section-label {
      font-size: 10px; font-weight: 700; letter-spacing: 2px;
      text-transform: uppercase; color: var(--slate); margin-bottom: 16px;
    }

    /* ── CART ITEM CARD ── */
    .cart-item-card {
      background: #fff; border-radius: 14px;
      border: 1.5px solid #e8edf2;
      display: flex; gap: 0;
      overflow: hidden;
      margin-bottom: 16px;
      transition: box-shadow 0.25s;
    }
    .cart-item-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.07); }

    .cart-item-img {
      width: 130px; min-height: 130px; flex-shrink: 0;
      background: #f0f4f8;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
    }
    .cart-item-img img {
      width: 100%; height: 100%; object-fit: cover;
    }
    .cart-item-img .img-icon {
      font-size: 40px; color: rgba(0,0,0,0.15);
    }

    .cart-item-body {
      flex: 1; padding: 18px 20px;
      display: flex; flex-direction: column; justify-content: space-between;
    }
    .cart-item-name {
      font-size: 15px; font-weight: 700; color: var(--charcoal);
      margin-bottom: 4px; line-height: 1.3;
    }
    .cart-item-desc {
      font-size: 12px; color: var(--slate); line-height: 1.55;
      margin-bottom: 14px;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .cart-item-footer {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }
    .qty-control {
      display: flex; align-items: center; gap: 0;
      border: 1.5px solid #e2e8f0; border-radius: 8px; overflow: hidden;
    }
    .qty-btn {
      width: 34px; height: 34px;
      background: #f8fafc; border: none;
      font-size: 16px; font-weight: 700; color: var(--charcoal);
      cursor: pointer; transition: background 0.2s;
      display: flex; align-items: center; justify-content: center;
    }
    .qty-btn:hover { background: var(--crimson); color: #fff; }
    .qty-display {
      min-width: 40px; text-align: center;
      font-size: 14px; font-weight: 700; color: var(--charcoal);
      border-left: 1.5px solid #e2e8f0; border-right: 1.5px solid #e2e8f0;
      padding: 0 8px; line-height: 34px;
    }
    .item-price-block { text-align: right; }
    .item-unit-price { font-size: 11px; color: var(--slate); }
    .item-total-price { font-size: 17px; font-weight: 800; color: var(--charcoal); }

    .remove-btn {
      background: transparent; border: none;
      color: #cbd5e1; font-size: 15px; cursor: pointer;
      transition: color 0.2s; padding: 4px;
    }
    .remove-btn:hover { color: var(--crimson); }

    /* ── ORDER SUMMARY ── */
    .order-summary {
      background: #fff; border-radius: 14px;
      border: 1.5px solid #e8edf2; padding: 28px;
      position: sticky; top: 80px;
    }
    .summary-title {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 22px; font-weight: 800; text-transform: uppercase;
      color: var(--charcoal); margin-bottom: 20px;
    }
    .summary-row {
      display: flex; justify-content: space-between;
      font-size: 13px; color: var(--slate); padding: 8px 0;
      border-bottom: 1px solid #f1f5f9;
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-row.total {
      font-size: 16px; font-weight: 800; color: var(--charcoal);
      padding-top: 16px; margin-top: 4px; border-top: 2px solid #e2e8f0;
      border-bottom: none;
    }
    .btn-checkout {
      display: block; width: 100%;
      background: var(--crimson); color: #fff;
      border: none; border-radius: 50px;
      font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
      padding: 16px; cursor: pointer; transition: all 0.25s;
      text-decoration: none; text-align: center; margin-top: 20px;
    }
    .btn-checkout:hover { background: var(--crimson-dark); color: #fff; transform: translateY(-1px); }
    .btn-continue {
      display: block; width: 100%;
      background: transparent; color: var(--charcoal);
      border: 1.5px solid #e2e8f0; border-radius: 50px;
      font-size: 13px; font-weight: 600;
      padding: 14px; cursor: pointer; transition: all 0.25s;
      text-decoration: none; text-align: center; margin-top: 10px;
    }
    .btn-continue:hover { border-color: var(--charcoal); color: var(--charcoal); }

    /* ── EMPTY CART ── */
    .empty-cart {
      text-align: center; padding: 80px 20px;
      background: #fff; border-radius: 14px; border: 1.5px solid #e8edf2;
    }
    .empty-cart-icon { font-size: 56px; color: #cbd5e1; margin-bottom: 18px; }
    .empty-cart-title {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 28px; font-weight: 800; text-transform: uppercase;
      color: var(--charcoal); margin-bottom: 8px;
    }
    .empty-cart-sub { font-size: 14px; color: var(--slate); margin-bottom: 24px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .cart-item-img { width: 90px; min-height: 90px; }
      .cart-item-name { font-size: 13px; }
      .order-summary { position: static; margin-top: 24px; }
    }
  </style>
</head>
<body>

<!-- ── TOP BAR ── -->
<div class="cart-topbar">
  <div class="container d-flex align-items-center justify-content-between">
    <a href="../dashboard/index.php" class="back-link">
      <i class="fas fa-arrow-left"></i> Continue Shopping
    </a>
    <div class="d-flex align-items-center">
      <span class="cart-topbar-title">Your Cart</span>
      <span class="cart-count-pill" id="topbarCount"><?= $count ?> ITEMS</span>
    </div>
    <div style="width:140px;"></div><!-- spacer for centering -->
  </div>
</div>

<!-- ── CART PAGE ── -->
<main class="cart-page">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT — Cart Items -->
      <div class="col-lg-8">
        <div class="section-label">Cart Items</div>

        <?php if (empty($cart)): ?>
        <div class="empty-cart">
          <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
          <div class="empty-cart-title">Your cart is empty</div>
          <p class="empty-cart-sub">Looks like you haven't added anything yet.</p>
          <a href="../dashboard/index.php" class="btn-checkout" style="display:inline-block;width:auto;padding:14px 36px;">
            Start Shopping
          </a>
        </div>

        <?php else: ?>
        <div id="cartItemsList">
          <?php foreach ($cart as $item): ?>
          <div class="cart-item-card" data-id="<?= htmlspecialchars($item['id']) ?>">

            <!-- Image -->
            <div class="cart-item-img">
              <?php if (!empty($item['image_url'])): ?>
                <img src="<?= htmlspecialchars($item['image_url']) ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>">
              <?php else: ?>
                <i class="fas fa-box img-icon"></i>
              <?php endif; ?>
            </div>

            <!-- Body -->
            <div class="cart-item-body">
              <div>
                <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="cart-item-desc"><?= htmlspecialchars($item['description']) ?></div>
              </div>
              <div class="cart-item-footer">
                <!-- Qty control -->
                <div class="qty-control">
                  <button class="qty-btn" onclick="updateQty('<?= $item['id'] ?>', <?= $item['qty'] - 1 ?>)">
                    <i class="fas fa-minus" style="font-size:10px;"></i>
                  </button>
                  <span class="qty-display" id="qty-<?= $item['id'] ?>"><?= $item['qty'] ?></span>
                  <button class="qty-btn" onclick="updateQty('<?= $item['id'] ?>', <?= $item['qty'] + 1 ?>)">
                    <i class="fas fa-plus" style="font-size:10px;"></i>
                  </button>
                </div>
                <!-- Price -->
                <div class="item-price-block">
                  <div class="item-unit-price">KES <?= number_format($item['price'], 0) ?> each</div>
                  <div class="item-total-price" id="line-<?= $item['id'] ?>">
                    KES <?= number_format($item['price'] * $item['qty'], 0) ?>
                  </div>
                </div>
                <!-- Remove -->
                <button class="remove-btn" onclick="removeItem('<?= $item['id'] ?>')" title="Remove">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </div>

          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- RIGHT — Order Summary -->
      <div class="col-lg-4">
        <div class="order-summary">
          <div class="summary-title">Order Summary</div>

          <div class="summary-row">
            <span>Items (<span id="summaryCount"><?= $count ?></span>)</span>
            <span id="summarySubtotal">KES <?= number_format($subtotal, 0) ?></span>
          </div>
          <div class="summary-row">
            <span>Delivery</span>
            <span style="color:#16a34a;font-weight:700;">Free</span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span id="summaryTotal">KES <?= number_format($subtotal, 0) ?></span>
          </div>

          <a href="checkout.php" class="btn-checkout">
            Checkout &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
          </a>
          <a href="../dashboard/index.php" class="btn-continue">
            Continue Shopping
          </a>
        </div>
      </div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const API = '../api';

  async function updateQty(id, newQty) {
    const res  = await fetch(`${API}/cart_update.php`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ id, qty: newQty }),
    });
    const data = await res.json();

    if (newQty <= 0) {
      // Remove the card from the DOM
      document.querySelector(`.cart-item-card[data-id="${id}"]`)?.remove();
    } else {
      // Update qty display and line total
      const card = document.querySelector(`.cart-item-card[data-id="${id}"]`);
      if (card) {
        card.querySelector(`#qty-${id}`).textContent = newQty;
        // Recalculate line total from server cart
        const item = data.cart.find(i => String(i.id) === String(id));
        if (item) {
          card.querySelector(`#line-${id}`).textContent =
            'KES ' + Number(item.price * item.qty).toLocaleString('en-KE');
        }
      }
    }
    refreshSummary(data);
  }

  async function removeItem(id) {
    const res  = await fetch(`${API}/cart_remove.php`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ id }),
    });
    const data = await res.json();
    document.querySelector(`.cart-item-card[data-id="${id}"]`)?.remove();

    if (data.cart.length === 0) {
      document.getElementById('cartItemsList').innerHTML = `
        <div class="empty-cart">
          <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
          <div class="empty-cart-title">Your cart is empty</div>
          <p class="empty-cart-sub">Looks like you haven't added anything yet.</p>
          <a href="../dashboard/index.php" class="btn-checkout"
             style="display:inline-block;width:auto;padding:14px 36px;">
            Start Shopping
          </a>
        </div>`;
    }
    refreshSummary(data);
  }

  function refreshSummary(data) {
    const fmt = n => 'KES ' + Number(n).toLocaleString('en-KE', { maximumFractionDigits: 0 });
    document.getElementById('summaryCount').textContent    = data.total_items;
    document.getElementById('summarySubtotal').textContent = fmt(data.subtotal);
    document.getElementById('summaryTotal').textContent    = fmt(data.subtotal);
    document.getElementById('topbarCount').textContent     = data.total_items + ' ITEMS';
  }
</script>
</body>
</html>