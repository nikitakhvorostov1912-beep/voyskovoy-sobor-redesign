# Дизайн-токены и UI-система

## 1. Цвета (CSS Custom Properties)

| Токен | HEX | Назначение | Контраст на белом |
|---|---|---|---|
| `--color-primary` | `#1a1a2e` | Тёмно-синий, шапка, заголовки разделов, primary buttons | 14.6:1 ✅ |
| `--color-primary-light` | `#2d2d44` | Градиенты, hover | 9.5:1 ✅ |
| `--color-accent` | `#c9a961` | Золото — акценты, ссылки, кнопки CTA | 2.5:1 ⚠️ (только для крупного текста ≥18px / иконок) |
| `--color-accent-hover` | `#b8960c` | Hover-золото | 3.6:1 ⚠️ |
| `--color-accent-light` | `#f0e6c8` | Бэкграунд бейджей, soft-acid | 1.1:1 ❌ (только bg) |
| `--color-secondary` | `#8b2635` | Бордовый — donate-кнопка, special-actions | 6.7:1 ✅ |
| `--color-secondary-hover` | `#a63042` | Hover-бордо | 5.3:1 ✅ |
| `--color-bg` | `#faf8f5` | Кремовый базовый фон | bg |
| `--color-bg-alt` | `#f5f0e8` | Альтернативный фон | bg |
| `--color-white` | `#ffffff` | Карточки, модалы | bg |
| `--color-text` | `#2d2d2d` | Основной текст | 12.6:1 ✅ |
| `--color-text-light` | `#555555` | Вторичный текст | 7.5:1 ✅ |
| `--color-text-muted` | `#777777` | Метки, плейсхолдеры | 4.6:1 ✅ borderline |
| `--color-text-on-dark` | `#f5f5f5` | Текст на dark-bg | inv |

