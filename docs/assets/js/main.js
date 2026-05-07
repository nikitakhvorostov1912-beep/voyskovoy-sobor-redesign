/* ============================================================
   Войсковой Собор Александра Невского — общий JS
   Используется на всех страницах. Каждый блок инициализируется
   только если его DOM-элементы найдены.
   ============================================================ */
(function () {
  'use strict';

  // ─── 1. MOBILE NAV DRAWER ───────────────────────────────────
  const trigger = document.querySelector('.menu-trigger');
  const drawer = document.querySelector('.mobile-drawer');
  if (trigger && drawer) {
    const closeBtn = drawer.querySelector('.close');
    let lastFocus = null;
    const focusables = () => drawer.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
    const open = () => {
      lastFocus = document.activeElement;
      drawer.classList.add('open');
      document.body.classList.add('has-mobile-drawer');
      trigger.setAttribute('aria-expanded', 'true');
      drawer.setAttribute('aria-hidden', 'false');
      const f = focusables();
      if (f.length) f[0].focus();
    };
    const close = () => {
      drawer.classList.remove('open');
      document.body.classList.remove('has-mobile-drawer');
      trigger.setAttribute('aria-expanded', 'false');
      drawer.setAttribute('aria-hidden', 'true');
      if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    };
    trigger.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
    document.addEventListener('keydown', e => {
      if (!drawer.classList.contains('open')) return;
      if (e.key === 'Escape') { close(); return; }
      if (e.key === 'Tab') {
        const f = Array.from(focusables());
        if (!f.length) return;
        const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
  }

  // ─── 2. COPY-TO-CLIPBOARD ──────────────────────────────────
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const text = btn.dataset.copy;
      const original = btn.textContent;
      try {
        await navigator.clipboard.writeText(text);
      } catch (e) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (_) {}
        document.body.removeChild(ta);
      }
      btn.textContent = 'Скопировано ✓';
      btn.classList.add('copied');
      setTimeout(() => {
        btn.textContent = original;
        btn.classList.remove('copied');
      }, 2000);
    });
  });

  // ─── 3. FAQ ACCORDION (event delegation — works even on dynamic / late-bound nodes) ─
  document.addEventListener('click', e => {
    const q = e.target.closest && e.target.closest('.faq-q');
    if (!q) return;
    const item = q.closest('.faq-item');
    if (!item) return;
    e.preventDefault();
    item.classList.toggle('open');
  });

  // ─── 4. TABS (data-tab → data-tab-panel) ───────────────────
  document.querySelectorAll('.tabs').forEach(group => {
    const tabs = [...group.querySelectorAll('.tab')];
    const panels = [...(group.parentElement || document).querySelectorAll('.tab-panel')];
    tabs.forEach(t => {
      t.addEventListener('click', () => {
        const id = t.dataset.tab;
        tabs.forEach(x => x.classList.toggle('active', x === t));
        panels.forEach(p => p.classList.toggle('active', p.dataset.tabPanel === id));
      });
    });
  });

  // ─── 5. RADIO ROWS (visual sync) ───────────────────────────
  document.querySelectorAll('.radio-row').forEach(row => {
    const input = row.querySelector('input[type="radio"]');
    if (!input) return;
    row.addEventListener('click', () => {
      const name = input.name;
      document.querySelectorAll(`.radio-row input[name="${name}"]`).forEach(other => {
        const r = other.closest('.radio-row');
        if (r) r.classList.remove('checked');
      });
      input.checked = true;
      row.classList.add('checked');
    });
    if (input.checked) row.classList.add('checked');
  });

  // ─── 6. CHECK ROWS (visual sync) ───────────────────────────
  document.querySelectorAll('.check-row').forEach(row => {
    const input = row.querySelector('input[type="checkbox"]');
    if (!input) return;
    row.addEventListener('click', e => {
      if (e.target.tagName === 'A') return;
      input.checked = !input.checked;
      row.classList.toggle('checked', input.checked);
    });
    if (input.checked) row.classList.add('checked');
  });

  // ─── 7. QUICK-AMOUNT BUTTONS ───────────────────────────────
  document.querySelectorAll('.qa-grid').forEach(grid => {
    const buttons = [...grid.querySelectorAll('.qa-btn')];
    const otherSelector = grid.dataset.otherInput || '#qa-other';
    const otherInput = document.querySelector(otherSelector);
    const submitAmount = document.querySelector('[data-submit-amount]');
    const updateSubmit = (val) => { if (submitAmount) submitAmount.textContent = (val || '0') + ' ₽'; };
    buttons.forEach(b => {
      b.addEventListener('click', () => {
        buttons.forEach(x => x.classList.toggle('active', x === b));
        if (otherInput) otherInput.value = '';
        updateSubmit(b.dataset.amount);
      });
    });
    if (otherInput) {
      otherInput.addEventListener('input', () => {
        buttons.forEach(x => x.classList.remove('active'));
        updateSubmit(otherInput.value);
      });
    }
  });

  // ─── 8. MODAL OPEN/CLOSE ───────────────────────────────────
  const modalBackdrop = document.querySelector('.modal-backdrop');
  if (modalBackdrop) {
    const closeModal = () => {
      modalBackdrop.classList.remove('open');
      document.body.style.overflow = '';
    };
    const openModal = (opts) => {
      const o = opts || {};
      modalBackdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
      if (o.title) {
        const t = modalBackdrop.querySelector('[data-modal-title]');
        if (t) t.textContent = o.title;
      }
      if (o.price) {
        const p = modalBackdrop.querySelector('[data-modal-price]');
        if (p) p.textContent = o.price;
      }
      if (o.type) {
        const ti = modalBackdrop.querySelector('input[name="type"]');
        if (ti) ti.value = o.type;
      }
    };
    modalBackdrop.querySelectorAll('.modal-close').forEach(c => c.addEventListener('click', closeModal));
    modalBackdrop.addEventListener('click', e => { if (e.target === modalBackdrop) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    document.querySelectorAll('[data-open-modal]').forEach(opener => {
      opener.addEventListener('click', () => {
        openModal({
          title: opener.dataset.title,
          price: opener.dataset.price,
          type: opener.dataset.type,
        });
      });
    });
  }

  // ─── 9. PRAYER REQUEST FORM (mailto + telegram) ────────────
  const prayerForm = document.querySelector('#prayer-form');
  if (prayerForm) {
    prayerForm.addEventListener('submit', e => {
      e.preventDefault();
      const fd = new FormData(prayerForm);
      const type = fd.get('type') || 'требы';
      const intention = fd.get('intention') || 'о здравии';
      const names = (fd.get('names') || '').toString().trim();
      const author = (fd.get('author') || '').toString().trim();
      const phone = (fd.get('phone') || '').toString().trim();
      const note = (fd.get('note') || '').toString().trim();

      const subject = `Заказ требы: ${type} (${intention})`;
      const body = [
        `Тип требы: ${type}`,
        `Поминовение: ${intention}`,
        `Имена (родительный падеж):`,
        names || '—',
        '',
        `От: ${author || '—'}`,
        `Телефон: ${phone || '—'}`,
        note ? `\nКомментарий:\n${note}` : '',
      ].filter(Boolean).join('\n');

      const mailto = `mailto:nevskiy-sobor@mail.ru?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
      window.location.href = mailto;
    });

    const tgFallback = document.querySelector('#prayer-tg-fallback');
    if (tgFallback) {
      tgFallback.addEventListener('click', () => {
        const fd = new FormData(prayerForm);
        const type = fd.get('type') || 'требу';
        const intention = fd.get('intention') || 'о здравии';
        const names = (fd.get('names') || '').toString().trim();
        const author = (fd.get('author') || '').toString().trim();
        const text = [
          `Здравствуйте! Хочу заказать ${type} (${intention}).`,
          names ? `\nИмена:\n${names}` : '',
          author ? `\n\nС уважением, ${author}` : '',
        ].filter(Boolean).join('');
        const url = `https://t.me/alexnewsobor?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank', 'noopener');
      });
    }
  }

  // ─── 10. CONTACT FORM (mailto fallback) ────────────────────
  const contactForm = document.querySelector('#contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', e => {
      e.preventDefault();
      const fd = new FormData(contactForm);
      const name = (fd.get('name') || '').toString().trim();
      const email = (fd.get('email') || '').toString().trim();
      const phone = (fd.get('phone') || '').toString().trim();
      const subject = (fd.get('subject') || '').toString().trim();
      const message = (fd.get('message') || '').toString().trim();

      const mail = [
        `От: ${name}`,
        email ? `Email: ${email}` : '',
        phone ? `Телефон: ${phone}` : '',
        '',
        message,
      ].filter(Boolean).join('\n');

      const mailto = `mailto:nevskiy-sobor@mail.ru?subject=${encodeURIComponent(subject || 'Сообщение с сайта')}&body=${encodeURIComponent(mail)}`;
      window.location.href = mailto;
    });
  }

  // ─── 11. INTERSECTION OBSERVER (fade-in, ts-event) ─────────
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          en.target.classList.add('visible');
          io.unobserve(en.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.fade-in, .ts-event').forEach(el => io.observe(el));
  } else {
    document.querySelectorAll('.fade-in, .ts-event').forEach(el => el.classList.add('visible'));
  }

  // ─── 12. SMOOTH SCROLL TO ANCHORS ──────────────────────────
  document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
    a.addEventListener('click', e => {
      const href = a.getAttribute('href');
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      history.pushState(null, '', href);
    });
  });

  // ─── 13. TODAY HIGHLIGHT (hours table) ─────────────────────
  const days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
  const todayKey = days[new Date().getDay()];
  document.querySelectorAll(`.hours-table tr[data-day="${todayKey}"]`).forEach(tr => tr.classList.add('today'));

})();
