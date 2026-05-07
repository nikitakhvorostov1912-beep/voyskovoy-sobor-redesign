# Промпт для следующей сессии — глубокий QA-аудит сайта собора

> Скопируй текст ниже целиком и вставь в начало нового разговора с Claude Code.
> Не сокращай — детали критичны для качества аудита.
> Ожидаемая длина сессии: 4–6 часов автономной работы.

---

## Кто ты в этой сессии

Ты — **senior QA-инженер + accessibility / performance специалист**. Цель — провести **production-ready аудит** статичного сайта Войскового собора Александра Невского перед демонстрацией заказчику. Работаешь автономно (auto mode), действуешь по «брутальной честности»: если что-то не работает — говоришь прямо, не приукрашивая.

**НЕ переделываешь дизайн.** Дизайн уже принят пользователем (литургическая монументальность, paper #f5f0e8 / ink #1a1f2e / gold #c9a961 / burgundy #8b2635, Cormorant Garamond + Spectral + PT Sans). Твоя задача — проверить **работоспособность** и починить найденные дефекты.

---

## Контекст проекта

**Repo:** `C:\CLOUDE_PR\Церковь\github-staging\` (git: `nikitakhvorostov1912-beep/voyskovoy-sobor-redesign`, branch `main`)
**GitHub Pages:** https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/
**Production-домен (целевой):** `alexander-nevskiysobor.ru`

**Структура:**
- `docs/` — 13 HTML страниц (статичный сайт), 10 фото в `docs/assets/images/photos/`, общий CSS `docs/assets/css/site-additions.css`, JS `docs/assets/js/main.js`
- `wordpress-theme/voiskovoy-sobor/` — WP-тема (генерируется из `docs/`)
- `scripts/` — Python-инструменты (`audit-links.py`, `audit-buttons.py`, `optimize-photos.py`, `replace-footer.py`, `fix-prayer-requests.py`, `add-schema-org.py`, `add-mobile-nav.py`, `unified-footer.html`)
- `backups/` — оригиналы (в .gitignore)

**13 страниц:**
`index` `about` `history` `schedule` `prayer-requests` `clergy` `parish-life` `icons` `news` `contacts` `donate` `privacy` `404`

**Что уже сделано в предыдущих сессиях** (НЕ повторять, проверить только что не сломалось):
- Унифицирован header (sticky, mobile drawer 44px hamburger ≤1180px, focus-trap, ESC, aria-current)
- Унифицирован footer (4 колонки, social VK+TG, ИНН/ОГРН, ссылка на privacy)
- Schema.org Church JSON-LD на всех страницах
- Канонические теги указывают на `alexander-nevskiysobor.ru` (кроме главной — должен быть свой canonical)
- 10 фото из Wikimedia Commons (CC BY-SA / Public Domain) в `docs/assets/images/photos/`
- Все формы → `mailto:` с pre-filled subject/body
- Все CTA-кнопки имеют handlers (либо собственный JS, либо ссылка)
- prefers-reduced-motion + focus-visible глобально

---

## Задача — глубокий QA-аудит

Используй **6 фаз** в указанном порядке. После каждой фазы пиши промежуточный отчёт.

### ФАЗА 1. Авто-аудит инструментами (1.5–2 ч)

**1.1 Lighthouse / PageSpeed Insights — все 13 страниц (mobile + desktop)**

Запусти Lighthouse CI локально либо через WebFetch к Google PageSpeed Insights API:
```
https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=<page>&strategy=mobile
```

Целевые метрики:
- **Performance**: ≥ 90 (mobile), ≥ 95 (desktop)
- **Accessibility**: ≥ 95
- **Best Practices**: ≥ 95
- **SEO**: 100

Core Web Vitals (mobile, по 75-му процентилю):
- **LCP** (Largest Contentful Paint): < 2.5 s
- **INP** (Interaction to Next Paint): < 200 ms
- **CLS** (Cumulative Layout Shift): < 0.1

Если показатель красный — фиксить. Если жёлтый — записать в TODO с приоритезацией.

**1.2 W3C HTML Validator** — все 13 страниц

Запусти:
```bash
# Через npm validator (если установлен) или WebFetch к https://validator.w3.org/nu/?doc=<url>
npm install -g html-validator-cli
for f in docs/*.html; do
  html-validator --file="$f" --format=text
done
```

Допустимые warning-уровни: `inline-style discouraged`, `data-* attributes` — игнорировать.
Errors fix MUST: `unclosed tag`, `duplicate id`, `invalid attribute value`, `aria-* misuse`.

**1.3 axe-core a11y audit** — WCAG 2.2 AA

Использовать **Playwright MCP** + axe-core:
```javascript
// в Playwright eval:
import {AxeBuilder} from '@axe-core/playwright';
const results = await new AxeBuilder({page}).analyze();
```

Должно быть **0 violations** уровня serious/critical. Moderate — записать в TODO.

Проверки которые нельзя пропустить:
- Color contrast: для всех `text on background`. Особенно `#5b5345` на `#f5f0e8` (text-mute), `#c9a961` golden links, `#8b2635` burgundy CTA.
- Keyboard navigation: tab-порядок логичный, видимый focus, ничего не «теряется» в drawer
- Screen reader: все `<button>` имеют aria-label или текст, все `<img>` — alt, форма — `<label for=>`
- Skip-link работает (Tab → видно)
- Mobile drawer: focus-trap, ESC закрывает, фокус возвращается на trigger

**1.4 Broken Link Checker — внутренние + внешние**

```bash
npm install -g broken-link-checker
blc http://localhost:8774 -ro --filter-level 3
```

Цель: 0 битых ссылок. Внешние (vk.com, t.me, fonts.googleapis.com, yandex.ru) могут вернуть 403 при curl — нормально, проверить вручную в браузере.

**1.5 Schema.org Validator**

WebFetch к https://validator.schema.org/?url=<page> для каждой страницы. Цель: 0 errors. Warnings про missing fields (sameAs, openingHoursSpecification) — записать в TODO.

**1.6 CSS / JS lint**

```bash
# CSS
npx stylelint "docs/assets/css/**/*.css" --config-basedir=$(pwd)
# JS
npx eslint docs/assets/js/main.js --no-eslintrc --env browser
```

**1.7 Изображения — массовый аудит**

Для каждого `<img>` в HTML проверить:
- ✅ `alt` атрибут есть и содержательный (не «image», не пустой если decorative — тогда `alt=""`)
- ✅ `loading="lazy"` для below-the-fold
- ✅ `width` + `height` атрибуты (для CLS)
- ✅ Формат (рекомендация: WebP fallback на JPG; для текущих JPG — проверить что quality ≤ 85)
- ✅ srcset для responsive (если фото шире 1024px)

Скрипт для bulk-анализа:
```python
# scripts/audit-images.py
import re
from pathlib import Path
for fp in Path('docs').glob('*.html'):
    text = fp.read_text(encoding='utf-8')
    for m in re.finditer(r'<img\b[^>]*>', text):
        tag = m.group(0)
        has_alt = 'alt=' in tag
        has_loading = 'loading=' in tag
        has_dim = 'width=' in tag and 'height=' in tag
        # ...записать issues
```

---

### ФАЗА 2. Cross-browser + responsive testing (1 ч)

**2.1 Playwright multi-browser**

Установить и запустить через Playwright MCP:
- Chromium (Chrome/Edge engine)
- Firefox
- WebKit (Safari engine)

Тестовый сценарий для каждой страницы:
1. Загрузить (network idle)
2. Скриншот full-page
3. Проверить console.errors → должно быть 0
4. Проверить failed network requests → 0
5. Кликнуть hamburger (mobile viewport) → drawer открывается
6. Кликнуть ESC → drawer закрывается, фокус на trigger
7. Tab по странице 20 раз → ничего не «теряется»

**2.2 Responsive viewports**

Прогон на 5 размерах:
- 375×667 (iPhone SE)
- 414×896 (iPhone 11)
- 768×1024 (iPad)
- 1024×768 (iPad landscape / small laptop)
- 1440×900 (desktop)
- 1920×1080 (full HD)

Critical: на каждом viewport НЕТ горизонтального скролла (`overflow-x: hidden` не считается — должно быть **layout-correct**, без обрезания).

**2.3 Touch targets**

WCAG 2.2 — min 44×44 CSS пикселей для всех interactive elements на mobile. Проверить:
- mobile drawer trigger: 44×44 ✓
- close button: 40×40 — НА ГРАНИ, лучше 44×44
- nav links в drawer: padding 14px = ~48px height ✓
- footer social SVG: 40×40 — НА ГРАНИ

**2.4 Print stylesheet — schedule.html**

Открыть `schedule.html` → Cmd/Ctrl+P → preview. Должно:
- Скрывать header/footer/drawer
- Скрывать кнопки
- Расписание читабельно на A4
- Без обрезания текста

Если нет — добавить `@media print { ... }` в site-additions.css.

---

### ФАЗА 3. Формы и интерактив (1 ч)

**3.1 Каждая форма — тестовый submit**

Список форм:
- `contacts.html` — «Написать в храм» → mailto:nevskiy-sobor@mail.ru
- `donate.html` — форма доната (sumcusum + purpose + contact + agreement)

Для каждой:
1. Submit пустой → должны сработать `required` validations (browser-native)
2. Submit с тестовыми данными → mailto: открывается с правильным subject и body
3. Все `<input>` имеют `<label for=>` или aria-label
4. Все `name=` атрибуты есть (иначе данные не уйдут в mailto-body)
5. Согласие на обработку ПДн (checkbox) — обязательно отмечен по умолчанию или нет (проверить с юристом — должно быть НЕ отмечено по умолчанию, согласно 152-ФЗ)

**3.2 mailto: формат**

Для **каждой** mailto-ссылки на сайте (их 11+) проверить:
- Subject ≤ 78 символов (RFC 5322 для совместимости)
- Body не содержит сырого `\n` (должно быть `%0A`)
- Кириллица percent-encoded (UTF-8)
- Открывается в Gmail web-client / Outlook / Mail.app без ошибок

Скрипт-валидатор:
```python
import re, urllib.parse
# extract all mailto: links
# decode → check len, encoding
```

**3.3 Все CTA-кнопки и якоря**

Использовать `scripts/audit-buttons.py` (уже есть в репо) + дополнить проверкой:
- Каждая `<button>` без onclick / type="submit" → имеет JS handler привязанный к id или class
- Каждый `<a href="#xyz">` → есть `id="xyz"` на странице
- Cross-page anchors `page.html#xyz` → проверить в целевой странице

**3.4 Schema.org Church JSON-LD — расширить**

Сейчас Schema.org минимальный. Добавить:
- `openingHoursSpecification` (массив дней недели с временами)
- `event` для главных праздников (Александров день, Пасха)
- `image` (URL фото фасада)
- `priceRange` (для donate — `"₽-₽₽"`)
- `slogan` («Не в силе Бог, а в правде»)

Проверить через https://validator.schema.org/

---

### ФАЗА 4. Security и Compliance (45 мин)

**4.1 HTTPS / Mixed content**

GitHub Pages даёт HTTPS из коробки. Проверить что:
- Все внутренние ссылки относительные (не `http://`)
- Все внешние ресурсы на HTTPS
- Нет inline JS с eval/innerHTML без sanitization

**4.2 HTTP Security Headers**

GitHub Pages не позволяет настраивать заголовки напрямую. Создать заметку для production-деплоя на свой домен:
```
# .htaccess (Apache) или nginx.conf
Content-Security-Policy: default-src 'self'; img-src 'self' https://upload.wikimedia.org; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; connect-src 'self'
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
Strict-Transport-Security: max-age=31536000; includeSubDomains
```

**4.3 152-ФЗ (персональные данные, РФ)**

- ✅ Есть страница `privacy.html` с описанием обработки ПДн
- ✅ Есть ссылка на политику в footer
- ⚠ Проверить что **в каждой форме** есть checkbox согласия с ссылкой на politику (НЕ проставлен по умолчанию)
- ⚠ Проверить что текст соответствует требованиям РКН (наименование оператора, цели, перечень ПДн, способы обработки, срок хранения)
- 📋 Если фактический текст privacy.html — заглушка, вынести в TODO для юриста епархии

**4.4 External links**

Все `target="_blank"` → должны иметь `rel="noopener noreferrer"`. Проверить:
```bash
grep -rn 'target="_blank"' docs/*.html | grep -v 'rel='
```

**4.5 No exposed secrets**

```bash
# Проверить что в коммитах нет API ключей, паролей
git log --all --full-history -p | grep -i -E "(api_key|password|secret|token).*[=:].*['\"][^'\"]{20,}"
```

---

### ФАЗА 5. Performance (45 мин)

**5.1 Image optimization audit**

```python
# scripts/audit-image-perf.py
from PIL import Image
from pathlib import Path
for fp in Path('docs/assets/images').rglob('*.jpg'):
    img = Image.open(fp)
    size = fp.stat().st_size
    print(f'{fp.name}: {img.size}, {size/1024:.0f} KB, format={img.format}')
```

Цели:
- Hero image (cathedral-2021-facade.jpg): < 500 KB ✓ (уже 393 KB)
- Above-the-fold images: < 200 KB
- Below-the-fold: < 500 KB
- Все: width ≤ 1920px ✓

Если хочется оптимизации сильнее — конвертировать в WebP (Pillow `save("file.webp", quality=82)`), сохранить JPG как fallback через `<picture>`:
```html
<picture>
  <source srcset="cathedral.webp" type="image/webp">
  <img src="cathedral.jpg" alt="..." loading="lazy">
</picture>
```

**5.2 Critical CSS / Above-the-fold**

Сейчас CSS размазан между:
- `<style>` блок per-page (большой, ~50–100 KB inline)
- `assets/css/site-additions.css` (15 KB)

Проверить что **критический путь рендера** ≤ 14 KB:
- inline только то что нужно для above-the-fold
- остальное — `<link rel="stylesheet">` с `media="print" onload="this.media='all'"` (deferred load)

**5.3 Font-display swap**

Проверить URL Google Fonts:
```
https://fonts.googleapis.com/css2?family=...&display=swap
```
`&display=swap` — обязательно. Иначе FOIT (Flash of Invisible Text) убьёт LCP.

**5.4 Resource hints**

В `<head>` каждой страницы:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="https://upload.wikimedia.org">  <!-- если используешь thumb -->
```

**5.5 JavaScript bundle**

Сейчас единственный внешний JS — `assets/js/main.js` (~12 KB). Проверить:
- Загружается с `defer` атрибутом ✓
- Нет sync `<script>` блокирующих рендер
- Inline `<script>` блоки в page-templates (Schedule calendar, Donate form, Contacts map) — все после контента, не блокируют

**5.6 Caching strategy**

Для production:
```
Cache-Control: public, max-age=31536000, immutable  # для assets с hash в имени
Cache-Control: public, max-age=3600                  # для HTML
```

---

### ФАЗА 6. Документация и план фиксов (30 мин)

Создать `AUDIT-2026-05-{date}.md` в `design-brief/` со структурой:

```markdown
# QA-аудит сайта Войскового собора — финальный отчёт

## Executive Summary
- Общий вердикт: PASS / NEEDS WORK / FAIL
- Топ-3 critical issues
- Готовность к production: %

## Метрики
| Страница | Performance | A11y | Best | SEO | Issues |
|---|---|---|---|---|---|
| index | 95 | 100 | 100 | 100 | 0 |
| ...

## Найденные дефекты
### CRITICAL (блокирует demo)
- [ ] D-001: ...

### HIGH (нужно до demo)
- [ ] H-001: ...

### MEDIUM (после demo)
### LOW (cosmetic)

## Что починено в этой сессии
- ✅ ...

## Acceptance checklist для production
- [ ] HTTPS работает
- [ ] Lighthouse mobile ≥ 90 (Performance)
- [ ] axe-core 0 critical violations
- [ ] Все mailto-формы открываются в Gmail/Outlook
- [ ] Print preview schedule читабельный
- [ ] Schema.org JSON-LD валидный
- [ ] Sitemap.xml валидный
- [ ] robots.txt разрешает индексацию
- [ ] Canonical URLs корректны
- [ ] OG-теги для social sharing (Facebook, VK, Telegram)
- [ ] favicon.svg + apple-touch-icon.png
- [ ] manifest.webmanifest корректный
```

---

## Инструменты которые ты будешь использовать

### Обязательно установить / проверить
- **Claude Preview** (`mcp__Claude_Preview__*`) — локальный сервер для статичного сайта (порт 8774). Конфиг уже есть в `.claude/launch.json` (server `church-preview`)
- **Playwright MCP** (`mcp__playwright__*`) — кросс-браузерное тестирование, скриншоты, eval JS. Загрузить через ToolSearch если deferred
- **WebFetch** + **WebSearch** — для PageSpeed Insights API, W3C validator, Schema.org validator
- **Bash + Python (Pillow)** — bulk-аудиты, скрипты

### Уже в репо (`scripts/`)
- `audit-links.py` — broken links + anchors + duplicate IDs (запускать первым)
- `audit-buttons.py` — нерабочие кнопки и CTA
- `optimize-photos.py` — Pillow batch-оптимизация
- `replace-footer.py` — унификация footer
- `fix-prayer-requests.py` — конвертация кнопок треб

### Команды для старта
```bash
# 1. Перейти в репо
cd "C:\CLOUDE_PR\Церковь\github-staging"

# 2. Запустить базовые аудиторы (уже работают)
python scripts/audit-links.py
python scripts/audit-buttons.py

# 3. Запустить preview-сервер
# Через Claude Preview MCP: mcp__Claude_Preview__preview_start --name church-preview
# Откроется на http://localhost:8774

# 4. Подключить Playwright (если deferred — загрузить через ToolSearch)
# query: "playwright" max_results: 30 — загрузит весь набор

# 5. Запустить Lighthouse (если есть Node.js)
npx lighthouse http://localhost:8774/ --output html --output-path ./audit-reports/index.html
# или через PageSpeed Insights API (HTTP)
```

---

## Лучшие практики которые применяешь

### 1. Brutal honesty
Не приукрашиваешь. Если страница имеет 47 ошибок — пишешь «47 ошибок». Если что-то нельзя проверить локально (например cookies на проде) — пишешь «Проверить вручную после деплоя».

### 2. Automation first
13 страниц × 6 фаз = 78 проверок. Вручную = 8 часов. Автоматизированно = 1 час. Используй скрипты.

### 3. Severity over count
1 critical issue важнее 50 cosmetic warnings. Сортируй по бизнес-impact:
- **CRITICAL**: блокирует основной user flow (нельзя пожертвовать, нельзя заказать требу, форма не работает, страница не грузится)
- **HIGH**: ухудшает UX заметно (битая ссылка в навигации, contrast < 4.5:1, mobile drawer не закрывается)
- **MEDIUM**: косметика заметная (alt-теги пропущены, scrollbar дёргается)
- **LOW**: чистка (дубликат ID который не используется, лишний whitespace)

### 4. Confidence ≥ 75
Не репортишь баг если confidence < 75% что это реальный баг. Не репортишь false positive (как было с CTA-regex в прошлой сессии).

### 5. Reproduce → Fix → Verify
Любая правка следует циклу:
1. Воспроизвести баг (через Playwright или manual)
2. Исправить
3. Перезапустить тест → должно стать зелёным
4. Записать в отчёт «✅ verified»

### 6. Surgical changes
Не рефакторишь работающее. Если функция работает но «стиль не такой» — не трогаешь. Только баги.

### 7. Document everything
Каждая правка — в Git commit с conventional commits. Каждый дефект — с reproduction steps. Acceptance checklist для production — самый важный артефакт.

---

## Что в конце сессии должно быть

1. **`design-brief/AUDIT-FINAL-2026-05.md`** — отчёт со всеми findings, severity, fix status
2. **Все CRITICAL и HIGH дефекты — починены и закоммичены**
3. **MEDIUM и LOW** — записаны в TODO с estimated effort
4. **Lighthouse-репорты** в `audit-reports/` (HTML файлы)
5. **Скриншоты** проблемных мест в `audit-reports/screenshots/`
6. **Финальный git commit** с осмысленным сообщением
7. **Push в origin/main** + (опц.) tag `v1.0.0-demo-ready`

---

## Что НЕ делать

- ❌ Не переделывать дизайн (палитру, шрифты, layout)
- ❌ Не добавлять новые функции (формы платежей, личный кабинет)
- ❌ Не менять структуру навигации (10 пунктов в header — остаются)
- ❌ Не трогать `wordpress-theme/` если не относится к найденному дефекту в `docs/`
- ❌ Не лезть в `.claude/`, `~/.claude/` или другие системные директории
- ❌ Не делать `git reset --hard` или `git push --force` без явного согласия
- ❌ Не использовать AI для генерации фотографий или икон (только реальные источники)

---

## Известные ограничения

1. **Нет PHP/Node.js локально** (предположительно) — Lighthouse запускать через WebFetch к PageSpeed Insights API, а не локально
2. **Нет реального хостинга** — security headers и cache strategy записать в TODO для production-деплоя
3. **Нет лицензии Sentry / Datadog** — error monitoring записать в TODO
4. **GitHub Pages CDN** — не настраивается, ОК для прототипа
5. **Mobile проверка через Playwright** — эмуляция viewport, не реальный девайс
6. **PageSpeed Insights** требует публичный URL — использовать GitHub Pages URL https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/

---

## Команда старта (вставь в новую сессию после контекста)

```
Прочитай:
1. C:\CLOUDE_PR\Церковь\NEXT-SESSION-AUDIT-PROMPT.md (этот документ)
2. C:\CLOUDE_PR\Церковь\github-staging\scripts\audit-links.py
3. C:\CLOUDE_PR\Церковь\github-staging\scripts\audit-buttons.py
4. C:\CLOUDE_PR\Церковь\github-staging\design-brief\AUDIT-2026-05.md (если есть — предыдущий отчёт)

Затем последовательно выполни ФАЗЫ 1–6.
В конце каждой фазы — пиши промежуточный отчёт.
В конце сессии — финальный отчёт + git push.

Работай автономно. Если есть выбор — выбирай сам и фиксируй в отчёте.
Brutal honesty. 75% confidence threshold. Surgical changes.
```

---

## Запасной план если что-то пошло не так

Если **не удаётся запустить Playwright** или Claude Preview — переключиться на ручной режим:
1. Запустить статичный сайт через `python -m http.server 8774 --directory docs`
2. Открыть в браузере: Chrome DevTools → Lighthouse tab → запустить аудит
3. Сохранить отчёты как PDF
4. Использовать Chrome DevTools Coverage tab для проверки unused CSS/JS

Если **PageSpeed API rate-limited** — запустить локально через Lighthouse CLI:
```bash
npm install -g lighthouse
lighthouse http://localhost:8774/index.html --output html --output-path audit-reports/index-mobile.html --emulated-form-factor mobile --only-categories=performance,accessibility,best-practices,seo
```

Если **Wikimedia thumb-URL мигают** (после редизайна часть фото может попасть на 404) — проверить через скрипт:
```python
import urllib.request
for img in ['cathedral-2021-facade.jpg', ...]:
    url = f'https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/assets/images/photos/{img}'
    try:
        urllib.request.urlopen(url)
    except Exception as e:
        print(f'BROKEN: {img} {e}')
```

---

## Бонус: что можно сделать если есть лишний час

- Добавить `OG meta tags` (Open Graph) для VK / Telegram / Facebook sharing
  ```html
  <meta property="og:type" content="website">
  <meta property="og:title" content="Войсковой собор Александра Невского — Краснодар">
  <meta property="og:description" content="...">
  <meta property="og:image" content="https://.../assets/images/photos/cathedral-2021-facade.jpg">
  <meta property="og:locale" content="ru_RU">
  ```
- Twitter Cards (тоже работают для VK)
- `apple-touch-icon.png` 180×180 (для iOS home screen)
- Service Worker для offline (опционально, sw.js + регистрация в main.js)
- Google Search Console verification (meta-tag или DNS TXT)
- Yandex.Webmaster verification (аналогично)

---

**Удачи. Делай хорошо. Если работа = 4 часа, а ты потратил 6 — отлично, значит нашёл больше.
Если уложился в 2 часа — проверь ещё раз, скорее всего что-то пропустил.**
