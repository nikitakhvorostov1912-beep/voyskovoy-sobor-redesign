# -*- coding: utf-8 -*-
"""Заменяет существующий <footer>...</footer> на унифицированный во всех HTML.

Идемпотентно: если уже есть class="usite-footer" — пропускает.
Делает бэкап в backups/footer-{timestamp}/.
"""
import re
import shutil
import time
from pathlib import Path

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")
TEMPLATE = Path(r"C:\CLOUDE_PR\Церковь\github-staging\scripts\unified-footer.html")
BACKUP_DIR = Path(r"C:\CLOUDE_PR\Церковь\github-staging\backups") / f"footer-{int(time.time())}"

# Greedy non-greedy match for one <footer ...>...</footer> block
RE_FOOTER = re.compile(r'<footer\b[^>]*>.*?</footer>', re.IGNORECASE | re.DOTALL)


def main() -> None:
    template = TEMPLATE.read_text(encoding="utf-8").rstrip()
    BACKUP_DIR.mkdir(parents=True, exist_ok=True)
    print(f"Backup -> {BACKUP_DIR}")

    files = sorted(DOCS.glob("*.html"))
    for fp in files:
        original = fp.read_text(encoding="utf-8")

        if 'class="usite-footer"' in original:
            print(f"--   {fp.name} (уже унифицирован)")
            continue

        # Backup
        shutil.copy2(fp, BACKUP_DIR / fp.name)

        # Найти и заменить footer
        new_text, count = RE_FOOTER.subn(template, original, count=1)

        if count == 0:
            # Нет footer — вставить перед </body>
            if "</body>" not in original:
                print(f"!!   {fp.name} — нет ни <footer>, ни </body> — пропускаю")
                continue
            new_text = original.replace("</body>", template + "\n</body>", 1)
            print(f"INS  {fp.name} (footer вставлен — раньше не было)")
        else:
            print(f"OK   {fp.name} (footer заменён)")

        fp.write_text(new_text, encoding="utf-8")


if __name__ == "__main__":
    main()
