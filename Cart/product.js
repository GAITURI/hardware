/**
 * 1. Product Quantity Modifier Handler
 * 
 */
function adjustQty(amount) {
    const qtyInput = document.getElementById('quantity-widget');
    if (!qtyInput) return;
    
    let currentQty = parseInt(qtyInput.value) || 1;
    currentQty += amount;
    
    if (currentQty < 1) {
        currentQty = 1;
    }
    qtyInput.value = currentQty;
}

/**
 * 2. Tab Menu Layout Panel Switching Matrix
 */
function switchTab(event, panelId) {
    // Hide all panel layers
    const panels = document.querySelectorAll('.tab-panel');
    panels.forEach(panel => panel.classList.add('hidden'));

    // Remove active styles across all header selector targets
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => {
        btn.classList.remove('border-b-2', 'border-mamboRed', 'text-gray-900');
        btn.classList.add('text-gray-400');
    });

    // Make designated pane target visible
    const targetPanel = document.getElementById(panelId);
    if (targetPanel) {
        targetPanel.classList.remove('hidden');
    }

    // Assign highlight styles onto the selected button interaction node
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('border-b-2', 'border-mamboRed', 'text-gray-900');
        event.currentTarget.classList.remove('text-gray-400');
    }
}

/**
 * 3. Modern Dynamic Component Card Generator (Tailwind Design)
 */
function buildCard(p) {
    console.log("Processing item into interface blueprint payload:", p);

    // Filter sanitization variables
    const safeName = String(p.name || '').replace(/"/g, '&quot;');
    const safeDesc = String(p.description || '').replace(/"/g, '&quot;');
    const safeImg  = String(p.image_url || '').replace(/"/g, '&quot;'); 
    const imgPath  = '../' + p.image_url.replace(/^\/+/, '');
    const materialTag = p.material ? p.material : 'Hardware';
    
    // Target Route Parameter Endpoint Integration 
    const productUrl = `product.php?id=${p.id || p.item_id}`;

    return `
        <div class="bg-white border border-gray-100 rounded-lg p-4 space-y-3 relative shadow-sm cursor-pointer hover:shadow-md transition-shadow group" 
             onclick="window.location.href='${productUrl}'"
             data-id="${p.id || p.item_id}"
             data-name="${safeName}"
             data-price="${p.price}"
             data-image="${safeImg}"
             data-description="${safeDesc}">
            
            ${p.onSale || p.is_sale ? '<span class="absolute top-3 left-3 bg-mamboRed text-white text-[9px] font-bold uppercase px-1.5 py-0.5 rounded z-10">On Sale</span>' : ''}
            
            <div class="h-44 bg-gray-50 rounded flex items-center justify-center p-4 overflow-hidden">
                <img src="${imgPath}" 
                     alt="${safeName}" 
                     class="h-full object-contain transition-transform duration-300 group-hover:scale-105"
                     onerror="this.onerror=null; this.src='../images/logoimg.jpg';">
            </div>
            
            <div class="space-y-1">
                <div class="flex text-amber-400 text-[10px]">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h4 class="font-bold text-xs text-gray-800 truncate uppercase">
                    ${p.name}
                </h4>
                <div class="flex items-baseline space-x-2 text-xs">
                    <span class="font-bold text-mamboRed">KES ${Number(p.price).toLocaleString('en-KE')}</span>
                    <span class="text-[9px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded ml-auto">
                        ${materialTag}
                    </span>
                </div>
            </div>
        </div>
    `;
}

/**
 * 4. Load & Initialize Related Product Feeds
 */
async function initProductDashboard() {
    try {
        const response = await fetch('../api/get_products.php');
        if (!response.ok) throw new Error('API server pipeline returned execution status mismatch');
        const data = await response.json();

        // Separate matching variants based on filtering attributes
        const latest   = data.filter(p => p.is_latest === true || p.is_latest == 1);
        const hotDeals = data.filter(p => p.onSale === true || p.is_sale == 1);

        const productsGrid = document.getElementById('productsGrid');
        const hotdealsGrid = document.getElementById('hotdealsGrid');

        // Render targets to grids if they exist in the current layout
        if (productsGrid) {
            productsGrid.className = "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6";
            productsGrid.innerHTML = latest.length
                ? latest.map(buildCard).join('')
                : '<p class="text-center text-muted py-4 text-xs col-span-full">No products found.</p>';
        }
       
        if (hotdealsGrid) {
            hotdealsGrid.className = "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6";
            hotdealsGrid.innerHTML = hotDeals.length
                ? hotDeals.map(buildCard).join('')
                : '<p class="text-center text-muted py-4 text-xs col-span-full">No hot deals right now.</p>';
        }

        // Initialize background cart hooks automatically
        if (typeof bindAddToCartButtons === 'function'){
            bindAddToCartButtons();
        }
    } catch (err){
        console.error('Failure intercepted initializing product data lists:', err);
    }
}

// Attach listeners to trigger dashboard on page load
document.addEventListener('DOMContentLoaded', () => {
    initProductDashboard();
    
    // Connect any legacy fallback tab toggles to avoid unexpected dependency crashes
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            const targetPane = document.getElementById('tab-' + btn.dataset.tab);
            if(targetPane) targetPane.classList.add('active');
        });
    });
});

/**
 * 5. Direct Execution Framework Callback Wrapper
 */
async function executeAddToCart(itemId, itemPrice) {
    const qtyInput = document.getElementById('quantity-widget');
    const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
    
    console.log("Dispatched direct action execution update to active cart module:", { itemId, itemPrice, quantity });
    
    // Check if our cart drawer/helper script is loaded globally
    if (typeof updateCartBackend === 'function') {
        try {
            // Await the backend database operations
            const outcome = await updateCartBackend(itemId, quantity, itemPrice);
            
            // Check the structured JSON response status from the server
            if (outcome && (outcome.status === 'success' || outcome.status ==='ok')) {
                // Update the navigation utility cart counts if elements exist
                const cartBadge = document.getElementById('cartBadge') || document.querySelector('.relative.cursor-pointer span');
                if (cartBadge) {
                    cartBadge.textContent = outcome.new_total_count || outcome.count || quantity;
                }
                
                // Smoothly redirect directly to the checkout page layout
                window.location.href = 'cart.php';
            } else {
                console.error('API Error Response:', outcome ? outcome.message : 'Unknown error');
                // Fallback redirect if it saved but returned weird formatting
                window.location.href = 'cart.php';
            }
        } catch (error) {
            console.error("Cart synchronization operation failed:", error);
            alert("We ran into an issue updating your cart. Please try again.");
        }
    } else {
        // Fallback approach if cart scripts fail to load in window context
        console.warn("updateCartBackend interface reference missing. Performing manual fallback redirect.");
        window.location.href = 'cart.php';
    }
}