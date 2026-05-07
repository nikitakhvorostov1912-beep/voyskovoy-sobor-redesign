# -*- coding: utf-8 -*-
"""Конвертация нерабочих кнопок prayer-requests.html в рабочие.

1. Все <button class="treba-cta">...</button> → <a> с mailto и pre-filled subject
2. <button class="btn-priest solid"> → <a href="tel:+78612620020">
3. <button class="btn-priest outlined"> → <a href="https://t.me/alexnewsobor">
4. FAQ accordion — handler в JS (добавить если нет)
"""
import re
import sys
import shutil
import time
import urllib.parse
from pathlib import Path

sys.stdout.reconfigure(encoding="utf-8")

DOC = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\prayer-requests.html")
BACKUP_DIR = Path(r"C:\CLOUDE_PR\Церковь\github-staging\backups") / f"prayer-fix-{int(time.time())}"


def make_mailto(treba_name: str, kind: str = "treba") -> str:
    """Compose mailto link with subject and body template."""
    if kind == "treba":
        subject = f"Заказ требы — {treba_name}"
        body = (
            f"Прошу принять заказ на требу: {treba_name}.\n\n"
            "Имена для поминовения (через запятую, в крещении):\n"
            "О здравии — \n"
            "О упокоении — \n\n"
            "Контакт для связи (телефон или email):\n"
        )
    elif kind == "appointment":
        subject = f"Запись на беседу — {treba_name}"
        body = (
            f"Прошу записать на беседу по таинству: {treba_name}.\n\n"
            "Имя:\n"
            "Контакт (телефон или email):\n"
            "Удобное время для встречи:\n"
        )
    else:
        subject = treba_name
        body = ""
    qs = urllib.parse.urlencode({"subject": subject, "body": body}, quote_via=urllib.parse.quote)
    return f"mailto:nevskiy-sobor@mail.ru?{qs}"


def main() -> None:
    BACKUP_DIR.mkdir(parents=True, exist_ok=True)
    shutil.copy2(DOC, BACKUP_DIR / DOC.name)
    print(f"Backup → {BACKUP_DIR}")

    text = DOC.read_text(encoding="utf-8")

    # ---- 1. treba-cta buttons ----
    btn_re = re.compile(
        r'<button\s+class="(treba-cta(?:\s+outlined)?)">(.*?)</button>',
        re.DOTALL,
    )

    matches = list(btn_re.finditer(text))
    print(f"\n1. Found {len(matches)} treba-cta buttons")

    # Replace each with the right mailto link, looking back for nearest h3
    new_text = text
    offset = 0
    for m in matches:
        cls = m.group(1)
        inner = m.group(2)
        # find nearest preceding h3 in pre-existing text (use original positions)
        before = text[:m.start()]
        h3_match = list(re.finditer(r'<h3[^>]*>([^<]+)</h3>', before))
        if not h3_match:
            print(f"  ! no h3 before button at {m.start()}")
            continue
        treba_name = h3_match[-1].group(1).strip()
        kind = "appointment" if "outlined" in cls else "treba"
        href = make_mailto(treba_name, kind)
        # build replacement (preserve inner HTML — text + svg)
        replacement = (
            f'<a class="{cls}" href="{href}" target="_blank" rel="noopener">'
            f'{inner}'
            f'</a>'
        )
        # Replace in new_text using current offset
        idx_in_new = m.start() + offset
        new_text = new_text[:idx_in_new] + replacement + new_text[idx_in_new + len(m.group(0)):]
        offset += len(replacement) - len(m.group(0))
        print(f"  ✓ [{cls:20}] {treba_name[:50]}")

    text = new_text

    # ---- 2. btn-priest solid → tel ----
    # <button class="btn-priest solid">Позвонить настоятелю +7 (861) 262-00-20</button>
    text, n_solid = re.subn(
        r'<button\s+class="btn-priest solid">(.*?)</button>',
        r'<a class="btn-priest solid" href="tel:+78612620020">\1</a>',
        text, flags=re.DOTALL
    )
    print(f"\n2. btn-priest solid → tel: {n_solid}")

    # ---- 3. btn-priest outlined → telegram ----
    text, n_out = re.subn(
        r'<button\s+class="btn-priest outlined">(.*?)</button>',
        r'<a class="btn-priest outlined" href="https://t.me/alexnewsobor" target="_blank" rel="noopener">\1</a>',
        text, flags=re.DOTALL
    )
    print(f"3. btn-priest outlined → telegram: {n_out}")

    # ---- 4. FAQ handler в JS ----
    # Проверим, есть ли уже инлайн-script с .faq-q handler
    has_faq_handler = bool(re.search(r"querySelectorAll\(['\"]\\\\?\.faq-q['\"]\)", text)) or \
                      "faq-item" in text and "addEventListener" in text and ".faq-q" in text
    if not has_faq_handler:
        # Insert script before closing </body>
        faq_script = """
<script>
  // FAQ accordion (added by fix-prayer-requests.py)
  document.querySelectorAll('.faq-q').forEach(q => {
    q.addEventListener('click', () => {
      const item = q.closest('.faq-item');
      if (item) item.classList.toggle('open');
    });
  });
</script>
"""
        if "</body>" in text:
            text = text.replace("</body>", faq_script + "\n</body>", 1)
            print(f"\n4. FAQ accordion handler: ADDED")
        else:
            print(f"\n4. FAQ handler: NOT INSERTED (no </body>)")
    else:
        print(f"\n4. FAQ handler: already exists")

    DOC.write_text(text, encoding="utf-8")
    print("\n✓ prayer-requests.html обновлён")


if __name__ == "__main__":
    main()
