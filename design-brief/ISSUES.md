# Найденные проблемы — аудит темы

> 22 пункта, отсортированы по приоритету. Pointer-формат `файл:строка` для прямой навигации.

---

## 🔴 BLOCKER (нужно фиксить до любого редизайна)

### #1 Битая кодировка в `style.css`
**Где:** `style.css:5` (Author), `style.css:11–18` (header-комменты).
**Что:** комментарии в Win1251, файл декларирует UTF-8 → mojibake `Р’Р°С€Рµ РРјСЏ` вместо «Ваше Имя».
**Фикс:** перекодировать `style.css` целиком в UTF-8 (без BOM или с BOM — оба работают), пересохранить через VS Code «Save with Encoding → UTF-8».

### #2 `assets/css/animations.css` — пустой файл (0 байт)
**Где:** `assets/css/animations.css`.
**Что:** в `functions.php:enqueue_assets` файл может подгружаться (или нет — нужно проверить), но он пуст. Если есть `wp_enqueue_style` на него — пустая 200-ка в network panel.
**Фикс:** либо убрать enqueue, либо наполнить keyframes анимаций (`scroll-reveal`, `gold-shine`, `pulse`, `slideUp` сейчас лежат в `style.css` или inline в шорткодах).

### #3 Inline `<style>` и `<script>` в шорткодах
**Где:**
- `functions.php:1051–1129` — `[church_home_slider]` (~80 строк CSS + JS)
- `functions.php:738–805` — `[church_service_order]` (~70 строк JS)
**Что:** при N карточек → N инстансов скрипта в DOM (хотя для слайдера выполнение защищено `forEach` — OK; но загрузка тяжёлая). Стили слайдера дублируются на каждый инстанс UID.
**Фикс:** вынести в `assets/css/slider.css` + `assets/js/slider.js`, enqueue conditionally при `is_front_page() || has_shortcode()`.

---

## 🟠 HIGH (UX-боль или серьёзные нарушения стандартов)

### #4 `page-contacts.php` игнорирует Customizer
**Где:** `page-contacts.php:14–35`.
**Что:** все контакты захардкожены — адрес, телефон, email, проезд, иконки соцсетей. Customizer-настройки `contacts_*` существуют, но не используются.
**Фикс:** заменить `<p>...</p>` на `<?php echo esc_html(get_theme_mod('contacts_phone', '...')); ?>` и т.д.

### #5 Расписание богослужений захардкожено
**Где:** `functions.php:912–933` шорткод `[church_schedule]`.
**Что:** 4 строки массивом. Любое изменение времени = редактирование PHP в проде.
**Фикс:** новый CPT `church_service_schedule` с полями day_of_week, time, service_name, frequency (weekly/monthly/specific_date).

### #6 Timeline в истории захардкожен
**Где:** `page-history.php:80–108`.
**Что:** 3 события (1893/1903/2024) inline в шаблоне.
**Фикс:** новый CPT `church_timeline` с полями year, title, description, image, или использовать существующий `church_history` с meta `_event_year`.

### #7 Заглушки в реквизитах пожертвований
**Где:** `page-donate.php:26–29`.
**Что:** «ИНН: XX XXXXXXXXXX», «Расчётный счёт: XXX...», «Банк: XXX». Это в проде.
**Фикс:** добавить controls в Customizer-секцию «⛪ Пожертвования»: `donate_legal_name`, `donate_inn`, `donate_kpp`, `donate_account`, `donate_bank`, `donate_bik`, `donate_correspondent`.

