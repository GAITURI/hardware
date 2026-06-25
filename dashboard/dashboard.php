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
  <title>Mambo Outdoor- Outdoor Product Shops in Ruiru</title>

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

  <link rel="stylesheet" href="/dashboard/styles.css"/>
</head>
<body>

<?php
// Pass current cart count to the page so the badge shows immediately
$cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
?>

<!-- TOP UTILITY BAR -->
<div class="top-bar">
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
          <div class="top-bar-value">info@outdoor.co.ke</div>
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

    <a class="navbar-brand me-auto"  href="dashboard.php">
      <div class="brand-wrap">
        <div class="brand-icon">
        <img src="../images/logoimg.jpg" alt="Mambo Outdoor Logo" class="brand-logo-img">
        </div>
        <div>
          <div class="brand-text-top">Mambo</div>
          <div class="brand-text-bot">Outdoors</div>
        </div>
      </div>
    </a>

    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Shop</a>
          <ul class="dropdown-menu border-0 shadow"
              style="border-radius:10px;min-width:200px;padding:8px;">
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Outdoor Chairs</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Outdoor Sets</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Outdoor Tables</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Grass Mats</a></li>
            <li><a class="dropdown-item" href="#" style="border-radius:6px;font-size:13px;font-weight:600;padding:8px 14px;">Swings</a></li>
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


<!--
     TICKER BAR
-->
<div class="ticker-bar">
  <div class="ticker-inner" id="ticker">
    <span class="ticker-item"><span class="ticker-dot"></span>Premium Outdoor Furniture</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Quality Hammocks</span>
    <span class="ticker-item"><span class="ticker-dot"></span> Outdoor &amp; Accessories</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Handcrafted Furniture </span>
    <span class="ticker-item"><span class="ticker-dot"></span> Imported &amp; Swings</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Free Delivery on Orders Over KES 5,000</span>
    <!-- duplicate for seamless loop -->
    <span class="ticker-item"><span class="ticker-dot"></span>Premium Outdoor Furniture</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Quality Hammocks</span>
    <span class="ticker-item"><span class="ticker-dot"></span> Outdoor &amp; Accessories</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Handcrafted Furniture </span>
    <span class="ticker-item"><span class="ticker-dot"></span> Imported &amp; Swings</span>
    <span class="ticker-item"><span class="ticker-dot"></span>Free Delivery on Orders Over KES 5,000</span>
  </div>
</div>


<!--
     HERO SECTION
-->
<section class="hero-section">
  <div class="container">
    <div class="row g-4">

      <div class="col-lg-8">
        <div class="hero-main h-100" >
          <img src="../images/hero1.png" id="heroImage" class="hero-img active"
          style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:opacity 0.5s ease;" alt="img1">
          <div class="hero-overlay"
               style="position:absolute;inset:0;background:linear-gradient(90deg,rgba(15,23,42,0.9) 0%,rgba(15,23,42,0.4) 60%,transparent 100%);"></div>
          <div class="hero-content" id="heroContent">
            <div class="hero-tag">Handmade Products</div>
            <div class="hero-title">Imported &amp;<br>Products</div>
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
           <img src="../images/quality set.jpg" alt="Quality Seats">
            <div class="side-card-overlay"></div>
            <div class="side-card-content">
              <div class="side-card-title">Quality Sets</div>
              <div class="side-card-sub">Beautiful Outdoor Sets</div>
            </div>
          </div>
          <div class="side-card">
            <img src="../images/handcrafted.jpg" alt="Handcrafted Products" >
            <div class="side-card-overlay"></div>
            <div class="side-card-content">
              <div class="side-card-title">Handcrafted Products</div>
              <div class="side-card-sub">From KES 10,500</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- category sections -->
<section class="categories-section">
  <div class="container">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#2d2d4e);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-hard-hat" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label">Handcrafted Furniture</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#0d1b2a,#1b3a5c);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-tint" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label">Rattan Products</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="cat-card">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#2a1a1a,#5c1b1b);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-bolt" style="font-size:64px;color:rgba(255,255,255,0.15);"></i>
          </div>
          <div class="cat-card-overlay"></div>
          <div class="cat-card-label"> Luxury Furniture</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Collection section -->
<section class="collection-section" id="shop">
  <div class="container">
    <div class="explore-heading-wrap">
      <div class="explore-line"></div>
      <h2 class="explore-text">EXPLORE OUR <br>Collection</h2>
      <div class="explore-line"></div>
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
        View Full Showroom &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
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
      <h2 class="about-title">ELEVATE YOUR Garden.<br>The Boutique Premium Standards.</h2>
      <p class="about-body">
      We craft and procure premium woven Rattan frameworks, synthetic composite daybeds, and commercial grade timber bar configurations built to withstand tropical conditions without shedding luxury fidelity.
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
                <img src="images/featured.jpg">
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


<!--
     CONSTRUCTION HELP
 -->
<section class="repair-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 repair-left">
        <div class="repair-tag">Construction Help</div>
        <h2 class="repair-title">Design PROBLEM?<br>CONSULT US</h2>
        <p class="repair-body">
          Are you Having trouble designing your outdoor space consult us.
          We have an experienced team to help you design to your needs.
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
            <div class="footer-logo-sub">Outdoors</div>
          </div>
        </div>
        <p class="footer-desc">
          Ruiru's trusted Outdoor store. Quality Outdoor Products, Chairs, Tables,
          and rattan supplies — all under one roof.
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
        <a class="footer-link" href="#">Outdoor Sets</a>
        <a class="footer-link" href="#">Swings and Hammocks</a>
        <a class="footer-link" href="#">Quality Rattan Sets</a>
        <a class="footer-link" href="#">Outdoor &amp; Accessories</a>
        <a class="footer-link" href="#">Green Mats</a>
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
          <div class="footer-contact-text">info@mambooutdoors.co.ke</div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">&copy; 2026 MamboOutdoors. All rights reserved.</div>
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
<script src="/dashboard/app.js?v=2"></script>
<script src="/dashboard/cart-drawer.js"></script>

</body>
</html>