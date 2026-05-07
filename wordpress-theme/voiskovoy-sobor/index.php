<?php
/**
 * Fallback-шаблон. На сайте собора используется в основном через page-templates/page-*.php,
 * но WP требует index.php для прохождения theme-check.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<main id="main" style="max-width:880px;margin:0 auto;padding:64px 32px 80px;font-family:'Spectral',Georgia,serif;">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?> style="margin-bottom:48px;">
                <h1 style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(28px,3.6vw,42px);line-height:1.15;color:#1a1f2e;margin:0 0 12px;">
                    <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;border-bottom:1px solid rgba(201,169,97,0.4);"><?php the_title(); ?></a>
                </h1>
                <div style="font-family:'PT Sans',sans-serif;font-size:11px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a961;margin-bottom:18px;">
                    <?php echo esc_html( get_the_date() ); ?>
                </div>
                <div style="line-height:1.7;color:#2a3144;">
                    <?php the_excerpt(); ?>
                </div>
                <p style="margin-top:14px;">
                    <a href="<?php the_permalink(); ?>" style="font-family:'PT Sans',sans-serif;font-size:11px;letter-spacing:0.24em;text-transform:uppercase;color:#8b2635;text-decoration:none;border-bottom:1px solid #c9a961;">Читать&nbsp;дальше&nbsp;→</a>
                </p>
            </article>
        <?php endwhile; ?>
        <div style="margin-top:32px;display:flex;gap:16px;font-family:'PT Sans',sans-serif;font-size:13px;letter-spacing:0.18em;text-transform:uppercase;">
            <?php previous_posts_link( '← Новее' ); ?>
            <?php next_posts_link( 'Старее →' ); ?>
        </div>
    <?php else : ?>
        <h1 style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:32px;color:#1a1f2e;">Записей нет</h1>
        <p>На&nbsp;сайте пока нет записей.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
