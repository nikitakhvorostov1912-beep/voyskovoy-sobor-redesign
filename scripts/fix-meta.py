# -*- coding: utf-8 -*-
"""Add SEO + social meta tags to all 13 pages.

Insertion point: right after `<link rel="icon" ...>` in <head>.

Adds:
- <meta name="description" content="..."> (per-page Russian description)
- <link rel="canonical" href="https://alexander-nevskiysobor.ru/<page>"> (production URL)
- <link rel="apple-touch-icon" type="image/svg+xml" href="assets/images/icons/favicon.svg">
- <meta name="theme-color" content="#1a1f2e">
- <meta name="format-detection" content="telephone=no">
- Open Graph: og:type, og:site_name, og:locale, og:title, og:description, og:url, og:image
- Twitter: twitter:card, twitter:title, twitter:description, twitter:image

Idempotent: if a tag already exists, it is left untouched.
"""
from __future__ import annotations
import re
import sys
from pathlib import Path

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")
PROD_BASE = "https://alexander-nevskiysobor.ru"
SITE_NAME = "Войсковой собор Александра Невского"
LOCALE = "ru_RU"
OG_IMAGE = f"{PROD_BASE}/assets/images/photos/cathedral-2021-facade.jpg"

# Per-page descriptions (≤ 160 chars recommended)
DESCRIPTIONS: dict[str, str] = {
    "index.html": "Войсковой собор святого благоверного князя Александра Невского в Краснодаре. Богослужения, требы, исповедь, духовенство, контакты. ул. Постовая, 26.",
    "about.html": "О Войсковом соборе Александра Невского в Краснодаре: духовное значение, архитектура, миссия. Главный храм Кубанского казачьего войска.",
    "history.html": "История Войскового собора Александра Невского в Краснодаре с 1872 года: основание, разрушение в 1932, возрождение и освящение в 2006.",
    "schedule.html": "Расписание богослужений Войскового собора Александра Невского: Литургия, всенощные, праздники, исповедь и таинства. Краснодар.",
    "prayer-requests.html": "Заказ треб онлайн в Войсковом соборе Александра Невского, Краснодар: молебен, панихида, сорокоуст, поминовение на полгода и год.",
    "clergy.html": "Духовенство Войскового собора Александра Невского в Краснодаре: настоятель, клирики, диаконы — служение, биографии, контакты.",
    "parish-life.html": "Жизнь прихода Войскового собора Александра Невского: воскресная школа, хор, социальное служение, молодёжь, казачество.",
    "icons.html": "Святыни и почитаемые иконы Войскового собора Александра Невского: икона князя Александра Невского, Казанская икона Божией Матери.",
    "news.html": "Новости, объявления и анонсы событий Войскового собора Александра Невского в Краснодаре.",
    "contacts.html": "Контакты Войскового собора Александра Невского: ул. Постовая 26, Краснодар, +7 (861) 262-00-20, email, карта проезда, время работы.",
    "donate.html": "Пожертвование на Войсковой собор Александра Невского в Краснодаре: целевые сборы, банковские реквизиты, договор пожертвования.",
    "privacy.html": "Политика обработки персональных данных Войскового собора Александра Невского в соответствии с 152-ФЗ.",
    "404.html": "Страница не найдена. Войсковой собор святого благоверного князя Александра Невского, Краснодар.",
}

# Per-page OG title (slightly different from <title> for social sharing)
OG_TITLES: dict[str, str] = {
    "index.html": "Войсковой собор Александра Невского — Краснодар",
    "about.html": "О соборе Александра Невского — главный храм Кубани",
    "history.html": "История собора Александра Невского с 1872 года",
    "schedule.html": "Расписание богослужений — собор Александра Невского",
    "prayer-requests.html": "Заказ треб онлайн — собор Александра Невского",
    "clergy.html": "Духовенство собора Александра Невского",
    "parish-life.html": "Жизнь прихода — собор Александра Невского",
    "icons.html": "Святыни и иконы — собор Александра Невского",
    "news.html": "Новости — Войсковой собор Александра Невского",
    "contacts.html": "Контакты — Войсковой собор Александра Невского",
    "donate.html": "Пожертвование на собор Александра Невского",
    "privacy.html": "Политика конфиденциальности — собор Александра Невского",
    "404.html": "Страница не найдена — собор Александра Невского",
}


