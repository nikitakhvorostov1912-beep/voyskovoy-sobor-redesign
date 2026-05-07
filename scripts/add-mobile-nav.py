"""Add mobile nav (hamburger + drawer + main.js + site-additions.css)
to all 12 HTML files in docs/.

Idempotent: re-running won't duplicate elements.
"""
import re
from pathlib import Path

DOCS = Path(__file__).resolve().parent.parent / "Церковь" / "github-staging" / "docs"
if not DOCS.exists():
    # When script lives next to docs/
    DOCS = Path(__file__).resolve().parent.parent / "docs"
if not DOCS.exists():
    # Fallback absolute
    DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")

NAV = [
    ("index.html",          "Главная"),
    ("about.html",          "О соборе"),
    ("history.html",        "История"),
    ("schedule.html",       "Расписание"),
    ("prayer-requests.html","Заказ треб"),
    ("clergy.html",         "Духовенство"),
    ("parish-life.html",    "Приход"),
    ("icons.html",          "Святыни"),
    ("news.html",           "Новости"),
    ("contacts.html",       "Контакты"),
]

TRIGGER = (
    '    <button class="menu-trigger" aria-label="Открыть меню" '
    'aria-expanded="false" aria-controls="mobile-drawer">\n'
    '      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" '
    'stroke-width="1.6" aria-hidden="true">\n'
    '        <line x1="3" y1="6" x2="21" y2="6"></line>\n'
    '        <line x1="3" y1="12" x2="21" y2="12"></line>\n'
    '        <line x1="3" y1="18" x2="21" y2="18"></line>\n'
    '      </svg>\n'
    '    </button>'
)

def make_drawer(current: str) -> str:
    items = ""
    for href, label in NAV:
        active = ' class="is-active" aria-current="page"' if href == current else ""
        items += f'      <a href="{href}"{active}>{label}</a>\n'
    return f'''
<div class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Главное меню" aria-hidden="true">
  <div class="mobile-drawer__head">
    <a href="index.html" class="mobile-drawer__brand">
      Войсковой Собор<br>Александра Невского
      <small>Краснодар · 1872</small>
    </a>
    <button class="close" aria-label="Закрыть меню">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <line x1="6" y1="6" x2="18" y2="18"></line>
        <line x1="6" y1="18" x2="18" y2="6"></line>
      </svg>
    </button>
  </div>
  <nav class="mobile-drawer__nav" aria-label="Главная навигация (мобильная)">
{items}  </nav>
  <a href="donate.html" class="mobile-drawer__cta">Пожертвовать</a>
  <div class="mobile-drawer__contact">
    <p>ул. Постовая, 26 · Краснодар</p>
    <p><a href="tel:+78612620020">+7 (861) 262-00-20</a></p>
    <p><a href="mailto:nevskiy-sobor@mail.ru">nevskiy-sobor@mail.ru</a></p>
  </div>
</div>

<script src="assets/js/main.js" defer></script>
'''

CTA_RE = re.compile(
    r'(<a\s+[^>]*href="donate\.html"[^>]*class="uheader__cta"[^>]*>)',
    re.IGNORECASE,
)

def transform(html: str, filename: str) -> str:
    # 1) Inject site-additions.css just before </head>
    if "site-additions.css" not in html:
        html = html.replace(
            "</head>",
            '<link rel="stylesheet" href="assets/css/site-additions.css">\n</head>',
            1,
        )

    # 2) Insert menu-trigger button before .uheader__cta
    if 'class="menu-trigger"' not in html:
        m = CTA_RE.search(html)
        if m:
            html = html[:m.start()] + TRIGGER + "\n    " + html[m.start():]

    # 3) Append drawer + main.js before </body>
    if 'class="mobile-drawer"' not in html:
        drawer = make_drawer(filename)
        html = html.replace("</body>", drawer + "</body>", 1)

    return html

def main() -> None:
    files = sorted(DOCS.glob("*.html"))
    if not files:
        print(f"No HTML files in {DOCS}")
        return
    for fp in files:
        original = fp.read_text(encoding="utf-8")
        updated = transform(original, fp.name)
        if updated != original:
            fp.write_text(updated, encoding="utf-8")
            print(f"OK   {fp.name}")
        else:
            print(f"--   {fp.name} (no changes)")

if __name__ == "__main__":
    main()