### #8 ЮKassa интеграция — заглушка
**Где:** `functions.php:546–554`.
**Что:** функция возвращает `payment_url = home_url('/payment-success?test=1')`, реального запроса к API ЮKassa нет.
**Фикс:** интеграция через [yoomoney/yookassa-sdk-php](https://github.com/yoomoney/yookassa-sdk-php). Это P2-задача, не блокер.

### #9 Дубль подключения шрифтов
**Где:**
- `style.css:15` — `@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond...&family=Playfair+Display...&family=Ruslan+Display&family=Inter...')`
- `functions.php:52–57` — `wp_enqueue_style('church-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display...&family=Inter...')`
**Что:** одни и те же шрифты грузятся дважды (Playfair + Inter). Render-blocking + лишние 50-100ms TTFB.
**Фикс:** оставить только enqueue (с полным набором семейств), убрать `@import` из CSS.

### #10 Inline-стили в каждом шаблоне
**Где:** `front-page.php`, `page-*.php`, `single-*.php` (десятки `style="..."` атрибутов).
**Что:** дизайн-система разбросана по 13 файлам. Любое изменение токена = grep + правка в 50+ мест.
**Фикс:** создать `assets/css/components.css`, перенести все классы (`.page-header`, `.about-grid`, `.feature-card`, `.service-card`, etc.), убрать `style="..."` из шаблонов.

### #11 N модалок в каталоге треб
**Где:** `page-services-order.php:72–78`.
**Что:** для каждой услуги (может быть 20+) генерируется свой `<div id="service-modal-{id}">` с inline-обработчиком. DOM-вес растёт линейно.
**Фикс:** один общий модал `<div id="service-order-modal">`, при клике на «Заказать» AJAX-запрос на content и подмена в модал.

### #12 Скрытые битые ссылки в меню
**Где:** `page-activities.php:26–55`, `front-page.php:84` (Духовенство).
**Что:** 7 ссылок (`/clergy`, `/bible-group`, `/sunday-school`, `/youth`, `/cossacks`, `/sisterhood`, `/choir`) ведут на страницы, которых может не быть в БД → 404.
**Фикс:** либо создать страницы (контент с описанием направления), либо завести CPT `church_ministry` с этими slug'ами как seed-данные.

### #13 `color: beige` (`#F5F5DC`) — захардкоженный цвет
**Где:** `page-news.php:10`, `page-services.php:10`, `page-schedule.php:10`, `page-donate.php:10`, `page-history.php:14`, `page-services-order.php:11`, `single-church_history.php:38`, `page.php:11`.
**Что:** `color: beige` использован 8 раз вместо `var(--color-text-on-dark)` или `var(--color-accent-light)`.
**Фикс:** заменить на токен.

### #14 Footer почти пустой
**Где:** `footer.php:8–14`.
**Что:** только `<p>copyright</p>`. Зарегистрированные виджет-зоны `footer-1`, `footer-2` (functions.php:1136–1153) не выводятся.
**Фикс:** 3-колоночный layout: контакты + навигация + соцсети + копирайт. Использовать `dynamic_sidebar('footer-1')` и т.д.

---

## 🟡 MEDIUM (улучшения, не блокирующие работу)

### #15 Шапка фиксированная не задокументирована
**Где:** `header.php:21` — `<header class="site-header-centered" id="site-header">`.
**Что:** в шаблоне нет `position: fixed`/`sticky`, нужно посмотреть в `style.css`. Для длинных страниц прихожанин теряет навигацию при скролле.
**Фикс:** уточнить, добавить `position: sticky; top: 0; z-index: 100;` + плавный shadow при скролле.

### #16 Mobile menu — overlay без анимации/фокус-ловушки
**Где:** `header.php:53–67` + `footer.php:18–31` (script фикс скролла).
**Что:** open/close работают, но нет focus trap (Tab выводит из меню), нет ESC-закрытия, нет `aria-expanded` на toggle, нет анимации.
**Фикс:** добавить `aria-expanded="true|false"` на `.mobile-toggle`, focus trap (можно через [focus-trap-js](https://github.com/focus-trap/focus-trap)), ESC-listener.

### #17 SVG slider-nav без `<title>`
**Где:** `functions.php:1033–1041`.
**Что:** `<svg>` без `<title>` — screen reader читает path. `aria-label` есть на `<button>` — частично OK, но `<svg role="img" aria-hidden="true">` пропущено.
**Фикс:** добавить `aria-hidden="true"` на SVG.

### #18 Скролл-анимация не disable-able
**Где:** классы `.scroll-reveal` повсеместно.
**Что:** нет `@media (prefers-reduced-motion: reduce)` отключения. Для пользователей с vestibular disorders — мигание.
**Фикс:** в animations.css:
```css
@media (prefers-reduced-motion: reduce) {
    .scroll-reveal, .gold-shine, .pulse, .slider-slide {
        animation: none !important;
        transition: none !important;
        opacity: 1 !important;
        transform: none !important;
    }
}
```

### #19 7.5 MB видео не оптимизировано
**Где:** `assets/videos/church-intro.mp4` (7891134 байт).
**Что:** не используется в шаблонах (?), но лежит в репо. Если планируется — нужна WebM-версия + poster + lazy.
**Фикс:** `ffmpeg -i church-intro.mp4 -vcodec libvpx-vp9 -b:v 1M -an church-intro.webm` + poster.jpg.

### #20 Stats-счётчики захардкожены
**Где:** `template-parts/stats.php:5,9,13`.
**Что:** «150 лет, 2000 прихожан, 50 волонтёров» — хардкод. К тому же `template-parts/stats.php` не вызывается ни одним шаблоном (search показал 0 ссылок).
**Фикс:** либо удалить файл, либо вынести в Customizer + подключить в `front-page.php`.

### #21 Двойной nonce в форме заказа
**Где:**
- `functions.php:71` — `wp_localize_script('church-main', 'churchData', ['nonce' => wp_create_nonce('church_order_nonce')])`
- `functions.php:671` — `$nonce = wp_create_nonce('church_order_nonce');` внутри shortcode
**Что:** 2 разных nonce одного action — оба работают, но избыточность.
**Фикс:** оставить один (в shortcode уместнее, чтобы форма работала независимо от main.js).

### #22 `iframe` Яндекс.Карты захардкожен
**Где:** `page-contacts.php:39–44`.
**Что:** координаты и URL карты в шаблоне.
**Фикс:** использовать `get_theme_mod('contacts_map_iframe')` (control уже есть в Customizer functions.php:1271–1281).

---

## ✅ Что уже сделано хорошо

- Skip-link `<a class="skip-link" href="#content">Перейти к содержимому</a>` в шапке.
- `aria-label` на `.mobile-toggle`.
- `loading="lazy"` на thumbnail'ах в шорткодах истории/услуг.
- Nonce + capability checks в save_post hooks (`save_post_church_service`).
- Sanitize callbacks в Customizer (`sanitize_text_field`, `esc_url_raw`, `rest_sanitize_boolean`).
- `DISALLOW_FILE_EDIT = true` для безопасности админки.
- Кастомный walker с `role="menu"`/`role="menuitem"`.
- `wp_kses_allowed_html` для разрешённого iframe.

---

## Что НЕ проверено (нужно живое окружение)

- Lighthouse score (perf / a11y / SEO / best-practices)
- Контрасты в реальных секциях (нужны скриншоты)
- Mobile breakpoints — code suggests responsive, но без preview не уверен
- Совместимость с конкретными версиями Kadence parent (theme требует `Template: kadence`, версия не указана)
- Реальные данные в БД (database.sql есть, но не поднят)
- Скорость загрузки слайдера на 3G
