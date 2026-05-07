# Промпт для следующей сессии Claude Code

> Скопируйте текст ниже целиком и вставьте в начало нового разговора с Claude Code. Не сокращайте — детали важны.

---

## Контекст проекта (вставить в новую сессию)

Я разрабатываю сайт **Войскового собора святого благоверного князя Александра Невского** в Краснодаре.
Путь проекта: `C:\CLOUDE_PR\Церковь\`.

Структура:

- `github-staging/docs/` — статичный HTML-сайт (13 страниц), уже работает на GitHub Pages.
- `wordpress-theme/voiskovoy-sobor/` — WordPress-тема, сгенерированная из статичного сайта в прошлой сессии.
- `wordpress-theme/voiskovoy-sobor.zip` — упакованная тема для импорта в WP.

**Стилистика**: литургическая монументальность. Палитра — paper `#f5f0e8`, ink `#1a1f2e`, gold `#c9a961`, burgundy `#8b2635`. Шрифты — Cormorant Garamond + Spectral + PT Sans.

**Что уже есть в WP-теме** (готово):

- `style.css` (Theme Name заголовок), `functions.php` (enqueue, Schema.org JSON-LD, register_nav_menus 3 шт, helpers `vs_img()`, `vs_mailto()`, `vs_current_slug()`)
- `header.php` (uheader + skip-link + fallback default menu)
- `footer.php` (usite-footer 4 колонки + mobile drawer + wp_footer())
- `index.php`, `page.php`, `404.php`
- `front-page.php` (главная — из docs/index.html)
- `page-templates/page-{slug}.php` × 11 (все страницы прихода)
- `assets/` — CSS, JS, images (10 фото из Wikimedia Commons CC BY-SA + клир + favicon)
- `screenshot.png` (1200×900 для WP-админки)
- `README.md` с инструкцией установки
- `manifest.webmanifest`

**Шаблоны генерируются скриптом**: `wordpress-theme/scripts/generate-wp-templates.py` — если нужно перегенерировать после правок в `docs/`, перезапустить `python wordpress-theme/scripts/generate-wp-templates.py`.

---

## Текущее состояние и что нужно доделать

В шаблонах **контент захардкожен** в PHP (HTML, инлайн `<style>`, инлайн `<script>`). Для production WP-сайта это не идеально — приход не сможет редактировать тексты через Gutenberg. Поэтому в этой сессии нужно:

### Задача 1 (PRIORITY 1, 30–60 мин). Тестовый прогон в локальном WP

1. Проверить, что тема активируется без ошибок в реальном WP. Возможные проблемы:
   - Кириллические комментарии в PHP — могут ломать парсер при особо строгих настройках. Проверь через `php -l` каждый PHP-файл.
   - `wp_head()` / `wp_footer()` правильно вызваны.
   - Шаблоны `page-{slug}.php` появляются в селекторе «Шаблон страницы» в редакторе WP.
2. Запустить локальный WP (через Local WP, XAMPP или Docker):
   ```bash
   # Если есть Docker:
   docker run -d --name wp-test -p 8080:80 -v C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor:/var/www/html/wp-content/themes/voiskovoy-sobor wordpress
   ```
3. Создать 11 страниц через WP-CLI:
   ```bash
   wp post create --post_type=page --post_title='О соборе' --post_status=publish --post_name=about
   wp post meta update <ID> _wp_page_template page-templates/page-about.php
   # ... и так для каждой
   ```

### Задача 2 (PRIORITY 2, 1–2 ч). Customizer-настройки

Вынести в `Settings API` через `customize_register`:

- Телефон приёмной (сейчас `+7 (861) 262-00-20` захардкожен в `header.php`, `footer.php`, `404.php`, всех page templates) → option `vs_main_phone`
- Email (`nevskiy-sobor@mail.ru`) → option `vs_email`
- Адрес (`ул. Постовая, 26 · Краснодар, 350063`) → option `vs_address`
- ИНН / ОГРН → option `vs_inn`, `vs_ogrn`
- Дата основания (1853 / 1872) → option `vs_founding_year`
- Соц.сети: VK, Telegram → options `vs_vk_url`, `vs_telegram_url`
- Цвета (paper, ink, gold, burgundy) — опционально, через `wp_get_custom_color_palette()`

Затем в шаблонах заменить захардкоженные значения на `<?php echo esc_html( get_theme_mod( 'vs_main_phone', '+7 (861) 262-00-20' ) ); ?>` и т.д.

### Задача 3 (PRIORITY 3, 2–3 ч). Custom Post Type «Новости»

