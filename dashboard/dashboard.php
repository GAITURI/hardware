<?php
/**
 * dashboard/index.php
 * Main storefront. Session started here so the cart API
 * and the drawer all share the same session.
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mambo Hardware - The Best Hardware Shop in Ruiru</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Barlow+Condensed:wght@600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { crimson:'#d32f2f', charcoal:'#1e293b', slate:'#64748b' } } },
      corePlugins: { preflight: false }
    }
  </script>

  <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<?php
// Pass current cart count to the page so the badge shows immediately
$cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
?>

<!-- ════════════════════════════════════════
     TOP UTILITY BAR
════════════════════════════════════════ -->
<div class="top-bar">
  <div class="container">
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


<!-- ════════════════════════════════════════
     MAIN NAVBAR
════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg main-nav sticky-top" id="mainNav">
  <div class="container">

    <a class="navbar-brand" href="#">
      <div class="brand-wrap">
        <div class="brand-icon"><i class="fas fa-hammer"></i></div>
        <div>
          <div class="brand-text-top">Mambo</div>
          <div class="brand-text-bot">Hardware</div>
        </div>
      </div>
    </a>

    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navMenu">
      <ul class="navbar-nav">
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

    <div class="nav-utilities">
      <button class="nav-icon-btn" id="searchBtn" aria-label="Search">
        <i class="fas fa-search"></i>
      </button>
      <!-- Cart button — clicked to open drawer (handled by cart-drawer.js) -->
      <a href="#" class="nav-icon-btn" aria-label="Cart" id="cartNavBtn">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge"><?= $cartCount ?: 0 ?></span>
      </a>
      <a href="#" class="btn-admin">
        <i class="fas fa-user-shield"></i> Admin
      </a>
    </div>

  </div>
</nav>


<!-- ════════════════════════════════════════
     TICKER BAR
════════════════════════════════════════ -->
<div class="ticker-bar">
  <div class="ticker-inner" id="ticker">
    <span class="ticker-item"><span class="ticker-dot"></span>Premium Wood Products</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Sanitary Products</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Kitchen &amp; Accessories</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Hardware Supplies</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Tiles &amp; Flooring</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Free Delivery on Orders Over KES 5,000</span>
    <!-- duplicate for seamless loop -->
    <span class="ticker-item"><span class="ticker-dot"></span>Premium Wood Products</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Sanitary Products</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Kitchen &amp; Accessories</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Hardware Supplies</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Tiles &amp; Flooring</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Free Delivery on Orders Over KES 5,000</span>
  </div>
</div>


<!-- ════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════ -->
<section class="hero-section">
  <div class="container">
    <div class="row g-3">

      <div class="col-lg-8">
        <div class="hero-main" style="background:#0f172a;">
          <img src="images/bowl1.jpg" id="heroImage" class="hero-img active"
               style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:opacity 0.5s ease;">
          <div class="hero-overlay"
               style="position:absolute;inset:0;background:linear-gradient(90deg,rgba(15,23,42,0.9) 0%,rgba(15,23,42,0.4) 60%,transparent 100%);"></div>
          <div class="hero-content" id="heroContent">
            <div class="hero-tag">Quality Hardware Materials</div>
            <div class="hero-title">Steel &amp;<br>Wood</div>
            <div class="hero-sub"></div>
            <a href="#shop" class="btn-hero">Shop Now &nbsp;<i class="fas fa-arrow-right fa-xs"></i></a>
          </div>
          <div class="hero-dots">
            <div class="hero-dot active" data-slide="0"></div>
            <div class="hero-dot"        data-slide="1"></div>
            <div class="hero-dot"        data-slide="2"></div>
            <div class="hero-dot"        data-slide="3"></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="hero-side h-100">
          <div class="side-card">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a2a4e,#2d4a8e);position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-layer-group" style="font-size:72px;color:rgba(255,255,255,0.2);"></i>
            </div>
            <div class="side-card-overlay"></div>
            <div class="side-card-content">
              <div class="side-card-title">Premium Boards</div>
              <div class="side-card-sub">Verified premium quality</div>
            </div>
          </div>
          <div class="side-card">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#2d1b4e,#4e2d7a);position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-industry" style="font-size:60px;color:rgba(255,255,255,0.2);"></i>
            </div>
            <div class="side-card-overlay"></div>
            <div class="side-card-content">
              <div class="side-card-title">Building Cement</div>
              <div class="side-card-sub">From KES 3,500</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     CATEGORIES
════════════════════════════════════════ -->
<section class="categories-section">
  <div class="container">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#2d2d4e);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-hard-hat" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label">Construction Material</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#0d1b2a,#1b3a5c);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-tint" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label">Water Products</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#2a1a1a,#5c1b1b);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-bolt" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label">Electrical Components</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     EXPLORE OUR COLLECTION
════════════════════════════════════════ -->
<section class="collection-section" id="shop">
  <div class="container">
    <div class="section-heading">
      <div class="section-badge">Showcase</div>
      <h2 class="section-title">EXPLORE OUR<br>COLLECTION</h2>
      <div class="section-rule">
        <div class="line"></div>
        <i class="fas fa-circle" style="font-size:6px;color:var(--crimson);opacity:0.5;"></i>
        <div class="line"></div>
      </div>
    </div>
    <div class="tab-nav">
      <button class="tab-btn active" data-tab="latest">Latest</button>
      <button class="tab-btn"        data-tab="hotdeals">Hot Deals</button>
    </div>
    <div class="tab-pane active" id="tab-latest">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4" id="productsGrid">
        <div class="col text-center py-5 text-muted">
          <i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br>Loading products…
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab-hotdeals">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4" id="hotdealsGrid">
        <div class="col text-center py-5 text-muted">
          <i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br>Loading products…
        </div>
      </div>
    </div>
    <div class="text-center mt-5">
      <a href="#" class="btn-hero" style="display:inline-flex;align-items:center;gap:10px;">
        View All Products &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
      </a>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     ABOUT SECTION
════════════════════════════════════════ -->
<section class="about-section" id="about">
  <div class="row g-0">
    <div class="col-lg-6 about-left">
      <div style="width:100%;height:480px;background:linear-gradient(135deg,#e8edf2,#d0d8e8);display:flex;align-items:center;justify-content:center;position:relative;">
        <div style="position:relative;width:280px;height:340px;">
          <div style="position:absolute;right:20px;top:20px;width:180px;height:300px;background:linear-gradient(180deg,#d0d8e8,#b8c4d8);border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,0.15);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-hammer" style="font-size:48px;color:rgba(0,0,0,0.2);"></i>
          </div>
          <div style="position:absolute;left:0;top:80px;width:100px;height:100px;background:linear-gradient(135deg,#fff,#e8edf2);border-radius:50%;box-shadow:0 8px 32px rgba(0,0,0,0.12);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-wrench" style="font-size:36px;color:rgba(0,0,0,0.25);"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 about-right">
      <div class="about-eyebrow">About Us</div>
      <h2 class="about-title">ELEVATE YOUR BUILD.<br>CONSTRUCT WITH CONFIDENCE.</h2>
      <p class="about-body">
        We supply quality building materials, tools, and hardware products trusted by professionals and homeowners alike.
        From construction essentials to finishing supplies, we make it easy to get durable products at competitive prices.
      </p>
      <a href="#" class="btn-learn">Learn More &nbsp;<i class="fas fa-arrow-right fa-xs"></i></a>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════════════ -->
<section class="featured-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 featured-left">
        <div class="featured-tag">Featured Products</div>
        <h2 class="featured-title">FEATURED<br>PRODUCTS</h2>
        <p class="featured-body">
          Every item in our store is carefully selected to ensure durability, safety, and value for money.
          Your satisfaction is our priority — build with confidence.
        </p>
        <a href="#shop" class="btn-buynow">Shop Now &nbsp;<i class="fas fa-arrow-right fa-xs"></i></a>
      </div>
      <div class="col-lg-7">
        <div class="featured-mosaic">
          <div class="mosaic-main">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#f4f6f9,#e8edf5);display:flex;align-items:center;justify-content:center;">
              <div style="text-align:center;padding:20px;">
                <i class="fas fa-tools" style="font-size:72px;color:rgba(0,0,0,0.12);display:block;margin-bottom:16px;"></i>
                <div style="font-size:12px;font-weight:600;color:rgba(0,0,0,0.25);letter-spacing:1px;text-transform:uppercase;">Professional Tools</div>
              </div>
            </div>
          </div>
          <div class="mosaic-sm">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#fff5f5,#ffe8e8);display:flex;align-items:center;justify-content:center;">
              <div style="text-align:center;">
                <i class="fas fa-layer-group" style="font-size:36px;color:rgba(211,47,47,0.2);display:block;margin-bottom:8px;"></i>
                <div style="font-size:10px;font-weight:700;color:rgba(0,0,0,0.25);letter-spacing:1px;text-transform:uppercase;">Boards &amp; Panels</div>
              </div>
            </div>
          </div>
          <div class="mosaic-sm">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#f0f8ff,#ddeeff);display:flex;align-items:center;justify-content:center;">
              <div style="text-align:center;">
                <i class="fas fa-shower" style="font-size:36px;color:rgba(30,41,59,0.2);display:block;margin-bottom:8px;"></i>
                <div style="font-size:10px;font-weight:700;color:rgba(0,0,0,0.25);letter-spacing:1px;text-transform:uppercase;">Sanitary Ware</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     CONSTRUCTION HELP
════════════════════════════════════════ -->
<section class="repair-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 repair-left">
        <div class="repair-tag">Construction Help</div>
        <h2 class="repair-title">CONSTRUCTION PROBLEM?<br>CONSULT US</h2>
        <p class="repair-body">
          Got a construction problem? We are here to help with reliable hardware and quality building materials
          for every stage of your project — from foundation to finishing.
        </p>
        <a href="#" class="btn-book">Book a Session &nbsp;<i class="fas fa-arrow-right fa-xs"></i></a>
      </div>
      <div class="col-lg-7">
        <div class="repair-gallery">
          <div class="repair-img-card">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#f0f0f0,#e0e0e0);display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-tools" style="font-size:64px;color:rgba(0,0,0,0.12);"></i>
            </div>
            <div class="repair-label">Before</div>
          </div>
          <div class="repair-img-card">
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a2a3e,#0d1b2a);display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-home" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
            </div>
            <div class="repair-label-after">After</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="brand-wrap mb-3">
          <div class="brand-icon"><i class="fas fa-hammer"></i></div>
          <div>
            <div class="footer-logo-text">MAMBO</div>
            <div class="footer-logo-sub">Hardware</div>
          </div>
        </div>
        <p class="footer-desc">
          Ruiru's trusted hardware store. Quality building materials, tools, sanitary ware,
          and electrical supplies — all under one roof.
        </p>
        <div class="footer-social">
          <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading">Quick Links</div>
        <a class="footer-link" href="#">Home</a>
        <a class="footer-link" href="#about">About Us</a>
        <a class="footer-link" href="#shop">Shop</a>
        <a class="footer-link" href="#">Blog</a>
        <a class="footer-link" href="#">Contact</a>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading">Categories</div>
        <a class="footer-link" href="#">Building Materials</a>
        <a class="footer-link" href="#">Kitchen Components</a>
        <a class="footer-link" href="#">Tools</a>
        <a class="footer-link" href="#">Wood &amp; Accessories</a>
        <a class="footer-link" href="#">Steel</a>
      </div>
      <div class="col-lg-4">
        <div class="footer-heading">Contact Us</div>
        <div class="footer-contact-item">
          <i class="fas fa-map-marker-alt footer-contact-icon"></i>
          <div class="footer-contact-text">Ruiru Bypass, Kamakis, Kiambu, Kenya</div>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-phone-alt footer-contact-icon"></i>
          <div class="footer-contact-text">+254798275251</div>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-envelope footer-contact-icon"></i>
          <div class="footer-contact-text">info@mambohardware.co.ke</div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">&copy; 2026 Mambo Hardware. All rights reserved.</div>
      <div class="footer-copy">Built with Trust</div>
    </div>
  </div>
</footer>


<!-- SCROLL TO TOP -->
<button id="scrollTop" aria-label="Scroll to top">
  <i class="fas fa-arrow-up"></i>
</button>


<!-- SEARCH MODAL -->
<div class="search-modal" id="searchModal" role="dialog" aria-modal="true">
  <div class="search-box">
    <div class="d-flex align-items-center gap-3 mb-3">
      <h6 style="font-weight:700;color:var(--charcoal);margin:0;">Search Products</h6>
      <button class="ms-auto btn-close" id="closeSearch"></button>
    </div>
    <input type="text" class="search-input" id="searchInput"
           placeholder="Search for construction materials, tools, sanitary ware…"
           autocomplete="off"/>
    <div class="mt-3 d-flex gap-2 flex-wrap">
      <span style="font-size:12px;font-weight:600;padding:6px 12px;background:#f1f5f9;border-radius:20px;cursor:pointer;color:var(--slate);">Construction Material</span>
      <span style="font-size:12px;font-weight:600;padding:6px 12px;background:#f1f5f9;border-radius:20px;cursor:pointer;color:var(--slate);">Boards</span>
      <span style="font-size:12px;font-weight:600;padding:6px 12px;background:#f1f5f9;border-radius:20px;cursor:pointer;color:var(--slate);">Shower Accessories</span>
      <span style="font-size:12px;font-weight:600;padding:6px 12px;background:#f1f5f9;border-radius:20px;cursor:pointer;color:var(--slate);">Tools</span>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Cart drawer must load BEFORE app.js so bindAddToCartButtons is available -->
<script src="app.js"></script>
<script src="cart-drawer.js"></script>

</body>
</html>