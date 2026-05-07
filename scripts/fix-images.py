# -*- coding: utf-8 -*-
"""Add explicit width/height + loading=lazy to <img> tags.

Why:
- Without intrinsic dimensions, browser cannot reserve layout space → CLS penalty.
- 11 of 11 images on site lack width/height (audit 2026-05-07).
- 4 clergy images lack loading="lazy" (below-the-fold).

Strategy:
- Read each image's actual pixel dimensions via PIL.
- For each <img> in HTML, inject width/height attributes from real file.
- For non-hero images (everything except first <img> on page), add loading="lazy".
- Hero images get fetchpriority="high" instead.
"""
from __future__ import annotations
import re
import sys
from pathlib import Path
from PIL import Image

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")

IMG_RE = re.compile(r'<img\b([^>]*)\bsrc\s*=\s*"([^"]+)"([^>]*)>', re.IGNORECASE)

# Cache image dimensions
DIM_CACHE: dict[str, tuple[int, int]] = {}


def get_dims(src: str) -> tuple[int, int] | None:
    if src in DIM_CACHE:
        return DIM_CACHE[src]
    if src.startswith(("http://", "https://", "//", "data:")):
        return None
    p = DOCS / src.split("?")[0].split("#")[0]
    if not p.exists():
        return None
    try:
        with Image.open(p) as im:
            DIM_CACHE[src] = im.size
            return im.size
    except Exception:
        return None


def has_attr(tag: str, attr: str) -> bool:
    return bool(re.search(rf'\b{attr}\s*=\s*"', tag, re.IGNORECASE))


def main() -> int:
    log = []
    for fp in sorted(DOCS.glob("*.html")):
        text = fp.read_text(encoding="utf-8")
        orig = text
        edits_per_file = 0
        # Track first <img> per page → fetchpriority=high, others → lazy
        offset = 0
        new_text = text
        first_img = True
        for m in IMG_RE.finditer(text):
            attrs_pre = m.group(1)
            src = m.group(2)
            attrs_post = m.group(3)
            full_tag = m.group(0)
            dims = get_dims(src)
            extras = []
            if dims is not None:
                w, h = dims
                if not has_attr(full_tag, "width"):
                    extras.append(f'width="{w}"')
                if not has_attr(full_tag, "height"):
                    extras.append(f'height="{h}"')
            if first_img:
                if not has_attr(full_tag, "fetchpriority"):
                    extras.append('fetchpriority="high"')
                first_img = False
            else:
                if not has_attr(full_tag, "loading"):
                    extras.append('loading="lazy"')
                if not has_attr(full_tag, "decoding"):
                    extras.append('decoding="async"')
            if not extras:
                continue
            # Insert extras before final ">"
            additions = " " + " ".join(extras)
            new_tag = full_tag[:-1] + additions + ">"
            new_text = new_text[: m.start() + offset] + new_tag + new_text[m.end() + offset :]
            offset += len(new_tag) - len(full_tag)
            edits_per_file += 1
        if new_text != orig:
            fp.write_text(new_text, encoding="utf-8")
            log.append(f"[ok]   {fp.name}: {edits_per_file} <img> updated")
        else:
            log.append(f"[skip] {fp.name}: no changes needed")

    print("=== IMAGE FIX RESULTS ===")
    for line in log:
        print(line)
    return 0


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    sys.exit(main())
