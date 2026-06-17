// Marmaris Health Center — UI script

document.addEventListener('DOMContentLoaded', () => {

  // Navbar shadow on scroll
  const nav = document.querySelector('.main-nav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 30);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Smooth scroll for in-page anchors
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id.length > 1 && document.querySelector(id)) {
        e.preventDefault();
        document.querySelector(id).scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Multi-step quote form (teklif-al sayfası)
  const stepper = document.querySelector('[data-quote-form]');
  if (stepper) {
    const steps = stepper.querySelectorAll('[data-step]');
    const items = stepper.querySelectorAll('.quote-stepper .step');
    let cur = 0;
    const goto = (i) => {
      cur = i;
      steps.forEach((s, k) => s.classList.toggle('d-none', k !== i));
      items.forEach((s, k) => {
        s.classList.toggle('active', k === i);
        s.classList.toggle('done', k < i);
      });
    };
    stepper.querySelectorAll('[data-next]').forEach(b => b.addEventListener('click', () => {
      // Geçerli adımdaki required alanları doğrula
      const inputs = steps[cur].querySelectorAll('input[required], select[required], textarea[required]');
      for (const inp of inputs) {
        if (!inp.value || (inp.type === 'email' && !/.+@.+\..+/.test(inp.value))) {
          inp.focus(); inp.classList.add('is-invalid'); return;
        }
        inp.classList.remove('is-invalid');
      }
      if (cur < steps.length - 1) goto(cur + 1);
    }));
    stepper.querySelectorAll('[data-prev]').forEach(b => b.addEventListener('click', () => { if (cur > 0) goto(cur - 1); }));
    goto(0);
  }

  // AJAX form gönderimi (iletisim ve teklif formları için fallback değil — normal POST)
  // Yapılacak ek bir özel davranış yok; PHP tarafı POST'u işliyor.
});
