/* 
   MAMBO Outdoors — dashboard/app.js
   Handles: product rendering, tabs, hero carousel,
            scroll-to-top, search modal, active nav.
   Cart is handled by cart-drawer.js (loaded separately).
═══════════════════════════════════════════════════════════ */

/* ════════════════════════════════════════
   HELPERS
════════════════════════════════════════ */

/** Render 5 star icons coloured by rating */
function renderStars(n) {
  return Array.from({ length: 5 }, (_, i) =>
    `<i class="fas fa-star" style="color:${i < n ? '#f59e0b' : '#e2e8f0'};"></i>`
  ).join('');
}


function buildCard(p) {
  console.log("Mellar Outdoors Product Payload Object:", p);
  const color = p.color || '#1e293b';

  // Escape for HTML attributes
  const safeName = String(p.name        || '').replace(/"/g, '&quot;');
  const safeDesc = String(p.description || '').replace(/"/g, '&quot;');
  const safeImg  = String(p.image_url || '').replace(/"/g, '&quot;'); 
  const imgPath = '../' + p.image_url.replace(/^\/+/, '');
  const resolvedId = p.id;
  const productUrl = `../cart/product.php?id=${resolvedId}`;
  
  return `
    <div class="col">
      <div class="product-card"
           data-id="${p.id}"
           data-name="${safeName}"
           data-price="${p.price}"
           data-image="${safeImg}"
           data-description="${safeDesc}">

        ${p.onSale ? '<span class="badge-sale">On Sale</span>' : ''}
       
        <div class="product-img-wrap"
             style="background:linear-gradient(135deg,${color}18,${color}08);">
          <a href="${productUrl}" class="d-block w-100 h-100">
            <img src="${imgPath}" alt="${safeName}" class="product-card-img">
          </a>
        </div>
       
        <div class="product-body">
          <div class="star-row">${renderStars(p.rating || 5)}</div>
          <div class="product-name">
            <a href="${productUrl}" class="text-decoration-none text-dark hover:text-danger font-weight-bold">
              ${p.name}
            </a>
          </div>

          <div class="price-row">
            <span class="price-now">KSh ${Number(p.price).toLocaleString('en-KE')}</span>
            ${p.oldPrice
              ? `<span class="price-old">KSh ${Number(p.oldPrice).toLocaleString('en-KE')}</span>`
              : ''}
          </div>
           <button class="hover-cart-btn text-center text-decoration-none border-0" 
        onclick="event.stopPropagation(); window.location.href='${productUrl}';">
  Add to Cart &nbsp;<i class="fas fa-cart-plus"></i>
</button>
            
        </div>
      </div>
    </div>`;
}

/*
   LOAD & RENDER PRODUCTS FROM PHP API
*/
async function initDashboard() {
  try {
    const response = await fetch('/api/get_products.php');
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();

    const latest   = data.filter(p => p.is_latest === true);
    const hotDeals = data.filter(p => p.onSale === true);

    const productsGrid = document.getElementById('productsGrid');
    const hotdealsGrid = document.getElementById('hotdealsGrid');

    if (productsGrid) {
      productsGrid.innerHTML = latest.length
        ? latest.map(buildCard).join('')
        : '<p class="text-center text-muted py-4">No products found.</p>';
    }
   
  
    if (hotdealsGrid) {
      hotdealsGrid.innerHTML = hotDeals.length
        ? hotDeals.map(buildCard).join('')
        : '<p class="text-center text-muted py-4">No hot deals right now.</p>';
    }
    if (typeof bindAddToCartButtons === 'function'){
      bindAddToCartButtons();
   }
  } catch (err){
    console.error('Error loading products:', err);
  }
}


document.addEventListener('DOMContentLoaded', initDashboard);

/* 
   PRODUCT TAB SWITCHING
 */
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
  });
});

/* 
   HERO CAROUSEL
  
*/
const slides = [
  { tag: 'New Stock',           title: 'Interior\nProducts',    sub: '',  btn: 'Shop Now',  img: '../images/hero2.png'  },
  { tag: 'Latest Arrival',      title: 'Beautiful\nSets',    sub: '',  btn: 'View More', img: '../images/hero3.png'  },
  { tag: 'Premium Products',  title: 'Buy\nBulk',  sub: '',  btn: 'View More', img: '../images/hero4.png'  },
  { tag: 'Latest Outdoor Units', title: 'Hand\nMade',          sub: '',  btn: 'Shop Now',  img: '../images/hero5.png'  },
];


let currentSlide = 0;
const heroContent = document.getElementById('heroContent');
const heroImg     = document.getElementById('heroImage');
const dots        = document.querySelectorAll('.hero-dot');

function goToSlide(i) {
  currentSlide = i;
  const s = slides[i];

  heroContent.style.opacity = '0';
  heroImg.style.opacity     = '0';

  setTimeout(() => {
    heroContent.innerHTML = `
      <div class="hero-tag">${s.tag}</div>
      <div class="hero-title">${s.title.replace('\n', '<br>')}</div>
      <div class="hero-sub">${s.sub}</div>
      <a href="#" class="btn-hero">
        ${s.btn} &nbsp;<i class="fas fa-arrow-right fa-xs"></i>
      </a>`;
    heroImg.src               = s.img;
    heroContent.style.opacity = '1';
    heroImg.style.opacity     = '1';
  }, 300);

  dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
}

heroContent.style.transition = 'opacity 0.3s';
heroImg.style.transition     = 'opacity 0.3s';

dots.forEach(d => d.addEventListener('click', () => goToSlide(+d.dataset.slide)));

let slideInterval = setInterval(() => goToSlide((currentSlide + 1) % slides.length), 4500);

heroImg.addEventListener('mouseenter', () => {
  clearInterval(slideInterval);
  goToSlide((currentSlide + 1) % slides.length);
});
heroImg.addEventListener('mouseleave', () => {
  slideInterval = setInterval(() => goToSlide((currentSlide + 1) % slides.length), 4500);
});

/* ════════════════════════════════════════
   SCROLL TO TOP
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
document.getElementById('searchBtn').addEventListener('click', () => {
  searchModal.classList.add('open');
  setTimeout(() => document.getElementById('searchInput').focus(), 300);
});
document.getElementById('closeSearch').addEventListener('click', () =>
  searchModal.classList.remove('open')
);
searchModal.addEventListener('click', e => {
  if (e.target === searchModal) searchModal.classList.remove('open');
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') searchModal.classList.remove('open');
});

/* ════════════════════════════════════════
   ACTIVE NAV LINK ON SCROLL
════════════════════════════════════════ */
const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
const sections = ['about', 'shop'].map(id => document.getElementById(id)).filter(Boolean);

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
  navLinks.forEach(l => {
    l.classList.remove('active');
    if (l.getAttribute('href') === '#' + current) l.classList.add('active');
  });
});