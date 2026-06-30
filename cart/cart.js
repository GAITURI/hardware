/* ── CONFIG ── */

/* ── SCROLL TO TOP ── */
window.addEventListener('scroll', () => {
  document.getElementById('scrollTop')?.classList.toggle('show', window.scrollY > 300);
});

/* ── HELPERS ── */
function fmt(n) {
  return 'KES ' + Number(n).toLocaleString('en-KE', { maximumFractionDigits: 0 });
}

/* ── ADD TO CART BACKEND INTERFACE ── */
/**
 * Invoked by product listing or details views via executeAddToCart()
 * to register new hardware items persistently to the database session.
 */
async function updateCartBackend(itemId, quantity, itemPrice) {
  const payload = {
    product_id: parseInt(itemId),
    quantity: parseInt(quantity),
    price: parseFloat(itemPrice)
  };

  const response = await fetch(`/api/cart_add.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
  });

  if (!response.ok) {
    throw new Error(`Server returned network status error: ${response.status}`);
  }

  return await response.json();
}

/* ── UPDATE QTY (ALIGNED WITH SCHEMA) ── */
/**
 * Modifies item quantities inside your cart table template structure 
 * dynamically via inline stepper components.
 */
async function updateQty(id, newQty) {
  const res  = await fetch(`/api/cart_update.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ product_id,id, qty: newQty }),
  });
  const data = await res.json();
  
  // Accommodates both standard schema layout properties
  const itemsList = data.items || data.cart || [];

  if (newQty <= 0) {
    removeRow(id);
    if (itemsList.length === 0) showEmpty();
  } else {
    /* update qty badge text */
    const qtyEl = document.getElementById('qty-' + id);
    if (qtyEl) qtyEl.textContent = newQty;

    /* update stepper button handlers inline dynamically */
    const row = document.querySelector(`.cart-row[data-id="${id}"]`);
    if (row) {
      const [dec, inc] = row.querySelectorAll('.qty-stepper button');
      if (dec) dec.setAttribute('onclick', `updateQty('${id}', ${newQty - 1})`);
      if (inc) inc.setAttribute('onclick', `updateQty('${id}', ${newQty + 1})`);
    }

    /* update line total in table row container layout */
    const item = itemsList.find(i => String(i.id) === String(id));
    if (item) {
      const currentQty = item.qty || item.quantity || newQty;
      const lineEl = document.getElementById('line-' + id);
      if (lineEl) lineEl.textContent = fmt(item.price * currentQty);

      /* update persistent side summary columns panel */
      const osQty = document.getElementById('os-qty-' + id);
      const osVal = document.getElementById('os-val-' + id);
      if (osQty) osQty.textContent = item.qty;
      if (osVal) osVal.textContent = fmt(item.price * item.qty);
    }
  }
  refreshTotals(data);
}

/* ── REMOVE ITEM (ALIGNED WITH SCHEMA) ── */
/**
 * Drops line items completely from the user session and drops database links
 */
async function removeItem(id) {
  const res  = await fetch(`/api/cart_remove.php`, {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ product_id:id }),
  });
  const data = await res.json();
  const itemsList = data.items || data.cart || [];
  
  removeRow(id);
  refreshTotals(data);
  if (itemsList.length === 0) showEmpty();
}

/* ── DOM HELPERS ── */
function removeRow(id) {
  document.querySelector(`.cart-row[data-id="${id}"]`)?.remove();
  document.getElementById('os-' + id)?.remove();
}

function refreshTotals(data) {
  const itemsList = data.items || data.cart || [];
  const subtotal = data.subtotal ?? itemsList.reduce((s, i) => s + (i.price * i.qty), 0);
  const count    = data.total_items || data.count || itemsList.reduce((s, i) => s + i.qty, 0);

  const osTotal = document.getElementById('osTotal');
  if (osTotal) osTotal.textContent = fmt(subtotal);

  const badge = document.getElementById('cartBadge') || document.querySelector('.relative.cursor-pointer span');
  if (badge) badge.textContent = count;
}

function showEmpty() {
  document.querySelector('.row.g-4')?.remove();
  const container = document.querySelector('main .container');
  if (container) {
    container.innerHTML = `
      <div class="empty-state" style="text-align: center; padding: 60px 20px;">
        <div class="empty-icon" style="font-size: 48px; color: #ccc; margin-bottom: 20px;">
          <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="empty-title" style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Your cart is empty</div>
        <p class="empty-sub" style="color: #666; margin-bottom: 30px;">Browse our catalogue and add something you need.</p>
        <a href="../dashboard/index.php#shop" class="btn-shop" style="display: inline-block; background: #c61a09; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 30px; font-weight: 500;">
          Start Shopping &nbsp;<i class="fas fa-arrow-right" style="font-size:11px;"></i>
        </a>
      </div>`;
  }
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