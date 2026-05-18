"""
Замена эмблемы в шапке сайта на полный логотип «Войсковой собор Александра Невского».

- В header (`uheader__sigil`) — SVG-щит заменяется на <img> с логотипом.
- В footer (`usite-footer__sigil`) — остаётся премиальный SVG-щит без изменений
  (чтобы не дублировать одинаковую графику в шапке и в подвале).

Идемпотентно. Запуск:
    python scripts/apply_logo_header_2026_05_18.py
"""
from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DOCS = ROOT / "docs"

# Регексп ловит блок:
#   <svg class="uheader__sigil" ...>
#     ... 4 строки SVG ...
#   </svg>
HEADER_SIGIL_RE = re.compile(
    r'<svg class="uheader__sigil"[^>]*>.*?</svg>',
    re.DOTALL,
)

NEW_HEADER_LOGO = (
    '<img class="uheader__sigil" '
    'src="assets/images/brand/logo-sobor-small.jpg" '
    'alt="Войсковой собор Александра Невского — Краснодар" '
    'width="54" height="48" loading="eager" decoding="async">'
)

MARKER = 'class="uheader__sigil" src='


def patch_html(path: Path) -> bool:
    src = path.read_text(encoding="utf-8")
    if MARKER in src:
        return False  # уже заменено
    new = HEADER_SIGIL_RE.sub(NEW_HEADER_LOGO, src, count=1)
    if new == src:
        return False
    path.write_text(new, encoding="utf-8")
    return True


def main() -> int:
    changed = []
    for html in sorted(DOCS.glob("*.html")):
        if patch_html(html):
            changed.append(html.name)
    if changed:
        print("✓ Заменён header-логотип в файлах:")
        for f in changed:
            print(f"  · {f}")
    else:
        print("· Логотип в header уже актуальный (no-op)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
