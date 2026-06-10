/* ═══════════════════════════════════════════════════════════
   OASIS TECHNOLOGIES — Main JavaScript
   Handles: Product rendering, tabs, hero carousel,
            scroll-to-top, search modal, active nav, cart
═══════════════════════════════════════════════════════════ */

/* ════════════════════════════════════════
   PRODUCT DATA
════════════════════════════════════════ */

/* ════════════════════════════════════════
   HELPERS
════════════════════════════════════════ */

/**
 * Render 5 star icons coloured by rating
 * @param {number} n - number of filled stars (1–5)
 * @returns {string} HTML string
 */
function renderStars(n) {
  return Array.from({ length: 5 }, (_, i) =>
    `<i class="fas fa-star" style="color:${i < n ? '#f59e0b' : '#e2e8f0'};"></i>`
  ).join('');
}

/**
 * Build a product card HTML string
 * @param {object} p - product object
 * @returns {string} HTML string for one grid column + card
 */

function buildCard(p) {
  // Use a fallback color if none exists in database
  const color = p.color || '#e2e8f0'; 
  
  return `
    <div class="col">
      <div class="product-card">
        ${p.onSale ? '<span class="badge-sale">On Sale</span>' : ''}
        <div class="product-img-wrap" style="background:linear-gradient(135deg, ${color}18, ${color}08);">
            <img src="${p.image_url}" alt="${p.name}" style="width:100%; border-radius:16px;">
        </div>
        <div class="product-body">
          <div class="star-row">${renderStars(p.rating || 5)}</div>
          <div class="product-name">${p.name}</div>
          <div class="price-row">
            <span class="price-now">KES ${p.price}</span>
            <span class="price-old">${p.oldPrice ? 'KES ' + p.oldPrice : ''}</span>
          </div>
          <button class="btn-hero mt-3 w-100 add-to-cart-btn" style="border-radius:8px; font-size:11px; padding:10px;">
            Add to Cart &nbsp;<i class="fas fa-cart-plus fa-xs"></i>
          </button>
        </div>
      </div>
    </div>`;
}
/* ════════════════════════════════════════
   RENDER PRODUCT GRIDS
════════════════════════════════════════ */


/* ════════════════════════════════════════
   PRODUCT TAB SWITCHING
════════════════════════════════════════ */
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    // Deactivate all buttons and panes
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    // Activate clicked button + matching pane
    btn.classList.add('active');
    document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
  });
});

/* ════════════════════════════════════════
   HERO CAROUSEL
════════════════════════════════════════ */
const slides = [
  {
    tag: 'New Stock',
    title: 'Interior\nProducts',
    sub: 'Sale Sale',
    btn: 'Shop now',
    img: 'images/bowl1.jpg'
  },
  {
    tag: 'Latest Arrival',
    title: 'Shower\nComponents',
    sub: 'Sale Sale',
    btn: 'View More',
  },
  {
    tag: 'Premium Electrical',
    title: 'Electrical\nProducts',
    sub: 'Sale Sale',
    btn: 'View More',
  },
  {
    tag: 'Tools & Accessories',
    title: 'POWER\nTools',
    sub: 'Sale Sale',
    btn: 'Shop Now',
    img:'images/power.jpg'
  },
];

let currentSlide = 0;
const heroContent = document.getElementById('heroContent');
const dots        = document.querySelectorAll('.hero-dot');

/**
 * Transition the hero content to slide i
 * @param {number} i - slide index
 */
function goToSlide(i) {
  currentSlide = i;
  const s = slides[i];

  // Fade out
  heroContent.style.opacity = '0';
  heroImg.style.opacity = '0';
  setTimeout(() => {
    heroContent.innerHTML = `
      <div class="hero-tag">${s.tag}</div>
      <div class="hero-title">${s.title.replace('\n', '<br>')}</div>
      <div class="hero-sub">${s.sub}</div>
      <a href="#" class="btn-hero">
        ${s.btn} &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
      </a>`;
      heroImg.src= s.img;
    heroContent.style.opacity = '1';
    heroImg.style.opacity='1';
  }, 300);

  // Sync dots
  dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
}

