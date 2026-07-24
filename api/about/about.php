<?php
/**
 * about/index.php — About Us page
 * Matches Oasis Technologies About page UI from screenshots.
 */
session_start();
$cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us — Mambo Hardware</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Barlow+Condensed:wght@700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/about/about.css"/>

</head>
<body>

<!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
<nav class="main-nav">
  <div class="container nav-inner">
    <a href="../dashboard/index.php" class="brand-wrap">
      <div class="brand-icon"><i class="fas fa-hammer"></i></div>
      <div>
        <div class="brand-text-top">Mambo</div>
        <div class="brand-text-bot">Hardware</div>
      </div>
    </a>

    <div class="nav-links">
      <a href="../dashboard/dashboard.php">Home</a>
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

    <div class="nav-utils">
      <button class="nav-icon-btn" aria-label="Search">
        <i class="fas fa-search"></i>
      </button>
      <a href="../cart/index.php" class="nav-icon-btn" aria-label="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge"><?= $cartCount ?: 0 ?></span>
      </a>
      <a href="#" class="btn-admin">
        <i class="fas fa-user-shield"></i> Admin
      </a>
    </div>
  </div>
</nav>


<!-- ══════════════════════════════════════════
     SECTION 1 — HERO SPLIT
     Image mosaic LEFT · Mission + Vision RIGHT
══════════════════════════════════════════ -->
<section class="s1">
  <div class="container">
    <div class="row align-items-center g-5">

      <!-- Image mosaic -->
      <div class="col-lg-6">
        <div class="s1-mosaic">
          <div class="s1-img-main">
            <?php if (file_exists('../dashboard/images/about1.jpg')): ?>
              <img src="../dashboard/images/about1.jpg" alt="Mambo Hardware store">
            <?php else: ?>
              <div style="width:100%;height:100%;background:linear-gradient(135deg,#e2e8f0,#cbd5e1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-store" style="font-size:72px;color:rgba(0,0,0,0.1);"></i>
              </div>
            <?php endif; ?>
          </div>
          <div class="s1-img-over">
            <?php if (file_exists('../dashboard/images/about2.jpg')): ?>
              <img src="../dashboard/images/about2.jpg" alt="Hardware products">
            <?php else: ?>
              <div style="width:100%;height:100%;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-tools" style="font-size:48px;color:rgba(0,0,0,0.12);"></i>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Text + Mission/Vision -->
      <div class="col-lg-6 s1-text">
        <h2 class="section-title-lg">RUIRU'S PREMIER<br>HARDWARE HUB</h2>
        <p class="section-body" style="margin-bottom:28px;">
          Mambo Hardware is a world-class solution provider specialising in premium building
          materials, sanitary ware, electrical components, tools, and finishing supplies.
          We aren't just a shop — we are a full-service hardware hub that pairs product sales
          with expert consultation and genuine quality.
        </p>

        <!-- Mission & Vision icons -->
        <div class="mission-vision-block">
          <div class="mv-item">
            <div class="mv-icon">
              <i class="fas fa-bullseye"></i>
            </div>
            <div>
              <div class="mv-label">Our Mission</div>
              <div class="mv-desc">
                To be Ruiru's most trusted hardware partner — providing premium building
                materials, genuine parts, and top-tier construction supplies with speed,
                honesty and care.
              </div>
            </div>
          </div>
          <div class="mv-item">
            <div class="mv-icon">
              <i class="fas fa-lightbulb"></i>
            </div>
            <div>
              <div class="mv-label">Our Vision</div>
              <div class="mv-desc">
                To become East Africa's number one hardware service and solution provider,
                built on a customer-centric model rooted in value, reliability and
                human connection.
              </div>
            </div>
          </div>
        </div>

        <a href="../dashboard/index.php#shop" class="btn-shop-products">
          SHOP OUR PRODUCTS
        </a>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 2 — BUILT TO ELEVATE
     Text + two buttons LEFT · Image RIGHT
