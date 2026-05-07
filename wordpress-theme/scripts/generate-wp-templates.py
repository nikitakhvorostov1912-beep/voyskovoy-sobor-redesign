# -*- coding: utf-8 -*-
"""Конвертация 13 docs/*.html → wordpress-theme/voiskovoy-sobor/page-templates/page-{slug}.php

Алгоритм:
1. Читаем docs/{slug}.html
2. Извлекаем:
   - per-page <style> блоки из <head> (page-specific CSS)
   - HTML контент между </header> и <footer>
   - per-page <script> блоки в конце <body> (без main.js, который уже в functions.php)
3. Заменяем абсолютные ссылки .html → home_url(/slug)
4. Заменяем assets/... → get_template_directory_uri() . '/assets/...'
5. Записываем как PHP-шаблон с Template Name заголовком.

Front-page: index.html → front-page.php (без Template Name).
"""
import re
import sys
from pathlib import Path

sys.stdout.reconfigure(encoding="utf-8")

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")
THEME = Path(r"C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor")
TEMPLATES = THEME / "page-templates"

# slug → (Template Name, target file)
PAGES = {
    "index":           ("Главная (front page)",          THEME / "front-page.php"),
    "about":           ("О соборе",                       TEMPLATES / "page-about.php"),
    "history":         ("История",                        TEMPLATES / "page-history.php"),
    "schedule":        ("Расписание богослужений",        TEMPLATES / "page-schedule.php"),
    "prayer-requests": ("Заказ треб",                     TEMPLATES / "page-prayer-requests.php"),
    "clergy":          ("Духовенство",                    TEMPLATES / "page-clergy.php"),
    "parish-life":     ("Приходская жизнь",               TEMPLATES / "page-parish-life.php"),
    "icons":           ("Святыни и иконы",                TEMPLATES / "page-icons.php"),
    "news":            ("Новости",                        TEMPLATES / "page-news.php"),
    "contacts":        ("Контакты",                       TEMPLATES / "page-contacts.php"),
    "donate":          ("Пожертвование",                  TEMPLATES / "page-donate.php"),
    "privacy":         ("Положение о персональных данных", TEMPLATES / "page-privacy.php"),
}


def extract_styles(text: str) -> list[str]:
    """All <style>...</style> blocks from <head>."""
    head_match = re.search(r'<head\b[^>]*>(.*?)</head>', text, re.DOTALL | re.IGNORECASE)
    if not head_match:
        return []
    head = head_match.group(1)
    return re.findall(r'<style\b[^>]*>.*?</style>', head, re.DOTALL | re.IGNORECASE)


def extract_inline_scripts(text: str) -> list[str]:
    """Inline <script> blocks (без src=). Из <body>."""
    body_match = re.search(r'<body\b[^>]*>(.*?)</body>', text, re.DOTALL | re.IGNORECASE)
    body = body_match.group(1) if body_match else text
    scripts = []
    for m in re.finditer(r'<script\b([^>]*)>(.*?)</script>', body, re.DOTALL | re.IGNORECASE):
        attrs = m.group(1)
        if 'src=' in attrs:
            continue  # внешний скрипт — пропускаем (main.js уже в functions.php)
        if 'application/ld+json' in attrs:
            continue  # Schema.org — выдаётся через wp_head() из functions.php
        scripts.append(m.group(0))
    return scripts


def extract_main_content(text: str) -> str:
    """Контент между </header> и <footer>. Удаляем mobile-drawer (он в footer.php)."""
    # Найдём первый <header ... class="uheader"...>...</header> и удалим его и всё перед ним
    header_close = re.search(r'</header>', text, re.IGNORECASE)
    if not header_close:
        # fallback: возьмём весь body
        body_match = re.search(r'<body\b[^>]*>(.*?)</body>', text, re.DOTALL | re.IGNORECASE)
        return body_match.group(1).strip() if body_match else text
    after_header = text[header_close.end():]

    # Найдём первый <footer ...>
    footer_open = re.search(r'<footer\b[^>]*>', after_header, re.IGNORECASE)
    if footer_open:
        content = after_header[:footer_open.start()]
    else:
        body_close = re.search(r'</body>', after_header, re.IGNORECASE)
        content = after_header[:body_close.start()] if body_close else after_header

    # Удаляем mobile-drawer (он будет в footer.php)
    content = re.sub(
        r'<div\s+class="mobile-drawer"[^>]*>.*?</div>\s*(?=<script|<footer|$)',
        '',
        content,
        flags=re.DOTALL,
    )
    # Если mobile-drawer без последующего тега — попробуем вырезать жадно
    content = re.sub(
        r'<div\s+class="mobile-drawer"[^>]*id="mobile-drawer"[^>]*>[\s\S]*?</div>\s*</div>\s*</div>',
        '',
        content,
    )

    # Удаляем теги <script> с src="assets/js/main.js" (он в functions.php)
    content = re.sub(
        r'<script\s+src="assets/js/main\.js"[^>]*></script>',
        '',
        content,
        flags=re.IGNORECASE,
    )
    return content.strip()


