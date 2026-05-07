# -*- coding: utf-8 -*-
"""Pass 2: fix the 4 failures from download-photos.py.
- Cyrillic in URL → urllib.parse.quote
- SSL self-signed on new.alexander-nevskiysobor.ru → ssl unverified context
- Try fallback URL on main domain
"""
from __future__ import annotations
import ssl
import sys
import urllib.parse
from pathlib import Path
from urllib.request import Request, urlopen

OUT_PHOTOS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\photos")
OUT_CLERGY = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\clergy")

UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0 Safari/537.36"
SSL_CTX = ssl._create_unverified_context()

# (multiple_url_candidates, target, kind)
DOWNLOADS = [
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/11/-МАКСИМ-ДМИТРИЕВИЧ-ПРОТОДИАКОН-1-e1709713712636.jpg",
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/11/-МАКСИМ-ДМИТРИЕВИЧ-ПРОТОДИАКОН-1-e1709713712636-350x200.jpg",
        ],
        "kadurov.jpg",
        "clergy",
    ),
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/Алесандр-Невский.jpg",
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/Алесандр-Невский-350x200.jpg",
            "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/02/Алесандр-Невский.jpg",
        ],
        "saint-alexander-nevsky.jpg",
        "photos",
    ),
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/02/23-3.jpg",
            "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/02/23-3.jpg",
        ],
        "icon-kazan-sobor.jpg",
        "photos",
    ),
    (
        [
            "https://alexander-nevskiysobor.ru/wp-content/uploads/2019/01/4_1-2.jpg",
            "http://new.alexander-nevskiysobor.ru/wp-content/uploads/2019/01/4_1-2.jpg",
        ],
        "archive-cathedral.jpg",
        "photos",
    ),
]


def quote_url(u: str) -> str:
    """Percent-encode non-ASCII in path while keeping scheme/host."""
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
    for urls, name, kind in DOWNLOADS:
        dst = (OUT_CLERGY if kind == "clergy" else OUT_PHOTOS) / name
        last_err = ""
        success = False
        for url in urls:
            ok, size, err = fetch(url, dst)
            if ok:
                log.append(f"[ok]   {name:32} {size//1024:>5} KB from {url[:60]}")
                success = True
                ok_n += 1
                break
            else:
                last_err = err
        if not success:
            log.append(f"[FAIL] {name:32}  {last_err}")
    print(f"=== DOWNLOAD v2: {ok_n}/{len(DOWNLOADS)} ===")
    for line in log:
        print(line)
    return 0 if ok_n == len(DOWNLOADS) else 1


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    sys.exit(main())
