# LAUNCH — старт сессии Claude Design

> **Скопируй этот блок целиком в новый чат Claude Code, открытый в папке `C:\CLOUDE_PR\Церковь\church-kadence-child-dev\`.**

---

## 🚀 Стартовая команда (копировать в чат)

```
Активируй skill `frontend-design` (alias `5128e1865d67:frontend-design`).

Контекст и материалы лежат в `C:\CLOUDE_PR\Церковь\design-brief\` —
прочитай в этом порядке:
  1. DESIGN-DIRECTION.md — главный POV «Литургическая монументальность»
  2. MOODBOARD.md — какие референсы что дают
  3. PROD-VS-DEV.md — критичная находка: live-сайт на другой теме, нужна миграция
  4. CONTEXT.md — карта локальной dev-темы Kadence-child v2.0.0
  5. PALETTE.md — текущие токены
  6. ISSUES.md — 22 проблемы с pointer'ами файл:строка
  7. TASKS.md — бэклог 25 задач в 4 фазах

Дополнительно изучи скриншоты:
  - `screenshots/*.png` — 18 кадров live-сайта (9 страниц × desktop + mobile)
  - `references/orthodox-media/*.png` — 6 русских ортодокс-медиа
  - `references/church-sites/*.png` — 6 церковных сайтов (рус + англ)

Текущий код темы — рядом, в `..\church-kadence-child-dev\`.

Теперь без лишних вопросов начни Phase 1 из DESIGN-DIRECTION.md
(Foundation): почини кодировку style.css, заведи theme.json,
вынеси inline-стили в components.css, разведи дубль шрифтов.

Работай small-steps: после каждого фикса показывай результат
и переходи к следующему. Все строки на русском, маркеры
«// Доработка START/END» при правке существующих файлов.
В конце Phase 1 — короткий отчёт что сделано и переход к Phase 2 (Hero & header).
```

---

## 📦 Что в брифе (полная карта)

```
C:\CLOUDE_PR\Церковь\
├── church-kadence-child-dev\           ← ИСХОДНЫЙ КОД (правим тут)
│   ├── style.css            58 KB     (есть mojibake — починить)
│   ├── functions.php        69 KB     (1530 строк, 32 секции, не трогать backend)
│   ├── front-page.php                 (главная — переделать hero)
│   ├── header.php / footer.php
│   ├── page-*.php                     (8 шаблонов — вынести inline-стили)
│   ├── single-*.php / archive-*.php
│   ├── inc/class-walker-nav-menu.php  (кастом меню — оставить)
│   ├── template-parts/stats.php
│   ├── assets/
│   │   ├── css/animations.css         (0 байт — наполнить!)
│   │   └── js/main.js                 (14 KB)
│   └── database.sql                   (1.4 MB — для локального WP, опц.)
│
└── design-brief\                       ← НАВИГАЦИЯ + РЕФЕРЕНСЫ
    ├── LAUNCH.md            ⭐ ЭТОТ ФАЙЛ
    ├── DESIGN-DIRECTION.md  ⭐ Bold POV «Литургическая монументальность»
    ├── MOODBOARD.md         ⭐ 8 референсов с конкретными приёмами
    ├── PROMPT.md                       (старый верх-уровневый промт)
    ├── PROD-VS-DEV.md                  (live ≠ dev — критичная находка)
    ├── CONTEXT.md                      (карта dev-темы)
    ├── PALETTE.md                      (текущие токены)
    ├── ISSUES.md                       (22 проблемы)
    ├── TASKS.md                        (25 задач P0-P3)
    ├── README.md
    │
    ├── screenshots\                    (live-сайт alexander-nevskiysobor.ru)
    │   ├── home-desktop.png  / home-mobile.png
    │   ├── schedule-desktop.png / -mobile.png
    │   ├── contacts-desktop.png / -mobile.png
    │   ├── about-desktop.png / -mobile.png
    │   ├── donate-desktop.png / -mobile.png
    │   ├── news-desktop.png / -mobile.png
    │   ├── history-desktop.png / -mobile.png
    │   ├── clergy-desktop.png / -mobile.png
    │   ├── choir-desktop.png / -mobile.png
    │   └── capture.ps1                 (для повторного снятия)
    │
    ├── references\
    │   ├── capture-refs.ps1            (для повторного снятия)
    │   ├── orthodox-media\             (6 русских медиа)
    │   │   ├── foma-ru.png             ⭐ современный медиа-layout
    │   │   ├── predanie-ru.png
    │   │   ├── pravoslavie-ru.png
    │   │   ├── patriarchia-ru.png
    │   │   ├── pravmir-ru.png
    │   │   └── blagovest-info.png
    │   ├── church-sites\               (6 церковных)
    │   │   ├── optina.png              ⭐ ГЛАВНЫЙ референс эстетики
    │   │   ├── oca.png                 ⭐ Структурный ортодокс
    │   │   ├── holy-trinity-jordanville.png ⭐ Героика портретная
    │   │   ├── svots.png
    │   │   ├── saintsabbas.png         (пустой — игнор)
    │   │   └── sretensky.png           (пустой — игнор)
    │   ├── typography\                 (пустая — для будущих)
    │   └── ornament\                   (пустая)
    │
    ├── moodboard\                      (рабочая папка для итераций)
    └── output\                         (рабочая папка для draft'ов)
```

---

## 🎯 4 фазы (как в DESIGN-DIRECTION.md)

| Фаза | Что | Время | DoD |
|---|---|---|---|
| **P1 Foundation** | Кодировка, theme.json, components.css | 1 день | Inline-стили вынесены, шрифты в одном месте, контрасты ОК |
| **P2 Hero & header** | Иконо-баннер с проды, sticky header, sigil | 1-2 дня | Hero на главной + chrome-узкий header на остальных |
| **P3 Components** | Карточки, ornament, quote, drop-cap | 1 день | Единая система компонентов |
| **P4 Pages** | Главная, треба, расписание, контакты, footer | 2 дня | 8 страниц по новой DS |
| **P5 Polish** | A11y, perf, schema, OG | 1 день | Lighthouse ≥ 95 a11y / 80 perf / 95 SEO |

**Итого:** 6-7 дней одного разработчика на визуальный редизайн.

---

## ⚠️ Декларация ограничений (для Claude Design)

1. **НЕ трогать backend** — CPT, AJAX, Customizer-секции, walker, мета-боксы остаются как есть. Это работает, не ломать.
2. **НЕ переписывать functions.php целиком** — все правки точечные, в маркерах START/END.
3. **НЕ менять URL-схему** — Kadence pretty-permalinks, кастомные slug'и CPT остаются.
4. **НЕ интегрировать реальный YooKassa** — это P2-задача после визуала, отдельной сессией.
5. **НЕ обещать что выкатим на прод** — мы делаем dev-версию, миграция — отдельный этап.
6. **НЕ удалять `Kadence` parent** — `Template: kadence` в `style.css` остаётся.

---

## ✅ Что разрешено

1. Создавать новые файлы: `theme.json`, `assets/css/components.css`, `assets/icons/sprite.svg`, новые `template-parts/*.php`.
2. Удалять или заменять `assets/css/animations.css` (сейчас пустой).
3. Перекодировать `style.css` целиком в UTF-8.
4. Выносить inline-стили из шаблонов в classes.
5. Создавать новые компонентные шаблоны (например `template-parts/hero.php`).
6. Менять `Customizer` — добавлять новые controls (но не ломать существующие).
7. Качать иконо-баннер с прода и класть в `assets/img/` (если нужно для Hero — попросить пользователя подтвердить URL).

---

## 🔑 Ключевые ссылки

- **Live-сайт:** <https://alexander-nevskiysobor.ru>
- **Главный референс эстетики:** <https://www.optina.ru/>
- **Главный референс структуры:** <https://www.oca.org/>
- **Главный референс типографики:** <https://foma.ru/>

---

## 💬 Ответы на типовые вопросы (от Claude Design к user)

| Вопрос | Ответ |
|---|---|
| Можно ли сменить parent Kadence на другую тему? | Нет. Остаётся Kadence. |
| Какой источник для hero-баннера? | Скачать с прода: <https://alexander-nevskiysobor.ru/> — там видно фоновую картинку. |
| Реальный YooKassa или заглушка? | Заглушка остаётся. Это не дизайн-задача. |
| Какие шрифты можно подключать? | Только Google Fonts. Cormorant Garamond, Spectral, Ruslan Display, PT Sans, PT Serif, Inter, Lora. |
| Нужно ли поддерживать старые браузеры? | Нет. Современные эверградд (Chrome 110+, Safari 16+, Firefox 110+). |
| Какой минимальный экран? | 360px ширина (старые Android). |
| WordPress версия? | 6.x, PHP 8.0+. |
| Готовый sigil/логотип есть? | Нет — рисуем SVG (двуглавый орёл) сами или используем простой крест с надписью. |
| Прод-сайт мигрирует? | Не сейчас. Сначала визуал dev-темы. |
| Языки кроме русского? | Нет. Только русский. |

---

## 📎 Альтернативный быстрый старт (если хочется минимально)

Если новой сессии или контекста для full-launch нет, можно стартовать так:

```
Прочитай C:\CLOUDE_PR\Церковь\design-brief\DESIGN-DIRECTION.md.
Активируй skill frontend-design.
Сделай Phase 1 из этого документа в одной итерации.
```

Это даст fundament-фазу за 1 шаг. После можно стартовать Phase 2 отдельной командой.
