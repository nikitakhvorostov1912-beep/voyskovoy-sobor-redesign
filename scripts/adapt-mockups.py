"""Адаптация HiFi-мокапов Claude Design в продакшен-страницы:
1. Заменяет href="#" на правильные .html ссылки по тексту в <a>
2. Чистит blob:// шрифты в schedule-mockup, заменяя их на @import Google Fonts
3. Корректирует реквизиты в donate.html на ВТБ
4. Сохраняет в финальные файлы (index/schedule/prayer-requests/donate/history/contacts)
"""
import re
import sys
from pathlib import Path

DOCS = Path(r'C:\CLOUDE_PR\Церковь\github-staging\docs')

href_mappings = [
    ('Расписание богослужений', 'schedule.html'),
    ('Святыни и иконы', 'icons.html'),
    ('Воскресная школа', 'parish-life.html#school'),
    ('Социальное служение', 'parish-life.html#sisterhood'),
    ('Хор собора', 'parish-life.html#choir'),
    ('Реквизиты для перевода', 'donate.html#bank-details'),
    ('Стать волонтёром', 'parish-life.html'),
    ('Стать волонтером', 'parish-life.html'),
    ('Заказать требу', 'prayer-requests.html'),
    ('Заказать молебен', 'prayer-requests.html'),
    ('Подать записку', 'prayer-requests.html'),
    ('Заказ треб', 'prayer-requests.html'),
    ('Жизнь прихода', 'parish-life.html'),
    ('Сестричество', 'parish-life.html#sisterhood'),
    ('О соборе', 'about.html'),
    ('Пожертвование', 'donate.html'),
    ('Пожертвовать', 'donate.html'),
    ('Реквизиты', 'donate.html#bank-details'),
    ('Расписание', 'schedule.html'),
    ('Молодёжь', 'parish-life.html#youth'),
    ('Молодежь', 'parish-life.html#youth'),
    ('Контакты', 'contacts.html'),
    ('Святыни', 'icons.html'),
    ('Главная', 'index.html'),
    ('Летопись', 'history.html'),
    ('Духовенство', 'clergy.html'),
    ('История', 'history.html'),
    ('Новости', 'news.html'),
]


def fix_links(content):
    """Заменяет <a href="#">текст</a> на href="<правильный>"."""
    def replacer(match):
        opening = match.group(1)  # <a ... href="#" ...>
        inner = match.group(2)
        closing = match.group(3)
        clean = re.sub(r'<[^>]+>', '', inner).strip()
        for pattern, target in href_mappings:
            if pattern.lower() in clean.lower():
                fixed = opening.replace('href="#"', f'href="{target}"', 1)
                return fixed + inner + closing
        return match.group(0)
    pattern = re.compile(
        r'(<a\b[^>]*href="#"[^>]*>)([\s\S]*?)(</a>)',
        re.IGNORECASE
    )
    return pattern.sub(replacer, content)


def strip_blob_fonts(content):
    """Удаляет ВСЕ @font-face с blob: URL и добавляет Google Fonts CSS @import.

    Это нужно для schedule-mockup, где 83 blob:// ссылок на шрифты.
    """
    # Удаляем все @font-face блоки полностью (вместе со скобками)
    pattern = re.compile(
        r'@font-face\s*\{[^{}]*blob:[^{}]*\}',
        re.MULTILINE
    )
    cleaned = pattern.sub('', content)

    # Добавляем Google Fonts <link> в head если ещё нет
    if 'fonts.googleapis.com/css2?family=Cormorant' not in cleaned:
        google_fonts_link = '''
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Spectral:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
'''
        cleaned = cleaned.replace('</title>', '</title>' + google_fonts_link, 1)

    return cleaned


def fix_donate_requisites(content):
    """В donate.html заменить макетные реквизиты на реальные ВТБ."""
    replacements = [
        ('Сбербанк России', 'Банк ВТБ (ПАО)'),
        ('ПАО Сбербанк', 'ПАО Банк ВТБ'),
        ('Сбербанк', 'Банк ВТБ'),
        # Любые макетные ИНН/ОГРН/счета
        ('40703810000010001234', '40703810100005000023'),
        ('30101810400000000225', '30101810145250000411'),
        ('044525225', '044525411'),
    ]
    for old, new in replacements:
        content = content.replace(old, new)
    return content


def fix_contacts_global(content):
    """Заменяет макетные phone/email на реальные во всех файлах."""
    replacements = [
        ('+7 (861) 262 50 04', '+7 (861) 262-00-20'),
        ('+7 (861) 262-50-04', '+7 (861) 262-00-20'),
        ('+78612625004',       '+78612620020'),
        ('canc@sobor-krd.ru',  'nevskiy-sobor@mail.ru'),
    ]
    for old, new in replacements:
        content = content.replace(old, new)
    return content


def add_meta(content, title=None, description=None, canonical=None):
    """Добавляет canonical, OG meta если их нет."""
    head_addition = []
    if canonical and 'rel="canonical"' not in content:
        head_addition.append(f'<link rel="canonical" href="{canonical}">')
    if 'theme-color' not in content:
        head_addition.append('<meta name="theme-color" content="#1a1f2e">')
    if 'rel="icon"' not in content:
        head_addition.append('<link rel="icon" type="image/svg+xml" href="assets/images/icons/favicon.svg">')
    if 'manifest.webmanifest' not in content and 'rel="manifest"' not in content:
        head_addition.append('<link rel="manifest" href="manifest.webmanifest">')

    if head_addition and '</title>' in content:
        content = content.replace('</title>', '</title>\n' + '\n'.join(head_addition) + '\n', 1)

    return content


def remove_omelette_script(content):
    """Удаляет огромный inline script Claude Design (omelette)."""
    pattern = re.compile(
        r'<script\s+data-omelette-injected[^>]*>[\s\S]*?</script>',
        re.MULTILINE
    )
    return pattern.sub('', content)


def remove_style_omelette(content):
    pattern = re.compile(
        r'<style\s+data-omelette-injected[^>]*>[\s\S]*?</style>',
        re.MULTILINE
    )
    return pattern.sub('', content)


def process(src_name, dst_name, *, fix_donate=False):
    src = DOCS / src_name
    dst = DOCS / dst_name
    if not src.exists():
        print(f'SKIP {src_name} (not found)')
        return
    text = src.read_text(encoding='utf-8')
    initial_size = len(text)

    text = remove_omelette_script(text)
    text = remove_style_omelette(text)
    text = strip_blob_fonts(text)
    text = fix_links(text)
    text = fix_contacts_global(text)
    if fix_donate:
        text = fix_donate_requisites(text)

    base_url = f'https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/{dst_name}'
    text = add_meta(text, canonical=base_url)

    dst.write_text(text, encoding='utf-8')
    refs_left = text.count('href="#"')
    print(f'{src_name} -> {dst_name}: {initial_size} -> {len(text)} bytes, {refs_left} href="#" left'.encode('ascii', 'replace').decode())


if __name__ == '__main__':
    process('schedule-mockup.html', 'schedule.html')
    process('prayer-mockup.html', 'prayer-requests.html')
    process('donate-mockup.html', 'donate.html', fix_donate=True)
    process('history-mockup.html', 'history.html')
    process('contacts-mockup.html', 'contacts.html')
    print('All done.')
