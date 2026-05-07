# -*- coding: utf-8 -*-
"""Pass 3: download remaining photos found on production site.
- 3 missing news photos (April 2026)
- Library photo (IMG_1345.jpg)
- March 2026 webp (latest content)
"""
from __future__ import annotations
import ssl
import sys
import urllib.parse
from pathlib import Path
from urllib.request import Request, urlopen

OUT_PHOTOS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\photos")
UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0 Safari/537.36"
SSL_CTX = ssl._create_unverified_context()

DOWNLOADS = [
    # 3 more news photos from April 24, 2026 (chaepitie/Easter/biblionight/hospital)
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225121_com.vkontakte.android_edit_203362417970010.jpg",
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225121_com.vkontakte.android_edit_203362417970010-350x200.jpg",
        ],
        "news-2026-04-easter-concert.jpg",
    ),
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225114_com.vkontakte.android_edit_203353893591366.jpg",
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225114_com.vkontakte.android_edit_203353893591366-350x200.jpg",
        ],
        "news-2026-04-biblionight.jpg",
    ),
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225109_com.vkontakte.android_edit_203344765056471.jpg",
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/04/Screenshot_20260424_225109_com.vkontakte.android_edit_203344765056471-350x200.jpg",
        ],
        "news-2026-04-hospital.jpg",
    ),
    # Library photo
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/IMG_1345.jpg",
            "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/02/IMG_1345.jpg",
        ],
        "library-interior.jpg",
    ),
    # March 2026 sidebar
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2026/03/0de0304f-55a1-42f1-9298-2429c6920b8c.webp",
        ],
        "feature-2026-03.webp",
    ),
]


def quote_url(u: str) -> str:
    parts = urllib.parse.urlsplit(u)
    path = urllib.parse.quote(parts.path, safe="/-_.+()")
    return urllib.parse.urlunsplit((parts.scheme, parts.netloc, path, parts.query, parts.fragment))


def fetch(url: str, dst: Path) -> tuple[bool, int, str]:
    try:
        req = Request(quote_url(url), headers={"User-Agent": UA, "Referer": "https://alexander-nevskiysobor.ru/"})
        with urlopen(req, timeout=25, context=SSL_CTX) as r:
            data = r.read()
        if len(data) < 1024:
            return False, len(data), f"too small ({len(data)} bytes)"
        dst.parent.mkdir(parents=True, exist_ok=True)
        dst.write_bytes(data)
        return True, len(data), ""
    except Exception as e:
        return False, 0, str(e)[:90]


def main() -> int:
    log = []
    ok_n = 0
    for urls, name in DOWNLOADS:
        dst = OUT_PHOTOS / name
        last_err = ""
        for url in urls:
            ok, size, err = fetch(url, dst)
            if ok:
                log.append(f"[ok]   {name:38} {size//1024:>5} KB")
                ok_n += 1
                break
            last_err = err
        else:
            log.append(f"[FAIL] {name:38}  {last_err}")
    print(f"=== DOWNLOAD v3: {ok_n}/{len(DOWNLOADS)} ===")
    for line in log:
        print(line)
    return 0 if ok_n == len(DOWNLOADS) else 1


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    sys.exit(main())
