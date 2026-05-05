# Скриншоты для дизайн-сессии

Эта папка пуста — нужно наполнить скриншотами, чтобы Claude Design имел визуальный контекст.

## Способ 1 — через Chrome MCP (если расширение подключено)

```
1. Открыть https://alexander-nevskiysobor.ru
2. Прогон по страницам:
   - / (главная)
   - /schedule
   - /services
   - /services-order (страница с карточками треб)
   - /donate
   - /history
   - /news
   - /contacts
3. Для каждой — desktop (1440×900) и mobile (390×844) скриншоты
4. Сохранить как home-desktop.png, home-mobile.png, schedule-desktop.png, ...
```

Команда для агента: «Открой сайт собора в Chrome, сделай скриншоты ключевых страниц в desktop и mobile, положи их в `design-brief/screenshots/`».

## Способ 2 — вручную (Win + Shift + S или PrintScreen)

Сохранять с осмысленными именами:
- `home-desktop.png`, `home-mobile.png`
- `schedule-desktop.png`, `schedule-mobile.png`
- `services-order-desktop.png`, `services-order-mobile.png`
- `donate-desktop.png`, `donate-mobile.png`
- `history-desktop.png`, `history-mobile.png`
- `news-desktop.png`, `news-mobile.png`
- `contacts-desktop.png`, `contacts-mobile.png`
- `header-mobile-menu.png` — открытое мобильное меню
- `service-order-modal.png` — модальное окно заказа требы

## Способ 3 — Lighthouse-отчёты

Дополнительно полезно:
```bash
# Из любого терминала с lighthouse CLI:
npx lighthouse https://alexander-nevskiysobor.ru --output html --output-path ./design-brief/screenshots/lighthouse-home.html --form-factor mobile
npx lighthouse https://alexander-nevskiysobor.ru/services-order --output html --output-path ./design-brief/screenshots/lighthouse-services.html --form-factor mobile
```

## Способ 4 — DevTools network

При проблемах с производительностью — скриншот вкладки Network → DOMContentLoaded / Load Time / размер бандла.
