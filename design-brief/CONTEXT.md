# Контекст темы Church Kadence Child v2.0.0

## 1. Шапка темы

```css
/*
Theme Name: Church Kadence Child DEV
Theme URI: https://alexander-nevskiysobor.ru
Description: DEV копия для разработки
Author: <битая кодировка>
Template: kadence
Version: 2.0.0
Text Domain: church-kadence-child
*/
```

⚠️ Author и комментарии в `style.css` (строки 12+) хранятся как Win1251, но файл декларирует UTF-8 → mojibake.

---

## 2. Custom Post Types (4 шт.)

| CPT | Slug | Меню в админке | Назначение | Таксономия |
|---|---|---|---|---|
| `church_service` | `services` | «Требы и услуги» (heart icon) | Каталог таинств для онлайн-заказа | `service_category` |
| `church_news` | `news` | «Новости прихода» (megaphone) | Лента новостей | `news_category` |
| `church_history` | `history-archive` | «История храма» (archive) | Архив исторических статей | `history_category` |
| `church_order` | — (private) | «Заказы» (clipboard) | Сохранённые заказы треб | — |

Все публичные, `show_in_rest=true`, поддерживают thumbnail/excerpt/revisions.

---

## 3. Meta-поля услуг (`church_service`)

| Ключ | Тип | Назначение |
|---|---|---|
| `_service_price` | number | Цена в рублях |
| `_service_price_type` | string (`fixed` / `per_name` / `per_10_names`) | Формула расчёта |
| `_service_min_names` | number | Минимум имён для panikhida-style |
| `_service_max_names` | number | Максимум |
| `_service_payment_enabled` | boolean | Включить онлайн-оплату для этой услуги |
| `_service_payment_description` | string | Описание оплаты |

---

## 4. Шорткоды

| Шорткод | Атрибуты | Где используется |
|---|---|---|
| `[church_home_slider]` | — (читает Customizer) | `front-page.php` |
| `[church_news count=N category=slug]` | count=6, category="" | `page-news.php` |
| `[church_services count=N order=ASC category=slug]` | count=6, order=ASC | `page-services.php` |
| `[church_schedule]` | — (хардкод 4 строки!) | `page-schedule.php` |
| `[church_history_archive count=N order=DESC category=slug]` | count=12 | `page-history.php` |
| `[church_service_order service_id=N]` | обязательный service_id | `page-services-order.php` (внутри модалки) |

---

## 5. AJAX endpoint

`POST /wp-admin/admin-ajax.php` action=`church_submit_service_order`:
- Принимает: service_id, customer_name/email/phone, names_count, names_list, order_comment, nonce
- Создаёт `church_order` post, сохраняет meta
- Шлёт email админу + Telegram-уведомление
- Возвращает payment_url (от ЮKassa-заглушки)
- Nonce: `wp_verify_nonce($_POST['church_order_nonce'], 'church_order_nonce')`

---

## 6. Customizer (3 секции, 31+ контрол)

### «⛪ Пожертвования» (priority 30)
- `donate_button_text` — текст кнопки (default: «Пожертвовать»)
- `donate_button_url` — URL платёжки
- `donate_show_in_menu` — показывать в шапке (boolean)

### «📍 Контакты» (priority 39)
- `contacts_address`, `contacts_phone`, `contacts_email`, `contacts_schedule`, `contacts_text`
- `contacts_map_iframe` — html iframe Яндекс.Карт
- `contacts_show_map` — boolean
- ⚠️ **`page-contacts.php` НЕ использует эти настройки** — там всё захардкожено.

### «🖼️ Слайдер на главной» (priority 25)
- `slider_image_{1..5}`, `slider_title_{1..5}`, `slider_subtitle_{1..5}`, `slider_link_{1..5}`
- `slider_auto_play` — boolean
- `slider_transition_speed` — мс (2000–10000, шаг 500)

### Платежи и Telegram — отдельные admin-pages, не Customizer
- Меню «💳 Платежи»: enabled, system, shop_id, secret_key, email
- Подменю «📬 Telegram»: enabled, bot_token, chat_id + кнопка теста

---

## 7. Walker меню

`Church_Walker_Nav_Menu` (`inc/class-walker-nav-menu.php`):
- Добавляет классы `menu-item`, `menu-item-depth-N`, `active`, `has-children`
- Subset role-атрибуты: `role="menu"`, `role="menuitem"`
- Стрелочка для dropdown — убрана (комментарий «Стрелочка убрана»)
- Ссылка получает класс `menu-link` + `has-dropdown-toggle` для родителей

Кнопка пожертвований инжектится в primary меню через filter `wp_nav_menu_items` (functions.php:1417).

---

## 8. Структура шаблонов

### Главная (`front-page.php`)

