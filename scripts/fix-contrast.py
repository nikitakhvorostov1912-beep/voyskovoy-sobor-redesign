# -*- coding: utf-8 -*-
"""Surgical contrast fixes for WCAG 2.2 AA compliance.

Target violations from axe-core (5 pages × 33+8+6+19+13 = 79 violations):
1. --gold-deep #a7-a98843..#a98a45 → #735923 (contrast on paper: 5.48 ✓)
2. --ink-mute #4a516680 (alpha 50%) → #5b5345 solid (contrast 6.69 ✓)
3. --ink-muted #4a5165 in prayer-requests.html — already 6.76 OK, skip
4. .site-photo-frame__attribution rgba(245,240,232,0.4) → rgba(...,0.78)
5. .quote-attribution opacity 0.7 → 0.92 (gold #c9a961 base needs less fade on ink)
"""
from __future__ import annotations
import re
from pathlib import Path

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")

# Pattern → replacement
GOLD_DEEP_PATTERN = re.compile(r'(--gold-deep\s*:\s*)#a[789][8-9a]?[0-9a-fA-F]{2,3}', re.IGNORECASE)
INK_MUTE_PATTERN = re.compile(r'(--ink-mute\s*:\s*)#4a516680', re.IGNORECASE)

NEW_GOLD_DEEP = '#735923'
NEW_INK_MUTE = '#5b5345'

changes = []

# 1. Fix --gold-deep across all html files
for fp in sorted(DOCS.glob('*.html')):
    text = fp.read_text(encoding='utf-8')
    orig = text
    new = GOLD_DEEP_PATTERN.sub(rf'\1{NEW_GOLD_DEEP}', text)
    if new != orig:
        # count matches
        n = sum(1 for _ in GOLD_DEEP_PATTERN.finditer(orig))
        changes.append((fp.name, '--gold-deep', n))
        text = new
    # 2. Fix --ink-mute (only index.html)
    new = INK_MUTE_PATTERN.sub(rf'\1{NEW_INK_MUTE}', text)
    if new != text:
        changes.append((fp.name, '--ink-mute', 1))
        text = new
    if text != orig:
        fp.write_text(text, encoding='utf-8')

# 3. Fix .site-photo-frame__attribution alpha in site-additions.css
css_path = DOCS / 'assets' / 'css' / 'site-additions.css'
css = css_path.read_text(encoding='utf-8')
orig_css = css
# Within .site-photo-frame__attribution { ... color: rgba(245, 240, 232, 0.4); ... }
css = re.sub(
    r'(\.site-photo-frame__attribution\s*\{[^}]*?color\s*:\s*rgba\(245,\s*240,\s*232,\s*)0\.4(\s*\)\s*;)',
    r'\g<1>0.78\g<2>',
    css,
    flags=re.DOTALL,
)
if css != orig_css:
    changes.append(('site-additions.css', 'photo-frame__attribution alpha 0.4→0.78', 1))
    css_path.write_text(css, encoding='utf-8')

# 4. Fix .quote-attribution opacity in prayer-requests.html
pr_path = DOCS / 'prayer-requests.html'
pr = pr_path.read_text(encoding='utf-8')
orig_pr = pr
# .quote-attribution { ... color: var(--gold); opacity: 0.7; ... }
pr = re.sub(
    r'(\.quote-attribution\s*\{[^}]*?opacity\s*:\s*)0\.7(\s*;)',
    r'\g<1>0.92\g<2>',
    pr,
    flags=re.DOTALL,
)
if pr != orig_pr:
    changes.append(('prayer-requests.html', 'quote-attribution opacity 0.7→0.92', 1))
    pr_path.write_text(pr, encoding='utf-8')

print('=== CONTRAST FIXES APPLIED ===')
for fn, what, n in changes:
    print(f'  {fn}: {what} ({n} replacement{"s" if n>1 else ""})')
print(f'\nTotal: {len(changes)} edits')
