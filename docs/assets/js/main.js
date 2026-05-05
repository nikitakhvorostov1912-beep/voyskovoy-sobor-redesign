/* ============================================================
   Главный bootstrap. Инициирует подмодули по data-cc-* атрибутам.
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Sticky header shadow ---------- */
  const header = document.querySelector('[data-cc-header]');
  if (header) {
    const update = () => {
      header.dataset.scrolled = window.scrollY > 8 ? 'true' : 'false';
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
  }

  /* ---------- Mobile nav ---------- */
  const toggle = document.querySelector('[data-cc-nav-toggle]');
  const nav = document.querySelector('[data-cc-nav]');
  if (toggle && nav) {
    const close = () => {
      toggle.setAttribute('aria-expanded', 'false');
      nav.dataset.open = 'false';
      document.body.style.overflow = '';
    };
    const open = () => {
      toggle.setAttribute('aria-expanded', 'true');
      nav.dataset.open = 'true';
      document.body.style.overflow = 'hidden';
    };
    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      isOpen ? close() : open();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') close();
    });
    nav.addEventListener('click', (e) => {
      if (e.target.tagName === 'A') close();
    });
  }

  /* ---------- Сurrent year в footer ---------- */
  const yearEl = document.querySelector('[data-cc-year]');
  if (yearEl) yearEl.textContent = String(new Date().getFullYear());

  /* ---------- Copy-to-clipboard кнопки ---------- */
  document.querySelectorAll('[data-cc-copy]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const target = btn.getAttribute('data-cc-copy');
      const value = (document.getElementById(target)?.textContent || '').trim();
      if (!value) return;
      try {
        await navigator.clipboard.writeText(value);
        const old = btn.textContent;
        btn.textContent = 'Скопировано ✓';
        btn.disabled = true;
        setTimeout(() => { btn.textContent = old; btn.disabled = false; }, 1800);
      } catch (e) {
        // fallback: select range
        const range = document.createRange();
        range.selectNode(document.getElementById(target));
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
      }
    });
  });

  /* ---------- Quick-amount chips (donate) ---------- */
  document.querySelectorAll('[data-cc-amount-group]').forEach((group) => {
    const chips = group.querySelectorAll('.cc-amount-chip');
    const input = group.querySelector('[data-cc-amount-input]');
    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        chips.forEach((c) => c.setAttribute('aria-pressed', 'false'));
        chip.setAttribute('aria-pressed', 'true');
        if (input) input.value = chip.dataset.amount || '';
      });
    });
    if (input) {
      input.addEventListener('input', () => {
        chips.forEach((c) => c.setAttribute('aria-pressed', c.dataset.amount === input.value ? 'true' : 'false'));
      });
    }
  });

  /* ---------- Modal: открыть/закрыть ---------- */
  document.querySelectorAll('[data-cc-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const id = trigger.getAttribute('data-cc-modal-open');
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      // pre-fill для prayer-requests:
      const type = trigger.getAttribute('data-cc-prayer-type');
      const price = trigger.getAttribute('data-cc-prayer-price');
      if (type) {
        const titleEl = modal.querySelector('[data-cc-prayer-title]');
        const typeInput = modal.querySelector('[name="prayer_type"]');
        const priceEl = modal.querySelector('[data-cc-prayer-price]');
        if (titleEl) titleEl.textContent = type;
        if (typeInput) typeInput.value = type;
        if (priceEl && price) priceEl.textContent = price;
      }
      const firstFocus = modal.querySelector('input, textarea, button:not([data-cc-modal-close])');
      if (firstFocus) firstFocus.focus();
    });
  });
  document.querySelectorAll('[data-cc-modal-close]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const modal = btn.closest('.cc-modal');
      if (modal) {
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });
  });
  document.querySelectorAll('.cc-modal').forEach((modal) => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.cc-modal[aria-hidden="false"]').forEach((m) => {
        m.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      });
    }
  });

  /* ---------- Form validation + submit (prayer-requests, contacts) ---------- */
  document.querySelectorAll('[data-cc-form]').forEach((form) => {
    const formType = form.getAttribute('data-cc-form'); // "prayer" | "contact"
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Очистить старые ошибки
      form.querySelectorAll('.cc-field[data-error]').forEach((f) => delete f.dataset.error);

      // Валидация
      let valid = true;
      form.querySelectorAll('[required]').forEach((field) => {
        if (!field.value || field.value.trim() === '') {
          field.closest('.cc-field')?.setAttribute('data-error', '');
          valid = false;
        }
      });
      // Проверка имён в записке (одно имя на строку, без титулов)
      const namesField = form.querySelector('[name="names"]');
      if (namesField && namesField.value) {
        const lines = namesField.value.split('\n').map(s => s.trim()).filter(Boolean);
        const ok = lines.every(line =>
          /^[А-Яа-яЁё][А-Яа-яЁё\-\s]{1,40}$/.test(line) && line.split(/\s+/).length <= 4
        );
        if (!ok) {
          namesField.closest('.cc-field')?.setAttribute('data-error', '');
          valid = false;
        }
      }

      if (!valid) {
        const firstError = form.querySelector('.cc-field[data-error]');
        firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }

      // Сборка письма
      const data = new FormData(form);
      let subject, body, telegramText;

      if (formType === 'prayer') {
        const type = data.get('prayer_type') || 'Записка';
        const orderType = data.get('order_type') || 'о здравии';
        const names = data.get('names') || '';
        const author = data.get('author') || '';
        const phone = data.get('phone') || '';
        const note = data.get('note') || '';

        subject = `Заказ требы — ${type} (${orderType})`;
        body =
`Тип требы: ${type}
Род поминовения: ${orderType}

Имена (полностью, в родительном падеже):
${names}

Заказчик: ${author}
Телефон: ${phone}
${note ? `\nКомментарий: ${note}` : ''}

— заявка отправлена с сайта собора`;
        telegramText = `🕯 Заказ требы\n\n${type} — ${orderType}\n\nИмена:\n${names}\n\nЗаказчик: ${author}\nТел: ${phone}${note ? `\n\nКомментарий: ${note}` : ''}`;
      } else if (formType === 'contact') {
        const name = data.get('name') || '';
        const email = data.get('email') || '';
        const phone = data.get('phone') || '';
        const message = data.get('message') || '';

        subject = `Сообщение с сайта собора — ${name}`;
        body =
`Имя: ${name}
Email: ${email}
Телефон: ${phone}

Сообщение:
${message}

— форма обратной связи на сайте собора`;
        telegramText = `📩 Обращение с сайта\n\nОт: ${name}\nEmail: ${email}\nТел: ${phone}\n\n${message}`;
      } else {
        return;
      }

      const mailto = `mailto:nevskiy-sobor@mail.ru?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

      // Action: открываем mailto. Если у пользователя нет почтового клиента — кнопка Telegram fallback в форме.
      window.location.href = mailto;

      // Показать success-блок
      const success = form.querySelector('[data-cc-form-success]');
      if (success) {
        success.hidden = false;
        // Сохранить telegram-ссылку в кнопке (если есть)
        const tgBtn = success.querySelector('[data-cc-telegram-fallback]');
        if (tgBtn) {
          tgBtn.href = `https://t.me/share/url?url=${encodeURIComponent('https://t.me/alexnewsobor')}&text=${encodeURIComponent(telegramText)}`;
        }
        success.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });

  /* ---------- Reading progress (history page) ---------- */
  const progress = document.querySelector('[data-cc-progress]');
  if (progress) {
    const update = () => {
      const h = document.documentElement;
      const max = (h.scrollHeight - h.clientHeight) || 1;
      const pct = (h.scrollTop / max) * 100;
      progress.style.width = `${pct}%`;
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
  }

  /* ---------- Lazy fade-in on scroll (IntersectionObserver) ---------- */
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('cc-anim');
          io.unobserve(e.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
    document.querySelectorAll('[data-cc-reveal]').forEach((el) => io.observe(el));
  }
})();
