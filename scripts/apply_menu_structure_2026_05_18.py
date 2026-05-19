"""
Пересборка структуры меню по эскизу заказчика (photo_2).

Целевая структура (6 пунктов):
  1. Главная
  2. О соборе ▾
      ├ История
      ├ Святыни
      └ Духовенство
  3. Расписание
  4. Богослужения  (новая страница services.html)
  5. Жизнь собора  (бывший parish-life.html → переименован пункт меню)
  6. Контакты

Изменения относительно текущего меню:
  · Заказ треб (prayer-requests.html)  → убран из верхнего меню
  · Новости (news.html)                → убран из верхнего меню
  · Духовенство (clergy.html)          → перенесён в подменю «О соборе»
  · Приход (parish-life.html)          → переименован в «Жизнь собора»
  · Богослужения (services.html)       → добавлен новый пункт между Расписанием и Жизнью собора

Mobile drawer обновляется аналогично — порядок и набор пунктов синхронизирован
с верхним меню, подпункты «О соборе» помечены классом mobile-drawer__sub
(стилизация добавлена в site-additions.css).

Идемпотентно. Запуск:
    python scripts/apply_menu_structure_2026_05_18.py
"""
from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DOCS = ROOT / "docs"


# ---------------------------------------------------------------------------
# 1. Верхнее меню (uheader__nav)
# ---------------------------------------------------------------------------

# Регексп ловит весь блок <nav class="uheader__nav" ...> ... </nav>
HEADER_NAV_RE = re.compile(
    r'<nav class="uheader__nav"[^>]*>.*?</nav>',
    re.DOTALL,
)

# Шаблон. {ACTIVE_<page>} раскрываются ниже под каждую страницу.
HEADER_NAV_TMPL = '''<nav class="uheader__nav" aria-label="Главная навигация">
      <a href="index.html"{ACTIVE_index}>Главная</a>
      <div class="uheader__nav-group has-submenu">
        <a href="about.html" class="uheader__nav-trigger{ACTIVE_trigger}"{TRIGGER_ARIA}><span class="uheader__nav-trigger-label">О соборе</span><svg class="uheader__caret" width="9" height="9" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <ul class="uheader__submenu" role="menu">
          <li><a href="history.html"{ACTIVE_history}>История</a></li>
          <li><a href="icons.html"{ACTIVE_icons}>Святыни</a></li>
          <li><a href="clergy.html"{ACTIVE_clergy}>Духовенство</a></li>
        </ul>
      </div>
      <a href="schedule.html"{ACTIVE_schedule}>Расписание</a>
      <a href="services.html"{ACTIVE_services}>Богослужения</a>
      <a href="parish-life.html"{ACTIVE_parish}>Жизнь собора</a>
      <a href="contacts.html"{ACTIVE_contacts}>Контакты</a>
    </nav>'''


# ---------------------------------------------------------------------------
# 2. Mobile drawer (mobile-drawer__nav)
# ---------------------------------------------------------------------------

MOBILE_NAV_RE = re.compile(
    r'<nav class="mobile-drawer__nav"[^>]*>.*?</nav>',
    re.DOTALL,
)

MOBILE_NAV_TMPL = '''<nav class="mobile-drawer__nav" aria-label="Главная навигация (мобильная)">
      <a href="index.html"{ACTIVE_index}>Главная</a>
      <a href="about.html"{ACTIVE_about}>О соборе</a>
      <a href="history.html" class="mobile-drawer__sub{ACTIVE_history_class}"{ACTIVE_history_cur}>История</a>
      <a href="icons.html" class="mobile-drawer__sub{ACTIVE_icons_class}"{ACTIVE_icons_cur}>Святыни</a>
      <a href="clergy.html" class="mobile-drawer__sub{ACTIVE_clergy_class}"{ACTIVE_clergy_cur}>Духовенство</a>
      <a href="schedule.html"{ACTIVE_schedule}>Расписание</a>
      <a href="services.html"{ACTIVE_services}>Богослужения</a>
      <a href="parish-life.html"{ACTIVE_parish}>Жизнь собора</a>
      <a href="contacts.html"{ACTIVE_contacts}>Контакты</a>
  </nav>'''