def rewrite_links(html: str) -> str:
    """
    1. href="page.html" → href="<?php echo esc_url(home_url('/page')); ?>"
    2. href="page.html#anchor" → href="<?php echo esc_url(home_url('/page')); ?>#anchor"
    3. src="assets/..." / href="assets/..." → get_template_directory_uri()
    4. url("assets/...") в inline-style → get_template_directory_uri()
    """
    # 1+2. .html links → home_url
    def replace_html_link(m):
        prefix = m.group(1)
        slug = m.group(2)
        anchor = m.group(3) or ""
        if slug == "index":
            url = "<?php echo esc_url(home_url(\"/\")); ?>"
        else:
            url = "<?php echo esc_url(home_url(\"/" + slug + "\")); ?>"
        return f'{prefix}"{url}{anchor}"'

    html = re.sub(
        r'(href=)"([a-z0-9_-]+)\.html(#[a-zA-Z0-9_-]+)?"',
        replace_html_link,
        html,
    )

    # 3. assets/... → get_template_directory_uri()
    def replace_asset(m):
        prefix = m.group(1)
        path = m.group(2)
        return f'{prefix}"<?php echo esc_url(get_template_directory_uri() . \"/assets/{path}\"); ?>"'

    html = re.sub(
        r'(href=|src=)"assets/([^"]+)"',
        replace_asset,
        html,
    )

    # 4. url("assets/...") in CSS background-image (внутри <style> или style="...")
    html = re.sub(
        r'url\(["\']?assets/([^"\')\s]+)["\']?\)',
        r'url(<?php echo esc_url(get_template_directory_uri() . "/assets/\1"); ?>)',
        html,
    )

    return html


def build_template(slug: str, template_name: str, html_text: str) -> str:
    """Создаёт PHP-шаблон страницы."""
    styles = extract_styles(html_text)
    scripts = extract_inline_scripts(html_text)
    content = extract_main_content(html_text)
    content = rewrite_links(content)

    # Стили: переносим в <head> через wp_head хук — самый простой путь
    # хочется встроить как inline <style> внутри тела шаблона
    styles_block = "\n".join(styles) if styles else ""
    scripts_block = "\n".join(scripts) if scripts else ""

    is_front = (slug == "index")
    template_header = "" if is_front else f"""<?php
/**
 * Template Name: {template_name}
 *
 * Шаблон страницы «{template_name}» — сгенерирован из docs/{slug}.html.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) {{ exit; }}
get_header();
?>"""
    if is_front:
        template_header = """<?php
/**
 * Front-page template — главная страница.
 *
 * Сгенерирован из docs/index.html.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>"""

    parts = [template_header]
    if styles_block:
        parts.append("<!-- Page-specific styles (из исходного HTML) -->")
        parts.append(styles_block)
    parts.append('<main id="main">')
    parts.append(content)
    parts.append("</main>")
    if scripts_block:
        parts.append("<!-- Page-specific inline scripts -->")
        parts.append(scripts_block)
    parts.append("<?php get_footer(); ?>")
    return "\n\n".join(parts) + "\n"


def main() -> None:
    TEMPLATES.mkdir(parents=True, exist_ok=True)

    for slug, (template_name, out_path) in PAGES.items():
        html_path = DOCS / f"{slug}.html"
        if not html_path.exists():
            print(f"  ! {slug}.html не найден")
            continue
        text = html_path.read_text(encoding="utf-8")
        php = build_template(slug, template_name, text)
        out_path.write_text(php, encoding="utf-8")
        print(f"  OK  {slug:20} → {out_path.relative_to(THEME)}  ({len(php):,} chars)")

    print(f"\n✓ Сгенерировано {len(PAGES)} шаблонов в {TEMPLATES}")


if __name__ == "__main__":
    main()
