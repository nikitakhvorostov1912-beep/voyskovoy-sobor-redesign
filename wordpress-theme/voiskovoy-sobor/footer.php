<?php
/**
 * Общий футер темы — usite-footer + mobile drawer + wp_footer().
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<footer class="usite-footer" role="contentinfo">
  <div class="usite-footer__inner">
    <div class="usite-footer__brand">
      <div class="usite-footer__brand-row">
        <svg class="usite-footer__sigil" viewBox="0 0 48 48" fill="none" aria-hidden="true">
          <path d="M24 2 L42 9 V25 C42 36 34 43 24 46 C14 43 6 36 6 25 V9 Z" stroke="#c9a961" stroke-width="1.2" fill="rgba(201,169,97,.06)"/>
          <g stroke="#c9a961" stroke-width="1.4" stroke-linecap="square">
            <line x1="24" y1="11" x2="24" y2="38"/>
            <line x1="18" y1="17" x2="30" y2="17"/>
            <line x1="16" y1="22" x2="32" y2="22"/>
            <line x1="18" y1="32" x2="30" y2="29"/>
          </g>
        </svg>
        <div>
          <div class="usite-footer__name">Войсковой Собор</div>
          <div class="usite-footer__sub">Александра Невского · Краснодар</div>
        </div>
      </div>
      <p class="usite-footer__addr">
        ул. Постовая, 26 · Краснодар, 350063<br>
        <a href="tel:+78612620020">+7&nbsp;(861)&nbsp;262-00-20</a><br>
        <a href="mailto:nevskiy-sobor@mail.ru">nevskiy-sobor@mail.ru</a>
      </p>
      <p class="usite-footer__quote">«Дом мой домом молитвы наречётся»<span>Евангелие от Матфея 21:13</span></p>
    </div>

    <nav class="usite-footer__col" aria-label="Приход">
      <h5>Приход</h5>
      <a href="<?php echo esc_url( home_url( '/about' ) ); ?>">О соборе</a>
      <a href="<?php echo esc_url( home_url( '/history' ) ); ?>">История</a>
      <a href="<?php echo esc_url( home_url( '/clergy' ) ); ?>">Духовенство</a>
      <a href="<?php echo esc_url( home_url( '/parish-life' ) ); ?>">Приходская жизнь</a>
      <a href="<?php echo esc_url( home_url( '/contacts' ) ); ?>">Контакты</a>
    </nav>

    <nav class="usite-footer__col" aria-label="Богослужение">
      <h5>Богослужение</h5>
      <a href="<?php echo esc_url( home_url( '/schedule' ) ); ?>">Расписание</a>
      <a href="<?php echo esc_url( home_url( '/prayer-requests' ) ); ?>">Заказ треб</a>
      <a href="<?php echo esc_url( home_url( '/icons' ) ); ?>">Святыни</a>
      <a href="<?php echo esc_url( home_url( '/news' ) ); ?>">Новости</a>
    </nav>

    <nav class="usite-footer__col" aria-label="Помощь храму">
      <h5>Помощь храму</h5>
      <a href="<?php echo esc_url( home_url( '/donate' ) ); ?>">Пожертвовать</a>
      <a href="<?php echo esc_url( home_url( '/donate' ) ); ?>#requisites">Реквизиты для перевода</a>
      <a href="<?php echo esc_url( home_url( '/parish-life' ) ); ?>#school">Воскресная школа</a>
      <a href="<?php echo esc_url( home_url( '/parish-life' ) ); ?>#sisterhood">Социальное служение</a>
    </nav>

    <div class="usite-footer__social" aria-label="Социальные сети">
      <a href="https://vk.com/voyskovoysoborkrasnodar" aria-label="ВКонтакте" rel="noopener" target="_blank">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M12.785 16.241s.288-.032.435-.193c.135-.148.131-.426.131-.426s-.019-1.31.586-1.502c.598-.19 1.367 1.262 2.181 1.821.616.422 1.084.33 1.084.33l2.176-.03s1.139-.071.599-.97c-.044-.073-.314-.665-1.617-1.881-1.366-1.272-1.183-1.066.461-3.296 1.001-1.359 1.401-2.187 1.276-2.541-.119-.34-.873-.25-.873-.25l-2.45.015s-.182-.025-.317.057c-.131.08-.215.265-.215.265s-.388 1.046-.905 1.937c-1.092 1.879-1.529 1.978-1.708 1.86-.416-.273-.312-1.097-.312-1.683 0-1.83.275-2.594-.529-2.794-.267-.066-.464-.111-1.146-.118-.875-.009-1.616.003-2.035.21-.279.139-.494.45-.363.467.162.022.529.099.724.366.252.345.243 1.117.243 1.117s.144 2.13-.336 2.395c-.329.181-.78-.189-1.754-1.894-.499-.872-.876-1.838-.876-1.838s-.072-.179-.201-.275c-.156-.116-.374-.153-.374-.153l-2.328.015s-.349.01-.477.163c-.114.136-.009.418-.009.418s1.823 4.286 3.887 6.444c1.892 1.978 4.04 1.847 4.04 1.847h.973z"/></svg>
      </a>
      <a href="https://t.me/alexnewsobor" aria-label="Telegram" rel="noopener" target="_blank">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="m22.05 2.077-2.16 18.96c-.16 1.4-.99 1.74-2 1.08l-5.68-4.18-2.74 2.64c-.3.3-.56.56-1.14.56l.41-5.78 10.55-9.53c.46-.41-.1-.64-.71-.23L5.55 12.06l-5.6-1.75c-1.22-.38-1.24-1.22.26-1.81L20.49.39c1.01-.38 1.9.23 1.56 1.69z"/></svg>
      </a>
    </div>
  </div>

  <div class="usite-footer__bottom">
    <span>© 1872&ndash;<?php echo esc_html( date_i18n( 'Y' ) ); ?> МПРПО Войсковой Собор Александра Невского. Екатеринодарская и Кубанская епархия Русской Православной Церкви.</span>
    <span>ИНН 2309091590 · ОГРН 1052335002521</span>
    <a href="<?php echo esc_url( home_url( '/privacy' ) ); ?>">Положение о персональных данных</a>
  </div>
</footer>

<!-- Mobile drawer (для всех страниц) -->
<div class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Главное меню" aria-hidden="true">
  <div class="mobile-drawer__head">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-drawer__brand">
      Войсковой Собор<br>Александра Невского
      <small>Краснодар · 1872</small>
    </a>
    <button class="close" aria-label="Закрыть меню">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <line x1="6" y1="6" x2="18" y2="18"></line>
        <line x1="6" y1="18" x2="18" y2="6"></line>
      </svg>
    </button>
  </div>
  <nav class="mobile-drawer__nav" aria-label="Главная навигация (мобильная)">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a>
    <a href="<?php echo esc_url( home_url( '/about' ) ); ?>">О соборе</a>
    <a href="<?php echo esc_url( home_url( '/history' ) ); ?>">История</a>
    <a href="<?php echo esc_url( home_url( '/schedule' ) ); ?>">Расписание</a>
    <a href="<?php echo esc_url( home_url( '/prayer-requests' ) ); ?>">Заказ треб</a>
    <a href="<?php echo esc_url( home_url( '/clergy' ) ); ?>">Духовенство</a>
    <a href="<?php echo esc_url( home_url( '/parish-life' ) ); ?>">Приход</a>
    <a href="<?php echo esc_url( home_url( '/icons' ) ); ?>">Святыни</a>
    <a href="<?php echo esc_url( home_url( '/news' ) ); ?>">Новости</a>
    <a href="<?php echo esc_url( home_url( '/contacts' ) ); ?>">Контакты</a>
  </nav>
  <a href="<?php echo esc_url( home_url( '/donate' ) ); ?>" class="mobile-drawer__cta">Пожертвовать</a>
  <div class="mobile-drawer__contact">
    <p>ул. Постовая, 26 · Краснодар</p>
    <p><a href="tel:+78612620020">+7 (861) 262-00-20</a></p>
    <p><a href="mailto:nevskiy-sobor@mail.ru">nevskiy-sobor@mail.ru</a></p>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
