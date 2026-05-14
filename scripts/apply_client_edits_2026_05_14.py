"""
Применяет правки клиента (Кристина Мельник, 12-14 мая 2026):
1. Шрифты: Cormorant Garamond → Cormorant Infant; + Monomakh для церковных акцентов
2. Меню «О соборе» становится выпадающим (История, Святыни — подпункты)
3. CSS-стили для dropdown

Запуск:
    python apply_client_edits_2026_05_14.py
"""
from __future__ import annotations

import re
import sys
from pathlib import Path

DOCS = Path(__file__).resolve().parent.parent / "docs"
HTML_FILES = sorted(DOCS.glob("*.html"))
CSS_ADDITIONS = DOCS / "assets" / "css" / "site-additions.css"

# ---------------------------------------------------------------------------
# 1. ШРИФТЫ
# ---------------------------------------------------------------------------

# Унифицированный Google Fonts URL — Cormorant Infant + Monomakh + Spectral + PT Sans
UNIFIED_FONTS_HREF = (
    "https://fonts.googleapis.com/css2?"
    "family=Cormorant+Infant:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500;1,600"
    "&family=Monomakh"
    "&family=Spectral:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500"
    "&family=PT+Sans:wght@400;700"
    "&display=swap"
)

# Любая link/import строка, которая ссылается на Cormorant+Garamond, заменяется на унифицированный URL
GFONTS_LINK_RE = re.compile(
    r'<link[^>]*href="https://fonts\.googleapis\.com/css2\?[^"]*Cormorant\+Garamond[^"]*"[^>]*>',
    re.IGNORECASE,
)
GFONTS_IMPORT_RE = re.compile(
    r"@import\s+url\(['\"]https://fonts\.googleapis\.com/css2\?[^'\"]*Cormorant\+Garamond[^'\"]*['\"]\);",
    re.IGNORECASE,
)


def replace_fonts(text: str) -> tuple[str, int]:
    """Заменяет шрифты в HTML/CSS. Возвращает изменённый текст и счётчик правок."""
    changes = 0

    # 1.1 Заменить все <link>, ведущие на Cormorant+Garamond, на унифицированный
    def _link_repl(_m: re.Match[str]) -> str:
        nonlocal changes
        changes += 1
        return (
            f'<link id="unified-fonts" rel="stylesheet" '
            f'href="{UNIFIED_FONTS_HREF}">'
        )

    text = GFONTS_LINK_RE.sub(_link_repl, text)

    # 1.2 Заменить @import url(...) с Cormorant Garamond
    def _import_repl(_m: re.Match[str]) -> str:
        nonlocal changes
        changes += 1
        return f"@import url('{UNIFIED_FONTS_HREF}');"

    text = GFONTS_IMPORT_RE.sub(_import_repl, text)

    # 1.3 Заменить во всех CSS-правилах семейство шрифта
    new_text = text.replace('"Cormorant Garamond"', '"Cormorant Infant"')
    if new_text != text:
        changes += text.count('"Cormorant Garamond"')
        text = new_text
    new_text = text.replace("'Cormorant Garamond'", "'Cormorant Infant'")
    if new_text != text:
        changes += text.count("'Cormorant Garamond'")
        text = new_text

    # 1.4 Подстановка Monomakh в --f-decor (декоративный вариант). Сохраняем fallback.
    decor_re = re.compile(
        r'(--f-decor:\s*)"Cormorant Infant",\s*serif;([^/]*?/\*[^*]*\*/)?',
    )

    def _decor_repl(m: re.Match[str]) -> str:
        nonlocal changes
        changes += 1
        # Сохраняем существующий комментарий, если он был
        return f'{m.group(1)}"Monomakh", "Cormorant Infant", serif; /* Old-Slavonic accents */'

    text = decor_re.sub(_decor_repl, text)

    return text, changes


# ---------------------------------------------------------------------------
# 2. ВЫПАДАЮЩЕЕ МЕНЮ «О СОБОРЕ» → История + Святыни
# ---------------------------------------------------------------------------

NAV_BLOCK_RE = re.compile(
    r'(<nav class="uheader__nav"[^>]*>)(.*?)(</nav>)',
    re.IGNORECASE | re.DOTALL,
)