══════════════════════════════════════════ -->
<section class="s2">
  <div class="container">
    <div class="row align-items-center g-5">

      <!-- Text -->
      <div class="col-lg-6 s2-text">
        <div class="s2-rule">
          <div class="s2-rule-line"></div>
          <span class="s2-rule-label">Mission &amp; Vision</span>
        </div>

        <h2 class="section-title-lg">BUILT TO ELEVATE.<br>DESIGNED TO<br>CONSTRUCT.</h2>

        <p class="s2-body">
          Everything we do is driven by one motto: "Elevate your build, construct with confidence."
          We focus on the quality and durability of every product, and the rugged reliability
          needed to keep your projects running perfectly.
        </p>

        <div class="s2-btns">
          <a href="#mission" class="btn-mission">Our Mission</a>
          <a href="#vision"  class="btn-vision">Our Vision</a>
        </div>

        <!-- Vision expandable text -->
        <div class="s2-vision-block" id="vision">
          <div class="s2-vision-rule">
            <div class="s2-vision-rule-line"></div>
            <span class="s2-vision-title">Elevate your build, construct with confidence.</span>
          </div>
          <p class="s2-vision-body">
            Our vision is to become East Africa's number one hardware service and solution
            provider — building a customer-centric business rooted in speed, human connection,
            value and proven results.
          </p>
        </div>
      </div>

      <!-- Image -->
      <div class="col-lg-6">
        <div class="s2-img">
          <?php if (file_exists('../dashboard/images/repair.jpg')): ?>
            <img src="../dashboard/images/repair.jpg" alt="Construction work">
          <?php else: ?>
            <div class="s2-img-placeholder">
              <i class="fas fa-hard-hat"></i>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 3 — WHAT SETS US APART
     3-col image mosaic LEFT · Skill bars RIGHT
══════════════════════════════════════════ -->
<section class="s3">
  <div class="container">
    <div class="row align-items-center g-5">

      <!-- 3-col image mosaic -->
      <div class="col-lg-6">
        <div class="s3-mosaic">
          <div class="s3-col">
            <?php if (file_exists('../dashboard/images/tool1.jpg')): ?>
              <img src="../dashboard/images/tool1.jpg" alt="Tools">
            <?php else: ?>
              <div class="s3-col-ph"><i class="fas fa-wrench"></i></div>
            <?php endif; ?>
          </div>
          <div class="s3-col">
            <?php if (file_exists('../dashboard/images/tool2.jpg')): ?>
              <img src="../dashboard/images/tool2.jpg" alt="Hardware">
            <?php else: ?>
              <div class="s3-col-ph"><i class="fas fa-hammer"></i></div>
            <?php endif; ?>
          </div>
          <div class="s3-col">
            <?php if (file_exists('../dashboard/images/tool3.jpg')): ?>
              <img src="../dashboard/images/tool3.jpg" alt="Construction">
            <?php else: ?>
              <div class="s3-col-ph"><i class="fas fa-hard-hat"></i></div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Text + bars -->
      <div class="col-lg-6 s3-text">
        <div class="eyebrow">Why Choose Us</div>
        <h2 class="section-title-lg" style="font-size:clamp(28px,4vw,42px);">WHAT SETS US APART</h2>
        <p class="s3-intro">
          We distinguish ourselves through a customer-centric model built on four pillars:
          Speed &amp; Reliability, Human Connection, Value and Proven Results.
        </p>

        <div class="skill-bar-list" id="skillBars">
          <div class="skill-bar-item">
            <div class="skill-bar-track">
              <div class="skill-bar-fill" style="--bar-w:97%">
                <span class="skill-bar-label">Speed &amp; Reliability</span>
                <span class="skill-bar-pct">97%</span>
              </div>
            </div>
          </div>
          <div class="skill-bar-item">
            <div class="skill-bar-track">
              <div class="skill-bar-fill" style="--bar-w:96%">
                <span class="skill-bar-label">Technical Excellence (Repairs)</span>
                <span class="skill-bar-pct">96%</span>
              </div>
            </div>
          </div>
          <div class="skill-bar-item">
            <div class="skill-bar-track">
              <div class="skill-bar-fill" style="--bar-w:99%">
                <span class="skill-bar-label">Customer-Centric Service</span>
                <span class="skill-bar-pct">99%</span>
              </div>
            </div>
          </div>
          <div class="skill-bar-item">
            <div class="skill-bar-track">
              <div class="skill-bar-fill" style="--bar-w:95%">
                <span class="skill-bar-label">Premium Product Sourcing</span>
                <span class="skill-bar-pct">95%</span>
              </div>
            </div>
          </div>
          <div class="skill-bar-item">
            <div class="skill-bar-track">
              <div class="skill-bar-fill" style="--bar-w:98%">
                <span class="skill-bar-label">Value &amp; Integrity</span>
                <span class="skill-bar-pct">98%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 4 — MEET OUR TEAM
