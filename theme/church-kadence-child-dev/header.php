<?php
/**
 * Header template — единая шапка для всех страниц новой DS
 * «Литургическая монументальность» — sticky chrome с sigil слева, меню по центру, ПОЖЕРТВОВАТЬ справа
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1a1f2e">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
    // Доработка START {schema+og} {2026-05-05}
    if (function_exists('church_render_schema_og')) {
        church_render_schema_og();
    }
    // Доработка END
    ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class('cc-page'); ?>>
<?php wp_body_open(); ?>

<a class="cc-skip-link" href="#cc-main"><?php esc_html_e('Перейти к содержимому', 'church-kadence-child'); ?></a>

<header class="cc-header" id="cc-header" role="banner">
    <div class="cc-container cc-header__inner">

        <a class="cc-header__sigil" href="<?php echo esc_url(home_url('/')); ?>" aria-label="На главную">
            <?php church_get_part('sigil', array('size' => 36)); ?>
            <span class="cc-header__sigil-text">
                <span class="cc-header__sigil-name">Войсковой Собор</span>
                <span class="cc-header__sigil-sub">св. блгв. кн. Александра Невского</span>
            </span>
        </a>

        <nav class="cc-header__nav" role="navigation" aria-label="Главное меню">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => '',
                'fallback_cb'    => function() {
                    echo '<ul>';
                    $links = array(
                        '/'         => __('Главная', 'church-kadence-child'),
                        '/news'     => __('Жизнь прихода', 'church-kadence-child'),
                        '/services' => __('Духовенство', 'church-kadence-child'),
                        '/schedule' => __('Расписание', 'church-kadence-child'),
                        '/donate'   => __('Пожертвование', 'church-kadence-child'),
                        '/contacts' => __('Контакты', 'church-kadence-child'),
                    );
                    foreach ($links as $href => $label) {
                        $current = trim($_SERVER['REQUEST_URI'], '/') === trim($href, '/') ? 'class="current"' : '';
                        printf('<li><a href="%s" %s>%s</a></li>', esc_url(home_url($href)), $current, esc_html($label));
                    }
                    echo '</ul>';
                },
                'depth'          => 1,
            ));
            ?>
        </nav>

        <div class="cc-header__actions">
            <button class="cc-header__search" type="button" aria-label="Поиск">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
            <?php
            $donate_url = function_exists('get_theme_mod') ? get_theme_mod('donate_button_url', home_url('/donate')) : home_url('/donate');
            ?>
            <a class="cc-btn cc-btn--blood cc-btn--small" href="<?php echo esc_url($donate_url); ?>"><?php esc_html_e('Пожертвовать', 'church-kadence-child'); ?></a>
            <button class="cc-header__toggle" type="button" aria-label="Открыть меню" aria-expanded="false" aria-controls="cc-mobile-menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>

    </div>
</header>

<div class="cc-mobile-menu" id="cc-mobile-menu" hidden>
    <nav role="navigation" aria-label="Мобильное меню">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'cc-mobile-menu__list',
            'fallback_cb'    => false,
            'depth'          => 2,
        ));
        ?>
    </nav>
</div>

<main id="cc-main" class="cc-main" role="main">
