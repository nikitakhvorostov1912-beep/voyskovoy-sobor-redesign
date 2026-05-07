# -*- coding: utf-8 -*-
"""Аудитор ссылок и навигации для статичного сайта собора.

Проверяет:
1. Битые внутренние ссылки (.html файлы существуют?)
2. Битые якоря (#section существует на странице?)
3. Согласованность header navigation между всеми страницами
4. Согласованность mobile drawer между всеми страницами
5. Согласованность footer ссылок между всеми страницами
6. is-active правильно поставлен (только на текущей странице)
7. Дубликаты id на одной странице
8. tel: / mailto: формат
9. Формы (action / method)
10. Внешние ссылки (https) — собрать список
"""
import re
import sys
from pathlib import Path
from collections import defaultdict, Counter

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")
HTML_FILES = sorted(DOCS.glob("*.html"))
HTML_NAMES = {f.name for f in HTML_FILES}

# ---------- helpers ----------
RE_HREF = re.compile(r'href\s*=\s*"([^"]+)"', re.IGNORECASE)
RE_SRC = re.compile(r'src\s*=\s*"([^"]+)"', re.IGNORECASE)
RE_ID = re.compile(r'\sid\s*=\s*"([^"]+)"', re.IGNORECASE)
RE_FORM = re.compile(r'<form\b([^>]*)>', re.IGNORECASE)
RE_ACTION = re.compile(r'action\s*=\s*"([^"]*)"', re.IGNORECASE)
RE_METHOD = re.compile(r'method\s*=\s*"([^"]*)"', re.IGNORECASE)

# Header nav block — between <nav class="uheader__nav"> and </nav>
RE_HEADER_NAV = re.compile(
    r'<nav[^>]*class="[^"]*uheader__nav[^"]*"[^>]*>(.*?)</nav>',
    re.IGNORECASE | re.DOTALL,
)
# Mobile drawer nav block
RE_DRAWER_NAV = re.compile(
    r'<nav[^>]*class="[^"]*mobile-drawer__nav[^"]*"[^>]*>(.*?)</nav>',
    re.IGNORECASE | re.DOTALL,
)
# Footer block
RE_FOOTER = re.compile(r'<footer\b[^>]*>(.*?)</footer>', re.IGNORECASE | re.DOTALL)
# CTA in header — атрибуты могут идти в любом порядке
RE_HEADER_CTA_TAG = re.compile(
    r'<a\b[^>]*class="[^"]*uheader__cta[^"]*"[^>]*>([^<]*)</a>',
    re.IGNORECASE,
)
RE_HREF_INLINE = re.compile(r'href="([^"]+)"', re.IGNORECASE)
# is-active marker
RE_IS_ACTIVE = re.compile(
    r'<a[^>]*href="([^"]+)"[^>]*class="[^"]*is-active[^"]*"',
    re.IGNORECASE,
)
RE_ARIA_CURRENT = re.compile(
    r'<a[^>]*href="([^"]+)"[^>]*aria-current="page"',
    re.IGNORECASE,
)

