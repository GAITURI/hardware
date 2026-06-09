/* ═══════════════════════════════════════════════════════════
   OASIS TECHNOLOGIES — Main JavaScript
   Handles: Product rendering, tabs, hero carousel,
            scroll-to-top, search modal, active nav, cart
═══════════════════════════════════════════════════════════ */

/* ════════════════════════════════════════
   PRODUCT DATA
════════════════════════════════════════ */
const products = [
  {
    id: 1,
    name: 'Cypress Timber Board 2x4 (12ft)',
    price: 'KES 1,850',
    oldPrice: 'KES 2,100',
    rating: 5,
    onSale: true,
    icon: 'fa-tree',
    color: '#8b5a2b',
  },
  {
    id: 2,
    name: 'Ordinary Portland Cement 50kg',
    price: 'KES 850',
    oldPrice: 'KES 950',
    rating: 5,
    onSale: true,
    icon: 'fa-industry',
    color: '#6c757d',
  },
  {
    id: 3,
    name: 'Corrugated Iron Sheet 3m',
    price: 'KES 1,450',
    oldPrice: 'KES 1,650',
    rating: 5,
    onSale: true,
    icon: 'fa-house',
    color: '#a9a9a9',
  },
  {
    id: 4,
    name: 'Stanley Claw Hammer 16oz',
    price: 'KES 1,200',
    oldPrice: 'KES 1,500',
    rating: 5,
    onSale: true,
    icon: 'fa-hammer',
    color: '#f4b400',
  },
  {
    id: 5,
    name: 'Makita Electric Drill 650W',
    price: 'KES 7,800',
    oldPrice: 'KES 9,000',
    rating: 5,
    onSale: true,
    icon: 'fa-screwdriver-wrench',
    color: '#0066cc',
  },
  {
    id: 6,
    name: 'Steel Reinforcement Bars Y12 (12m)',
    price: 'KES 1,650',
    oldPrice: 'KES 1,850',
    rating: 5,
    onSale: true,
    icon: 'fa-grip-lines',
    color: '#495057',
  },
  {
    id: 7,
    name: 'Wheelbarrow Heavy Duty',
    price: 'KES 5,500',
    oldPrice: 'KES 6,200',
    rating: 4,
    onSale: false,
    icon: 'fa-cart-flatbed',
    color: '#d97706',
  },
  {
    id: 8,
    name: 'PVC Water Pipe 32mm (6m)',
    price: 'KES 1,100',
    oldPrice: 'KES 1,350',
    rating: 5,
    onSale: true,
    icon: 'fa-faucet',
    color: '#2563eb',
  },
];

const hotDeals = [
  {
    id: 9,
    name: 'Bosch Angle Grinder 230mm',
    price: 'KES 8,500',
    oldPrice: 'KES 10,000',
    rating: 5,
    onSale: true,
    icon: 'fa-gear',
    color: '#1f2937',
  },
  {
    id: 10,
    name: 'Ceramic Floor Tiles 60x60cm (Box)',
    price: 'KES 2,800',
    oldPrice: 'KES 3,300',
    rating: 5,
    onSale: true,
    icon: 'fa-border-all',
    color: '#d6d3d1',
  },
  {
    id: 11,
    name: 'Paint Roller Set Professional',
    price: 'KES 950',
    oldPrice: 'KES 1,250',
    rating: 4,
    onSale: true,
    icon: 'fa-paint-roller',
    color: '#dc2626',
  },
  {
    id: 12,
    name: 'Assorted Nails Pack 5kg',
    price: 'KES 750',
    oldPrice: 'KES 950',
    rating: 5,
    onSale: true,
    icon: 'fa-thumbtack',
    color: '#525252',
  },
];
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
  return `
    <div class="col">
      <div class="product-card">
        ${p.onSale ? '<span class="badge-sale">On Sale</span>' : ''}
        <div class="product-img-wrap"
             style="background:linear-gradient(135deg,${p.color}18,${p.color}08);">
          <div style="
            width:120px; height:120px;
            background:linear-gradient(135deg,${p.color}25,${p.color}10);
            border-radius:16px;
            display:flex; align-items:center; justify-content:center;">
            <i class="fas ${p.icon}"
               style="font-size:48px; color:${p.color}; opacity:0.6;"></i>
          </div>
        </div>
        <div class="product-body">
          <div class="star-row">${renderStars(p.rating)}</div>
          <div class="product-name">${p.name}</div>
          <div class="price-row">
            <span class="price-now">${p.price}</span>
            <span class="price-old">${p.oldPrice}</span>
          </div>
          <button class="btn-hero mt-3 w-100 add-to-cart-btn"
                  style="border-radius:8px; font-size:11px; padding:10px;">
            Add to Cart &nbsp;<i class="fas fa-cart-plus fa-xs"></i>
          </button>
        </div>
      </div>
    </div>`;
}

/* ════════════════════════════════════════
   RENDER PRODUCT GRIDS
════════════════════════════════════════ */
document.getElementById('productsGrid').innerHTML  = products.map(buildCard).join('');
document.getElementById('hotdealsGrid').innerHTML  = hotDeals.map(buildCard).join('');

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

  setTimeout(() => {
    heroContent.innerHTML = `
      <div class="hero-tag">${s.tag}</div>
      <div class="hero-title">${s.title.replace('\n', '<br>')}</div>
      <div class="hero-sub">${s.sub}</div>
      <a href="#" class="btn-hero">
        ${s.btn} &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
      </a>`;
    heroContent.style.opacity = '1';
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