<?php
/**
 * 404-шаблон. Содержимое импортируется из docs/404.html через next-session prompt
 * (см. NEXT-SESSION-PROMPT.md в корне темы).
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<main id="main" style="background:#f5f0e8;padding:120px 32px;text-align:center;font-family:'Spectral',Georgia,serif;">
    <div style="max-width:640px;margin:0 auto;">
        <div style="font-family:'PT Sans',sans-serif;font-size:11px;letter-spacing:0.34em;text-transform:uppercase;color:#c9a961;margin-bottom:24px;">Страница не найдена</div>
        <h1 style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(72px,12vw,140px);line-height:0.95;color:#8b2635;margin:0 0 18px;">404</h1>
        <p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:24px;color:#1a1f2e;margin:0 0 16px;line-height:1.4;">«Се, стою у двери и стучу»</p>
        <p style="font-size:11px;letter-spacing:0.28em;text-transform:uppercase;color:#c9a961;margin:0 0 36px;">Откровение 3:20</p>
        <p style="color:#2a3144;line-height:1.7;margin:0 0 36px;max-width:480px;margin-left:auto;margin-right:auto;">Запрашиваемой страницы не&nbsp;существует или&nbsp;она была перенесена. Вернитесь на&nbsp;главную или&nbsp;выберите раздел из&nbsp;меню.</p>
        <p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;background:#8b2635;color:#f5f0e8;font-family:'PT Sans',sans-serif;font-size:11.5px;letter-spacing:0.24em;text-transform:uppercase;font-weight:700;padding:16px 32px;text-decoration:none;border:1px solid #8b2635;">На&nbsp;главную</a>
        </p>
    </div>
</main>

<?php get_footer(); ?>
