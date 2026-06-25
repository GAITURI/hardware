<?php
/**
 * cart/index.php — Cart + Checkout page
 * UI matches Oasis Technologies screenshot exactly.
 */
session_start();

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../api/cart_helper.php';

$cartData = getCartData($pdo);
$items     = $cartData['items'];
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Barlow+Condensed:wght@700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        mamboRed: '#ef4444',
                        mamboDark: '#1e293b',
                        crimson:'#d32f2f',
                        charcoal:'#1e293b',
                        slate:'#64748b'
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="/cart/cart.css"/>
</head>
<body>




<!-- ══════════════════════════════════════════
     Topbar and Navbar
══════════════════════════════════════════ -->


<div class="top-bar hidden lg:block">
  <div class="container-fluid px-4">
    <div class="row align-items-center">

      <div class="col-md-4 top-bar-item">
        <div class="top-bar-icon"><i class="fas fa-phone-alt"></i></div>
        <div>
          <div class="top-bar-label">Call Us Now</div>
          <div class="top-bar-value">+254798275251</div>
        </div>
      </div>

      <div class="col-md-4 top-bar-item">
        <div class="top-bar-icon"><i class="fas fa-envelope"></i></div>
        <div>
          <div class="top-bar-label">Email Us</div>
          <div class="top-bar-value">info@mambohardware.co.ke</div>
        </div>
      </div>

      <div class="col-md-4 d-flex align-items-center justify-content-between ps-4">
        <div class="top-bar-item" style="border-right:none">
          <div class="top-bar-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="top-bar-label">Find Us</div>
            <div class="top-bar-value" style="font-size:12px;line-height:1.35">
              Ruiru Bypass<br>Kamakis, Kiambu, Kenya
            </div>
          </div>
        </div>
        <div class="d-flex gap-2">
          <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- main navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm main-nav sticky-top" id="mainNav">
  <div class="container-fluid px-2 px-md-4">

    <a class="navbar-brand me-auto"  href="../dashboard/dashboard.php">
      <div class="brand-wrap">
        <div class="brand-icon">
        <img src="../images/logoimg.jpg" alt="Mambo Hardware Logo" class="brand-logo-img">
        </div>
        <div>
          <div class="brand-text-top">Mambo</div>
          <div class="brand-text-bot">Hardware</div>
        </div>
      </div>
    </a>
    
    <div class="d-flex align-items-center gap-2 order-lg-last">
    <div class="nav-utilities d-flex align-items-center gap-2">
      <button class="nav-icon-btn" id="searchBtn" aria-label="Search">
        <i class="fas fa-search"></i>
      </button>
      <!-- Cart button — clicked to open drawer (handled by cart-drawer.js) -->
      <a href="cart.php" class="nav-icon-btn relative position-relative" aria-label="View Cart">
    <i class="fas fa-shopping-cart"></i>
    <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 4px 6px;">
      <?= $count ?>
    </span>
  </a>
      <a href="#" class="btn-admin">
        <i class="fas fa-user-shield"></i> <span class="d-none d-md-inline">Admin</span>
      </a>
    </div>
    <button class="navbar-toggler border-0" type="button"data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
  <div class="collapse navbar-collapse justify-content-center" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Shop</a>
          <ul class="dropdown-menu border-0 shadow"
              style="border-radius:10px;min-width:200px;padding:8px;">
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Building Materials</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Sanitary Ware</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Electrical</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Tools</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Tiles & Flooring</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
      </ul>
    </div>

</div>
</nav>
<!-- page start -->
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

  <?php if (empty($items)): ?>
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
        <?php foreach ($items as $item): ?>
          <div class="cart-row" data-id="<?= htmlspecialchars($item['id']) ?>">

            <!-- PRODUCT -->
            <div class="product-cell">
              <div class="product-thumb">
                <?php if (!empty($item['image_url'])): ?>
                  <img src="../<?= htmlspecialchars(ltrim($item['image_url'], '/')) ?>"
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
        <a href="../dashboard/dashboard.php" class="continue-link">
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
          <?php foreach ($items as $item): ?>
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
<script src="/cart/cart.js"></script>
</body>
</html>