// Set up fade transition
heroContent.style.transition = 'opacity 0.3s';

// Dot click listeners
dots.forEach(d =>
  d.addEventListener('click', () => goToSlide(+d.dataset.slide))
);

// Auto-advance every 4.5 s
setInterval(() => goToSlide((currentSlide + 1) % slides.length), 4500);

/* ════════════════════════════════════════
   SCROLL TO TOP BUTTON
════════════════════════════════════════ */
const scrollTopBtn = document.getElementById('scrollTop');

window.addEventListener('scroll', () => {
  scrollTopBtn.classList.toggle('visible', window.scrollY > 300);
});

scrollTopBtn.addEventListener('click', () =>
  window.scrollTo({ top: 0, behavior: 'smooth' })
);

/* ════════════════════════════════════════
   SEARCH MODAL
════════════════════════════════════════ */
const searchModal = document.getElementById('searchModal');

// Open
document.getElementById('searchBtn').addEventListener('click', () => {
  searchModal.classList.add('open');
  setTimeout(() => document.getElementById('searchInput').focus(), 300);
});

// Close via × button
document.getElementById('closeSearch').addEventListener('click', () =>
  searchModal.classList.remove('open')
);

// Close on backdrop click
searchModal.addEventListener('click', e => {
  if (e.target === searchModal) searchModal.classList.remove('open');
});

// Close on Escape key
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') searchModal.classList.remove('open');
});

/* ════════════════════════════════════════
   ACTIVE NAV LINK ON SCROLL
════════════════════════════════════════ */
const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
const sections = ['about', 'shop']
  .map(id => document.getElementById(id))
  .filter(Boolean);

window.addEventListener('scroll', () => {
  let current = '';

  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 120) current = s.id;
  });

  navLinks.forEach(l => {
    l.classList.remove('active');
    if (l.getAttribute('href') === '#' + current) l.classList.add('active');
  });
});

/* ════════════════════════════════════════
   CART COUNTER (delegated — catches
   dynamically rendered cards too)
════════════════════════════════════════ */
let cartCount = 0;
const cartBadge = document.querySelector('.cart-badge');

document.addEventListener('click', e => {
  const btn = e.target.closest('.add-to-cart-btn');
  if (!btn) return;

  e.preventDefault();
  cartCount++;
  cartBadge.textContent = cartCount;

  // Feedback state
  btn.innerHTML = '<i class="fas fa-check fa-xs"></i> Added';
  btn.style.background = '#16a34a';

  setTimeout(() => {
    btn.innerHTML = 'Add to Cart &nbsp;<i class="fas fa-cart-plus fa-xs"></i>';
    btn.style.background = '';
  }, 1200);
});
/* ════════════════════════════════════════
   CLEAN INITIALIZATION
════════════════════════════════════════ */

// This function replaces the old, broken 'products.map' lines
async function initDashboard() {
  try {
    const response = await fetch('get_products.php');
    if (!response.ok) throw new Error("Network response was not ok");
    const data = await response.json();

    // Filter categories (Ensure DB column 'category' uses 'latest' or 'hotdeal')
    const latest = data.filter(p => p.category === 'latest');
    const hotDeals = data.filter(p => p.category === 'hotdeal');

    const productsGrid = document.getElementById('productsGrid');
    const hotdealsGrid = document.getElementById('hotdealsGrid');

    if (productsGrid) productsGrid.innerHTML = latest.map(buildCard).join('');
    if (hotdealsGrid) hotdealsGrid.innerHTML = hotDeals.map(buildCard).join('');
    
  } catch (err) {
    console.error("Error loading products:", err);
  }
}

// Run only ONCE when the page finishes loading
document.addEventListener('DOMContentLoaded', initDashboard);