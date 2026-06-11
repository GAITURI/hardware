/**
 * dashboard/cart-drawer.js
 * ─────────────────────────────────────────────────────────────
 * Manages the slide-in cart panel shown when "Add to Cart"
 * is clicked on any product card.
 *
 * Depends on:
 *   • ../api/cart_add.php     POST
 *   • ../api/cart_update.php  POST
 *   • ../api/cart_remove.php  POST
 *   • ../api/cart_get.php     GET
 *
 * The drawer HTML is injected once into <body> and then
 * toggled open / closed via the .cart-drawer--open class.
 * ─────────────────────────────────────────────────────────────
 */

/* ── 1. CONSTANTS ─────────────────────────────────────────── */
const CART_API = '../api';

/* ── 2. INJECT DRAWER HTML ───────────────────────────────── */
function injectCartDrawer() {
  if (document.getElementById('cartDrawer')) return; // already injected

  document.body.insertAdjacentHTML('beforeend', `
    <!-- CART DRAWER BACKDROP -->
    <div class="cart-backdrop" id="cartBackdrop"></div>

    <!-- CART DRAWER PANEL -->
    <aside class="cart-drawer" id="cartDrawer" aria-label="Your cart" role="complementary">

      <!-- Header -->
      <div class="cd-header">
        <div class="cd-header-left">
          <span class="cd-title">YOUR CART</span>
          <span class="cd-badge" id="cdBadge">0 ITEMS</span>
        </div>
        <button class="cd-close" id="cdClose" aria-label="Close cart">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Scrollable item list -->
      <div class="cd-body" id="cdBody">
        <div class="cd-empty" id="cdEmpty">
          <i class="fas fa-shopping-cart cd-empty-icon"></i>
          <p>Your cart is empty</p>
          <span>Add items to get started</span>
        </div>
        <div id="cdItems"></div>
      </div>

      <!-- Footer: subtotal + actions -->
      <div class="cd-footer" id="cdFooter" style="display:none;">
        <div class="cd-subtotal-row">
          <span class="cd-subtotal-label">Subtotal</span>
          <span class="cd-subtotal-value" id="cdSubtotal">KES 0</span>
        </div>
        <a href="../cart/index.php" class="cd-btn-checkout">
          CHECKOUT &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
        </a>
        <button class="cd-btn-continue" id="cdContinue">Continue Shopping</button>
      </div>

    </aside>
  `);

  /* inject styles */
  injectDrawerStyles();

  /* wire close controls */
  document.getElementById('cdClose').addEventListener('click',    closeCartDrawer);
  document.getElementById('cartBackdrop').addEventListener('click', closeCartDrawer);
  document.getElementById('cdContinue').addEventListener('click',  closeCartDrawer);

  /* wire nav-bar cart icon to open */
  document.querySelectorAll('.nav-icon-btn[aria-label="Cart"], a[aria-label="Cart"]')
    .forEach(el => el.addEventListener('click', e => {
      e.preventDefault(); openCartDrawer();
    }));
}

/* ── 3. OPEN / CLOSE ─────────────────────────────────────── */
function openCartDrawer() {
  document.getElementById('cartDrawer').classList.add('cart-drawer--open');
  document.getElementById('cartBackdrop').classList.add('cart-backdrop--visible');
  document.body.style.overflow = 'hidden';
}

function closeCartDrawer() {
  document.getElementById('cartDrawer').classList.remove('cart-drawer--open');
  document.getElementById('cartBackdrop').classList.remove('cart-backdrop--visible');
  document.body.style.overflow = '';
}

