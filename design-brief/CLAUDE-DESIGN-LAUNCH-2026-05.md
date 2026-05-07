# Стартовая команда для Claude Design — единый объединённый проект

> Дата: 2026-05-06
> Цель: один проект «Войсковой Собор Александра Невского — сайт» из 12 страниц на ОДНОЙ Design System.
> Предусловие: пользователь предварительно удалил мусор из claude.ai/design (см. ниже).

---

## ⚙️ Что сделать ДО открытия Claude Design

Зайти в claude.ai/design и:

1. **Удалить** проекты-мусор:
   - `метаданные` (`/p/e71d954c-8048-4c4c-8264-86b43d7e88c4`)
   - `тест` (`/p/62c114dc-6878-403f-95f5-de2baf37efde`)

2. **Удалить** дубликат Design System:
   - Открыть оба `Design System` (`/p/3ac4d8ca-...` и `/p/66c777a7-...`)
   - Сравнить даты последнего изменения и токены
   - **Удалить более старый**, оставить актуальный

3. **Создать новый проект**:
   - Кнопка **New prototype**
   - Имя: **«Войсковой Собор Александра Невского — сайт»**
   - Design system: оставшийся (НЕ дубликат)
   - Тип: **High fidelity**

4. Открыть чат внутри нового проекта — скопировать туда весь блок ниже.

---

## 🚀 Стартовая команда (копировать в чат Claude Design)

