# Design Direction — bold POV для редизайна

> Сжатая стратегия для `frontend-design` skill. Один POV, одно направление, без размытых компромиссов.

---

## ⚓ Концепция в одной строке

> **«Литургическая монументальность»** — собор как место, где время остановлено: камень, золото, пергамент, голос настоятеля. Сайт не «продаёт услуги», он **встречает прихожанина**.

---

## 🎯 Что делает редизайн **запоминающимся** (the one thing)

**Иконо-баннер в hero, который параллаксит при скролле, и под ним — литургическая цитата дня от святых отцов в стиле monastery quote.**

- На главной — fullscreen hero: икона князя Александра Невского + панорама собора + золотая надпись каллиграфическим Cormorant Garamond. Параллакс при скролле. Под баннером — карточка с центрированной цитатой («Не в силе Бог, а в правде» — слова князя Александра Невского) на пергаменте, золотая подпись.
- На остальных страницах — компактная monastery-style шапка с гербом войска (двуглавый орёл) и кириллической логотипикой.
- Пользователь приходит — видит **храм**, не «лендинг услуг».

---

## 🎨 Эстетический tone

| Параметр | Выбор |
|---|---|
| Тон | **Refined / Classical** (НЕ minimalist, НЕ maximalist — refined) |
| Эпоха | XIX-век Российская империя × современная читаемость |
| Аналогия | «Журнал монастыря» — Optina + The New Yorker |
| Темп | Медленный, созерцательный |
| Звук (если бы был) | Хор a cappella, басы и тенор |

**Аналогии-референсы для тона:**
- Журнал Foma + Сайт Optina + Издательство Никея = ~ что мы хотим
- НЕ: Хиллсонг + Megachurch.org + glassmorphism = что мы НЕ хотим

---

## 🏛️ 3 столпа дизайна

### 1. Типографика как главный визуальный элемент
- Каждый раздел открывается **большим заголовком** в Cormorant Garamond (1-1.2 line-height, 700 weight, 4-5rem на desktop)
- **Drop-cap** в больших статьях (история, проповеди)
- **Цитаты** — крупные, центрированные, в кавычках-«ёлочках», подпись золотым курсивом
- **Числа** (даты, расписание, цены) — Spectral tabular-nums, а не sans-serif
- **Тонкие межстрочные** (1.7-1.8 для body) — медленное чтение

### 2. Цвет как литургический сигнал
- **Кремовый/пергамент** — фон, мирный, постоянный
- **Тёмно-синий ink** — текст, шапка, авторитет
- **Золото** — акценты, ссылки, sigil. **Никогда не градиент** — чистое, плоское
- **Бордовый страстной** — donate-кнопки, особые объявления (память мученика, Великий пост)
- НЕТ цветов вне этих 4 групп

### 3. Декор как почерк, не как декорация
- **Ornament-divider** — SVG-крест с виноградной лозой, везде где сейчас `<hr>`
- **Gold-leaf rule** — тонкая золотая линия 1px над/под особыми карточками
- **Sigil** — двуглавый орёл войска в footer'е и favicon
- НЕ в каждой секции — чтобы не «забить» взгляд

---

## 🧩 Hero — единственная «вау»-секция

```html
<section class="hero">
  <div class="hero__sky">
    <!-- Параллакс-фон: панорама собора + лёгкий gradient overlay -->
  </div>
  <div class="hero__icon">
    <!-- Икона князя Александра Невского, чуть выпирающая из общего слоя -->
  </div>
  <h1 class="hero__title">
    <span class="hero__title-prefix">Войсковой Собор</span>
    <span class="hero__title-main">Александра Невского</span>
    <span class="hero__title-place">Краснодар</span>
  </h1>
  <blockquote class="hero__quote">
    «Не в силе Бог, а в правде»
    <cite>— благоверный князь Александр Невский</cite>
  </blockquote>
  <a class="hero__schedule-cta">
    <span class="hero__schedule-label">Ближайшая служба</span>
    <strong class="hero__schedule-when">Завтра, 09:00</strong>
    <span class="hero__schedule-name">Божественная литургия</span>
  </a>
</section>
```

**Анимация (load):**
1. Hero-фон — медленный fade-in 1200ms
2. Icon — slide-in с правой стороны 800ms, delay 200ms
3. Title — буквы появляются wave 1200ms, delay 400ms (но без overdoing — это собор)
4. Quote — fade + slide-up 1000ms, delay 1200ms
5. Schedule-CTA — pulse один раз для привлечения внимания, delay 2000ms

