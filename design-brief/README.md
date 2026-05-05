# Design Brief — доработка темы Войскового Собора Александра Невского

Готовый пакет промта для запуска Claude Design на доработке темы.

## Файлы

| Файл | Назначение |
|---|---|
| **[LAUNCH.md](LAUNCH.md)** | 🚀 Финальная стартовая команда для новой сессии Claude Design — копировать в чат |
| **[DESIGN-DIRECTION.md](DESIGN-DIRECTION.md)** | 🎨 Bold POV «Литургическая монументальность», 4 фазы с DoD |
| **[MOODBOARD.md](MOODBOARD.md)** | 🖼️ 8 референсов с конкретными приёмами и палитрой/типографикой |
| **[PROD-VS-DEV.md](PROD-VS-DEV.md)** | ⚠️ Live ≠ dev: разные темы, разные URL, нужна миграция |
| [PROMPT.md](PROMPT.md) | (старый верхне-уровневый промт, оставлен для истории) |
| [CONTEXT.md](CONTEXT.md) | Полная карта локальной dev-темы: CPT, шорткоды, AJAX, Customizer |
| [PALETTE.md](PALETTE.md) | Текущие токены |
| [ISSUES.md](ISSUES.md) | Аудит локальной темы — 22 проблемы с pointer'ами `файл:строка` |
| [TASKS.md](TASKS.md) | Бэклог: 25 задач в 4 фазах (P0/P1/P2/P3) с DoD и оценками |
| [screenshots/](screenshots/) | **18 PNG live-сайта** (9 страниц × desktop+mobile) |
| [references/](references/) | **12 PNG референсов** (orthodox-media + church-sites) |
| [moodboard/](moodboard/) | Рабочая папка для draft'ов |
| [output/](output/) | Рабочая папка для итераций |

## Как использовать

### ⭐ Рекомендуемый старт

1. Открой новый чат Claude Code в папке `C:\CLOUDE_PR\Церковь\church-kadence-child-dev\`
2. Скопируй блок «Стартовая команда» из [LAUNCH.md](LAUNCH.md) в чат
3. Claude Design активирует skill `frontend-design`, прочитает все материалы и начнёт Phase 1.
4. После каждой фазы — короткий отчёт + переход к следующей.

### Альтернативный быстрый старт

```
Прочитай C:\CLOUDE_PR\Церковь\design-brief\DESIGN-DIRECTION.md.
Активируй skill frontend-design.
Сделай Phase 1.
```

### Через subagent в текущей сессии

```
/agent feature-dev:feature-dev
Используй design-brief/. Стартуй с DESIGN-DIRECTION.md → Phase 1.
```

## Текущее состояние темы

- **Где живёт:** `C:\CLOUDE_PR\Церковь\church-kadence-child-dev\` (локально, скопирована с продa)
- **Основа:** Kadence parent + дочерняя `church-kadence-child-dev` v2.0.0
- **Скоуп:** WordPress + 4 CPT + 6 шорткодов + 3 секции Customizer + платежи + Telegram-уведомления
- **Размер:** 13 шаблонов, functions.php 1530 строк, style.css 58 KB
- **Уровень готовности:** работающая production-тема с инлайн-стилями и недопиленным функционалом — нужен системный редизайн без слома backend

## Бренд

**Войсковой Собор святого благоверного князя Александра Невского**
г. Краснодар, ул. Постовая 26
<https://alexander-nevskiysobor.ru>

Палитра — традиционная православная: `#1a1a2e` тёмно-синий + `#c9a961` золото + `#8b2635` бордовый + `#faf8f5` кремовый.

Шрифты: Cormorant Garamond, Playfair Display, Ruslan Display, Inter.

## Что НЕ сделано в этом брифе (отдельные задачи для пользователя)

- ✅ Скриншоты live-сайта — **сделаны** (18 PNG в `screenshots/`)
- Lighthouse-отчёт по производительности
- Решение по опции A/B/C из [PROD-VS-DEV.md](PROD-VS-DEV.md) (продолжать редизайн / улучшать прод / гибрид)
- Подтверждение технических решений (видео/фото/SVG в hero, реальный YooKassa shop, источник «Святого дня», судьба плагина GiveWP)
- Контент для скрытых страниц (Духовенство, Воскресная школа и т.д.)
- Финальный логотип/sigil
- Доступ к админке прода (нужен для миграции данных и понимания плагинов)

---

**Дата сборки брифа:** 2026-05-05
**Автор брифа:** Claude (Opus 4.7) через анализ исходников темы
