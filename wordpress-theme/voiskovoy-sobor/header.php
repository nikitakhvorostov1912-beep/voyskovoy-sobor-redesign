<?php
/**
 * Общий заголовок темы — <head> + <header class="uheader"> + начало <body>.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
$vs_slug = vs_current_slug();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="manifest" href="<?php echo esc_url( get_template_directory_uri() . '/manifest.webmanifest' ); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo vs_img( 'icons/favicon.svg' ); ?>">
<meta name="theme-color" content="#1a1f2e">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">Перейти к содержимому</a>

<header class="uheader" role="banner">
  <div class="uheader__inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="uheader__brand" aria-label="Войсковой Собор Александра Невского — главная">
      <svg class="uheader__sigil" viewBox="0 0 48 48" fill="none" aria-hidden="true">
        <path d="M24 2 L42 9 V25 C42 36 34 43 24 46 C14 43 6 36 6 25 V9 Z" stroke="#c9a961" stroke-width="1.2" fill="rgba(201,169,97,.06)"></path>
        <g stroke="#c9a961" stroke-width="1.4" stroke-linecap="square">
          <line x1="24" y1="11" x2="24" y2="38"></line>
          <line x1="18" y1="17" x2="30" y2="17"></line>
          <line x1="16" y1="22" x2="32" y2="22"></line>
          <line x1="18" y1="32" x2="30" y2="29"></line>
        </g>
      </svg>
      <span class="uheader__name">
        Войсковой Собор<br>Александра Невского
        <small>Краснодар · 1872</small>
      </span>
    </a>

    <?php
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
            'theme_location'  => 'primary',
            'container'       => 'nav',
            'container_class' => 'uheader__nav',
            'menu_class'      => '',
            'depth'           => 1,
            'fallback_cb'     => 'vs_default_primary_menu',
            'items_wrap'      => '%3$s',
        ) );
    } else {
        vs_default_primary_menu();
    }
    ?>

    <button class="menu-trigger" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobile-drawer">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </button>

    <a href="<?php echo esc_url( home_url( '/donate' ) ); ?>" class="uheader__cta">Пожертвовать</a>
  </div>
</header>

<?php
/**
 * Меню по умолчанию — если в WP-админке не настроено главное меню.
 */
if ( ! function_exists( 'vs_default_primary_menu' ) ) {
    function vs_default_primary_menu() {
        $vs_slug = vs_current_slug();
        $items = array(
            'index'           => array( 'Главная',     home_url( '/' ) ),
            'about'           => array( 'О соборе',    home_url( '/about' ) ),
            'history'         => array( 'История',     home_url( '/history' ) ),
            'schedule'        => array( 'Расписание',  home_url( '/schedule' ) ),
            'prayer-requests' => array( 'Заказ треб',  home_url( '/prayer-requests' ) ),
            'clergy'          => array( 'Духовенство', home_url( '/clergy' ) ),
            'parish-life'     => array( 'Приход',      home_url( '/parish-life' ) ),
            'icons'           => array( 'Святыни',     home_url( '/icons' ) ),
            'news'            => array( 'Новости',     home_url( '/news' ) ),
            'contacts'        => array( 'Контакты',    home_url( '/contacts' ) ),
        );
        echo '<nav class="uheader__nav" aria-label="Главная навигация">';
        foreach ( $items as $key => $info ) {
            $cls   = ( $vs_slug === $key ) ? ' class="is-active" aria-current="page"' : '';
            echo '<a href="' . esc_url( $info[1] ) . '"' . $cls . '>' . esc_html( $info[0] ) . '</a>';
        }
        echo '</nav>';
    }
}
?>