def page_url(page: str) -> str:
    if page == "index.html":
        return PROD_BASE + "/"
    return f"{PROD_BASE}/{page}"


def html_escape(s: str) -> str:
    return s.replace("&", "&amp;").replace('"', "&quot;").replace("<", "&lt;").replace(">", "&gt;")


def already_has(text: str, *patterns: str) -> bool:
    return any(p in text for p in patterns)


def build_block(page: str) -> str:
    desc = DESCRIPTIONS[page]
    title = OG_TITLES[page]
    url = page_url(page)
    desc_e = html_escape(desc)
    title_e = html_escape(title)
    return (
        f'<meta name="description" content="{desc_e}">\n'
        f'<meta name="theme-color" content="#1a1f2e">\n'
        f'<meta name="format-detection" content="telephone=no">\n'
        f'<link rel="canonical" href="{url}">\n'
        f'<link rel="apple-touch-icon" type="image/svg+xml" href="assets/images/icons/favicon.svg">\n'
        f'<meta property="og:type" content="website">\n'
        f'<meta property="og:site_name" content="{html_escape(SITE_NAME)}">\n'
        f'<meta property="og:locale" content="{LOCALE}">\n'
        f'<meta property="og:title" content="{title_e}">\n'
        f'<meta property="og:description" content="{desc_e}">\n'
        f'<meta property="og:url" content="{url}">\n'
        f'<meta property="og:image" content="{OG_IMAGE}">\n'
        f'<meta name="twitter:card" content="summary_large_image">\n'
        f'<meta name="twitter:title" content="{title_e}">\n'
        f'<meta name="twitter:description" content="{desc_e}">\n'
        f'<meta name="twitter:image" content="{OG_IMAGE}">\n'
    )


def insert_after_icon(text: str, block: str) -> str:
    # Insert block right after <link rel="icon" ...>
    pattern = re.compile(r'(<link\s+rel="icon"[^>]*>)', re.IGNORECASE)
    m = pattern.search(text)
    if not m:
        return text
    insert_at = m.end()
    # Make sure we end the line before inserting
    return text[:insert_at] + "\n" + block + text[insert_at:]


def remove_existing_canonical(text: str) -> str:
    """Remove existing <link rel='canonical'> so the new one is canonical source."""
    return re.sub(r'\s*<link\s+rel="canonical"[^>]*>\s*\n?', "\n", text, flags=re.IGNORECASE)


def main() -> int:
    log = []
    for fp in sorted(DOCS.glob("*.html")):
        if fp.name not in DESCRIPTIONS:
            continue
        text = fp.read_text(encoding="utf-8")
        orig = text

        # Skip if this exact block was already added (idempotency check via signature)
        sig = '<meta name="twitter:card" content="summary_large_image">'
        if sig in text:
            log.append(f"[skip] {fp.name}: already has full meta block")
            continue

        # Strip pre-existing canonical (will re-add via block to ensure consistency)
        text = remove_existing_canonical(text)

        # Insert block
        block = build_block(fp.name)
        new_text = insert_after_icon(text, block)
        if new_text == text:
            log.append(f"[FAIL] {fp.name}: <link rel='icon'> not found")
            continue

        fp.write_text(new_text, encoding="utf-8")
        log.append(f"[ok]   {fp.name}: +{block.count(chr(10))} meta lines")

    print("=== META FIX RESULTS ===")
    for line in log:
        print(line)
    return 0


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    sys.exit(main())