def _build_dropdown_nav(opening: str, body: str, closing: str, current_file: str) -> str:
    """
    Перестраивает содержимое <nav class="uheader__nav">:
    - Заменяет три отдельные ссылки (О соборе / История / Святыни) на одну
      группу с выпадающим списком.
    - Сохраняет атрибут is-active / aria-current на актуальной странице.
    """
    # Захват тега <a ...> целиком — все атрибуты в одной группе для анализа
    link_re = re.compile(
        r'<a\s+([^>]+)>(.*?)</a>',
        re.IGNORECASE | re.DOTALL,
    )
    href_re = re.compile(r'href="([^"]+)"', re.IGNORECASE)
    class_re = re.compile(r'class="([^"]*)"', re.IGNORECASE)

    # Целевые href для свёртки в dropdown
    SUBITEMS = {"about.html", "history.html", "icons.html"}

    # Подзаголовки выпадашки — без дубля главного пункта «О соборе»,
    # т.к. сам триггер уже ведёт на about.html
    sub_meta = [
        ("history.html", "История"),
        ("icons.html", "Святыни"),
    ]

    # Определяем, активен ли pull-down (т.е. текущая страница — одна из 3)
    dropdown_active = current_file in SUBITEMS

    parts: list[str] = []
    seen_dropdown = False

    for m in link_re.finditer(body):
        attrs = m.group(1)
        text = m.group(2).strip()

        href_m = href_re.search(attrs)
        if not href_m:
            continue
        href = href_m.group(1)

        class_m = class_re.search(attrs)
        cls = (class_m.group(1) if class_m else "").strip()

        if href in SUBITEMS:
            if not seen_dropdown:
                seen_dropdown = True
                parts.append(_dropdown_html(sub_meta, current_file, dropdown_active))
            continue

        # Сохраняем активный статус как был
        if "is-active" in cls:
            parts.append(f'<a href="{href}" class="is-active" aria-current="page">{text}</a>')
        else:
            parts.append(f'<a href="{href}">{text}</a>')

    new_body = "\n      " + "\n      ".join(parts) + "\n    "
    return f"{opening}{new_body}{closing}"


def _dropdown_html(items: list[tuple[str, str]], current_file: str, active: bool) -> str:
    """Возвращает HTML выпадающего пункта меню «О соборе».
    Триггер — обычная ссылка <a href="about.html">: клик ведёт на about.html,
    hover/focus открывает подменю с дочерними страницами."""
    active_cls = " is-active" if active else ""
    trigger_active_attrs = ' class="uheader__nav-trigger is-active" aria-current="page"' if active else ' class="uheader__nav-trigger"'
    li_items: list[str] = []
    for href, label in items:
        item_active = ' class="is-active" aria-current="page"' if href == current_file else ""
        li_items.append(f'<li><a href="{href}"{item_active}>{label}</a></li>')
    submenu = "\n          ".join(li_items)
    caret_svg = (
        '<svg class="uheader__caret" width="9" height="9" viewBox="0 0 12 12" '
        'fill="none" aria-hidden="true">'
        '<path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" '
        'stroke-linecap="round" stroke-linejoin="round"/></svg>'
    )
    return (
        f'<div class="uheader__nav-group has-submenu{active_cls}">\n'
        f'        <a href="about.html"{trigger_active_attrs} '
        f'aria-haspopup="true" aria-expanded="false">'
        f'<span class="uheader__nav-trigger-label">О соборе</span>{caret_svg}</a>\n'
        f'        <ul class="uheader__submenu" role="menu">\n'
        f'          {submenu}\n'
        f'        </ul>\n'
        f'      </div>'
    )


def transform_nav(text: str, current_file: str) -> tuple[str, int]:
    changed = 0

    def _repl(m: re.Match[str]) -> str:
        nonlocal changed
        new_block = _build_dropdown_nav(m.group(1), m.group(2), m.group(3), current_file)
        if new_block != m.group(0):
            changed += 1
        return new_block

    text = NAV_BLOCK_RE.sub(_repl, text)
    return text, changed


# ---------------------------------------------------------------------------
# 3. CSS для dropdown (добавляется ОДИН раз в site-additions.css)
# ---------------------------------------------------------------------------

DROPDOWN_CSS_MARKER_START = "/* === DROPDOWN О СОБОРЕ — 2026-05-14 === */"
DROPDOWN_CSS_MARKER_END = "/* === /DROPDOWN === */"

