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
$stmt = $pdo->prepare("SELECT * FROM hardware_items WHERE id = ?");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> — Mambo Hardware</title>
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
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .sticky-nav { position: sticky; top: 0; z-index: 1000; background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .tab-active { border-bottom: 2px solid #ef4444; color: #ef4444; font-weight: 700; }
    </style>
</head>
<body>

    <nav class="sticky-nav py-3 px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="bg-red-500 text-white font-extrabold px-3 py-1 rounded text-sm tracking-wider">Mambo</div>
                <div class="text-xs text-gray-500 font-bold uppercase tracking-widest hidden sm:block">Hardware</div>
            </div>
            
            <div class="flex space-x-8 font-medium text-sm text-gray-700">
                <a href="dashboard/dashboard.php" class="hover:text-mamboRed transition-colors">Home</a>
                <a href="#about" class="hover:text-mamboRed transition-colors">About</a>
                <a href="#shop" class="hover:text-mamboRed transition-colors">Shop</a>
                <a href="#blog" class="hover:text-mamboRed transition-colors">Blog</a>
                <a href="#contact" class="hover:text-mamboRed transition-colors">Contact</a>
            </div>

            <div class="flex items-center space-x-6 text-gray-700">
                <button class="hover:text-mamboRed transition-colors"><i class="fas fa-search"></i></button>
                <div class="relative cursor-pointer hover:text-mamboRed transition-colors">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span class="absolute -top-2 -right-2 bg-mamboRed text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold">0</span>
                </div>
                <button class="border border-gray-200 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center space-x-1 hover:bg-gray-50">
                    <i class="far fa-user-circle text-sm text-gray-400"></i>
                    <span>Admin</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <nav class="text-xs text-gray-400 font-medium mb-6">
            <a href="dashboard/dashboard.php" class="hover:underline">Home</a> <span class="mx-2">/</span>
            <a href="#shop" class="hover:underline">Shop</a> <span class="mx-2">/</span>
            <span class="text-gray-600"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-16">
            
        <div class="lg:col-span-6 space-y-4">
    <div class="relative bg-gray-50 border border-gray-100 rounded-lg p-6 flex justify-center items-center overflow-hidden">
        <span class="absolute top-4 left-4 bg-mamboRed text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded shadow-sm z-10">On Sale</span>
        
        <img src="../dashboard/<?php echo htmlspecialchars($product['image_url']); ?>" 
             alt="Product Primary Image" 
             class="w-full aspect-square object-contain max-h-[400px] transition-transform duration-300 hover:scale-105">
    </div>

    <div class="flex space-x-3">
        <div class="w-20 h-20 border-2 border-mamboRed rounded p-1 cursor-pointer bg-white overflow-hidden">
            <img src="../dashboard/<?php echo htmlspecialchars($product['image_url']); ?>" 
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
                    
                    <button type="button" onclick="executeAddToCart(<?php echo $product['id']; ?>, <?php echo $product['price']; ?>)" class="bg-mamboRed hover:bg-red-600 text-white font-bold text-xs uppercase px-8 py-3.5 rounded-full flex items-center space-x-2 transition-all shadow-sm">
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
                <button class="tab-active pb-2">DESCRIPTION</button>
                <button class="text-gray-400 hover:text-gray-600 pb-2">SPECIFICATION</button>
                <button class="text-gray-400 hover:text-gray-600 pb-2">REVIEWS</button>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-extrabold text-gray-900 uppercase">Stay Charged Anywhere With Premium Components</h3>
                <p class="text-gray-600 text-sm leading-relaxed max-w-4xl">
                    Our performance-tested systems are selected specifically to withstand the dynamic demands of daily operations. Engineered with safety matrices that neutralize power overloads, short-circuit loops, and thermal spikes.
                </p>
            </div>
        </section>

        <section class="border-t border-gray-100 pt-12">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-1 h-6 bg-mamboRed"></div>
                <h2 class="text-md font-black tracking-wider uppercase text-gray-900">You May Also Like</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (empty($related_products)): ?>
                    <?php for($i = 1; $i <= 4; $i++): ?>
                    <div class="bg-white border border-gray-100 rounded-lg p-4 space-y-3 relative group shadow-sm">
                        <span class="absolute top-3 left-3 bg-mamboRed text-white text-[9px] font-bold uppercase px-1.5 py-0.5 rounded">On Sale</span>
                        <div class="h-44 bg-gray-50 rounded flex items-center justify-center p-4">
                            <img src="dashboard/images/elec1.jpg" alt="Related Item preview" class="h-full object-contain mix-blend-multiply">
                        </div>
                        <div class="space-y-1">
                            <div class="flex text-amber-400 text-[10px]"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <h4 class="font-bold text-xs text-gray-800 truncate uppercase">Alternative Model Core Wire</h4>
                            <div class="flex items-baseline space-x-2 text-xs">
                                <span class="font-bold text-mamboRed">KES 2,500</span>
                                <span class="text-[10px] text-gray-400 line-through">KES 3,000</span>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                <?php else: ?>
                    <?php foreach($related_products as $rp): ?>
                    <div class="bg-white border border-gray-100 rounded-lg p-4 space-y-3 relative shadow-sm cursor-pointer" onclick="window.location.href='product.php?id=<?php echo $rp['id']; ?>'">
                        <div class="h-44 bg-gray-50 rounded flex items-center justify-center p-4">
                            <img src="<?php echo htmlspecialchars($rp['image_url']); ?>" alt="Related Product" class="h-full object-contain">
                        </div>
                        <div class="space-y-1">
                            <div class="flex text-amber-400 text-[10px]"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <h4 class="font-bold text-xs text-gray-800 truncate uppercase"><?php echo htmlspecialchars($rp['name']); ?></h4>
                            <div class="text-xs font-bold text-mamboRed">KES <?php echo number_format($rp['price']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <footer class="bg-gray-900 text-gray-400 text-xs py-12 mt-20 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <span class="text-white font-extrabold text-sm tracking-widest uppercase">Mambo Hardware</span>
                <p class="leading-relaxed">Your one-stop terminal for industrial-grade construction materials, tools, electrical suites, and premium interior accessories.</p>
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
                    <li>Support: dispatch@mambohardware.co.ke</li>
                </ul>
            </div>
            <div>
                <h5 class="text-white font-bold mb-3 uppercase tracking-wider">Compliance Matrix</h5>
                <p class="leading-relaxed">Protected under standard encryption layers. Authorized dealer of certified hardware modules across East Africa.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-gray-800 text-center text-[11px]">
            &copy; 2026 Mambo Hardware / Oasis Technologies. All rights reserved.
        </div>
    </footer>

    <script>
        function adjustQty(amount) {
            const qtyInput = document.getElementById('quantity-widget');
            let currentQty = parseInt(qtyInput.value) || 1;
            currentQty += amount;
            if (currentQty < 1) currentQty = 1;
            qtyInput.value = currentQty;
        }

        async function executeAddToCart(productId, productPrice) {
            const quantity = parseInt(document.getElementById('quantity-widget').value) || 1;
            
            try {
                const response = await fetch('../api/cart_add.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        product_id:parseInt(productId),
                        price: parseFloat(productPrice) || 0,
                        quantity: quantity
                    })
                });
                
                const outcome = await response.json();
                if (outcome.status === 'success') {
                    // alert('Items registered successfully to your cart session.');
                    window.location.href = 'cart.php';
                    // If you have a global update counter implementation inside cart-drawer.js, invoke it here
                    const cartBadge = document.querySelector('.relative.cursor-pointer span');
                    if (cartBadge) {
                             cartBadge.textContent = outcome.new_total_count;
                    }
                 } else {
                    console.error('API Error Response:', outcome.message);
                }
            } catch (err) {
                console.error('XHR execution error:', err);
            }
        }
    </script>
</body>
</html>