/* ── 4. RENDER ITEMS ─────────────────────────────────────── */
function renderDrawer(cart, subtotal, total_items) {
  const itemsEl   = document.getElementById('cdItems');
  const emptyEl   = document.getElementById('cdEmpty');
  const footerEl  = document.getElementById('cdFooter');
  const badgeEl   = document.getElementById('cdBadge');
  const subtotalEl= document.getElementById('cdSubtotal');

  /* update nav badge */
  const navBadge = document.querySelector('.cart-badge');
  if (navBadge) navBadge.textContent = total_items;

  badgeEl.textContent   = total_items + (total_items === 1 ? ' ITEM' : ' ITEMS');
  subtotalEl.textContent = 'KES ' + Number(subtotal).toLocaleString('en-KE', { maximumFractionDigits: 0 });

  if (!cart || cart.length === 0) {
    emptyEl.style.display  = 'flex';
    itemsEl.innerHTML      = '';
    footerEl.style.display = 'none';
    return;
  }

  emptyEl.style.display  = 'none';
  footerEl.style.display = 'block';

  itemsEl.innerHTML = cart.map(item => {
    const imgHtml = item.image_url
      ? `<img src="${item.image_url}" alt="${escHtml(item.name)}">`
      : `<i class="fas fa-box cd-item-icon"></i>`;

    return `
      <div class="cd-item" data-id="${item.id}">
        <!-- Product image -->
        <div class="cd-item-img">${imgHtml}</div>

        <!-- Description block -->
        <div class="cd-item-info">
          <div class="cd-item-name">${escHtml(item.name)}</div>
          <div class="cd-item-desc">${escHtml(item.description || '')}</div>
          <div class="cd-item-unit">KES ${Number(item.price).toLocaleString('en-KE', {maximumFractionDigits:0})} each</div>

          <div class="cd-item-bottom">
            <!-- Qty stepper -->
            <div class="cd-qty">
              <button class="cd-qty-btn" data-action="dec" data-id="${item.id}" data-qty="${item.qty}">
                <i class="fas fa-minus"></i>
              </button>
              <span class="cd-qty-val">${item.qty}</span>
              <button class="cd-qty-btn" data-action="inc" data-id="${item.id}" data-qty="${item.qty}">
                <i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- Line total -->
            <span class="cd-item-total">
              KES ${Number(item.price * item.qty).toLocaleString('en-KE', {maximumFractionDigits:0})}
            </span>
            <!-- Remove -->
            <button class="cd-remove" data-id="${item.id}" title="Remove item">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>`;
  }).join('');

  /* delegate qty + remove clicks */
  itemsEl.querySelectorAll('.cd-qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id  = btn.dataset.id;
      const qty = parseInt(btn.dataset.qty);
      const next = btn.dataset.action === 'inc' ? qty + 1 : qty - 1;
      drawerUpdateQty(id, next);
    });
  });
  itemsEl.querySelectorAll('.cd-remove').forEach(btn => {
    btn.addEventListener('click', () => drawerRemoveItem(btn.dataset.id));
  });
}

/* ── 5. API CALLS ─────────────────────────────────────────── */

/** Add a product → open drawer */
async function addToCart(product) {
  try {
    const res  = await fetch(`${CART_API}/cart_add.php`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(product),
    });
    const data = await res.json();
    renderDrawer(data.cart, data.subtotal, data.total_items);
    openCartDrawer();
  } catch (err) {
    console.error('addToCart error', err);
  }
}

async function drawerUpdateQty(id, qty) {
  const res  = await fetch(`${CART_API}/cart_update.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ id, qty }),
  });
  const data = await res.json();
  renderDrawer(data.cart, data.subtotal, data.total_items);
}

async function drawerRemoveItem(id) {
  const res  = await fetch(`${CART_API}/cart_remove.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ id }),
  });
  const data = await res.json();
  renderDrawer(data.cart, data.subtotal, data.total_items);
}

async function loadCartFromSession() {
  try {
    const res  = await fetch(`${CART_API}/cart_get.php`);
    const data = await res.json();
    renderDrawer(data.cart, data.subtotal, data.total_items);
  } catch (err) { /* silent */ }
}

/* ── 6. UTILITY ──────────────────────────────────────────── */
function escHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

/* ── 7. INTERCEPT PRODUCT CARD CLICKS ────────────────────── */
/**
 * Called by the main app.js once products are rendered.
 * Reads product metadata from data-* attributes on the card.
 */
function bindAddToCartButtons() {
  document.addEventListener('click', e => {
    const btn = e.target.closest('.add-to-cart-btn');
    if (!btn) return;
    e.preventDefault();

    /* Read data attrs set by buildCard() */
    const card = btn.closest('.product-card');
    if (!card) return;

    const product = {
      id:          card.dataset.id,
      name:        card.dataset.name,
      price:       parseFloat(card.dataset.price),
      image_url:   card.dataset.image,
      description: card.dataset.description,
      qty:         1,
    };

    /* Visual feedback on the button */
    btn.innerHTML = '<i class="fas fa-check fa-xs"></i> Added';
    btn.style.background = '#16a34a';
    setTimeout(() => {
      btn.innerHTML = 'Add to Cart &nbsp;<i class="fas fa-cart-plus fa-xs"></i>';
      btn.style.background = '';
    }, 1200);

    addToCart(product);
  });
}

