# Войсковой Собор Александра Невского — превью редизайна

> Hi-Fi мокапы и WP-тема для приходского сайта <https://alexander-nevskiysobor.ru>.
> Дизайн-направление: **«Литургическая монументальность»**.

## 🌐 Live preview

- 🏛 [Главная](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/home.html)
- 📅 [Расписание богослужений](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/schedule.html)
- 🕯 [Заказ треб](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/services-order.html)
- 🤲 [Пожертвование](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/donate.html)
- 📜 [История собора](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/history.html)
- 📍 [Контакты](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/contacts.html)
- ▶️ **[Галерея всех 6 мокапов](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/)**

## Структура

```
voyskovoy-sobor-redesign/
├── docs/                              GitHub Pages serve
│   ├── index.html                     Галерея 6 мокапов
│   ├── home.html
│   ├── schedule.html
│   ├── services-order.html
│   ├── donate.html
│   ├── history.html
│   └── contacts.html
├── theme/
│   └── church-kadence-child-dev/      WordPress тема (Kadence-child)
└── design-brief/                      Бриф, контекст, референсы
    ├── DESIGN-DIRECTION.md            POV «Литургическая монументальность»
    ├── MOODBOARD.md
    ├── PROD-VS-DEV.md                 live ≠ dev (важно)
    ├── CONTEXT.md / ISSUES.md / TASKS.md
    ├── screenshots/                   18 PNG прода (desktop + mobile)
    └── references/                    12 PNG референсов (Optina, OCA, Foma)
```

## Дизайн-система

| | |
|---|---|
| Палитра | пергамент `#f5f0e8` · ink `#1a1f2e` · золото `#c9a961` · бордовый `#8b2635` |
| Шрифты | Cormorant Garamond · Spectral · PT Sans · Ruslan Display |
| Тон | Refined classical · XIX-век × современная читаемость |
| Hero | Иконо-баннер с иконой князя + цитата «Не в силе Бог, а в правде» |
| Декор | Ornament-divider (крест с виноградной лозой), drop-cap, ken-burns в hero истории |

## Тестирование

### HTML-мокапы (без WP)
Открой `docs/*.html` в браузере — это standalone, работает оффлайн.
Для GitHub Pages → серверится автоматически из `docs/`.

### WP-тема
1. **Local by Flywheel** (рекомендую): <https://localwp.com>
2. Скопируй `theme/church-kadence-child-dev/` в `wp-content/themes/`
3. Установи Kadence parent (бесплатно из WP репозитория)
4. Активируй child-тему
5. Создай pages со slug'ами: `schedule`, `services-order`, `donate`, `history`, `contacts`
6. Привяжи Page Templates (в правой панели редактора страниц)
7. **Customize → ⛪ Пожертвования** — заполни ИНН/КПП/Р.с/БИК

## Известные ограничения мокапов

- 01-Главная — большой файл (1.3 MB), грузится медленно. Это бандлер от Claude Design — не оптимизирован для прода.
- Все мокапы — **прототипы**. Реальный YooKassa, реальные фото собора, GiveWP-плагин — нужно подключать на этапе интеграции.
- Шрифты Ruslan Display нет в Google Fonts — fallback на Cormorant Garamond italic.
- В hero-cover Истории — стилизованный SVG-силуэт собора. Реальное фото вставляется по месту.