Сейчас `page-news.html` (новости) — статичная страница с захардкоженными карточками. Нужно:

1. Зарегистрировать CPT `vs_news` в `functions.php`:
   ```php
   register_post_type('vs_news', array(
       'labels' => array('name' => 'Новости', 'singular_name' => 'Новость'),
       'public' => true,
       'has_archive' => true,
       'rewrite' => array('slug' => 'news'),
       'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
       'menu_icon' => 'dashicons-megaphone',
   ));
   ```
2. Создать `single-vs_news.php` для отображения одной новости (стиль из исходного `news.html`)
3. Создать `archive-vs_news.php` для списка новостей (заменит page-news.php)
4. Обновить page-news.php — превратить в страницу-обёртку которая выводит `[news_archive]` shortcode или WP Loop

### Задача 4 (PRIORITY 4, 1 ч). Gutenberg-блоки и Reusable Patterns

Для страниц `about`, `history`, `parish-life`, `icons`, `donate` — отделить:
- **Шаблонные структуры** (hero, ribbon, footer-cta) → оставить в `page-templates/page-*.php` через `the_content()`
- **Текстовый контент** → вынести в Gutenberg, поле `post_content`

Конкретно: добавить в начало каждого `page-{slug}.php`:
```php
<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
```
И перенести из захардкоженного HTML в `wp_insert_post()` при первичной активации темы (через хук `after_switch_theme`).

### Задача 5 (PRIORITY 5, 30 мин). Фикс известных нюансов

1. Mobile drawer выводится из `footer.php`, но также может остаться рудиментом в page-templates после конвертации скрипта — проверить через `grep -r 'class="mobile-drawer"' page-templates/`. Если есть — удалить.
2. Per-page `<style>` блоки могут конфликтовать с темой темы. Проверить визуально в браузере.
3. Поверх `wp_head()` все Schema.org / preconnect / fonts уже подключены через `functions.php`. Проверить, что в page-templates НЕТ дубликатов:
   ```bash
   grep -r 'fonts.googleapis.com' wordpress-theme/voiskovoy-sobor/page-templates/
   grep -r 'application/ld+json' wordpress-theme/voiskovoy-sobor/page-templates/
   ```
   Если есть — удалить (они уже идут через wp_head).

### Задача 6 (PRIORITY 6, опц.). Form handling

Сейчас формы используют `<form action="mailto:...">`. На production WP-сайте это плохой UX. Заменить на:
- **Contact Form 7** (бесплатный плагин) — для формы «Написать в храм» в `page-contacts.php`
- **WPForms Lite** или **Forminator** — для формы доната с реквизитами
- **Yoast Donations** или **GiveWP** — для приёма пожертвований online (если приход откроет платёжный шлюз)

---

## Команды для старта новой сессии

После того как вставите этот промпт в Claude Code, начните с:

```
Прочитай:
1. C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor\README.md
2. C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor\functions.php
3. C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor\header.php
4. C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor\footer.php

Затем приступай к Задаче 1 (тестовый прогон). У меня нет локального WP — настрой Docker-контейнер, разверни тему, проверь что все 12 страниц работают. Если ошибки — фикси. Когда тема пройдёт smoke test — переходи к Задаче 2 (Customizer).
```

---

## Если приход хочет редактировать тексты сам

Для прихода (заказчика), который НЕ программист, после Задачи 4 (Gutenberg) рабочий процесс будет такой:

1. **WP-админка** → **Страницы**
2. Выбрать страницу (например, «История»)
3. Редактировать текст в визуальном Gutenberg-редакторе (BOLD, заголовки, картинки)
4. **Обновить** → изменения публикуются на сайте.

До завершения Задачи 4 — приход редактирует через `wp-admin/theme-editor.php` (Внешний вид → Редактор тем) или через FTP, что менее удобно.

---

## Альтернатива: вместо WordPress

Если приход в итоге решит остаться на статичном GitHub Pages (`docs/`) — текущая WP-тема нужна как **backup** на случай миграции в будущем.

GitHub Pages пробег:
- ✓ бесплатно
- ✓ быстро (HTTP/2, CDN)
- ✗ нет редактора для не-программиста (только git/markdown)
- ✗ форма доната через mailto (а не платёжный шлюз)

WordPress пробег:
- ✗ хостинг ~ 200–500 ₽/мес (Beget, Reg.ru, Timeweb)
- ✓ редактор для прихода
- ✓ полноценный платёжный шлюз через GiveWP / WooCommerce
- ✓ комментарии, формы, новости, расписание-календарь как ACF