/* ── 8. STYLES ───────────────────────────────────────────── */
function injectDrawerStyles() {
  const style = document.createElement('style');
  style.textContent = `
    /* ── Backdrop ── */
    .cart-backdrop {
      position: fixed; inset: 0; z-index: 1099;
      background: rgba(15,23,42,0.55);
      backdrop-filter: blur(3px);
      opacity: 0; pointer-events: none;
      transition: opacity 0.35s ease;
    }
    .cart-backdrop--visible {
      opacity: 1; pointer-events: all;
    }

    /* ── Drawer panel ── */
    .cart-drawer {
      position: fixed; top: 0; right: 0;
      width: 420px; max-width: 100vw; height: 100vh;
      background: #fff;
      z-index: 1100;
      display: flex; flex-direction: column;
      transform: translateX(100%);
      transition: transform 0.38s cubic-bezier(0.4,0,0.2,1);
      box-shadow: -8px 0 40px rgba(0,0,0,0.12);
    }
    .cart-drawer--open { transform: translateX(0); }

    /* ── Header ── */
    .cd-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 24px; border-bottom: 1.5px solid #e8edf2;
      background: #0f172a; flex-shrink: 0;
    }
    .cd-header-left { display: flex; align-items: center; gap: 10px; }
    .cd-title {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 20px; font-weight: 800; letter-spacing: 1px;
      color: #fff; text-transform: uppercase;
    }
    .cd-badge {
      background: #d32f2f; color: #fff;
      font-size: 10px; font-weight: 700; letter-spacing: 1px;
      padding: 4px 10px; border-radius: 50px;
    }
    .cd-close {
      background: rgba(255,255,255,0.08);
      border: none; color: rgba(255,255,255,0.75);
      width: 34px; height: 34px; border-radius: 8px;
      font-size: 15px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
    }
    .cd-close:hover { background: #d32f2f; color: #fff; }

    /* ── Scrollable body ── */
    .cd-body {
      flex: 1; overflow-y: auto; padding: 16px 20px;
      scroll-behavior: smooth;
    }
    .cd-body::-webkit-scrollbar { width: 4px; }
    .cd-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

    /* ── Empty state ── */
    .cd-empty {
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      height: 260px; text-align: center; gap: 8px;
    }
    .cd-empty-icon { font-size: 44px; color: #cbd5e1; }
    .cd-empty p { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
    .cd-empty span { font-size: 13px; color: #64748b; }

    /* ── Single item card ── */
    .cd-item {
      display: flex; gap: 14px;
      padding: 16px 0; border-bottom: 1px solid #f1f5f9;
    }
    .cd-item:last-child { border-bottom: none; }

    .cd-item-img {
      width: 76px; height: 76px; flex-shrink: 0;
      border-radius: 10px; overflow: hidden;
      background: #f0f4f8;
      display: flex; align-items: center; justify-content: center;
    }
    .cd-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .cd-item-icon { font-size: 26px; color: rgba(0,0,0,0.2); }

    .cd-item-info { flex: 1; min-width: 0; }
    .cd-item-name {
      font-size: 13.5px; font-weight: 700; color: #1e293b;
      line-height: 1.35; margin-bottom: 4px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cd-item-desc {
      font-size: 11.5px; color: #64748b; line-height: 1.5; margin-bottom: 6px;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .cd-item-unit { font-size: 11px; color: #94a3b8; margin-bottom: 10px; }

    .cd-item-bottom {
      display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }

    /* Qty stepper */
    .cd-qty {
      display: flex; align-items: center;
      border: 1.5px solid #e2e8f0; border-radius: 7px; overflow: hidden;
    }
    .cd-qty-btn {
      width: 28px; height: 28px; border: none;
      background: #f8fafc; color: #1e293b; font-size: 9px;
      cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center;
    }
    .cd-qty-btn:hover { background: #d32f2f; color: #fff; }
    .cd-qty-val {
      min-width: 32px; text-align: center;
      font-size: 13px; font-weight: 700; color: #1e293b;
      border-left: 1.5px solid #e2e8f0;
      border-right: 1.5px solid #e2e8f0;
      padding: 0 4px; line-height: 28px;
    }

    .cd-item-total {
      font-size: 14px; font-weight: 800; color: #1e293b;
      margin-left: auto;
    }
    .cd-remove {
      background: transparent; border: none;
      color: #cbd5e1; font-size: 13px; cursor: pointer;
      transition: color 0.2s; padding: 4px;
    }
    .cd-remove:hover { color: #d32f2f; }

    /* ── Footer ── */
    .cd-footer {
      border-top: 1.5px solid #e8edf2;
      padding: 20px 24px; flex-shrink: 0;
      background: #fff;
    }
    .cd-subtotal-row {
      display: flex; justify-content: space-between;
      align-items: center; margin-bottom: 16px;
    }
    .cd-subtotal-label { font-size: 14px; color: #64748b; font-weight: 600; }
    .cd-subtotal-value { font-size: 22px; font-weight: 800; color: #1e293b; }
    .cd-btn-checkout {
      display: block; width: 100%;
      background: #d32f2f; color: #fff;
      border: none; border-radius: 50px;
      font-size: 12px; font-weight: 700; letter-spacing: 1.5px;
      text-transform: uppercase; padding: 15px;
      cursor: pointer; transition: all 0.25s;
      text-decoration: none; text-align: center; margin-bottom: 10px;
    }
    .cd-btn-checkout:hover { background: #b71c1c; color: #fff; transform: translateY(-1px); }
    .cd-btn-continue {
      display: block; width: 100%;
      background: transparent; color: #1e293b;
      border: 1.5px solid #e2e8f0; border-radius: 50px;
      font-size: 12px; font-weight: 600; padding: 13px;
      cursor: pointer; transition: all 0.25s; text-align: center;
    }
    .cd-btn-continue:hover { border-color: #1e293b; }

    @media (max-width: 480px) {
      .cart-drawer { width: 100vw; }
    }
  `;
  document.head.appendChild(style);
}

/* ── 9. INIT ─────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  injectCartDrawer();
  bindAddToCartButtons();
  loadCartFromSession(); // restore badge / count on page load
});