```
Создаю единый объединённый проект сайта Войскового Собора Александра 
Невского (Краснодар, ул. Постовая 26) — 12 кликабельных страниц на 
ОДНОЙ Design System.

═══════════════════════════════════════════════════════════════
1. ИСТОЧНИК ИСТИНЫ — читать в ПОРЯДКЕ
═══════════════════════════════════════════════════════════════
Все материалы лежат в C:\CLOUDE_PR\Церковь\github-staging\

(а) DESIGN-DIRECTION — главный POV
  → design-brief/DESIGN-DIRECTION.md  (POV «Литургическая монументальность»)
  → design-brief/MOODBOARD.md         (8 референсов, Optina + Foma + OCA)
  → design-brief/PALETTE.md           (текущие токены)

(б) Аудит и текущее состояние
  → design-brief/AUDIT-2026-05.md     (12 страниц + 35 проблем + ассеты)
  → design-brief/PROD-VS-DEV.md       (live ≠ dev, миграция нужна)
  → design-brief/CONTEXT.md           (карта WP-темы)

(в) Текущий HTML — как код-источник для рестилинга
  → docs/index.html, docs/about.html, docs/history.html, docs/schedule.html, 
    docs/prayer-requests.html, docs/donate.html, docs/clergy.html, 
    docs/parish-life.html, docs/icons.html, docs/news.html, 
    docs/contacts.html, docs/404.html

═══════════════════════════════════════════════════════════════
2. ЧТО УЖЕ ИСПРАВЛЕНО В КОДЕ — НЕ ОТМЕНЯТЬ
═══════════════════════════════════════════════════════════════
Перед твоим прибытием 6 мая 2026 в HTML внесены 25+ точечных правок. 
НЕ переделывай эти места обратно — они верные.

✅ Меню header унифицировано на 10 пунктов во всех 12 файлах:
   Главная · О соборе · История · Расписание · Заказ треб · Духовенство · 
   Приход · Святыни · Новости · Контакты + CTA «Пожертвовать»
   У каждой страницы свой class="is-active" + aria-current="page"

✅ index.html quick-links — 3/3 ссылок указывают на правильные target URL
✅ index.html schedule-foot CTA → prayer-requests.html
✅ index.html footer-ссылки в «Жизнь прихода» и «Прихожанам» — правильные
✅ index.html хардкод даты заменён на «См. расписание» (динамика — задача 
   будущего)
✅ index.html JS sticky-shadow исправлен (querySelector('.uheader')) + 
   добавлена проверка prefers-reduced-motion для параллакса
✅ schedule.html SSR-дубликаты убраны через JS-cleanup в начале <script>
✅ schedule.html «Записать имя →» — все 4 ссылки ведут на prayer-requests
✅ schedule.html pamyatki — правильные target URL
✅ schedule.html VK/YouTube — реальные URL (vk.com/voyskovoysoborkrasnodar, 
   t.me/alexnewsobor)
✅ schedule.html footer «Священство» → clergy.html, «Воскресная школа» → 
   parish-life.html#school
✅ schedule.html имена клириков (Григорий/Олейников) → реальные 
   (прот. Иоанн Гармаш, иер. Александр Клочков)
✅ prayer-requests цены унифицированы (все «X ₽»)
✅ prayer-requests footer контакты → mailto:/tel:/https:/Telegram
✅ donate «Положение о ПД» → privacy.html (страницу-заглушку нужно создать)
✅ icons.html цитата → «Честь, воздаваемая образу, восходит к первообразу» 
   (Свт. Василий Великий, догмат VII Вселенского Собора)
✅ history.html — невалидные <link> теги внутри <svg> удалены
✅ clergy.html footer «Краснодар · 1872» (унифицировано с index/about)
✅ about.html архитектор — «Иван Денисович Черник, ученик К. А. Тона» 
   (унифицировано с history)
✅ contacts.html «Карта проезда» → реальный yandex.ru/maps URL

═══════════════════════════════════════════════════════════════
3. ДИЗАЙН-СИСТЕМА (одна, без дублей)
═══════════════════════════════════════════════════════════════

Палитра — РОВНО 4 группы:
  paper:    #f5f0e8 (фон), #efe8dc (карточки), #ebe2d0 (углубления)
  ink:      #1a1f2e (текст/шапка), #2a3144 (приглушённый), 
            rgba(74,81,102,0.5) (вспомогательный)
  gold:     #c9a961 (акценты), #a78843 (hover), #e0cd97 (soft фон), 
            rgba(201,169,97,0.32) (gold-line)
  burgundy: #8b2635 (donate/важное), #6e1d29 (hover)

НЕТ цветов вне 4 групп. НЕТ пастельных градиентов, НЕТ glassmorphism, 
НЕТ teal/purple/неоновых акцентов.

Шрифты:
  display:  Cormorant Garamond 400/500/600/700
  decor:    Ruslan Display 400 ⚠️ ОБЯЗАТЕЛЬНО подключить (сейчас в HTML 
            есть fallback на Cormorant italic — это бракованно по брифу)
  body:     Spectral 400/500
  ui:       PT Sans 400/700
  numeric:  Spectral tabular-nums (даты, время, цены)

Spacing scale: 4 / 8 / 12 / 16 / 24 / 32 / 48 / 60 / 80 / 100

Декор-элементы (в DS как переиспользуемые компоненты):
  · Ornament-divider (византийский крест с виноградной лозой) — у нас 
    уже есть в index, перенести в DS как <symbol id="ornament">
  · Sigil — двуглавый орёл войска. Сейчас в коде 3-4 разных стилизации 
    (грубый щит в header, более detailed орёл в footer index, 4-линейный 
    крест в clergy footer). Сделать ОДИН канонический sigil-eagle и 
    использовать везде — favicon + header + footer + 404
  · Drop-cap (1-я буква параграфа золотом, 4× размер, серифный)
  · Gold-leaf rule — тонкая золотая линия для special-карточек
  · Quote с «ёлочками» «...» и золотой подписью

═══════════════════════════════════════════════════════════════
4. ЧТО ТРЕБУЕТСЯ СОЗДАТЬ В CLAUDE DESIGN (визуальные правки)
═══════════════════════════════════════════════════════════════

ВЫСШИЙ ПРИОРИТЕТ — Mobile gamburger menu (P0):
  Сейчас на <860px navigation скрывается, а гамбургер-кнопка отсутствует — 
  пользователь на телефоне теряет навигацию. Нужно:
  · Иконка-гамбургер в правом верхнем углу uheader на <860px
  · Click → fullscreen overlay (фон --ink, текст --paper)
  · 10 пунктов меню в столбик, шрифт ui 18px letter-spacing 0.18em
  · ESC закрывает + клик на overlay-фон закрывает
  · Focus-trap внутри overlay (Tab не выходит)
  · aria-expanded на кнопке, aria-modal на overlay
  · prefers-reduced-motion: убирает slide-анимацию

ВЫСОКИЙ ПРИОРИТЕТ — Photo-slot'ы (P0/P1):

  index.html (P0):
  · Hero-фон → slot для фотопанорамы собора (3840×2160). Сейчас inline 
    SVG-силуэт собора-плейсхолдер (data:image/svg+xml на line 287)
  · Hero icon Александра Невского → slot для цифровой копии иконы. Сейчас 
    inline SVG примитивный портрет (lines 1135-1256)
  · About-photo → slot для архивной sepia-фотографии. Сейчас inline SVG
  · 3 news-cards → slot для реальных снимков. Сейчас 3 inline SVG

  history.html (P0):
  · Cover → slot для архивной литографии собора XIX в. Сейчас inline 
    SVG-силуэт (комментарий «placeholder until photo asset is provided»)
  · ДОБАВИТЬ embedded архивные фото в текст глав (мин. 5 штук): 
    1) Иоанн Карташевский †1928, 2) разрушение 1932, 3) освящение 2006 
    Алексием II, 4) интерьер собора, 5) звонница

  donate.html (P1):
  · photo-placeholder в hero (line 1227) → slot для реального фото фасада 
    собора (3000×2000). Сейчас див с надписью «прикрепите фото»
  · СБП QR (lines 1346-1374) → slot для реального PNG/SVG QR-кода от ВТБ
  · ЮMoney «410 011 ••• ••• 234» → реальный кошелёк прихода

  clergy.html (P1):
  · Card 1 (Протодиакон Максим Кадуров) → slot для фото 1200×1500. 
    Сейчас инициалы «МК» в кружке (placeholder-initials)

  icons.html (P0):
  · Полностью переделать страницу: вместо 7-10 inline SVG-икон сделать 
    grid 3-4 колонки, каждая ячейка — slot для реальной фотографии иконы 
    (1500×2000) с подписью. 5-7 чтимых икон собора:
    — Икона блгв. кн. Александра Невского с частицей мощей (главная)
    — Казанский образ Пресвятой Богородицы
    — Иные чтимые иконы (нужен список от настоятеля)

  parish-life.html (P1):
  · 5 секций (#choir/#school/#sisterhood/#youth/#cossack) — добавить 
    photo-slot для каждой (2400×1600). Сейчас все inline SVG-иллюстрации
  · Добавить CTA «Связаться с куратором» в каждой секции (для записи в 
    школу, в хор и т. д.)
  · Расширить #cossack — это собор кубанского казачества, заслуживает 
    отдельной полноценной страницы (на проде была)

  news.html (P1):
  · 5+ карточек → photo-slot для каждой (1600×900). Сейчас inline SVG
  · СОЗДАТЬ template для single news page (`single-news.html`):
    breadcrumbs · h1 заголовок · meta (дата+категория+автор) · big-photo 
    · article-body с drop-cap · share-buttons · related-news (3 карточки)
    Сейчас ВСЕ «Читать далее» ведут на news.html (саму себя)

  contacts.html (P0):
  · Заменить faux Yandex map (CSS-плейсхолдер lines 988-1027) на реальный 
    iframe yandex.ru/maps с координатами 45.014800° N, 38.971200° E

СРЕДНИЙ ПРИОРИТЕТ — Polish (P2):

  · CSS-вычистить мёртвый код .topbar/.topbar-inner/.sigil в index.html 
    (не используется, header использует .uheader)
  · Добавить `@media (prefers-reduced-motion: reduce)` глобально, 
    отключающий все анимации
  · Добавить `:focus-visible` стили для всех интерактивных элементов 
    (контраст ≥ 3:1 для UI)
  · Schema.org `Church` + `Event` + `Organization` JSON-LD во всех страницах
  · Open Graph + Twitter Cards — единый сценарий
  · Lighthouse a11y ≥ 95, perf ≥ 80, SEO ≥ 95 для каждой страницы

═══════════════════════════════════════════════════════════════
5. КАРТА 12 СТРАНИЦ
═══════════════════════════════════════════════════════════════

Каждая страница (кроме index и 404) — единая структура:
  uheader (sticky, 10 пунктов + CTA) → ribbon (узкий header с цитатой 
  Писания и золотой линией) → секции контента → footer (3-кол + brand 
  с sigil + соцсети + реквизиты) → mobile-stickybar (если применимо)

Только главная (index) имеет полный hero (icon + параллакс-фон + 
schedule-CTA).

| # | Страница       | Особенности                                       | Главная задача DS               |
|---|----------------|---------------------------------------------------|---------------------------------|
| 1 | index          | Полный hero, ornament-divider, quick-links×4, news×3 | photo-slots (фон/icon/about/3news) |
| 2 | about          | drop-cap, 5 stats, 3 cards «Внутри собора»        | финиш типографики               |
| 3 | history        | 7 глав, 3 pullquote, помянник, timeline, drop-cap | embed архивных фото             |
| 4 | schedule       | calendar grid, sidebar svc-panel, mobile-list, pamyatki×3, info-block | визуальная полировка             |
| 5 | prayer-requests| breadcrumbs, каталог 10 треб, FAQ×6, 3 способа    | рабочие form-кнопки             |
| 6 | donate         | quick-amounts, 3 paymethod tabs, реквизиты, allocation | photo+QR+ЮMoney slots           |
| 7 | clergy         | featured (настоятель), grid 4 клириков с био      | slot фото для Кадурова          |
| 8 | parish-life    | anchor-nav (5), 5 секций                          | photo-slots × 5                 |
| 9 | icons          | grid чтимых икон (5-7)                            | photo-slots × 5-7               |
| 10| news           | grid карточек 5+                                  | photo-slots + single-template   |
| 11| contacts       | 3 cards адрес/тел/email + map + hours + transport | реальный yandex iframe          |
| 12| 404            | err-cover + 2 CTA (главная + расписание)          | оставить как есть               |

═══════════════════════════════════════════════════════════════
6. ЧТО НЕ ДЕЛАТЬ (anti-patterns)
═══════════════════════════════════════════════════════════════
✗ НЕ изобретать новые цвета вне 4 групп
✗ НЕ добавлять scroll-reveal на каждом блоке (только hero one-shot anim 
  на главной)
✗ НЕ использовать emoji в production UI (только SVG-спрайт)
✗ НЕ создавать дубликат Design System
✗ НЕ менять URL-схему (latin slug-и в docs/*.html остаются)
✗ НЕ переделывать уже исправленные места (см. секцию 2 выше)
✗ НЕ добавлять параллакс на каждом блоке (только hero на главной)
✗ НЕ использовать spring/bounce easing — это собор, не shopping app
✗ НЕ ставить кнопки CTA на каждой секции — только в смысловых местах
✗ НЕ оставлять inline-стили в шаблонах — всё через классы и токены

═══════════════════════════════════════════════════════════════
7. ПОРЯДОК РАБОТЫ
═══════════════════════════════════════════════════════════════

Phase 1 — Design System (1 день):
  Палитра + типография + spacing + 8 базовых компонентов 
  (uheader, mobile-overlay, footer, btn, card, ribbon, ornament, quote)

Phase 2 — Universal templates (1 день):
  uheader с mobile-toggle, footer 3-кол с реквизитами, ribbon-узкий-
  header, sigil-двуглавый-орёл canonical SVG

Phase 3 — Index + about + 404 (1 день):
  Главная (полный hero), about (drop-cap), 404 (как есть)

Phase 4 — Содержательные страницы (2 дня):
  history (7 глав + photo-slots в текст), clergy (5-я карточка), 
  schedule (sidebar + calendar polish), prayer-requests (рабочая форма)

Phase 5 — Контент-зависимые (1 день):
  donate (photo + QR + ЮMoney slots), icons (grid чтимых икон с 
  photo-slots), parish-life (5 секций с фото), news (grid + single 
  template), contacts (real iframe yandex)

Phase 6 — Polish + handoff (1 день):
  A11y (focus-visible, prefers-reduced-motion), Schema.org, OG, 
  handoff bundle через api.anthropic.com/v1/design/h/...

ИТОГО: 7 дней одного дизайнера, либо 4 дня с параллельной работой.

═══════════════════════════════════════════════════════════════
8. ЧТО ВЫДАТЬ В КОНЦЕ
═══════════════════════════════════════════════════════════════

· Handoff bundle URL (api.anthropic.com/v1/design/h/...)
· ZIP с финальными HTML + assets/icons/sprite.svg
· Список photo-slots с инструкциями (resolution, alt-text, размещение)
· DESIGN.md с описанием Design System (палитра, токены, компоненты)

Активируй skill `frontend-design`, прочитай все 7 .md из (а)+(б), 
посмотри текущий HTML из (в). Стартуй с Phase 1.

После каждой фазы — короткий отчёт + переход к следующей.
Все строки на русском (UI, alt, aria-label, кнопки, формы, error 
messages).
```

---

## 📥 После handoff bundle от Claude Design

В новом чате Claude Code в `C:\CLOUDE_PR\Церковь\github-staging\`:

```
/from-design <URL bundle>
```

Это активирует skill `claude-design-handoff` — bundle распакуется в 
`prototypes/forms/<имя>/` (или `frontend-prototype/`), Claude Code сравнит 
с `docs/`, применит как diffs.

После handoff Claude Code:
1. Применит остальные исправления (privacy.html stub, обработчики кнопок)
2. Подключит реальные ассеты (когда пришлёшь)
3. Пройдёт `/quality-gate` + Lighthouse + linkchecker
4. Деплой на GitHub Pages (через `/deploy` или вручную)

---

## 📋 Что нужно прислать после Phase 6 (acquisition list)

См. секцию 4 + секцию 7.3 в [AUDIT-2026-05.md](AUDIT-2026-05.md):
- Реальные фотографии собора, икон, духовенства, жизни прихода
- СБП QR от ВТБ
- YooKassa shopId/secretKey
- ЮMoney реальный кошелёк
- Положение о ПД (текст для privacy.html)
- Подтверждение реальных имён клириков для расписания
- Решение вопроса об архитекторе (сейчас унифицировано на «Иван Денисович 
  Черник»)