⚠️ **A11y-замечания:**
- `--color-accent` (#c9a961) — НЕ использовать для текста <18px на белом. Сейчас встречается в `news-read-more` и подобных местах → провал WCAG AA.
- `--color-text-muted` (#777) на `--color-bg` (#faf8f5) — формально 4.5:1, но с учётом тёплого фона ощущается ниже.

## 2. Тени и радиусы

| | |
|---|---|
| `--shadow-card` | `0 5px 20px rgba(0,0,0,0.08)` |
| `--shadow-hover` | `0 15px 40px rgba(0,0,0,0.12)` |
| `--radius` | `12px` (карточки, кнопки, модалы) |
| `--transition` | `all 0.3s ease` |

## 3. Типографика

### Шрифты
| Семейство | Использование | Источник |
|---|---|---|
| **Cormorant Garamond** 400/500/600/700 | h1–h6 (по умолчанию) | Google Fonts |
| **Playfair Display** 400/500/600/700 | h1 на page-headers (inline override), fallback | Google Fonts |
| **Ruslan Display** 400 | hero-title `.gold-shine`, особо торжественные надписи | Google Fonts |
| **Inter** 300/400/500/600 | body, кнопки, формы | Google Fonts |

### Размеры (текущие, в основном через inline-style)
| Где | font-size |
|---|---|
| `.hero-title` | (CSS не показан, но через `font-family: Ruslan Display`) |
| Page-header h1 | inline: `42–48px` |
| Section h2 | inline: `36px` (Playfair) |
| h3 в карточках | без явного `font-size`, наследуется |
| h4 быстрых ссылок | без явного |
| Body | `16px` (через `body { font-size: ?; line-height: 1.6 }`) |
| `news-excerpt` / muted | inline: `14px` |
| Slider title | inline в `<style>` шорткода: `48px` (mobile: `26px`) |

⚠️ Проблема: размеры разбросаны по inline-стилям → нет single source. Нужна шкала (см. TASKS).

### Веса
- 400 — body
- 500 — links, accent-emphasis
- 600 — h1–h6, кнопки, бейджи
- 700 — выделения, цена, год в timeline

### Line-height
- `body`: `1.6`
- `h1–h6`: `1.3`
- `news-excerpt`, длинный текст: `1.7–1.8`

## 4. Spacing

Текущая шкала (по факту inline):
- `padding: 60px 0` или `80px 0` — секции
- `padding: 30–50px` — карточки
- `gap: 20–60px` — гриды
- `margin-bottom: 15–50px` — разделители

Нужна формальная шкала (4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 / 80 / 100), но это опциональное улучшение P3.

## 5. Анимации (CSS-классы)

| Класс | Эффект |
|---|---|
| `.scroll-reveal` | базовый — появление при попадании в viewport |
| `.from-bottom`, `.from-left`, `.from-right` | направление сдвига |
| `.scale-in` | масштабирование |
| `.delay-1`, `.delay-2`, `.delay-3` | задержки 0.1/0.2/0.3s |
| `.gold-shine` | анимированный «золотой блик» по тексту |
| `.pulse` | пульсация (на CTA-кнопках) |

⚠️ Реализация скорее всего в `style.css` (animations.css ПУСТ — 0 байт). Нужно проверить наличие keyframes и при необходимости перенести в animations.css.

## 6. Декор

- `.ornament-divider` — декоративный разделитель (текущая реализация неизвестна, нужно глянуть в style.css; вероятно — псевдоэлемент с символом «✦» или линия с точкой по центру)
- Иконки разделов: emoji 🕊️✝️💛📰 ⚔️🙏🎵 📖🎓👫📜📅⛪
- Hero placeholder: emoji ⛪ на градиенте

## 7. Компоненты (по факту)

### Кнопки
- `.hero-button` — основная CTA (золотая или primary, зависит от секции)
- `.hero-button-outline` — outlined вариант
- `.donate-button` — бордовая для пожертвований (?)
- `.history-filter-btn`, `.history-filter-btn.active` — фильтры

### Карточки
- `.feature-card` — quick-links на главной
- `.service-card` — каталог таинств
- `.service-order-card` — карточка с кнопкой «Заказать»
- `.news-card` — новость
- `.history-card` — статья истории (большая, с обложкой и текстом)

### Сетки
- `.services-grid` — `repeat(auto-fit, minmax(250–280px, 1fr))`
- `.news-grid` — `repeat(auto-fill, minmax(300px, 1fr))`
- `.history-archive-grid` — нужно глянуть в CSS

### Модалы
- `.service-modal` — fullscreen overlay с анимацией fadeIn (определена inline в `page-services-order.php`!)

### Слайдер
- `.church-home-slider` — full-width hero
- `.slider-slide`, `.slider-overlay`, `.slider-content`, `.slider-title`, `.slider-subtitle`, `.slider-button`
- `.slider-nav.prev/.next`, `.slider-dots`, `.slider-dot.active`
- 100vh max-700px min-400px (mobile: 80vh max-450px min-350px)

### Шапка
- `.site-header-centered` — фиксированная (?)
- `.site-title-centered` — золотое название
- `.main-navigation-centered`, `.menu-primary-centered`
- `.mobile-toggle`, `.mobile-menu-overlay`, `.mobile-menu-container`, `.mobile-navigation`

### Footer
- `.site-footer` — текущий минимум (только copyright)
- Виджеты: `footer-1`, `footer-2` (зарегистрированы, но шаблон не использует!)

## 8. Что нужно консолидировать в новой системе

1. **Унификация palette → CSS custom properties + Kadence color groups в `theme.json`**
2. **Шкала типографики** в `theme.json` `typography.fontSizes`
3. **Spacing scale** в `theme.json` `spacing.spacingSizes`
4. **Component classes** в новом `assets/css/components.css`:
   - `.btn`, `.btn--primary`, `.btn--secondary`, `.btn--outline`, `.btn--ghost`
   - `.card`, `.card--service`, `.card--news`, `.card--history`
   - `.hero`, `.hero__title`, `.hero__subtitle`, `.hero__cta`
   - `.section`, `.section--dark`, `.section__title`, `.section__divider`
5. **Animations** в `assets/css/animations.css` (сейчас пустой!)
6. **Icon system** — SVG-спрайт `assets/icons/sprite.svg` с use-references
7. **Customizer-токены** для кастомизации палитры из админки (опционально)
