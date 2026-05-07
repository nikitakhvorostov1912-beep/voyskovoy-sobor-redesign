# Быстрый старт для следующей сессии — глубокий QA-аудит

> Это сокращённая версия. Полный промпт со всеми деталями — в `NEXT-SESSION-AUDIT-PROMPT.md` (рядом).
> Скопируй блок ниже целиком и вставь в новую сессию Claude Code.

---

## ⬇️ КОПИРУЙ ЭТО В НОВУЮ СЕССИЮ ⬇️

```
Ты — senior QA + accessibility + performance специалист.
Задача: глубокий production-ready аудит сайта Войскового собора Александра Невского.
НЕ переделываешь дизайн (литургическая монументальность принята).
Brutal honesty. Auto mode. 75% confidence threshold. Surgical changes.

Repo: C:\CLOUDE_PR\Церковь\github-staging\
GitHub: nikitakhvorostov1912-beep/voyskovoy-sobor-redesign (branch main)
GitHub Pages: https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/
13 страниц: index, about, history, schedule, prayer-requests, clergy, parish-life, icons, news, contacts, donate, privacy, 404

Прочитай в первую очередь:
1. C:\CLOUDE_PR\Церковь\NEXT-SESSION-AUDIT-PROMPT.md  ← полный промпт со всеми фазами
2. C:\CLOUDE_PR\Церковь\github-staging\scripts\audit-links.py
3. C:\CLOUDE_PR\Церковь\github-staging\scripts\audit-buttons.py

Затем выполни 6 фаз последовательно:
ФАЗА 1. Авто-аудит инструментами (Lighthouse, axe-core, W3C, broken-link-checker, Schema.org validator, image audit) — 1.5-2ч
ФАЗА 2. Cross-browser + responsive (Playwright Chromium/Firefox/WebKit, 5 viewports, touch targets, print) — 1ч
ФАЗА 3. Формы и интерактив (mailto submit, валидация, Schema.org расширение) — 1ч
ФАЗА 4. Security и Compliance (HTTPS, CSP, 152-ФЗ, external links rel) — 45мин
ФАЗА 5. Performance (image optimization, critical CSS, font-display, resource hints) — 45мин
ФАЗА 6. Финальный отчёт + git push — 30мин

После КАЖДОЙ фазы — промежуточный отчёт.
В конце сессии — `design-brief/AUDIT-FINAL-2026-05.md` + git commit + push.

Целевые метрики:
- Lighthouse Performance ≥ 90 (mobile), Accessibility ≥ 95, SEO 100
- Core Web Vitals: LCP < 2.5s, INP < 200ms, CLS < 0.1
- axe-core: 0 critical/serious violations
- broken-link-checker: 0 битых ссылок
- W3C HTML validator: 0 errors

Что НЕ делать:
❌ Не переделывать дизайн / палитру / шрифты
❌ Не добавлять новые функции (платежи, личный кабинет)
❌ Не трогать wordpress-theme/ если дефект не там
❌ Не делать git reset --hard / push --force без согласия

Стартуй с ФАЗЫ 1.1 — Lighthouse / PageSpeed Insights.
Используй PageSpeed Insights API через WebFetch (локального Lighthouse может не быть):
https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=<page>&strategy=mobile

Действуй автономно. Если есть выбор — выбирай сам. Если confidence < 75% — пиши «нужна верификация», не делай слепо.
```

## ⬆️ КОНЕЦ КОПИРУЕМОГО БЛОКА ⬆️

---

## Что подготовить ПЕРЕД новой сессией (опционально, ускорит работу)

### 1. Если есть Node.js — установи Lighthouse CLI
```bash
npm install -g lighthouse @axe-core/cli broken-link-checker html-validator-cli stylelint
```

### 2. Если хочется PageSpeed API без rate-limit — получи free API key
1. Открой https://console.developers.google.com/
2. Создай проект → включи **PageSpeed Insights API**
3. Создай API key
4. Сохрани в файл `~/.pagespeed-key` или передай в сессию

### 3. Локальный preview уже настроен
В `.claude/launch.json` есть конфиг `church-preview` (порт 8774). Старт через `mcp__Claude_Preview__preview_start`.

---

## Что я ожидаю получить в конце аудит-сессии

1. **`design-brief/AUDIT-FINAL-2026-05.md`** — отчёт с executive summary, метриками по страницам, списком дефектов с severity, что починено
2. **Все CRITICAL и HIGH — починены** в коде, закоммичены, запушены
3. **`audit-reports/`** — папка с Lighthouse HTML-репортами (или JSON если HTML не получится) и скриншотами
4. **TODO для production**: что ещё надо доделать вне рамок этой сессии (security headers на хостинге, юр.текст privacy.html от епархии, реальный платёжный шлюз)
5. **Acceptance checklist** — чек-лист «готово к demo» с галочками

---

## Если в следующей сессии возникнут вопросы

Все детали (как чем тестировать, какие конкретные команды запускать, как обрабатывать edge cases) — в `NEXT-SESSION-AUDIT-PROMPT.md` (полная версия, ~600 строк).

Не нужно повторять уже сделанные исправления — они в git log:
```
65a8f24 feat: redesign + WordPress theme + photos from Wikimedia Commons
434abb6 fix: унификация max-width контентных секций до 1720px
8782f44 fix: убрать регресс — битый kadurov.jpg и неиспользуемый design-system.css
f6febd0 fix: убрать агрессивный CSS, ломавший рендер контента
1539ac5 fix: единый шрифт логотипа на всех 12 страницах
```

Что **уже точно работает** (не тестировать заново, только smoke-проверка):
- ✅ 13 HTML страниц + privacy.html
- ✅ Schema.org JSON-LD на каждой странице
- ✅ Mobile drawer (focus-trap, ESC)
- ✅ Унифицированный footer
- ✅ Все mailto: формы (10× treba + 1 contacts + 3 tier)
- ✅ Все ссылки в шапке/футере резолвятся (нет битых)
- ✅ 10 фото из Wikimedia Commons расставлены
- ✅ canonical на alexander-nevskiysobor.ru (кроме 404 — staging URL у которого, проверить)

Что **НЕ тестировано** (главные кандидаты на CRITICAL findings):
- ❓ Реальная производительность (Core Web Vitals не замерены)
- ❓ Контрастность цветов (WCAG AA — не проверено инструментально)
- ❓ Cross-browser совместимость (только Chromium тестирован через Claude Preview)
- ❓ Mobile touch targets (визуально проверены, инструментально нет)
- ❓ Print preview schedule
- ❓ OG-теги для social sharing (не добавлены)
- ❓ apple-touch-icon (не уверен что есть)
- ❓ Все mailto открываются в Gmail web-client (не тестировалось — только проверена корректность URL)
- ❓ Sitemap.xml + robots.txt валидация
- ❓ 152-ФЗ соответствие реальному тексту privacy.html

Эти точки — приоритет для следующей сессии.
