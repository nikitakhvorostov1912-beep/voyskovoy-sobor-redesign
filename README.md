# Войсковой собор святого благоверного князя Александра Невского

Сайт прихода Войскового собора святого благоверного князя Александра Невского в&nbsp;Краснодаре. Кафедральный храм Кубанского казачьего войска и Екатеринодарской и Кубанской епархии Русской Православной Церкви.

**Адрес:** 350063, г. Краснодар, ул. Постовая, 26
**Телефон:** +7&nbsp;(861)&nbsp;262‑00‑20 · **E-mail:** nevskiy-sobor@mail.ru

## 🌐 Live

<https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/>

| Раздел | Страница |
|---|---|
| 🏛 Главная | [/](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/) |
| 📅 Расписание богослужений | [/schedule.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/schedule.html) |
| 🕯 Заказ треб | [/prayer-requests.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/prayer-requests.html) |
| 🤲 Пожертвование | [/donate.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/donate.html) |
| ⛪ О соборе | [/about.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/about.html) |
| 📜 Летопись | [/history.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/history.html) |
| ✝️ Духовенство | [/clergy.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/clergy.html) |
| 🪔 Святыни | [/icons.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/icons.html) |
| 👥 Жизнь прихода | [/parish-life.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/parish-life.html) |
| 📰 Новости | [/news.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/news.html) |
| 📍 Контакты | [/contacts.html](https://nikitakhvorostov1912-beep.github.io/voyskovoy-sobor-redesign/contacts.html) |

## Структура

```
docs/                              ← GitHub Pages serve
├── index.html                     Главная
├── schedule.html                  Расписание
├── prayer-requests.html           Заказ треб (с рабочей формой)
├── donate.html                    Пожертвование (реквизиты ВТБ)
├── about.html                     О соборе
├── history.html                   Летопись 1853 → 2006
├── clergy.html                    5 священнослужителей
├── icons.html                     Святыни
├── parish-life.html               Хор · Школа · Сестричество · Молодёжь
├── news.html                      Новости
├── contacts.html                  Контакты + Yandex карта
├── 404.html                       Страница не найдена
├── sitemap.xml                    11 URL для поисковиков
├── robots.txt
├── manifest.webmanifest           PWA
└── assets/
    ├── css/                       tokens · reset · base · components · layout
    ├── js/main.js                 mobile menu · forms · copy · modals · reveal-on-scroll
    └── images/
        ├── clergy/                Фото 5 священнослужителей
        └── icons/favicon.svg, sigil.svg

theme/church-kadence-child-dev/    WordPress Kadence-child тема
design-brief/                      Бриф, скриншоты прода, референсы
```

## Дизайн-система

| | |
|---|---|
| Палитра | пергамент `#f5f0e8` · ink `#1a1f2e` · литургическое золото `#c9a961` · страстной бордовый `#8b2635` |
| Шрифты | Cormorant Garamond (заголовки) · Spectral (текст) · PT Sans (UI) |
| Контейнер | `1200px` (`narrow` 720px / `wide` 1400px) |
| Шрифтовая шкала | fluid `clamp()` от mobile до desktop |
| Анимации | `cc-anim` через IntersectionObserver, `prefers-reduced-motion` поддержан |
| A11y | Skip-link, focus-visible 2px outline, aria-current, aria-label, ARIA roles |

## Функциональность

### ✅ Работает на статике (без backend)

- **Заказ треб** — форма заполняется → открывается почтовый клиент с готовым письмом на `nevskiy-sobor@mail.ru`. Fallback: кнопка Telegram, передающая запиской прямо в канал собора.
- **Контактная форма** — то же самое: mailto + Telegram fallback.
- **Реквизиты** — каждое поле копируется одной кнопкой (Clipboard API + fallback на Selection).
- **Quick-amount** — выбор суммы пожертвования через chip-кнопки или ручной ввод.
- **Mobile menu** — гамбургер, ESC закрывает, focus-trap.
- **Sticky header** — тень при скролле.
- **Reading progress** — прогресс-бар на длинных текстах (data-cc-progress).
- **Modal** — модальные окна для формы треб, ESC и backdrop-click закрывают.
- **Yandex карта** — встроена через `<iframe>` с `loading="lazy"`.

### 🔌 Что подключить для полного запуска

1. **CMS / WordPress** — есть готовая Kadence-child тема в `theme/church-kadence-child-dev/`. Backend для CPT `church_service` (расписание), `church_news` (новости), `church_history` уже написан в `functions.php`.
2. **СБП QR** — на странице donate сейчас placeholder. Нужно:
   - В банке ВТБ запросить QR-код для приёма пожертвований.
   - Заменить SVG-плейсхолдер на реальный PNG/SVG QR.
3. **YooKassa / CloudPayments** — для приёма пожертвований картой:
   - Зарегистрировать ИП/ЮЛ на платформе (требуются ИНН/КПП/р/с — все есть).
   - Получить shopId + secretKey.
   - Заменить плейсхолдер-кнопку «СБП» виджетом интеграции.
4. **Реальная Telegram-форма** — сейчас формы через `mailto:`. Если нужен серверный приём:
   - Создать Telegram-бота через @BotFather.
   - Поставить простой serverless (Cloudflare Worker / Vercel Function) с переменной BOT_TOKEN.
   - Заменить `mailto:` на `fetch('/api/order')`.

## Дизайн-система: токены

Все цвета, шрифты, отступы — в `docs/assets/css/tokens.css`:

```css
--color-paper: #f5f0e8;
--color-ink: #1a1f2e;
--color-gold: #c9a961;
--color-blood: #8b2635;
--font-display: 'Cormorant Garamond', serif;
--font-body: 'Spectral', Georgia, serif;
--font-ui: 'PT Sans', system-ui, sans-serif;
```

Изменение токена — обновляет всю систему.

## Реквизиты прихода (для разработчика)

```
Местная религиозная организация
православный приход войскового собора
святого благоверного князя Александра Невского
г. Краснодара Екатеринодарской и Кубанской епархии
Русской Православной Церкви

ИНН  2309091590
КПП  230901001
ОГРН 1052335002521

Юр. адрес:    350063, г. Краснодар, ул. Красная, д. 1
Факт. адрес:  350063, г. Краснодар, ул. Постовая, д. 26

Р/с   40703810100005000023
Банк  Филиал «Центральный» Банка ВТБ (ПАО) г. Москва
БИК   044525411
К/с   30101810145250000411
```

## Локальный запуск

```bash
cd docs
python -m http.server 8772
# Открыть http://localhost:8772
```

## SEO + социальные сети

- Schema.org `Church` на каждой странице (адрес, координаты, часы работы, телефоны)
- Schema.org `Person` на странице духовенства (5 священнослужителей)
- Open Graph + Twitter Cards
- canonical URL
- sitemap.xml с 11 URL
- robots.txt с разрешением полной индексации
- manifest.webmanifest для PWA

## Стек

Чистый HTML5 + CSS3 + Vanilla JavaScript (без фреймворков и сборщиков). Все ресурсы серверятся напрямую с GitHub Pages. Загрузка главной страницы — менее 50 KB включая шрифты.

## Деплой

- GitHub Pages: `main` branch, folder `/docs`
- При push в `main` сайт пересобирается автоматически за ~30 секунд
- WP-тема в `theme/church-kadence-child-dev/` — для будущей миграции на полноценный WordPress

## Контакты

- Сайт: <https://alexander-nevskiysobor.ru> (текущий действующий)
- ВКонтакте: <https://vk.com/voyskovoysoborkrasnodar>
- Telegram-канал: <https://t.me/alexnewsobor>
