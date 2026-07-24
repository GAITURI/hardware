window.addEventListener('scroll', () => {
    document.getElementById('scrollTop').classList.toggle('show', scrollY > 300);
  });
  
  /* ── SKILL BARS — animate on scroll into view ── */
  const bars = document.querySelectorAll('.skill-bar-fill');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });
  bars.forEach(b => observer.observe(b));
  
  /* ── TESTIMONIAL SWITCHER ── */
  let currentTesti = 0;
  const slides = document.querySelectorAll('.testimonial-slide');
  const avDots  = document.querySelectorAll('.av-dot');
  
  function showTestimonial(idx) {
    slides[currentTesti].classList.remove('active');
    avDots[currentTesti].classList.remove('active');
    currentTesti = idx;
    slides[currentTesti].classList.add('active');
    avDots[currentTesti].classList.add('active');
  }
  
  /* Auto-rotate testimonials every 6 s */
  setInterval(() => showTestimonial((currentTesti + 1) % slides.length), 6000);