# ---------- per-file analysis ----------
def analyze(file: Path) -> dict:
    text = file.read_text(encoding="utf-8", errors="replace")
    name = file.name

    # All hrefs
    hrefs = RE_HREF.findall(text)
    srcs = RE_SRC.findall(text)
    ids = RE_ID.findall(text)

    # Local .html links
    local_html = [h for h in hrefs if re.match(r'^[a-z0-9_-]+\.html(#.*)?$', h, re.I)]
    # Anchor only (#x)
    pure_anchors = [h for h in hrefs if h.startswith("#") and h != "#"]
    # External
    external = [h for h in hrefs if re.match(r'^https?://', h, re.I)]
    # tel:
    tel_links = [h for h in hrefs if h.startswith("tel:")]
    # mailto:
    mailto_links = [h for h in hrefs if h.startswith("mailto:")]
    # Asset links (assets/...)
    assets = [h for h in hrefs if h.startswith("assets/") or h.startswith("./assets/")]
    # Empty / placeholder
    placeholders = [h for h in hrefs if h in ("", "#", "javascript:void(0)")]

    # Forms
    forms = []
    for m in RE_FORM.finditer(text):
        attrs = m.group(1)
        action = RE_ACTION.search(attrs)
        method = RE_METHOD.search(attrs)
        forms.append({
            "action": action.group(1) if action else None,
            "method": method.group(1) if method else None,
            "raw": m.group(0)[:200],
        })

    # Header nav
    header_nav_match = RE_HEADER_NAV.search(text)
    header_nav_links = []
    if header_nav_match:
        header_nav_links = RE_HREF.findall(header_nav_match.group(1))

    # Drawer nav
    drawer_nav_match = RE_DRAWER_NAV.search(text)
    drawer_nav_links = []
    if drawer_nav_match:
        drawer_nav_links = RE_HREF.findall(drawer_nav_match.group(1))

    # Footer
    footer_match = RE_FOOTER.search(text)
    footer_links = []
    if footer_match:
        footer_links = RE_HREF.findall(footer_match.group(1))

    # CTA in header — найти тег с class=uheader__cta, потом извлечь href отдельно
    cta_data = None
    for m in RE_HEADER_CTA_TAG.finditer(text):
        tag_text = m.group(0)
        href_m = RE_HREF_INLINE.search(tag_text)
        if href_m:
            cta_data = (href_m.group(1), m.group(1).strip())
            break

    # is-active and aria-current
    is_active_hrefs = RE_IS_ACTIVE.findall(text)
    aria_current_hrefs = RE_ARIA_CURRENT.findall(text)

    # Duplicate IDs
    id_counter = Counter(ids)
    duplicate_ids = {k: v for k, v in id_counter.items() if v > 1}

    return {
        "name": name,
        "hrefs": hrefs,
        "srcs": srcs,
        "ids": ids,
        "duplicate_ids": duplicate_ids,
        "local_html": local_html,
        "pure_anchors": pure_anchors,
        "external": external,
        "tel_links": tel_links,
        "mailto_links": mailto_links,
        "assets": assets,
        "placeholders": placeholders,
        "forms": forms,
        "header_nav_links": header_nav_links,
        "drawer_nav_links": drawer_nav_links,
        "footer_links": footer_links,
        "cta": cta_data,
        "is_active_hrefs": is_active_hrefs,
        "aria_current_hrefs": aria_current_hrefs,
    }


def check_anchor_target(file_data: dict, anchor: str) -> bool:
    """Check if #anchor exists as id="anchor" on the same page."""
    target = anchor.lstrip("#")
    if not target:
        return True  # # alone is "go to top", valid
    return target in set(file_data["ids"])