DROPDOWN_CSS = f"""
{DROPDOWN_CSS_MARKER_START}
/* === Группа «О соборе» — обёртка над триггером и подменю ============== */
body .uheader__nav .uheader__nav-group {{
  position: relative;
  display: inline-flex;
  align-items: stretch;          /* высота как у <a>-соседей */
  margin: 0;
  padding: 0;
  line-height: inherit;
}}
/* Триггер — ведёт себя ровно как <a>-соседи (одинаковый padding, одинаковая высота) */
body .uheader__nav .uheader__nav-trigger {{
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 0;
  line-height: inherit;
  border-bottom: 1px solid transparent !important;  /* перекрываем gold underline на is-active */
}}
.uheader__caret {{
  flex-shrink: 0;
  display: inline-block;
  transition: transform 0.22s ease;
  opacity: 0.85;
}}
.uheader__nav-group:hover .uheader__caret,
.uheader__nav-group:focus-within .uheader__caret {{
  transform: rotate(180deg);
  opacity: 1;
}}
/* На «О соборе» НЕ подсвечиваем триггер как is-active — иначе двойная золотая линия с верхом submenu.
   Активность видна только по подсветке подпункта внутри submenu. */
body .uheader__nav .uheader__nav-trigger.is-active {{
  color: rgba(245, 240, 232, 0.82) !important;
  border-bottom-color: transparent !important;
}}
body .uheader__nav .uheader__nav-group:hover .uheader__nav-trigger,
body .uheader__nav .uheader__nav-group:focus-within .uheader__nav-trigger {{
  color: #c9a961 !important;
}}

/* === Подменю ========================================================= */
.uheader__submenu {{
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 220px;
  margin: 0;
  padding: 0;
  list-style: none;
  background: #1a1f2e;
  border: 1px solid rgba(201, 169, 97, 0.45);
  box-shadow: 0 18px 44px -18px rgba(26, 31, 46, 0.7);
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transform: translateY(6px);
  transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0.2s;
  z-index: 50;
}}
.uheader__nav-group:hover .uheader__submenu,
.uheader__nav-group:focus-within .uheader__submenu {{
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
  transform: translateY(0);
  transition: opacity 0.2s ease, transform 0.2s ease;
}}
.uheader__submenu li {{
  margin: 0;
  list-style: none;
  padding: 0;
  border-bottom: 1px solid rgba(201, 169, 97, 0.14);
}}
.uheader__submenu li:last-child {{ border-bottom: 0; }}

body .uheader__submenu a {{
  position: relative;
  display: block;
  padding: 14px 26px;
  color: rgba(245, 240, 232, 0.88) !important;
  text-decoration: none;
  font-family: "PT Sans", system-ui, sans-serif;
  font-size: 11px;
  font-weight: 400;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  border: 0 !important;
  border-bottom: 0 !important;
  transition: background-color 0.15s ease, color 0.15s ease, padding-left 0.15s ease;
}}
body .uheader__submenu a::before {{
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: #c9a961;
  opacity: 0;
  transition: opacity 0.15s ease;
}}
body .uheader__submenu a:hover,
body .uheader__submenu a:focus-visible {{
  background: rgba(201, 169, 97, 0.10);
  color: #c9a961 !important;
  padding-left: 30px;
  outline: none;
}}
body .uheader__submenu a:hover::before,
body .uheader__submenu a:focus-visible::before,
body .uheader__submenu a.is-active::before {{
  opacity: 1;
}}
body .uheader__submenu a.is-active {{
  color: #c9a961 !important;
}}

/* На мобильных <= 980px шапка скрывается, dropdown тоже не нужен */
@media (max-width: 980px) {{
  .uheader__nav-group {{ display: none; }}
}}
{DROPDOWN_CSS_MARKER_END}
"""


def inject_dropdown_css(css_path: Path) -> None:
    text = css_path.read_text(encoding="utf-8")
    if DROPDOWN_CSS_MARKER_START in text:
        # Перезаписываем существующий блок
        pattern = re.compile(
            re.escape(DROPDOWN_CSS_MARKER_START)
            + r".*?"
            + re.escape(DROPDOWN_CSS_MARKER_END),
            re.DOTALL,
        )
        text = pattern.sub(DROPDOWN_CSS.strip(), text)
    else:
        text = text.rstrip() + "\n\n" + DROPDOWN_CSS.strip() + "\n"
    css_path.write_text(text, encoding="utf-8")


# ---------------------------------------------------------------------------
# main
# ---------------------------------------------------------------------------


def main() -> int:
    total_font_changes = 0
    total_nav_changes = 0
    for html in HTML_FILES:
        text = html.read_text(encoding="utf-8")
        original = text

        text, fc = replace_fonts(text)
        text, nc = transform_nav(text, html.name)

        if text != original:
            html.write_text(text, encoding="utf-8")
            print(f"✓ {html.name}  fonts={fc}  nav={nc}")
        total_font_changes += fc
        total_nav_changes += nc

    if CSS_ADDITIONS.exists():
        css_text = CSS_ADDITIONS.read_text(encoding="utf-8")
        css_orig = css_text
        css_text, css_fc = replace_fonts(css_text)
        if css_text != css_orig:
            CSS_ADDITIONS.write_text(css_text, encoding="utf-8")
            print(f"✓ site-additions.css  fonts={css_fc}")
            total_font_changes += css_fc
        inject_dropdown_css(CSS_ADDITIONS)
        print("✓ dropdown CSS injected into site-additions.css")

    print(
        f"\nИтого: шрифтов заменено {total_font_changes}, nav-блоков перестроено {total_nav_changes}"
    )
    return 0


if __name__ == "__main__":
    sys.exit(main())
