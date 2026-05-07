<?php
/**
 * Voiskovoy Sobor theme functions
 *
 * @package VoiskovoySobor
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme version constant — used for cache-busting on CSS/JS.
 */
define( 'VS_THEME_VERSION', '1.0.0' );

/**
 * Theme setup — регистрация меню, поддержка title-tag, post-thumbnails и пр.
 */
function vs_theme_setup() {
    load_theme_textdomain( 'voiskovoy-sobor', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 80,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script',
    ) );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );

    register_nav_menus( array(
        'primary' => __( 'Главное меню (шапка)', 'voiskovoy-sobor' ),
        'mobile'  => __( 'Мобильное меню (drawer)', 'voiskovoy-sobor' ),
        'footer'  => __( 'Меню в подвале', 'voiskovoy-sobor' ),
    ) );
}
add_action( 'after_setup_theme', 'vs_theme_setup' );

/**
 * Подключение стилей и скриптов.
 */
function vs_enqueue_assets() {
    // Основная таблица темы (с заголовком темы)
    wp_enqueue_style(
        'voiskovoy-sobor-style',
        get_stylesheet_uri(),
        array(),
        VS_THEME_VERSION
    );

    // Google Fonts (Cormorant Garamond, Spectral, PT Sans)
    wp_enqueue_style(
        'voiskovoy-sobor-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Spectral:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=PT+Sans:wght@400;700&display=swap',
        array(),
        null
    );

    // Дополнения сайта (header sticky, mobile drawer, footer, photo styling, toast)
    wp_enqueue_style(
        'voiskovoy-sobor-additions',
        get_template_directory_uri() . '/assets/css/site-additions.css',
        array( 'voiskovoy-sobor-style' ),
        VS_THEME_VERSION
    );

    // Mobile drawer + общая интерактивность
    wp_enqueue_script(
        'voiskovoy-sobor-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        VS_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'vs_enqueue_assets' );

/**
 * Helper: вернуть URL картинки темы (assets/images/...).
 *
 * Пример: <?php echo vs_img( 'photos/cathedral-2021-facade.jpg' ); ?>
 */
function vs_img( $relative ) {
    return esc_url( get_template_directory_uri() . '/assets/images/' . ltrim( $relative, '/' ) );
}

/**
 * Helper: безопасный вывод mailto: ссылки с pre-filled subject и body (для требоводных кнопок).
 */
function vs_mailto( $subject, $body = '' ) {
    $email = 'nevskiy-sobor@mail.ru';
    $params = array();
    if ( $subject ) {
        $params['subject'] = $subject;
    }
    if ( $body ) {
        $params['body'] = $body;
    }
    return 'mailto:' . $email . ( $params ? '?' . http_build_query( $params, '', '&', PHP_QUERY_RFC3986 ) : '' );
}

/**
 * Привести путь URL "/about" к slug страницы для подсветки is-active в меню.
 */
function vs_current_slug() {
    $slug = '';
    if ( is_front_page() ) {
        $slug = 'index';
    } elseif ( is_page() ) {
        global $post;
        if ( $post ) {
            $slug = $post->post_name;
        }
    } elseif ( is_404() ) {
        $slug = '404';
    }
    return $slug;
}

/**
 * Schema.org Church JSON-LD — выводится в head.
 */
function vs_schema_org() {
    $json = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'Church',
        'name'             => 'Войсковой собор святого благоверного князя Александра Невского',
        'alternateName'    => 'Войсковой Собор Александра Невского',
        'url'              => home_url( '/' ),
        'telephone'        => '+7 (861) 262-00-20',
        'email'            => 'nevskiy-sobor@mail.ru',
        'address'          => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'ул. Постовая, 26',
            'addressLocality' => 'Краснодар',
            'postalCode'      => '350063',
            'addressRegion'   => 'Краснодарский край',
            'addressCountry'  => 'RU',
        ),
        'geo'              => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => 45.0148,
            'longitude' => 38.9712,
        ),
        'foundingDate'     => '1853',
        'openingHours'     => 'Mo-Su 07:00-19:00',
        'sameAs'           => array(
            'https://vk.com/voyskovoysoborkrasnodar',
            'https://t.me/alexnewsobor',
        ),
        'parentOrganization' => array(
            '@type' => 'ReligiousOrganization',
            'name'  => 'Екатеринодарская и Кубанская епархия Русской Православной Церкви',
        ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
add_action( 'wp_head', 'vs_schema_org' );