После загрузки — anim **отключаются**. Никаких scroll-reveal в листинге новостей.

---

## 🗺️ Типы страниц и их poses

| Страница | Тон | Layout | Hero |
|---|---|---|---|
| **Главная** | Встреча | Hero + 4 секции | Полный иконо-баннер с цитатой |
| **Расписание** | Информация | Таблица + календарь | Узкий header «Расписание богослужений» с золотой линией |
| **Заказ треб** | Сервис | Каталог карточек с фото | Узкий header + подзаголовок «Помолитесь о близких» |
| **О соборе / История** | Story | Long-form статья | Sepia-фото архивное + h1 |
| **Новости** | Лента | Журнал-grid (asymmetric) | Узкий header «Жизнь прихода» |
| **Духовенство** | Портрет | Карточки с большими фото | Узкий header |
| **Контакты** | Прямой контакт | Левая колонка адрес+телефон+email, справа карта | Без hero, сразу контакты |
| **Пожертвования** | Просьба | Центрированный блок с цитатой о милосердии | Узкий header + крупная цитата (1 Кор 13) |

**Правило:** только главная имеет полный hero. Все остальные — узкий header с золотой линией и monastery-quote под заголовком (если есть подходящая).

---

## 📱 Mobile

- Шапка → собирается в один ряд: логотип-sigil слева + гамбургер справа
- Hero на мобиле — короче (60vh), но иконо-баннер сохраняется
- Цитата — 80% ширины, центрирована
- Schedule-CTA — full-width, sticky внизу экрана при скролле (так часто пользователь хочет узнать «когда служба»)
- Таблица расписания → карточки по дням
- Карточки треб — одна в ряд, с большим фото и кнопкой «Заказать» внизу

---

## 🎬 Motion principles

**ДА:**
- One-shot page-load animation (1.5-2s, никогда не больше)
- Subtle hover на ссылках (color shift + 1px translate-y или underline-grow)
- Smooth scroll к якорям
- Cross-fade между фото в галерее
- Sticky-header с лёгкой тенью при scroll

**НЕТ:**
- Параллакс на каждом блоке (только hero)
- Scroll-reveal на каждом элементе
- 3D-эффекты, rotate, flip
- Auto-playing video с звуком
- Spring/bounce easing (контра-интуитивно для собора)

**Easing:** `cubic-bezier(0.16, 1, 0.3, 1)` (Apple-like, плавный) или `cubic-bezier(0.65, 0, 0.35, 1)` (gentle in-out). НЕТ ease-out (стандартный CSS) — слишком безвкусно.

---

## 🎨 Backgrounds & atmosphere

**Главный фон (`--color-paper`):**
- Не плоский цвет — лёгкая текстура «пергамент» через SVG noise (3-5% opacity)
- Inline SVG `<feTurbulence>` для генерации, не картинка

**Тёмные секции (`--color-ink`):**
- Базовый цвет + тонкая текстура «грифельная доска» (тёмная noise)
- Используется для футера и для специальных секций («Память святых»)

**Карточки:**
- Фон чуть светлее основного: `--color-paper-dark`
- Тонкая 1px рамка `rgba(201, 169, 97, 0.15)` — едва видна, но даёт благородство
- Тень `0 4px 20px rgba(42, 38, 32, 0.06)` — приглушённая тёплая

**НЕТ:**
- Чисто белый фон #fff
- Чисто чёрный фон #000
- CSS-градиенты в фонах секций (только в overlay над фото)

---

## 🔡 Ключевые типографические композиции

### Hero title
```css
.hero__title {
  font-family: var(--font-display);
  font-weight: 600;
  font-size: var(--text-display); /* clamp(3.5rem, 3rem + 2.5vw, 5.5rem) */
  line-height: 1.05;
  letter-spacing: -0.02em;
  color: var(--color-paper);
}
.hero__title-prefix { /* «Войсковой Собор» */
  display: block;
  font-size: 0.45em;
  letter-spacing: 0.3em;
  text-transform: uppercase;
  font-weight: 500;
  color: var(--color-gold);
  margin-bottom: 0.3em;
}
.hero__title-main {  /* «Александра Невского» — главный */
  display: block;
  font-family: var(--font-decor); /* Ruslan Display для торжества */
}
.hero__title-place { /* «Краснодар» */
  display: block;
  font-size: 0.35em;
  letter-spacing: 0.4em;
  font-weight: 400;
  margin-top: 0.5em;
  color: var(--color-gold-pale);
}
```

