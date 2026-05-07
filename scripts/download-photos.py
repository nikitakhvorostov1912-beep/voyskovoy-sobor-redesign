# -*- coding: utf-8 -*-
"""Download original-size photos from production site alexander-nevskiysobor.ru.

WordPress generates thumbnails like `-350x200`. The original is at the same path
without the size suffix. We try original first, then fall back to thumbnail.

Targets:
- Clergy (5): garmash, kadurov, feer, popov, klochkov
- Icons (1): kazan-original (особо чтимая святыня собора)
- Saint (1): alexander-nevsky portrait
- Archive (2): cathedral 1853-1930, restoration 2000-2006
- Hero (1): cropped-header for banner
"""
from __future__ import annotations
import re
import sys
from pathlib import Path
from urllib.request import Request, urlopen
from urllib.error import HTTPError, URLError

OUT_PHOTOS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\photos")
OUT_CLERGY = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\clergy")

# (url, target_name, type)
DOWNLOADS = [
    # Clergy — strip -350x200 suffix to get original
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/NanxmllXD0I-e1550168147399.jpg",
        "garmash.jpg",
        "clergy",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/11/-МАКСИМ-ДМИТРИЕВИЧ-ПРОТОДИАКОН-1-e1709713712636.jpg",
        "kadurov.jpg",
        "clergy",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/DSC_3952.jpg",
        "feer.jpg",
        "clergy",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2025/01/IMG_20250115_093952_406-1.jpg",
        "popov.jpg",
        "clergy",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2025/01/IMG_20250115_094503_360.jpg",
        "klochkov.jpg",
        "clergy",
    ),
    # Icons & saint
    (
        "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/02/23-3.jpg",
        "icon-kazan-sobor.jpg",
        "photos",
    ),
    (
        "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/02/Алесандр-Невский.jpg",
        "saint-alexander-nevsky.jpg",
        "photos",
    ),
    # Archive / history
    (
        "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/01/4_1-2.jpg",
        "archive-cathedral.jpg",
        "photos",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/01/3_1_1.jpg",
        "history-restoration-2006.jpg",
        "photos",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/01/1_2.jpg",
        "history-1853-1930.jpg",
        "photos",
    ),
    # Hero header
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/cropped-header1-1.png",
        "hero-header.png",
        "photos",
    ),
    # Bonus: news photos from 2026-04 (latest activity)
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/IMG_20260424_230328_654.jpg",
        "news-2026-04-1.jpg",
        "photos",
    ),
    (
        "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225125_com.vkontakte.android_edit_203370163126259.jpg",
        "news-2026-04-2.jpg",
        "photos",
    ),
]

UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0 Safari/537.36"


def fetch(url: str, dst: Path) -> tuple[bool, int, str]:
    try:
        req = Request(url, headers={"User-Agent": UA, "Referer": "https://alexander-nevskiysobor.ru/"})
        with urlopen(req, timeout=20) as r:
            data = r.read()
        dst.parent.mkdir(parents=True, exist_ok=True)
        dst.write_bytes(data)
        return True, len(data), ""
    except HTTPError as e:
        return False, 0, f"HTTP {e.code}"
    except URLError as e:
        return False, 0, f"URL {e.reason}"
    except Exception as e:
        return False, 0, str(e)[:80]


def fetch_with_fallback(url: str, dst: Path) -> tuple[bool, int, str]:
    ok, n, err = fetch(url, dst)
    if ok:
        return True, n, ""
    # Try -350x200 fallback (insert before .jpg/.png)
    fb = re.sub(r"(\.[a-z]{3,4})$", r"-350x200\1", url, flags=re.I)
    if fb != url:
        ok2, n2, err2 = fetch(fb, dst)
        if ok2:
            return True, n2, f"(fallback thumbnail)"
    return False, 0, err


def main() -> int:
    log: list[str] = []
    ok_n = 0
    for url, name, kind in DOWNLOADS:
        dst = (OUT_CLERGY if kind == "clergy" else OUT_PHOTOS) / name
        ok, size, note = fetch_with_fallback(url, dst)
        status = "[ok]   " if ok else "[fail] "
        size_kb = f"{size//1024} KB" if ok else ""
        log.append(f"{status}{name:32} {size_kb:>10} {note}".rstrip() + (f"\n           src: {url[:90]}" if not ok else ""))
        if ok:
            ok_n += 1
    print(f"=== DOWNLOAD: {ok_n}/{len(DOWNLOADS)} ===")
    for line in log:
        print(line)
    return 0 if ok_n == len(DOWNLOADS) else 1


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    sys.exit(main())