```
[church_home_slider]                  — если есть слайды, иначе:
<section class="hero">                — fallback с emoji ⛪ и двумя CTA
<section class="about-church-main">   — 2-колонка (фото + текст), Customizer-фото
<section class="quick-links-section"> — 4 карточки: расписание / таинства / донат / новости
<section class="cta-section">         — приглашение в храм + контакты-кнопка
```

### Шаблоны страниц (`page-{slug}.php`)

Все используют общий паттерн:
1. `<section class="page-header">` — градиент primary→primary-light, h1 + подзаголовок
2. `<section class="...-full">` — белая карточка с контентом и шорткодом

Кроме `page-contacts.php` (отдельный layout с Яндекс.Картой) и `page-donate.php` (бордовый header + блок реквизитов).

### Single-страницы

`single-church_news.php` — простой layout: meta + thumbnail + content.
`single-church_history.php` — heavy layout с hero-фото, gradient overlay, prev/next-навигацией.

---

## 9. Хуки и фильтры

```php
add_action('init', 'church_*_register_meta')
add_action('init', 'church_register_*_cpt', 1)
add_action('add_meta_boxes', 'church_service_meta_boxes')
add_action('save_post_church_service', 'church_service_save_fields')
add_action('admin_menu', 'church_payment_settings_page')
add_action('admin_menu', 'church_telegram_settings_page')
add_action('admin_menu', 'church_add_rewrite_flush_button')
add_action('after_setup_theme', 'church_child_setup')
add_action('after_setup_theme', 'church_child_register_menus')
add_action('wp_enqueue_scripts', 'church_child_enqueue_assets', 20)
add_action('wp_ajax_church_submit_service_order', 'church_submit_service_order_ajax')
add_action('wp_ajax_nopriv_church_submit_service_order', 'church_submit_service_order_ajax')
add_action('init', 'church_cleanup_head')
add_action('customize_register', 'church_customize_register', 20)
add_action('after_switch_theme', 'church_flush_rewrite_rules_on_activation')
add_action('widgets_init', 'church_child_widgets_init')

add_filter('wp_nav_menu_items', 'church_add_donate_to_menu', 10, 2)
add_filter('nav_menu_css_class', 'church_add_menu_classes', 10, 3)
add_filter('manage_church_order_posts_columns', 'church_order_columns')
add_filter('wp_kses_allowed_html', 'church_allow_iframe_in_contacts', 10, 2)
```

---

## 10. Пути и константы

| | |
|---|---|
| Stylesheet directory | `wp-content/themes/church-kadence-child-dev/` |
| Template directory | `wp-content/themes/kadence/` (parent) |
| Стиль parent | `wp_enqueue_style('kadence-parent')` |
| Стиль child | `wp_enqueue_style('church-child', deps=['kadence-parent'])` |
| Шрифты | enqueue + `@import` (дублируется!) |
| `DISALLOW_FILE_EDIT` | true (functions.php:1166) |

---

## 11. JS-ассеты

`assets/js/main.js` (14 KB) — подключается с зависимостью `jquery`, локализуется как `churchData`:
```js
churchData = {
    ajaxUrl: '/wp-admin/admin-ajax.php',
    homeUrl: '...',
    nonce: '...'  // wp_create_nonce('church_order_nonce')
}
```

⚠️ В шорткоде `[church_service_order]` создаётся **второй nonce** (через `wp_create_nonce` внутри shortcode) — дублирование, но не блокер.

Слайдер имеет inline `<script>` в шорткоде `[church_home_slider]` — не выносится в отдельный JS.
Форма заказа имеет inline `<script>` в `[church_service_order]`.

---

## 12. CSS-структура

`style.css` (58 KB):
1. Подключение шрифтов (`@import` Google Fonts) — строки 12-15
2. Цветовые переменные (`:root`) — строки 17-47
3. Базовые сбросы — строки 49+
4. Шапка центрированная (`.site-header-centered`) — много строк
5. Слайдер (`.church-home-slider`) — 50+ строк
6. Hero, sections, cards
7. Утилиты (анимации scroll-reveal, gold-shine, и т.п.)

`assets/css/animations.css` — **0 байт** (создан, но пуст).
`assets/css/test.css` (?) — `assets/test.css` 36 байт.

---

## 13. Скрытые ссылки в коде (страниц нет!)

Из `page-activities.php` ссылки на:
- `/clergy` (Духовенство)
- `/bible-group` (Библейская группа)
- `/sunday-school` (Воскресная школа)
- `/youth` (Молодёжное объединение)
- `/cossacks` (Казачество)
- `/sisterhood` (Сестринство)
- `/choir` (Хор)

Из `front-page.php`:
- `/schedule`, `/donate`, `/services`, `/news`, `/history`, `/contacts`, `/clergy`

Если этих страниц нет в БД → 404.

---

## 14. База данных

`database.sql` (1.4 MB) — лежит в корне темы. Можно использовать для локального восстановления (XAMPP/Local-by-Flywheel) и снятия скриншотов через Chrome MCP.
