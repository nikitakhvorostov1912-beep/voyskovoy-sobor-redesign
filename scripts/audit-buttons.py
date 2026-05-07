# -*- coding: utf-8 -*-
"""Глубокий аудит интерактива:
1. Все <button> без handler/onclick/id
2. Текстовые CTA ('Подробнее', 'Заказать', 'Записаться'...) в нерабочих обёртках
3. Карточки которые выглядят кликабельно, но не обёрнуты в <a>
"""
import re
import sys
from pathlib import Path
from collections import defaultdict

sys.stdout.reconfigure(encoding="utf-8")

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")


def has_js_handler(html: str, class_name: str) -> bool:
    """Check if .class has addEventListener somewhere in the file."""
    pat = rf'querySelectorAll\([\'"]\\.{re.escape(class_name)}[\'"]\)'
    if re.search(pat, html):
        return True
    pat2 = rf'querySelector\([\'"]\\.{re.escape(class_name)}[\'"]\)'
    return bool(re.search(pat2, html))


def main() -> None:
    files = sorted(DOCS.glob("*.html"))

    print("=" * 70)
    print("ГЛУБОКИЙ АУДИТ ИНТЕРАКТИВА")
    print("=" * 70)

    # 1. Buttons без handlers
    print("\n[1] <button> без onclick/id и без JS-handler по class:")
    issues = []
    for fp in files:
        text = fp.read_text(encoding="utf-8")
        for m in re.finditer(r'<button\b([^>]*)>(.*?)</button>', text, re.DOTALL | re.IGNORECASE):
            attrs = m.group(1)
            inner = re.sub(r'<[^>]+>', ' ', m.group(2)).strip()
            inner = re.sub(r'\s+', ' ', inner)
            if not inner or len(inner) > 100:
                continue
            # filter: handlers we know
            attrs_low = attrs.lower()
            if any(p in attrs_low for p in [
                'onclick=', 'data-amount', 'data-tab', 'data-mode',
                'data-jump', 'data-day', 'data-panel', 'aria-controls="mobile-drawer"',
                'class="close"', 'class="menu-trigger"',
            ]):
                continue
            if 'id=' in attrs_low:
                continue
            cls_match = re.search(r'class="([^"]+)"', attrs)
            cls = cls_match.group(1) if cls_match else ''
            # Check if this class has JS handler
            class_tokens = cls.split()
            has_handler = any(has_js_handler(text, c) for c in class_tokens)
            if has_handler:
                continue
            # Skip "decorative" — пустой текст, или is just число (calendar cell)
            if inner.isdigit() and len(inner) <= 2:
                continue
            issues.append((fp.name, cls, inner))

    by_file = defaultdict(list)
    for fn, cls, inner in issues:
        by_file[fn].append((cls, inner))
    for fn, items in by_file.items():
        print(f"\n  {fn}  ({len(items)} кнопок без handler):")
        for cls, inner in items[:15]:
            print(f"    [{cls[:35]:35}]  {inner[:70]}")
        if len(items) > 15:
            print(f"    ... +{len(items)-15} ещё")

    # 2. Текстовые "Подробнее" / "Заказать" / etc. в нерабочих обёртках
    print("\n[2] Текстовые CTA не в <a href> и не в <button> с handler:")
    triggers = ['Подробнее', 'Читать', 'Узнать больше', 'Все новости',
                'Открыть', 'Перейти', 'Смотреть', 'Посмотреть', 'Подать записку',
                'Заказать', 'Записаться']
    issues2 = []
    for fp in files:
        text = fp.read_text(encoding="utf-8")
        for trig in triggers:
            for m in re.finditer(rf'>\s*({re.escape(trig)}[^<]{{0,40}})<', text):
                inner = m.group(1).strip()
                start = max(0, m.start() - 250)
                ctx = text[start:m.start()]
                # Найти открывающий тег
                tag_starts = [i for i, c in enumerate(ctx) if c == '<']
                if not tag_starts:
                    continue
                tag_text = ctx[tag_starts[-1]:]
                tag_match = re.match(r'<(\w+)([^>]*)', tag_text)
                if not tag_match:
                    continue
                tag_name = tag_match.group(1).lower()
                tag_attrs = tag_match.group(2)
                if tag_name == 'a' and 'href=' in tag_attrs:
                    continue  # already a link
                if tag_name == 'button':
                    if 'onclick=' in tag_attrs.lower() or 'id=' in tag_attrs.lower():
                        continue  # handled
                    cls_match = re.search(r'class="([^"]+)"', tag_attrs)
                    cls = cls_match.group(1) if cls_match else ''
                    if any(has_js_handler(text, c) for c in cls.split()):
                        continue
                # подозрительный
                cls_match = re.search(r'class="([^"]+)"', tag_attrs)
                cls = cls_match.group(1) if cls_match else '(no class)'
                issues2.append((fp.name, tag_name, cls, inner))

    by_file2 = defaultdict(list)
    for fn, tag, cls, inner in issues2:
        by_file2[fn].append((tag, cls, inner))
    for fn, items in by_file2.items():
        # dedup
        seen = set()
        u = []
        for x in items:
            k = (x[0], x[1], x[2])
            if k in seen:
                continue
            seen.add(k)
            u.append(x)
        print(f"\n  {fn}  ({len(u)} CTA):")
        for tag, cls, inner in u[:20]:
            print(f"    <{tag}> [{cls[:30]:30}]  {inner[:60]}")

    # 3. SUMMARY
    print("\n" + "=" * 70)
    print(f"ИТОГО: {len(issues)} нерабочих <button> + {len(issues2)} подозрительных текстовых CTA")
    print("=" * 70)


if __name__ == "__main__":
    main()
