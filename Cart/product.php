<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../api/cart_helper.php'; // <-- Call it here!
// 1. Get the ID from the URL (not name)
$product_id = isset($_GET['id']) ? trim($_GET['id']) : '';

// 2. Validate that it's a numeric ID
if (empty($product_id) || !is_numeric($product_id)) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

// 3. Query the database using the correct variable
$stmt = $pdo->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// 4. If product doesn't exist, redirect
if (!$product) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

// Now $product is defined and populated
$p_name  = htmlspecialchars($product['name']);
$p_price = floatval($product['price']);
// 5. Query Related Products (Same material, exclude current item, limit to 4)
$related_products = [];
if (!empty($product['material'])) {
    $related_stmt = $pdo->prepare("SELECT * FROM items WHERE material = ? AND item_id != ? LIMIT 4");
    $related_stmt->execute([$product['material'], $product_id]);
    $related_products = $related_stmt->fetchAll();
}

// Fallback: If less than 3 matching items found, pull general fallback items to maintain layout integrity
if (count($related_products) < 3) {
    // Collect already matched IDs so we don't duplicate them
    $exclude_ids = [$product_id];
    foreach ($related_products as $rp) {
        $exclude_ids[] = $rp['item_id'];
    }
    
    // Create placeholders dynamically for the NOT IN clause (e.g., ?, ?)
    $placeholders = implode(',', array_fill(0, count($exclude_ids), '?'));
    
    // Calculate how many extra products we need to hit the max limit of 4
    $needed_count = 4 - count($related_products);
    
    $fallback_stmt = $pdo->prepare("SELECT * FROM items WHERE item_id NOT IN ($placeholders) ORDER BY RAND() LIMIT $needed_count");
    $fallback_stmt->execute($exclude_ids);
    $fallback_products = $fallback_stmt->fetchAll();
    
    // Merge the fallback rows into your related products list
    $related_products = array_merge($related_products, $fallback_products);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> — Mambo Outdoor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    
    <link rel="stylesheet" href="/cart/product.css"/>
</head>
<body>

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
          <div class="top-bar-value">info@mamboOutdoor.co.ke</div>
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
        <img src="../images/logoimg.jpg" alt="Mambo Outdoor Logo" class="brand-logo-img">
        </div>
        <div>
          <div class="brand-text-top">Mambo</div>
          <div class="brand-text-bot">Outdoor</div>
        </div>
      </div>
    </a>
    
    <div class="d-flex align-items-center gap-2 order-lg-last">
    <div class="nav-utilities d-flex align-items-center gap-2">
      <button class="nav-icon-btn" id="searchBtn" aria-label="Search">
        <i class="fas fa-search"></i>
      </button>
      <!-- Cart button — clicked to open drawer (handled by cart-drawer.js) -->
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
    

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-16">
            
        <div class="lg:col-span-6 space-y-4">
    <div class="relative bg-gray-50 border border-gray-100 rounded-lg p-6 flex justify-center items-center overflow-hidden">
        <span class="absolute top-4 left-4 bg-mamboRed text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded shadow-sm z-10">On Sale</span>
        
        <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="img should be here" class="w-full aspect-square object-contain max-h-[400px] transition-transform duration-300 hover:scale-105">
    </div>

    <div class="flex space-x-3">
        <div class="w-20 h-20 border-2 border-mamboRed rounded p-1 cursor-pointer bg-white overflow-hidden">
            <img src="../<?php echo htmlspecialchars($product['image_url']); ?>"
                 alt="Thumbnail Preview"
                 class="w-full h-full object-cover">
        </div>
    </div>
</div>
            <div class="lg:col-span-6 space-y-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 uppercase tracking-tight">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>

                <div class="flex items-center space-x-1 text-amber-400 text-xs">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>

                <div class="flex items-baseline space-x-4">
                    <span class="text-2xl font-bold text-mamboRed">KES <?php echo number_format($product['price']); ?></span>
                    <?php if (!empty($product['oldPrice'])): ?>
                        <span class="text-sm text-gray-400 line-through">KES <?php echo number_format($product['oldPrice']); ?></span>
                    <?php endif; ?>
                </div>

                <p class="text-gray-600 text-sm leading-relaxed border-b border-gray-100 pb-6">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>

                <div class="flex items-center space-x-4 py-2">
                    <div class="flex items-center border border-gray-200 rounded-full bg-gray-50 px-2 py-1">
                        <button type="button" onclick="adjustQty(-1)" class="w-8 h-8 flex items-center justify-center font-bold text-gray-500 hover:text-mamboRed text-sm">-</button>
                        <input type="text" id="quantity-widget" value="1" readonly class="w-10 text-center bg-transparent text-sm font-bold text-gray-800 focus:outline-none">
                        <button type="button" onclick="adjustQty(1)" class="w-8 h-8 flex items-center justify-center font-bold text-gray-500 hover:text-mamboRed text-sm">+</button>
                    </div>
                    
                    <button type="button" onclick="executeAddToCart(<?php echo $product['item_id']; ?>, <?php echo $product['price']; ?>)" class="bg-mamboRed hover:bg-red-600 text-white font-bold text-xs uppercase px-8 py-3.5 rounded-full flex items-center space-x-2 transition-all shadow-sm">
                        <span>Add To Cart</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-y-2 py-4 border-t border-b border-gray-100 text-xs">
                    <span class="text-gray-400 font-medium">SKU:</span>
                    <span class="col-span-2 text-gray-700 font-semibold"><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></span>

                    <span class="text-gray-400 font-medium">Brands:</span>
                    <span class="col-span-2 text-gray-700 font-semibold"><?php echo htmlspecialchars($product['brand'] ?? 'General'); ?></span>

                    <span class="text-gray-400 font-medium">In Stock:</span>
                    <span class="col-span-2 text-green-600 font-bold"><?php echo htmlspecialchars($product['stock_status'] ?? 'Available'); ?></span>

                    <span class="text-gray-400 font-medium">Share:</span>
                    <span class="col-span-2 text-gray-500 flex space-x-3 cursor-pointer"><i class="fas fa-share-alt hover:text-mamboRed"></i></span>
                </div>

                <div class="space-y-3 pt-2 text-xs">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-gray-400 text-sm"></i>
                        <span class="text-gray-600">Delivery in Thika Town: <strong class="text-green-600">Free</strong></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-globe-africa text-gray-400 text-sm"></i>
                        <span class="text-gray-600">Outside Thika: <strong class="text-gray-800">KES 300 delivery fee</strong></span>
                    </div>
                </div>

                <div class="border border-gray-100 rounded-lg p-4 bg-gray-50/50 space-y-3">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">Safe Checkout Via</span>
                    <div class="flex space-x-2 text-[10px] font-bold text-gray-600">
                        <span class="border border-gray-200 px-2 py-1 rounded bg-white flex items-center"><i class="fas fa-mobile-alt text-green-500 mr-1.5"></i> M-Pesa</span>
                        <span class="border border-gray-200 px-2 py-1 rounded bg-white flex items-center"><i class="far fa-credit-card text-blue-500 mr-1.5"></i> Visa / Mastercard</span>
                        <span class="border border-gray-200 px-2 py-1 rounded bg-white flex items-center"><i class="fas fa-lock text-gray-400 mr-1.5"></i> SSL Encrypted</span>
                    </div>
                </div>

                <div class="border border-amber-100 rounded-lg p-3 bg-amber-50/40 flex items-start space-x-3 text-xs text-gray-500">
                    <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                    <span><strong>No returns on this item:</strong> All sales are final. Contact us if you received a defective unit.</span>
                </div>
            </div>
        </div>

        <section class="border-t border-gray-100 pt-10 mb-16">
            <div class="flex space-x-8 border-b border-gray-200 text-xs font-bold tracking-wider mb-6 pb-2">
                <button onClick="switchTab(event, 'tab-description')" class="tab-button border-b-2 border-mamboRed text-gray-900 pb-2 focus:outline-none">DESCRIPTION</button>
                <button onClick="switchTab(event, 'tab-specification')" class="tab-button border-b-2 border-mamboRed text-gray-600 pb-2 focus:outline-none">Specifications</button>
                <button onClick="switchTab(event, 'tab-reviews')" class="tab-button border-b-2 border-mamboRed text-gray-600 pb-2 focus:outline-none">Reviews</button>

            </div>
            <div id="product-tab-content">
        
        <div id="tab-description" class="tab-panel space-y-4">
            <p class="text-gray-600 text-sm leading-relaxed max-w-4xl">
                <?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?>
            </p>
        </div>

        <div id="tab-specification" class="tab-panel space-y-4 hidden">
            <h3 class="text-lg font-extrabold text-gray-900 uppercase">Product Specifications</h3>
            <div class="max-w-xl border border-gray-100 rounded-lg overflow-hidden text-sm">
                <div class="grid grid-cols-2 bg-gray-50 px-4 py-2.5 border-b border-gray-100">
                    <span class="text-gray-400 font-medium">Color</span>
                    <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($product['color'] ?? 'N/A'); ?></span>
                </div>
                <div class="grid grid-cols-2 px-4 py-2.5 border-b border-gray-100">
                    <span class="text-gray-400 font-medium">Material</span>
                    <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($product['material'] ?? 'N/A'); ?></span>
                </div>
                <div class="grid grid-cols-2 bg-gray-50 px-4 py-2.5 border-b border-gray-100">
                    <span class="text-gray-400 font-medium">Seating Capacity</span>
                    <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($product['seating_capacity'] ?? 'N/A'); ?> Persons</span>
                </div>
                <div class="grid grid-cols-2 px-4 py-2.5">
                    <span class="text-gray-400 font-medium">Weight</span>
                    <span class="text-gray-800 font-semibold"><?php echo !empty($product['weight_kg']) ? htmlspecialchars($product['weight_kg']) . ' kg' : 'N/A'; ?></span>
                </div>
            </div>
        </div>

        <div id="tab-reviews" class="tab-panel space-y-4 hidden">
            <h3 class="text-lg font-extrabold text-gray-900 uppercase">Customer Reviews</h3>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <div class="flex text-amber-400 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <span>No reviews posted yet for this product.</span>
            </div>
        </div>

    </div>
        </section>
        <section class="border-t border-gray-100 pt-12">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-1 h-6 bg-mamboRed"></div>
                <h2 class="text-md font-black tracking-wider uppercase text-gray-900">You May Also Like</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach($related_products as $rp): 
                    // Sanitize text parameters to ensure clean custom dataset injection loops
                    $safeName = htmlspecialchars($rp['name'] ?? '');
                    $safeDesc = htmlspecialchars($rp['description'] ?? '');
                    $safeImg  = htmlspecialchars($rp['image_url'] ?? '');
                    $productUrl = "product.php?id=" . $rp['item_id'];
                    $materialTag = !empty($rp['material']) ? htmlspecialchars($rp['material']) : 'Outdoor';
                ?>
                    <div class="bg-white border border-gray-100 rounded-lg p-4 space-y-3 relative shadow-sm cursor-pointer hover:shadow-md transition-shadow group" 
                         onclick="window.location.href='<?php echo $productUrl; ?>'"
                         data-id="<?php echo $rp['item_id']; ?>"
                         data-name="<?php echo $safeName; ?>"
                         data-price="<?php echo $rp['price']; ?>"
                         data-image="<?php echo $safeImg; ?>"
                         data-description="<?php echo $safeDesc; ?>">
                        
                        <span class="absolute top-3 left-3 bg-mamboRed text-white text-[9px] font-bold uppercase px-1.5 py-0.5 rounded z-10">On Sale</span>
                        
                        <div class="h-44 bg-gray-50 rounded flex items-center justify-center p-4 overflow-hidden">
                            <img src="../<?php echo $safeImg; ?>" 
                                 alt="<?php echo $safeName; ?>" 
                                 class="h-full object-contain transition-transform duration-300 group-hover:scale-105"
                                 onerror="this.onerror=null; this.src='../dashboard/images/logoimg.jpg';">
                        </div>
                        
                        <div class="space-y-1">
                            <div class="flex text-amber-400 text-[10px]">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <h4 class="font-bold text-xs text-gray-800 truncate uppercase">
                                <?php echo $safeName; ?>
                            </h4>
                            <div class="flex items-baseline space-x-2 text-xs">
                                <span class="font-bold text-mamboRed">KES <?php echo number_format($rp['price']); ?></span>
                                <span class="text-[9px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded ml-auto">
                                    <?php echo $materialTag; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900 text-gray-400 text-xs py-12 mt-20 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <span class="text-white font-extrabold text-sm tracking-widest uppercase">Mambo Outdoor</span>
                <p class="leading-relaxed">Your one-stop terminal for quality and luxury products</p>
            </div>
            <div>
                <h5 class="text-white font-bold mb-3 uppercase tracking-wider">Quick Directives</h5>
                <ul class="space-y-2">
                    <li><a href="dashboard/dashboard.php" class="hover:text-white transition-colors">Return to Dashboard</a></li>
                    <li><a href="#shop" class="hover:text-white transition-colors">Catalog Collections</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-bold mb-3 uppercase tracking-wider">Fulfillment Coordinates</h5>
                <ul class="space-y-2">
                    <li>Ruiru,Kamulu Kenya</li>
                    <li>Support: dispatch@mamboOutdoor.co.ke</li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-bold mb-3 uppercase tracking-wider">Compliance Matrix</h5>
                <p class="leading-relaxed">Authorized Outdoor Dealers.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-gray-800 text-center text-[11px]">
            &copy; 2026 Mambo Outdoor . All rights reserved.
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/cart/cart.js"></script> <script src="/cart/product.js?v=2"></script>

</body>
</html>