══════════════════════════════════════════ -->
<section class="s4">
  <div class="container">
    <div class="s4-header">
      <h2 class="section-title-lg" style="text-align:center;">MEET OUR TEAM</h2>
      <p class="s4-intro">
        The people behind Mambo Hardware — passionate, knowledgeable and committed
        to getting you the best products at the best price, built right and backed with integrity.
      </p>
    </div>

    <div class="row g-4">
      <?php
      $team = [
        ['name'=>'James Mwangi',  'role'=>'Founder & CEO',              'icon'=>'fa-user-tie'],
        ['name'=>'Wanjiru Njoroge','role'=>'Head of Operations',         'icon'=>'fa-user-cog'],
        ['name'=>'Peter Kamau',   'role'=>'Lead Construction Consultant','icon'=>'fa-hard-hat'],
      ];
      foreach ($team as $member): ?>
      <div class="col-lg-4 col-md-6">
        <div class="team-card">
          <div class="team-photo">
            <i class="fas <?= $member['icon'] ?> ph-icon"></i>
          </div>
          <div class="team-name"><?= $member['name'] ?></div>
          <div class="team-role"><?= $member['role'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     SECTION 5 — TESTIMONIALS
══════════════════════════════════════════ -->
<section class="s5">
  <div class="container">

    <div class="s5-header">
      <h2 class="section-title-lg" style="text-align:center;">WHAT OUR CUSTOMERS SAY</h2>
      <p class="s5-intro">
        Don't take our word for it — here's what real customers from Ruiru and beyond
        have to say about buying and building with us.
      </p>
    </div>

    <?php
    $testimonials = [
      [
        'headline' => '"THEY ACTUALLY CARE ABOUT CUSTOMERS"',
        'quote'    => '"I\'ve sourced building materials from Mambo Hardware three times over two years — tiles, piping, and electrical fittings. Every single time, the team listened to my budget and gave honest recommendations. They never pushed me to overspend. That kind of integrity is rare."',
        'name'     => 'Grace Wanjiru',
        'label'    => 'Loyal Customer',
        'icon'     => 'fa-user-circle',
      ],
      [
        'headline' => '"BEST HARDWARE PRICES IN RUIRU"',
        'quote'    => '"I compared prices at four different hardware shops. Mambo had the best deals on cement and roofing materials, and the staff actually knew what they were talking about. I\'ll be coming back for my next project."',
        'name'     => 'Samuel Njoroge',
        'label'    => 'Building Contractor',
        'icon'     => 'fa-user-hard-hat',
      ],
      [
        'headline' => '"FAST DELIVERY, QUALITY PRODUCTS"',
        'quote'    => '"Ordered sanitary ware and kitchen fittings for my renovation. Delivery was same-day to Kamakis. Everything was genuine quality — no counterfeits. Exactly what you need when you\'re on a construction timeline."',
        'name'     => 'Aisha Muthoni',
        'label'    => 'Homeowner',
        'icon'     => 'fa-user',
      ],
    ];
    ?>

    <!-- Avatar selector -->
    <div class="avatar-selector" id="avatarSelector">
      <?php foreach ($testimonials as $i => $t): ?>
      <div class="av-dot <?= $i===0?'active':'' ?>"
           onclick="showTestimonial(<?= $i ?>)">
        <i class="fas <?= $t['icon'] ?> av-dot-ph" style="font-size:22px;color:#94a3b8;"></i>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Testimonial slides -->
    <div id="testimonialContainer">
      <?php foreach ($testimonials as $i => $t): ?>
      <div class="testimonial-slide <?= $i===0?'active':'' ?>" id="testi-<?= $i ?>">
        <div class="testi-avatar">
          <i class="fas <?= $t['icon'] ?> ph" style="font-size:72px;color:#94a3b8;"></i>
        </div>
        <div class="testi-content">
          <div class="testi-headline"><?= $t['headline'] ?></div>
          <p class="testi-quote"><?= $t['quote'] ?></p>
          <div class="testi-name"><?= $t['name'] ?></div>
          <div class="testi-label"><?= $t['label'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5">
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
      <div class="col-lg-2 col-6">
        <div class="footer-col-title">Useful Links</div>
        <a href="../dashboard/index.php" class="footer-link">Home</a>
        <a href="index.php"              class="footer-link">About Us</a>
        <a href="../dashboard/index.php#shop" class="footer-link">Shop</a>
        <a href="#" class="footer-link">Contact</a>
        <a href="#" class="footer-link">Blog</a>
        <a href="#" class="footer-link">Legal</a>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-col-title">Quick Links</div>
        <a href="#" class="footer-link">Collections</a>
        <a href="#" class="footer-link">About Us</a>
        <a href="#" class="footer-link">Contact Us</a>
        <a href="#" class="footer-link">Blog</a>
        <a href="#" class="footer-link">Privacy Policy</a>
      </div>
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

<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top">
  <i class="fas fa-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script src="/about/about.js"></script>

</body>
</html>