# Какие страницы соответствуют каким пунктам:
#   filename → { какие "ACTIVE_*" заполнить }
def active_attrs_for(page: str) -> dict[str, str]:
    """Возвращает словарь подстановок для шаблона nav."""
    # все базовые ключи — пустые
    keys = [
        "ACTIVE_index", "ACTIVE_about", "ACTIVE_trigger",
        "ACTIVE_history", "ACTIVE_icons", "ACTIVE_clergy",
        "ACTIVE_schedule", "ACTIVE_services", "ACTIVE_parish", "ACTIVE_contacts",
        "TRIGGER_ARIA",
        # mobile-only
        "ACTIVE_history_class", "ACTIVE_icons_class", "ACTIVE_clergy_class",
        "ACTIVE_history_cur", "ACTIVE_icons_cur", "ACTIVE_clergy_cur",
    ]
    d = {k: "" for k in keys}
    d["TRIGGER_ARIA"] = ' aria-haspopup="true" aria-expanded="false"'

    active_attr = ' class="is-active" aria-current="page"'
    submenu_active_class = " is-active"
    submenu_active_cur = ' aria-current="page"'

    if page == "index.html":
        d["ACTIVE_index"] = active_attr
    elif page == "about.html":
        # «О соборе» как корень группы — подсветка триггера через класс is-active
        d["ACTIVE_trigger"] = " is-active"
        d["TRIGGER_ARIA"] = ' aria-haspopup="true" aria-expanded="false" aria-current="page"'
        d["ACTIVE_about"] = active_attr
    elif page == "history.html":
        d["ACTIVE_trigger"] = " is-active"
        d["ACTIVE_history"] = active_attr
        d["ACTIVE_history_class"] = submenu_active_class
        d["ACTIVE_history_cur"] = submenu_active_cur
    elif page == "icons.html":
        d["ACTIVE_trigger"] = " is-active"
        d["ACTIVE_icons"] = active_attr
        d["ACTIVE_icons_class"] = submenu_active_class
        d["ACTIVE_icons_cur"] = submenu_active_cur
    elif page == "clergy.html":
        d["ACTIVE_trigger"] = " is-active"
        d["ACTIVE_clergy"] = active_attr
        d["ACTIVE_clergy_class"] = submenu_active_class
        d["ACTIVE_clergy_cur"] = submenu_active_cur
    elif page == "schedule.html":
        d["ACTIVE_schedule"] = active_attr
    elif page == "services.html":
        d["ACTIVE_services"] = active_attr
    elif page == "parish-life.html":
        d["ACTIVE_parish"] = active_attr
    elif page == "contacts.html":
        d["ACTIVE_contacts"] = active_attr
    # для остальных (404, donate, prayer-requests, news, privacy) — без активного пункта
    return d


def patch_file(path: Path) -> bool:
    src = path.read_text(encoding="utf-8")
    page = path.name
    d = active_attrs_for(page)

    new_header = HEADER_NAV_TMPL.format(**d)
    new_mobile = MOBILE_NAV_TMPL.format(**d)

    out = HEADER_NAV_RE.sub(new_header.replace("\\", r"\\"), src, count=1)
    out = MOBILE_NAV_RE.sub(new_mobile.replace("\\", r"\\"), out, count=1)
    if out == src:
        return False
    path.write_text(out, encoding="utf-8")
    return True


def main() -> int:
    changed = []
    for f in sorted(DOCS.glob("*.html")):
        if patch_file(f):
            changed.append(f.name)
    print(f"Меню обновлено в {len(changed)} файлах:")
    for f in changed:
        print(f"  · {f}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