# ---------- aggregation ----------
def main() -> None:
    print("=" * 70)
    print("АУДИТ ССЫЛОК — Войсковой Собор Александра Невского")
    print("=" * 70)

    results = {f.name: analyze(f) for f in HTML_FILES}
    issues = []  # (severity, file, message)

    # 1. Битые .html ссылки
    print("\n[1] БИТЫЕ ВНУТРЕННИЕ ССЫЛКИ (.html не существует):")
    found_broken = False
    for name, d in results.items():
        for href in d["local_html"]:
            target = href.split("#")[0]
            if target not in HTML_NAMES:
                msg = f"  {name}: <a href=\"{href}\"> -> {target} НЕ СУЩЕСТВУЕТ"
                print(msg)
                issues.append(("CRITICAL", name, f"Broken link: {href}"))
                found_broken = True
    if not found_broken:
        print("  OK — все .html ссылки указывают на существующие файлы")

    # 2. Битые якоря (#section)
    print("\n[2] БИТЫЕ ЯКОРЯ (#section не найдены на странице):")
    found_anchor_issue = False
    for name, d in results.items():
        for href in d["pure_anchors"]:
            if not check_anchor_target(d, href):
                msg = f"  {name}: <a href=\"{href}\"> — id={href[1:]!r} НЕ найден"
                print(msg)
                issues.append(("HIGH", name, f"Broken anchor: {href}"))
                found_anchor_issue = True
        # Cross-page anchors (page.html#section)
        for href in d["local_html"]:
            if "#" in href:
                target_file, anchor = href.split("#", 1)
                if target_file in results:
                    target_data = results[target_file]
                    if anchor and anchor not in set(target_data["ids"]):
                        msg = f"  {name}: <a href=\"{href}\"> — id={anchor!r} нет на {target_file}"
                        print(msg)
                        issues.append(("HIGH", name, f"Cross-page anchor: {href} -> id={anchor} missing"))
                        found_anchor_issue = True
    if not found_anchor_issue:
        print("  OK — все якоря резолвятся")

    # 3. Header navigation согласованность
    print("\n[3] СОГЛАСОВАННОСТЬ HEADER NAVIGATION:")
    nav_signatures = {name: tuple(d["header_nav_links"]) for name, d in results.items()}
    # Группируем по сигнатуре
    sig_groups = defaultdict(list)
    for name, sig in nav_signatures.items():
        sig_groups[sig].append(name)

    if len(sig_groups) == 1:
        print(f"  OK — одинаковый header на всех {len(HTML_FILES)} страницах")
        sig = next(iter(sig_groups.keys()))
        print(f"  Состав ({len(sig)} ссылок): {list(sig)}")
    else:
        print(f"  ПРОБЛЕМА — найдено {len(sig_groups)} разных вариантов header:")
        for sig, files in sig_groups.items():
            print(f"\n  Вариант ({len(sig)} ссылок) на страницах: {files}")
            print(f"    {list(sig)}")
            issues.append(("HIGH", ", ".join(files), f"Header nav variant: {len(sig)} links"))

    # 4. Drawer navigation согласованность
    print("\n[4] СОГЛАСОВАННОСТЬ MOBILE DRAWER NAVIGATION:")
    drawer_signatures = {name: tuple(d["drawer_nav_links"]) for name, d in results.items()}
    drawer_groups = defaultdict(list)
    for name, sig in drawer_signatures.items():
        drawer_groups[sig].append(name)

    if len(drawer_groups) == 1:
        print(f"  OK — одинаковый drawer на всех {len(HTML_FILES)} страницах")
        sig = next(iter(drawer_groups.keys()))
        print(f"  Состав ({len(sig)} ссылок): {list(sig)}")
    else:
        print(f"  ПРОБЛЕМА — найдено {len(drawer_groups)} вариантов drawer:")
        for sig, files in drawer_groups.items():
            print(f"\n  Вариант ({len(sig)} ссылок) на страницах: {files}")
            print(f"    {list(sig)}")
            issues.append(("HIGH", ", ".join(files), f"Drawer nav variant: {len(sig)} links"))

    # 5. Header == Drawer (должны совпадать по составу)
    print("\n[5] HEADER == DRAWER (одинаковый состав ссылок?):")
    for name in results:
        h = set(results[name]["header_nav_links"])
        d = set(results[name]["drawer_nav_links"])
        if h != d:
            only_h = h - d
            only_d = d - h
            msg = f"  {name}: расхождение — только в header: {only_h}, только в drawer: {only_d}"
            print(msg)
            issues.append(("MEDIUM", name, f"Header/drawer mismatch: only_h={only_h}, only_d={only_d}"))

    # 6. CTA в header
    print("\n[6] HEADER CTA (Пожертвовать):")
    for name, d in results.items():
        if d["cta"] is None:
            print(f"  {name}: CTA НЕ НАЙДЕНА")
            issues.append(("HIGH", name, "Missing header CTA"))
        else:
            href, text = d["cta"]
            if href != "donate.html":
                print(f"  {name}: CTA href={href!r} (ожидалось donate.html)")
                issues.append(("HIGH", name, f"Wrong CTA href: {href}"))

    # 7. is-active правильно?
    print("\n[7] is-active / aria-current=\"page\" правильно расставлены?")
    for name, d in results.items():
        active = set(d["is_active_hrefs"]) | set(d["aria_current_hrefs"])
        # Ожидаем: is-active указывает на текущий файл
        expected = name
        # Index — особый случай
        if not active:
            print(f"  {name}: НЕТ is-active маркера (нет подсветки текущей страницы)")
            issues.append(("MEDIUM", name, "No is-active marker on current page"))
        else:
            wrong = [a for a in active if a != expected]
            if wrong:
                print(f"  {name}: is-active указывает на {wrong} (должно на {expected})")
                issues.append(("HIGH", name, f"Wrong is-active: {wrong}"))

    # 8. Дубликаты id
    print("\n[8] ДУБЛИКАТЫ id НА СТРАНИЦЕ:")
    found_dup = False
    for name, d in results.items():
        if d["duplicate_ids"]:
            print(f"  {name}: {d['duplicate_ids']}")
            for k, v in d["duplicate_ids"].items():
                issues.append(("MEDIUM", name, f"Duplicate id={k!r} ({v} times)"))
            found_dup = True
    if not found_dup:
        print("  OK")

    # 9. Footer ссылки
    print("\n[9] FOOTER NAVIGATION:")
    footer_sigs = {name: tuple(d["footer_links"]) for name, d in results.items()}
    footer_groups = defaultdict(list)
    for name, sig in footer_sigs.items():
        footer_groups[sig].append(name)

    if len(footer_groups) == 1:
        sig = next(iter(footer_groups.keys()))
        print(f"  OK — одинаковый footer ({len(sig)} ссылок)")
    else:
        print(f"  Найдено {len(footer_groups)} вариантов footer:")
        for sig, files in footer_groups.items():
            print(f"\n  Вариант ({len(sig)} ссылок) на: {files}")
            for s in sig[:20]:
                print(f"    - {s}")
            issues.append(("LOW", ", ".join(files), f"Footer variant: {len(sig)} links"))

    # 10. Формы
    print("\n[10] ФОРМЫ:")
    for name, d in results.items():
        for f in d["forms"]:
            action = f["action"] or "(нет)"
            method = f["method"] or "(нет)"
            print(f"  {name}: <form action={action!r} method={method!r}>")
            if f["action"] is None:
                issues.append(("HIGH", name, "Form without action attribute"))
            if f["method"] is None:
                issues.append(("MEDIUM", name, "Form without method attribute"))

    # 11. Tel / Mailto формат
    print("\n[11] TEL / MAILTO ССЫЛКИ:")
    all_tel = set()
    all_mail = set()
    for name, d in results.items():
        for t in d["tel_links"]:
            all_tel.add(t)
            # Базовая валидация: tel:+... или tel:8...
            if not re.match(r'^tel:[\+\d\s\-()]+$', t):
                print(f"  {name}: подозрительный tel={t!r}")
                issues.append(("MEDIUM", name, f"Suspicious tel: {t}"))
        for m in d["mailto_links"]:
            all_mail.add(m)
            if not re.match(r'^mailto:[^\s@]+@[^\s@]+\.[^\s@]+', m):
                print(f"  {name}: подозрительный mailto={m!r}")
                issues.append(("MEDIUM", name, f"Suspicious mailto: {m}"))
    print(f"\n  Уникальные tel: {sorted(all_tel)}")
    print(f"  Уникальные mailto: {sorted(all_mail)}")
    if len(all_tel) > 1:
        print(f"  ⚠ найдено {len(all_tel)} разных номеров — проверить")
        issues.append(("MEDIUM", "(global)", f"Multiple tel: {all_tel}"))
    if len(all_mail) > 1:
        print(f"  ⚠ найдено {len(all_mail)} разных email — проверить")
        issues.append(("MEDIUM", "(global)", f"Multiple mailto: {all_mail}"))

    # 12. Внешние ссылки (для ручной проверки)
    print("\n[12] ВНЕШНИЕ ССЫЛКИ (https://):")
    all_external = set()
    for name, d in results.items():
        for h in d["external"]:
            all_external.add(h)
    for h in sorted(all_external):
        print(f"  {h}")

    # 13. Placeholder ссылки (#, javascript:void(0), пустые)
    print("\n[13] ПУСТЫЕ / PLACEHOLDER ССЫЛКИ:")
    for name, d in results.items():
        if d["placeholders"]:
            print(f"  {name}: {d['placeholders']}")
            for p in d["placeholders"]:
                issues.append(("LOW", name, f"Placeholder href: {p!r}"))

    # 14. Битые asset ссылки (assets/...)
    print("\n[14] БИТЫЕ ASSET ССЫЛКИ:")
    found_asset_issue = False
    for name, d in results.items():
        for src in d["assets"]:
            # remove query string / fragment
            clean = src.split("?")[0].split("#")[0]
            asset_path = DOCS / clean
            if not asset_path.exists():
                print(f"  {name}: <{src}> — файл не существует")
                issues.append(("HIGH", name, f"Missing asset: {src}"))
                found_asset_issue = True
    # Также проверим src= (img, script)
    for name, d in results.items():
        for src in d["srcs"]:
            if src.startswith("http") or src.startswith("//"):
                continue
            clean = src.split("?")[0].split("#")[0]
            asset_path = DOCS / clean
            if not asset_path.exists():
                print(f"  {name}: <... src={src!r}> — файл не существует")
                issues.append(("HIGH", name, f"Missing src: {src}"))
                found_asset_issue = True
    if not found_asset_issue:
        print("  OK")

    # ---------- Итоговая сводка ----------
    print("\n" + "=" * 70)
    print("СВОДКА")
    print("=" * 70)
    by_sev = Counter(i[0] for i in issues)
    print(f"  CRITICAL: {by_sev['CRITICAL']}")
    print(f"  HIGH:     {by_sev['HIGH']}")
    print(f"  MEDIUM:   {by_sev['MEDIUM']}")
    print(f"  LOW:      {by_sev['LOW']}")
    print(f"\n  Всего: {len(issues)}")

    if issues:
        print("\n--- ДЕТАЛИ ---")
        for sev in ("CRITICAL", "HIGH", "MEDIUM", "LOW"):
            sev_issues = [i for i in issues if i[0] == sev]
            if sev_issues:
                print(f"\n[{sev}]")
                for s, f, m in sev_issues:
                    print(f"  {f}: {m}")


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    main()
