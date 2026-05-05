# Бэклог доработок — 4 фазы, 25 задач

> Каждая задача формата: **T-NN: Заголовок** · приоритет · оценка · DoD (definition of done) · refs.

---

## 🔵 P0 — Фундамент (1 день)

Без этого редизайн будет накладывать боль. Делаем строго первым.

### T-01: Перекодировать `style.css` в UTF-8
- **Оценка:** 10 мин
- **DoD:** комментарии в шапке `style.css` читаемы по-русски, нет mojibake.
- **Как:** VS Code → `style.css` → правый-нижний угол status bar «Win1251» → Reopen with Encoding → UTF-8 → если кракозябры, то Save with Encoding → UTF-8 + переписать комменты вручную с кириллицы.
- **Связано:** [ISSUES #1]

### T-02: Завести `theme.json`
- **Оценка:** 1 ч
- **DoD:** файл `theme.json` существует в корне темы, содержит все цветовые токены, шкалу типографики, spacing scale, совместим с Kadence parent.
- **Как:**
  ```jsonc
  {
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 2,
    "settings": {
      "color": {
        "palette": [
          { "slug": "primary", "color": "#1a1a2e", "name": "Тёмно-синий" },
          { "slug": "accent", "color": "#c9a961", "name": "Золото" },
          { "slug": "secondary", "color": "#8b2635", "name": "Бордовый" },
          ...
        ]
      },
      "typography": {
        "fontFamilies": [...],
        "fontSizes": [
          { "slug": "small", "size": "14px" },
          { "slug": "base", "size": "16px" },
          { "slug": "lg", "size": "18px" },
          { "slug": "xl", "size": "24px" },
          { "slug": "h3", "size": "28px" },
          { "slug": "h2", "size": "36px" },
          { "slug": "h1", "size": "48px" }
        ]
      },
      "spacing": {
        "spacingSizes": [
          { "slug": "xs", "size": "8px" },
          { "slug": "sm", "size": "16px" },
          { "slug": "md", "size": "24px" },
          { "slug": "lg", "size": "40px" },
          { "slug": "xl", "size": "60px" },
          { "slug": "2xl", "size": "80px" }
        ]
      }
    }
  }
  ```

### T-03: Наполнить или удалить `assets/css/animations.css`
- **Оценка:** 30 мин
- **DoD:** файл либо удалён + enqueue убран, либо наполнен keyframes для `.scroll-reveal`, `.gold-shine`, `.pulse`, `.slideUp`.
- **Refs:** [ISSUES #2, #18]

### T-04: Создать `assets/css/components.css` и вынести inline-стили
- **Оценка:** 4 ч
- **DoD:** все `style="…"` атрибуты в `.php`-шаблонах заменены на классы. Минимум 80% inline-CSS перенесено. Сайт визуально идентичен.
- **Файлы для обработки:** все `page-*.php`, `front-page.php`, `single-*.php`, `archive-*.php`.
- **Refs:** [ISSUES #10]

### T-05: Развести дубль шрифтов
- **Оценка:** 15 мин
- **DoD:** в `style.css` нет `@import` Google Fonts. В `functions.php` `wp_enqueue_style('church-fonts')` указывает все 4 семейства (Cormorant Garamond, Playfair Display, Ruslan Display, Inter).
- **Refs:** [ISSUES #9]

---

## 🟢 P1 — UX/UI редизайн (3-4 дня)

Главная фаза, ради которой обычно зовут designer-агента.

### T-06: Hero-блок главной — уйти от emoji-fallback
- **Оценка:** 4 ч
- **DoD:** при пустом слайдере показывается одно из:
  - короткое hero-видео (`assets/videos/church-intro.mp4` с poster)
  - параллакс-фото купола с золотым декором и градиентом-оверлеем
  - SVG-герб собора
- Не emoji ⛪.
- **Decision point:** какой из 3 вариантов — нужно подтверждение от пользователя.

### T-07: SVG-icon system для разделов
- **Оценка:** 3 ч
- **DoD:** `assets/icons/sprite.svg` со 12+ иконками в стиле православной гравюры (крест, голубь, свеча, икона, евангелие, чаша, кадило, колокол, орёл, голубой солей, сердце-донат, газета). Использование через `<svg><use xlink:href="#icon-name"/></svg>`. Замена всех emoji в шаблонах.
- **Refs:** [PALETTE.md / Иконки]

### T-08: SVG `.ornament-divider` византийский
- **Оценка:** 1 ч
- **DoD:** `.ornament-divider` рендерит SVG-крест с виноградной лозой по бокам (или другой православный паттерн), `width: 200px; height: 32px;`. Текущий `<div>` заменён на `<svg>` с aria-hidden.

### T-09: Логотип/sigil в шапку
- **Оценка:** 2 ч
- **DoD:** в `header.php` слева от названия — кастом-логотип (`get_custom_logo()` если задан, иначе fallback SVG двуглавого орла войска). Customizer уже поддерживает custom-logo (`functions.php:90`).

### T-10: Один общий модал в каталоге треб
- **Оценка:** 3 ч
- **DoD:** `page-services-order.php` имеет один `<div id="service-order-modal">` в конце шаблона. Карточки услуг открывают его через JS, контент подгружается через AJAX (новый action `church_load_service_form`) или через шорткод-cache в data-attributes.
- **Refs:** [ISSUES #11]

### T-11: Footer 3-колонный
- **Оценка:** 2 ч
- **DoD:**
  ```
  | Контакты         | Навигация    | Соцсети           |
  |------------------|--------------|-------------------|
  | Адрес            | Расписание   | VK / Telegram /   |
  | Телефон          | Таинства     | Email             |
  | Email            | Новости      |                   |
  | Часы работы      | История      |                   |
  ```
  + строка снизу: copyright + ссылка на политику.
- **Использовать:** `dynamic_sidebar('footer-1')`, `dynamic_sidebar('footer-2')`, `church_get_contacts_settings()`.
- **Refs:** [ISSUES #14]

### T-12: Sticky header + scroll shadow
- **Оценка:** 1 ч
- **DoD:** `.site-header-centered { position: sticky; top: 0; }`, JS-toggle класса `.is-scrolled` при `scrollY > 50`, в этом классе тонкая тень `box-shadow: 0 2px 8px rgba(0,0,0,0.08)`.
- **Refs:** [ISSUES #15]

### T-13: Mobile menu улучшения
- **Оценка:** 2 ч
- **DoD:**
  - `aria-expanded="true|false"` на `.mobile-toggle`
  - ESC-key закрывает overlay
  - Focus trap внутри overlay
  - `prefers-reduced-motion` отключает slide-анимацию
- **Refs:** [ISSUES #16]

### T-14: Унификация карточек (news / history / service / feature)
- **Оценка:** 3 ч
- **DoD:** все 4 типа используют общую структуру `.card`:
  ```html
  <article class="card card--news">
    <div class="card__media">...</div>
    <div class="card__body">
      <span class="card__meta">...</span>
      <h3 class="card__title">...</h3>
      <p class="card__excerpt">...</p>
      <a class="card__cta">...</a>
    </div>
  </article>
  ```
  с модификаторами для специфики. Hover/focus-состояния единые.

### T-15: Контактная страница использует Customizer
- **Оценка:** 1 ч
- **DoD:** `page-contacts.php` читает все значения через `get_theme_mod` или `church_get_contacts_settings()`. Хардкод адреса/телефона/email удалён.
- **Refs:** [ISSUES #4]

---

## 🟣 P2 — Функционал (5-6 дней)

После того как UI приведён к одному виду.

### T-16: CPT `church_service_schedule` — расписание из админки
- **Оценка:** 4 ч
- **DoD:** новый CPT с полями `day_of_week` (select), `time` (time), `service_name` (text), `frequency` (weekly/monthly/specific), `notes`. Шорткод `[church_schedule]` читает CPT вместо хардкода. Старый хардкод-fallback удалить.
- **Refs:** [ISSUES #5]

### T-17: CPT `church_timeline` — события истории
- **Оценка:** 3 ч
- **DoD:** новый CPT с полями `event_year` (number), `title`, `description`, `image`, `category`. `page-history.php` рендерит timeline из CPT.
- **Refs:** [ISSUES #6]

### T-18: Реквизиты пожертвований в Customizer
- **Оценка:** 2 ч
- **DoD:** в секции «⛪ Пожертвования» добавлены controls: `donate_legal_name`, `donate_inn`, `donate_kpp`, `donate_account`, `donate_bank`, `donate_bik`, `donate_correspondent`. `page-donate.php` рендерит из `get_theme_mod`.
- **Refs:** [ISSUES #7]

### T-19: Реальная YooKassa интеграция
- **Оценка:** 8 ч
- **DoD:** `church_create_yookassa_payment` делает реальный POST к `https://api.yookassa.ru/v3/payments` с auth (`shop_id:secret_key`), сохраняет `_payment_id` в meta заказа, возвращает confirmation URL. Вебхук `church_yookassa_webhook` принимает status и обновляет `_payment_status`.
- **Refs:** [ISSUES #8], [yoomoney/yookassa-sdk-php](https://github.com/yoomoney/yookassa-sdk-php)
- **Decision point:** есть ли договор с ЮKassa у прихода / есть ли тестовый shop?

### T-20: Шаблоны для скрытых страниц
- **Оценка:** 4 ч
- **DoD:** созданы `page-clergy.php`, `page-bible-group.php`, `page-sunday-school.php`, `page-youth.php`, `page-cossacks.php`, `page-sisterhood.php`, `page-choir.php`. Каждый шаблон — отдельный layout с photogallery + about + контакт-руководителя.
- **Альтернатива:** один CPT `church_ministry` + единый шаблон.
- **Refs:** [ISSUES #12]

### T-21: Виджет «Святой дня»
- **Оценка:** 3 ч
- **DoD:** новый WP widget `Church_Saint_Of_The_Day_Widget` с настройками источника. Подключается RSS-канал [pravoslavie.ru/calendar.xml](https://pravoslavie.ru) или JSON [calend.ru/api](https://calend.ru). Кешируется на 24 часа через `wp_cache_set`. Показывается в footer-1 или sidebar.

### T-22: Поиск по новостям и архиву
- **Оценка:** 3 ч
- **DoD:** на `archive-church_news.php` и `page-history.php` форма поиска (`get_search_form`) с фильтром `?post_type=church_news`. Кастомный `searchform.php`.

---

## ⚪ P3 — Performance / SEO / A11y (2-3 дня)

«Polish», когда основное готово.

### T-23: Schema.org разметка
- **Оценка:** 4 ч
- **DoD:**
  - `Organization` / `PlaceOfWorship` / `Church` JSON-LD в `wp_head`
  - `Event` для каждого расписания (если CPT `church_service_schedule` сделан)
  - `Article` для новостей и истории
- **Тест:** [Rich Results Test](https://search.google.com/test/rich-results)

### T-24: Оптимизация медиа
- **Оценка:** 3 ч
- **DoD:**
  - `church-intro.mp4` → также WebM, ≤ 2 MB, 1080p, 30s.
  - Все картинки в `assets/img/` (если будут) — в WebP с fallback.
  - `<picture>` теги в шаблонах для поддержки.
  - `loading="lazy"` на всех `<img>` ниже сгиба.

### T-25: A11y финальный проход
- **Оценка:** 3 ч
- **DoD:**
  - Контрасты по WCAG AA (≥ 4.5:1 для текста, ≥ 3:1 для UI). `--color-accent` НЕ для текста <18px.
  - `:focus-visible` стили на всех интерактивах (кнопки, ссылки, инпуты).
  - `prefers-reduced-motion` отключает анимации.
  - Lighthouse a11y ≥ 95.
  - axe-core: 0 violations.
- **Refs:** [ISSUES #18, PALETTE.md / контрасты]

---

## Декомпозиция по неделям (рекомендация)

| Неделя | Задачи | Часы |
|---|---|---|
| 1 | P0 (T01-T05) + старт P1 (T06-T08) | ~20 ч |
| 2 | P1 (T09-T15) | ~14 ч |
| 3 | P2 (T16-T18, T20) | ~13 ч |
| 4 | P2 (T19, T21-T22) + P3 (T23-T25) | ~24 ч |

**Итого:** ~71 час чистой разработки, ≈ 2 рабочих недели одного разработчика, или 1 неделя в две руки.

---

## Что зависит от уточнений у заказчика

1. **T-06 (Hero):** какой из 3 вариантов? (видео/фото-параллакс/SVG-герб)
2. **T-19 (YooKassa):** есть ли реальный договор и shop-id у прихода?
3. **T-20 (скрытые страницы):** контент для каждого направления — кто пишет?
4. **T-21 (Святой дня):** какой источник предпочтителен (pravoslavie.ru / calend.ru / azbyka.ru)?
5. **Логотип храма:** есть ли готовый sigil/SVG/png-фавикон или нужно нарисовать?
6. **Реквизиты пожертвований:** реальные данные банка?
