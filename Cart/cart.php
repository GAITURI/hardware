<?php
/**
 * cart/index.php — Cart + Checkout page
 * UI matches Oasis Technologies screenshot exactly.
 */
session_start();

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../api/cart_helper.php';

$cartData = getCartData($pdo);
$cart     = $cartData['items'];
$subtotal = $cartData['subtotal'];
$count    = $cartData['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart — Mambo Hardware</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Barlow+Condensed:wght@700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    /* ══════════════════════════════════
       DESIGN TOKENS
    ══════════════════════════════════ */
    :root {
      --red:        #d32f2f;
      --red-dark:   #b71c1c;
      --ink:        #111827;
      --ink-soft:   #374151;
      --muted:      #6b7280;
      --border:     #e5e7eb;
      --surface:    #f9fafb;
      --white:      #ffffff;
      --font:       'Inter', sans-serif;
      --font-cond:  'Barlow Condensed', sans-serif;
      --radius:     10px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: var(--font);
      background: var(--surface);
      color: var(--ink);
      font-size: 14px;
      line-height: 1.55;
      -webkit-font-smoothing: antialiased;
    }

    /* ══════════════════════════════════
       NAVBAR — matches dashboard exactly
    ══════════════════════════════════ */
    .main-nav {
      background: #fff;
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 1050;
      padding: 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 66px;
    }

    /* Brand */
    .brand-wrap {
      display: flex; align-items: center; gap: 9px;
      text-decoration: none; flex-shrink: 0;
    }
    .brand-icon {
      width: 40px; height: 40px; border-radius: 8px;
      background: var(--red); color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; flex-shrink: 0;
    }
    .brand-text-top {
      font-size: 14px; font-weight: 800;
      color: var(--ink); line-height: 1;
    }
    .brand-text-bot {
      font-size: 8px; font-weight: 600;
      color: var(--red); letter-spacing: 1.8px;
      text-transform: uppercase;
    }

    /* Nav links */
    .nav-links {
      display: flex; align-items: center; gap: 2px;
    }
    .nav-links a {
      font-size: 13.5px; font-weight: 600;
      color: var(--ink-soft); text-decoration: none;
      padding: 8px 15px; border-radius: 7px;
      transition: color 0.2s, background 0.18s;
      position: relative;
    }
    .nav-links a::after {
      content: '';
      position: absolute; bottom: 4px; left: 15px; right: 15px;
      height: 2px; background: var(--red);
      transform: scaleX(0); transition: transform 0.2s;
      transform-origin: center;
    }
    .nav-links a:hover { color: var(--red); }
    .nav-links a:hover::after,
    .nav-links a.active::after { transform: scaleX(1); }
    .nav-links a.active { color: var(--red); }

    /* Dropdown */
    .nav-links .dropdown-menu {
      border: 1px solid var(--border); border-radius: 10px;
      padding: 6px; box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .nav-links .dropdown-item {
      font-size: 13px; font-weight: 600;
      border-radius: 6px; padding: 8px 14px;
      color: var(--ink-soft);
    }
    .nav-links .dropdown-item:hover { background: #fef2f2; color: var(--red); }

    /* Right utilities */
    .nav-utils { display: flex; align-items: center; gap: 7px; }
    .nav-icon-btn {
      width: 38px; height: 38px; border-radius: 8px;
      border: 1.5px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      color: var(--ink); font-size: 14px;
      text-decoration: none; background: #fff;
      transition: all 0.2s; cursor: pointer;
      position: relative;
    }
    .nav-icon-btn:hover,
    .nav-icon-btn.active-cart {
      border-color: var(--red); color: var(--red);
    }
    .cart-badge {
      position: absolute; top: -5px; right: -5px;
      background: var(--red); color: #fff;
      font-size: 9px; font-weight: 700;
      width: 17px; height: 17px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      border: 2px solid #fff;
    }
    .btn-admin {
      font-size: 12.5px; font-weight: 600; color: var(--ink);
      border: 1.5px solid var(--border); border-radius: 8px;
      padding: 7px 14px; text-decoration: none;
      display: flex; align-items: center; gap: 6px;
      background: #fff; transition: all 0.2s;
    }
    .btn-admin:hover { color: var(--ink); border-color: var(--ink); }

    /* ══════════════════════════════════
       PAGE HEADER BAND
    ══════════════════════════════════ */
    .page-header {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 22px 0 20px;
    }
    .page-header h1 {
      font-family: var(--font-cond);
      font-size: 34px; font-weight: 800;
      text-transform: uppercase; letter-spacing: 0.4px;
      color: var(--ink); line-height: 1;
    }

    /* ══════════════════════════════════
       MAIN LAYOUT
    ══════════════════════════════════ */
    .cart-page { padding: 36px 0 90px; }

    /* ══════════════════════════════════
       CART TABLE PANEL
    ══════════════════════════════════ */
    .cart-panel {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }

    /* Column header row */
    .cart-col-header {
      display: grid;
      grid-template-columns: 1fr 120px 120px 130px 40px;
      align-items: center;
      padding: 11px 22px;
      border-bottom: 1px solid var(--border);
      background: var(--surface);
    }
    .col-label {
      font-size: 10.5px; font-weight: 700;
      letter-spacing: 1.4px; text-transform: uppercase;
      color: var(--muted);
    }
    .col-label.center { text-align: center; }
    .col-label.right  { text-align: right; }

    /* Cart row */
    .cart-row {
      display: grid;
      grid-template-columns: 1fr 120px 120px 130px 40px;
      align-items: center;
      padding: 18px 22px;
      border-bottom: 1px solid var(--border);
      transition: background 0.15s;
    }
    .cart-row:last-child { border-bottom: none; }
    .cart-row:hover { background: #fafafa; }

    /* Product cell */
    .product-cell { display: flex; align-items: center; gap: 14px; }
    .product-thumb {
      width: 62px; height: 62px; border-radius: 8px;
      border: 1px solid var(--border);
      background: var(--surface);
      overflow: hidden; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }
    .product-thumb img {
      width: 100%; height: 100%; object-fit: cover;
    }
    .product-thumb .ph { font-size: 20px; color: #d1d5db; }
    .product-name {
      font-size: 14px; font-weight: 600;
      color: var(--ink); margin-bottom: 3px;
      line-height: 1.3;
    }
    .product-unit {
      font-size: 12px; color: var(--muted);
    }

    /* Qty stepper */
    .qty-cell { display: flex; justify-content: center; }
    .qty-stepper {
      display: inline-flex; align-items: center;
      border: 1.5px solid var(--border); border-radius: 8px;
      overflow: hidden; height: 34px;
    }
    .qty-stepper button {
      width: 32px; height: 34px;
      border: none; background: var(--surface);
      color: var(--ink); font-size: 11px;
      cursor: pointer; transition: all 0.18s;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .qty-stepper button:hover { background: var(--red); color: #fff; }
    .qty-stepper .qty-val {
      min-width: 34px; text-align: center;
      font-size: 14px; font-weight: 700; color: var(--ink);
      border-left: 1.5px solid var(--border);
      border-right: 1.5px solid var(--border);
      line-height: 34px; padding: 0 2px;
    }

    /* Price / Total cells */
    .price-cell {
      text-align: right;
      font-size: 14px; font-weight: 500; color: var(--ink-soft);
    }
    .total-cell {
      text-align: right;
      font-size: 14px; font-weight: 700; color: var(--ink);
    }

    /* Remove button */
    .remove-cell { display: flex; justify-content: center; }
    .remove-btn {
      background: none; border: none;
      color: #d1d5db; font-size: 13px; cursor: pointer;
      padding: 6px; border-radius: 6px;
      transition: all 0.18s;
      display: flex; align-items: center; justify-content: center;
    }
    .remove-btn:hover { color: var(--red); background: #fef2f2; }

    /* Continue shopping */
    .continue-link {
      display: inline-flex; align-items: center; gap: 7px;
      font-size: 13px; font-weight: 600; color: var(--muted);
      text-decoration: none; padding: 16px 22px;
      transition: color 0.2s;
    }
    .continue-link:hover { color: var(--red); }
    .continue-link i { font-size: 11px; }

    /* ══════════════════════════════════
       ORDER SUMMARY SIDEBAR
    ══════════════════════════════════ */
    .order-summary {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      position: sticky; top: 82px;
    }

    .os-title {
      font-size: 13px; font-weight: 800;
      letter-spacing: 1.2px; text-transform: uppercase;
      color: var(--ink);
      padding: 18px 22px 14px;
      border-bottom: 1px solid var(--border);
    }

    .os-body { padding: 18px 22px; }

    /* Per-item rows */
    .os-item {
      display: flex; justify-content: space-between;
      align-items: baseline; gap: 10px;
      padding: 5px 0;
      font-size: 13px; color: var(--ink-soft);
      border-bottom: 1px solid #f3f4f6;
    }
    .os-item:last-child { border-bottom: none; }
    .os-item-name { flex: 1; min-width: 0; line-height: 1.4; }
    .os-item-val  { font-weight: 700; color: var(--ink); white-space: nowrap; font-size: 13px; }

    /* Total row */
    .os-total {
      display: flex; justify-content: space-between; align-items: center;
      padding: 14px 0 16px;
      font-size: 16px; font-weight: 800; color: var(--ink);
      border-top: 2px solid var(--border);
      margin-top: 10px;
    }

    /* Delivery block */
    .delivery-block {
      border: 1px solid var(--border); border-radius: 9px;
      overflow: hidden; margin-bottom: 20px;
    }
    .delivery-header {
      background: var(--surface);
      padding: 10px 14px;
      display: flex; align-items: center; gap: 7px;
      font-size: 10.5px; font-weight: 700;
      letter-spacing: 1.2px; text-transform: uppercase;
      color: var(--muted); border-bottom: 1px solid var(--border);
    }
    .delivery-header i { font-size: 11px; }
    .delivery-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 10px 14px;
      font-size: 13px; border-bottom: 1px solid var(--border);
    }
    .delivery-row:last-of-type { border-bottom: none; }
    .dzone { display: flex; align-items: center; gap: 8px; color: var(--ink-soft); }
    .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .dot-g { background: #22c55e; }
    .dot-b { background: #3b82f6; }
    .dot-s { background: #9ca3af; }
    .dprice   { font-size: 13px; font-weight: 700; color: var(--ink); }
    .dnote    { font-size: 12px; font-style: italic; color: var(--muted); }
    .delivery-callout {
      background: #f0fdf4;
      padding: 10px 14px;
      font-size: 12px; color: #166534; line-height: 1.55;
      border-top: 1px solid #dcfce7;
    }

    /* Form */
    .form-label-sm {
      display: block;
      font-size: 12px; font-weight: 600; color: var(--ink-soft);
      margin-bottom: 5px;
    }
    .form-input {
      width: 100%; padding: 10px 13px;
      border: 1.5px solid var(--border); border-radius: 8px;
      font-family: var(--font); font-size: 13.5px; color: var(--ink);
      background: #fff; outline: none;
      transition: border-color 0.2s;
      margin-bottom: 13px;
    }
    .form-input:focus { border-color: var(--red); }
    .form-input::placeholder { color: #9ca3af; }

    /* Pay button */
    .btn-pay {
      display: flex; align-items: center; justify-content: center; gap: 10px;
      width: 100%; padding: 15px 20px;
      background: var(--red); color: #fff;
      border: none; border-radius: 50px;
      font-family: var(--font);
      font-size: 14px; font-weight: 700;
      cursor: pointer; transition: all 0.25s;
      margin-bottom: 14px; text-decoration: none;
      letter-spacing: 0.2px;
    }
    .btn-pay:hover {
      background: var(--red-dark); color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(211,47,47,0.3);
    }

    /* Trust badges */
    .trust-row {
      display: flex; align-items: center; justify-content: center;
      gap: 8px; flex-wrap: wrap;
    }
    .trust-badge {
      display: inline-flex; align-items: center; gap: 5px;
      border: 1.5px solid var(--border); border-radius: 50px;
      padding: 5px 12px;
      font-size: 11.5px; font-weight: 600; color: var(--ink-soft);
    }
    .badge-dot { width: 8px; height: 8px; border-radius: 50%; }

    /* ══════════════════════════════════
       EMPTY STATE
    ══════════════════════════════════ */
    .empty-state {
      background: #fff; border: 1px solid var(--border);
      border-radius: var(--radius); padding: 80px 40px; text-align: center;
    }
    .empty-icon   { font-size: 52px; color: #d1d5db; margin-bottom: 18px; }
    .empty-title  {
      font-family: var(--font-cond);
      font-size: 28px; font-weight: 800; text-transform: uppercase;
      color: var(--ink); margin-bottom: 8px;
    }
    .empty-sub    { font-size: 14px; color: var(--muted); margin-bottom: 24px; }
    .btn-shop {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--red); color: #fff; border-radius: 50px;
      font-size: 13px; font-weight: 700;
      padding: 13px 32px; text-decoration: none; transition: all 0.25s;
    }
    .btn-shop:hover { background: var(--red-dark); color: #fff; transform: translateY(-1px); }

    /* ══════════════════════════════════
       FOOTER
    ══════════════════════════════════ */
    .site-footer { background: #0f172a; padding: 56px 0 0; }
    .footer-brand-name {
      font-size: 17px; font-weight: 800; color: #fff; line-height: 1;
    }
    .footer-brand-sub {
      font-size: 8.5px; font-weight: 600; color: var(--red);
      letter-spacing: 2px; text-transform: uppercase;
    }
    .footer-desc {
      font-size: 13px; color: rgba(255,255,255,0.45);
      line-height: 1.7; margin: 13px 0 16px;
    }
    .footer-contact-item {
      display: flex; align-items: center; gap: 9px; margin-bottom: 7px;
    }
    .footer-contact-item i   { color: var(--red); font-size: 12px; width: 14px; }
    .footer-contact-item a   {
      font-size: 13px; color: rgba(255,255,255,0.55);
      text-decoration: none; transition: color 0.2s;
    }
    .footer-contact-item a:hover { color: #fff; }
    .footer-col-title {
      font-size: 10.5px; font-weight: 700; letter-spacing: 2px;
      text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 14px;
    }
    .footer-link {
      display: block; font-size: 13.5px;
      color: rgba(255,255,255,0.55); text-decoration: none;
      padding: 4px 0; transition: color 0.2s;
    }
    .footer-link:hover { color: #fff; }
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.06);
      padding: 18px 0; margin-top: 48px;
      display: flex; align-items: center; justify-content: space-between;
      font-size: 12px; color: rgba(255,255,255,0.25);
    }
    .footer-socials  { display: flex; gap: 7px; }
    .footer-soc-btn  {
      width: 34px; height: 34px; border-radius: 8px;
      border: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.5); font-size: 13px; text-decoration: none;
      transition: all 0.2s;
    }
    .footer-soc-btn:hover { background: var(--red); border-color: var(--red); color: #fff; }

    /* ══════════════════════════════════
       SCROLL TO TOP
    ══════════════════════════════════ */
    #scrollTop {
      position: fixed; bottom: 24px; right: 24px; z-index: 999;
      width: 42px; height: 42px; border-radius: 50%;
      background: var(--red); color: #fff; border: none;
      font-size: 15px; cursor: pointer;
      box-shadow: 0 4px 14px rgba(211,47,47,0.38);
      opacity: 0; transform: translateY(10px);
      transition: all 0.28s;
      display: flex; align-items: center; justify-content: center;
    }
    #scrollTop.show { opacity: 1; transform: translateY(0); }

    /* ══════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════ */
    @media (max-width: 991px) {
      .nav-links       { display: none; }
      .order-summary   { position: static; margin-top: 0; }
    }
    @media (max-width: 640px) {
      .cart-col-header { display: none; }
      .cart-row {
        grid-template-columns: 1fr;
        gap: 10px;
        padding: 16px;
      }
      .price-cell, .total-cell { text-align: left; }
      .qty-cell  { justify-content: flex-start; }
      .remove-cell { justify-content: flex-start; }
      .page-header h1 { font-size: 26px; }
    }
  </style>
</head>
<body>

<!-- ══════════════════════════════════════════
     FULL SITE NAVBAR
══════════════════════════════════════════ -->
<nav class="main-nav">
  <div class="container nav-inner">

    <!-- Brand -->
    <a href="../dashboard/index.php" class="brand-wrap">
      <div class="brand-icon"><i class="fas fa-hammer"></i></div>
      <div>
        <div class="brand-text-top">Mambo</div>
        <div class="brand-text-bot">Hardware</div>
      </div>
    </a>

    <!-- Centre nav links -->
    <div class="nav-links">
      <a href="../dashboard/index.php">Home</a>
      <a href="../dashboard/index.php#about">About</a>
      <div class="dropdown">
        <a href="../dashboard/index.php#shop" class="dropdown-toggle" data-bs-toggle="dropdown">Shop</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Building Materials</a></li>
          <li><a class="dropdown-item" href="#">Sanitary Ware</a></li>
          <li><a class="dropdown-item" href="#">Electrical</a></li>
          <li><a class="dropdown-item" href="#">Tools</a></li>
          <li><a class="dropdown-item" href="#">Tiles &amp; Flooring</a></li>
        </ul>
      </div>
      <a href="#">Blog</a>
      <a href="#">Contact</a>
    </div>

    <!-- Right utilities -->
    <div class="nav-utils">
      <a href="../dashboard/index.php" class="nav-icon-btn" aria-label="Search">
        <i class="fas fa-search"></i>
      </a>
      <!-- Cart — active state, we are on this page -->
      <a href="index.php" class="nav-icon-btn active-cart" aria-label="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge" id="cartBadge"><?= $count ?: 0 ?></span>
      </a>
      <a href="#" class="btn-admin">
        <i class="fas fa-user-shield"></i> Admin
      </a>
    </div>

  </div>
</nav>


<!-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ -->
<div class="page-header">
  <div class="container">
    <h1>Your Cart</h1>
  </div>
</div>


<!-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ -->
<main class="cart-page">
  <div class="container">

  <?php if (empty($cart)): ?>
  <!-- ── EMPTY STATE ── -->
  <div class="empty-state">
    <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
    <div class="empty-title">Your cart is empty</div>
    <p class="empty-sub">You haven't added any products yet.</p>
    <a href="../dashboard/index.php#shop" class="btn-shop">
      Start Shopping &nbsp;<i class="fas fa-arrow-right" style="font-size:11px;"></i>
    </a>
  </div>

  <?php else: ?>
  <div class="row g-4 align-items-start">

    <!-- ════════════════════════════
         LEFT — CART TABLE
    ════════════════════════════ -->
    <div class="col-lg-8">
      <div class="cart-panel">

        <!-- Column headers -->
        <div class="cart-col-header">
          <div class="col-label">Product</div>
          <div class="col-label center">QTY</div>
          <div class="col-label right">Price</div>
          <div class="col-label right">Total</div>
          <div></div>
        </div>

        <!-- Rows -->
        <div id="cartBody">
        <?php foreach ($cart as $item): ?>
          <div class="cart-row" data-id="<?= htmlspecialchars($item['id']) ?>">

            <!-- PRODUCT -->
            <div class="product-cell">
              <div class="product-thumb">
                <?php if (!empty($item['image_url'])): ?>
                  <img src="../dashboard/<?= htmlspecialchars($item['image_url']) ?>"
                       alt="<?= htmlspecialchars($item['name']) ?>">
                <?php else: ?>
                  <i class="fas fa-box ph"></i>
                <?php endif; ?>
              </div>
              <div>
                <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="product-unit">KES <?= number_format($item['price'], 0) ?> each</div>
              </div>
            </div>
                
            <!-- QTY -->
            <div class="qty-cell">
              <div class="qty-stepper">
                <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['qty'] - 1 ?>)"
                        aria-label="Decrease">
                  <i class="fas fa-minus"></i>
                </button>
                <span class="qty-val" id="qty-<?= $item['id'] ?>"><?= $item['qty'] ?></span>
                <button onclick="updateQty('<?= $item['id'] ?>', <?= $item['qty'] + 1 ?>)"
                        aria-label="Increase">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>

            <!-- PRICE -->
            <div class="price-cell">
              KES <?= number_format($item['price'], 0) ?>
            </div>

            <!-- TOTAL -->
            <div class="total-cell" id="line-<?= $item['id'] ?>">
              KES <?= number_format($item['price'] * $item['qty'], 0) ?>
            </div>

            <!-- REMOVE -->
            <div class="remove-cell">
              <button class="remove-btn"
                      onclick="removeItem('<?= $item['id'] ?>')"
                      title="Remove item">
                <i class="fas fa-times"></i>
              </button>
            </div>

          </div>
        <?php endforeach; ?>
        </div>

        <!-- Continue shopping -->
        <a href="../dashboard/index.php" class="continue-link">
          <i class="fas fa-arrow-left"></i> Continue Shopping
        </a>

      </div><!-- /cart-panel -->
    </div>

    <!-- ════════════════════════════
         RIGHT — ORDER SUMMARY
    ════════════════════════════ -->
    <div class="col-lg-4">
      <div class="order-summary">

        <div class="os-title">Order Summary</div>

        <div class="os-body">

          <!-- Per-item list -->
          <div id="osSummaryItems">
          <?php foreach ($cart as $item): ?>
            <div class="os-item" id="os-<?= $item['id'] ?>">
              <span class="os-item-name">
                <?= htmlspecialchars($item['name']) ?>
                &times; <span id="os-qty-<?= $item['id'] ?>"><?= $item['qty'] ?></span>
              </span>
              <span class="os-item-val" id="os-val-<?= $item['id'] ?>">
                KES <?= number_format($item['price'] * $item['qty'], 0) ?>
              </span>
            </div>
          <?php endforeach; ?>
          </div>

          <!-- Grand total -->
          <div class="os-total">
            <span>Total</span>
            <span id="osTotal">KES <?= number_format($subtotal, 0) ?></span>
          </div>

          <!-- Delivery rates -->
          <div class="delivery-block">
            <div class="delivery-header">
              <i class="fas fa-truck"></i> Delivery Rates
            </div>
            <div class="delivery-row">
              <span class="dzone"><span class="dot dot-g"></span>Ruiru Town</span>
              <span class="dprice">KES 100 – 200</span>
            </div>
            <div class="delivery-row">
              <span class="dzone"><span class="dot dot-b"></span>Outside Ruiru</span>
              <span class="dprice">KES 300</span>
            </div>
            <div class="delivery-row">
              <span class="dzone"><span class="dot dot-s"></span>Other areas</span>
              <span class="dnote">Discussed after order</span>
            </div>
            <div class="delivery-callout">
              Our team will <strong>call you</strong> after your order to confirm delivery.
              Fee is <strong>not charged online.</strong>
            </div>
          </div>

          <!-- Checkout form -->
          <label class="form-label-sm">Full Name</label>
          <input class="form-input" type="text" id="fName"
                 placeholder="John Doe" autocomplete="name"/>

          <label class="form-label-sm">Email Address</label>
          <input class="form-input" type="email" id="fEmail"
                 placeholder="john@example.com" autocomplete="email"/>

          <label class="form-label-sm">Phone Number</label>
          <input class="form-input" type="tel" id="fPhone"
                 placeholder="+254 700 000 000" autocomplete="tel"/>

          <label class="form-label-sm">Location / Address</label>
          <input class="form-input" type="text" id="fLocation"
                 placeholder="e.g. Ruiru Town, Kamakis…"/>

          <!-- Pay button -->
          <button class="btn-pay" onclick="handleCheckout()">
            Make Payment with M-Pesa / Card
            <i class="fas fa-arrow-right" style="font-size:12px;"></i>
          </button>

          <!-- Trust badges -->
          <div class="trust-row">
            <span class="trust-badge">
              <span class="badge-dot" style="background:#22c55e;"></span> M-Pesa
            </span>
            <span class="trust-badge">
              <span class="badge-dot" style="background:#1d4ed8;"></span> Visa / Mastercard
            </span>
            <span class="trust-badge" style="color:var(--muted);">
              <i class="fas fa-lock" style="font-size:10px;"></i> SSL Encrypted
            </span>
          </div>

        </div><!-- /os-body -->
      </div><!-- /order-summary -->
    </div>

  </div><!-- /row -->
  <?php endif; ?>

  </div><!-- /container -->
</main>


<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5">

      <!-- Brand col -->
      <div class="col-lg-4">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
          <div class="brand-icon"><i class="fas fa-hammer"></i></div>
          <div>
            <div class="footer-brand-name">MAMBO</div>
            <div class="footer-brand-sub">Hardware</div>
          </div>
        </div>
        <p class="footer-desc">
          Ruiru's trusted hardware store. Quality building materials, tools,
          sanitary ware, and electrical supplies — all under one roof.
        </p>
        <div class="footer-contact-item">
          <i class="fas fa-phone-alt"></i>
          <a href="tel:+254798275251">+254 798 275 251</a>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-envelope"></i>
          <a href="mailto:info@mambohardware.co.ke">info@mambohardware.co.ke</a>
        </div>
      </div>

      <!-- Useful Links -->
      <div class="col-lg-2 col-6">
        <div class="footer-col-title">Useful Links</div>
        <a href="../dashboard/index.php" class="footer-link">Home</a>
        <a href="../dashboard/index.php#about" class="footer-link">About Us</a>
        <a href="../dashboard/index.php#shop" class="footer-link">Shop</a>
        <a href="#" class="footer-link">Contact</a>
        <a href="#" class="footer-link">Blog</a>
        <a href="#" class="footer-link">Legal</a>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-2 col-6">
        <div class="footer-col-title">Quick Links</div>
        <a href="#" class="footer-link">Collections</a>
        <a href="#" class="footer-link">About Us</a>
        <a href="#" class="footer-link">Contact Us</a>
        <a href="#" class="footer-link">Blog</a>
        <a href="#" class="footer-link">Privacy Policy</a>
      </div>

      <!-- Extra Links -->
      <div class="col-lg-2 col-6">
        <div class="footer-col-title">Extra Links</div>
        <a href="#" class="footer-link">Terms of Use</a>
        <a href="#" class="footer-link">Returns Policy</a>
        <a href="#" class="footer-link">Warranty Info</a>
        <a href="#" class="footer-link">FAQ</a>
      </div>

    </div>

    <div class="footer-bottom">
      <span>&copy; 2026 Mambo Hardware. All rights reserved.</span>
      <div class="footer-socials">
        <a href="#" class="footer-soc-btn"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="footer-soc-btn"><i class="fab fa-instagram"></i></a>
        <a href="#" class="footer-soc-btn"><i class="fab fa-twitter"></i></a>
        <a href="#" class="footer-soc-btn"><i class="fab fa-tiktok"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll to top -->
<button id="scrollTop" aria-label="Back to top"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="fas fa-arrow-up"></i>
</button>


<!-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── CONFIG ── */
const API = '../api';

/* ── SCROLL TO TOP ── */
window.addEventListener('scroll', () => {
  document.getElementById('scrollTop').classList.toggle('show', scrollY > 300);
});

/* ── HELPERS ── */
function fmt(n) {
  return 'KES ' + Number(n).toLocaleString('en-KE', { maximumFractionDigits: 0 });
}

/* ── UPDATE QTY ── */
async function updateQty(id, newQty) {
  const res  = await fetch(`${API}/cart_update.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ id, qty: newQty }),
  });
  const data = await res.json();

  if (newQty <= 0) {
    removeRow(id);
    if (data.cart.length === 0) showEmpty();
  } else {
    /* update qty badge */
    const qtyEl = document.getElementById('qty-' + id);
    if (qtyEl) qtyEl.textContent = newQty;

    /* update stepper button handlers inline */
    const row = document.querySelector(`.cart-row[data-id="${id}"]`);
    if (row) {
      const [dec, inc] = row.querySelectorAll('.qty-stepper button');
      dec.setAttribute('onclick', `updateQty('${id}',${newQty - 1})`);
      inc.setAttribute('onclick', `updateQty('${id}',${newQty + 1})`);
    }

    /* update line total in table */
    const item = data.cart.find(i => String(i.id) === String(id));
    if (item) {
      const lineEl = document.getElementById('line-' + id);
      if (lineEl) lineEl.textContent = fmt(item.price * item.qty);

      /* update sidebar */
      const osQty = document.getElementById('os-qty-' + id);
      const osVal = document.getElementById('os-val-' + id);
      if (osQty) osQty.textContent = item.qty;
      if (osVal) osVal.textContent = fmt(item.price * item.qty);
    }
  }
  refreshTotals(data);
}

/* ── REMOVE ITEM ── */
async function removeItem(id) {
  const res  = await fetch(`${API}/cart_remove.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ id }),
  });
  const data = await res.json();
  removeRow(id);
  refreshTotals(data);
  if (data.cart.length === 0) showEmpty();
}

/* ── DOM HELPERS ── */
function removeRow(id) {
  document.querySelector(`.cart-row[data-id="${id}"]`)?.remove();
  document.getElementById('os-' + id)?.remove();
}

function refreshTotals(data) {
  const subtotal = data.subtotal ?? data.cart.reduce((s, i) => s + i.price * i.qty, 0);
  const count    = data.total_items ?? data.cart.reduce((s, i) => s + i.qty, 0);

  const osTotal = document.getElementById('osTotal');
  if (osTotal) osTotal.textContent = fmt(subtotal);

  const badge = document.getElementById('cartBadge');
  if (badge) badge.textContent = count;
}

function showEmpty() {
  document.querySelector('.row.g-4')?.remove();
  document.querySelector('main .container').innerHTML = `
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
      <div class="empty-title">Your cart is empty</div>
      <p class="empty-sub">Browse our catalogue and add something you need.</p>
      <a href="../dashboard/index.php#shop" class="btn-shop">
        Start Shopping &nbsp;<i class="fas fa-arrow-right" style="font-size:11px;"></i>
      </a>
    </div>`;
}

/* ── CHECKOUT ── */
function handleCheckout() {
  const name     = document.getElementById('fName')?.value.trim();
  const phone    = document.getElementById('fPhone')?.value.trim();
  const location = document.getElementById('fLocation')?.value.trim();

  if (!name || !phone || !location) {
    alert('Please fill in your Full Name, Phone Number, and Location before continuing.');
    return;
  }
  /* TODO: wire M-Pesa STK push or redirect to payment gateway */
  alert(`Thank you, ${name}!\nOur team will call ${phone} to confirm your order and delivery to ${location}.`);
}
</script>
</body>
</html>