### Quote
```css
.quote {
  font-family: var(--font-display);
  font-size: var(--text-xl);
  line-height: 1.4;
  font-style: italic;
  text-align: center;
  max-width: 36ch; /* короткие строки для уважения к словам */
  margin: 0 auto;
  color: var(--color-text);
}
.quote::before { content: "«"; color: var(--color-gold); margin-right: 0.1em; }
.quote::after { content: "»"; color: var(--color-gold); margin-left: 0.1em; }
.quote cite {
  display: block;
  margin-top: 1em;
  font-family: var(--font-decor);
  font-size: 0.65em;
  font-style: normal;
  color: var(--color-gold-dark);
  letter-spacing: 0.05em;
}
```

### Drop-cap (для длинных текстов)
```css
.article p:first-of-type::first-letter {
  font-family: var(--font-decor);
  font-size: 5em;
  float: left;
  line-height: 0.85;
  margin: 0.05em 0.1em 0 0;
  color: var(--color-gold);
  text-shadow: 0 0 0.5px var(--color-gold-dark);
}
```

---

## 🔧 Technical execution rules для Claude Design

1. **Только Kadence-child overrides** — не трогать Kadence parent. Все стили в `style.css` дочерней темы или в новом `assets/css/components.css`.
2. **CSS custom properties everywhere** — никаких хардкод-цветов
3. **Mobile-first** — clamp() для всех размеров, container-queries где возможно
4. **Кастомные SVG в `assets/icons/`** — все иконки спрайтом, без emoji
5. **Шрифты — Google Fonts через preconnect + display=swap**
6. **A11y from day 1** — `:focus-visible`, `prefers-reduced-motion`, ARIA на nav/dialog/carousel
7. **Без зависимостей JS-фреймворков** — vanilla JS + WordPress hooks (Kadence parent уже с ним работает)
8. **Все строки на русском** — UI, alt, aria-label, кнопки, формы, error messages
9. **Маркеры доработок** при правке существующих файлов:
   ```php
   // Доработка START {short-task} {2026-05-05}
   …
   // Доработка END
   ```

---

## 📝 Acceptance criteria для каждого этапа

### Phase 1 — Foundation (день 1)
- [ ] Кодировка `style.css` починена (UTF-8 без mojibake)
- [ ] `theme.json` создан с финальной палитрой (4 группы) и типографикой (5 семейств)
- [ ] `assets/css/components.css` создан, в нём — все компонентные классы (`.btn`, `.card`, `.hero`, `.section`)
- [ ] 80%+ inline `style="..."` вынесены в classes
- [ ] Шрифты подключены через preconnect, без `@import` в CSS

### Phase 2 — Hero & header (день 2)
- [ ] Hero на главной — параллакс-баннер + икона + title + quote + schedule-CTA
- [ ] Шапка с гербом-sigil слева, навигация по центру, поиск/donate справа
- [ ] Sticky header с лёгкой тенью при scroll
- [ ] Mobile-toggle с focus-trap, ESC-close, ARIA

### Phase 3 — Content components (день 3)
- [ ] Карточки новостей / истории / служений — единый класс `.card` с модификаторами
- [ ] Ornament-divider SVG (custom) — заменяет все `<hr>` и `.ornament-divider`
- [ ] Quote-блок — основной компонент для цитат и обращений
- [ ] Drop-cap для длинных статей

### Phase 4 — Specific pages (день 4-5)
- [ ] Главная: hero + about + расписание-preview + новости-preview + donate-cta
- [ ] Заказ треб: один общий модал, не N
- [ ] Расписание: таблица + календарь, реактивная (CPT в идеале — но пока хардкод OK)
- [ ] Контакты: используют Customizer-настройки
- [ ] Footer: 3-колонный с виджетами

### Phase 5 — Polish (день 6)
- [ ] Все a11y-проверки (контраст, focus, reduced-motion)
- [ ] Lighthouse: a11y ≥ 95, performance ≥ 80, SEO ≥ 95
- [ ] Schema.org Church + Event
- [ ] Open Graph

---

## 🚫 Что НЕ нужно делать в этой сессии

- НЕ интегрировать реальный YooKassa (это P2, после визуального redesign'a)
- НЕ создавать `church_service_schedule` CPT (P2)
- НЕ создавать `church_timeline` CPT (P2)
- НЕ переписывать backend AJAX-логику заказов
- НЕ менять URL-схему

Дизайн-сессия фокусируется на **визуальной части** + структуре шаблонов. Backend остаётся.
