<?php
/**
 * Шаблон по умолчанию для страниц без выбранного Page Template.
 * Большинство страниц используют свой page-templates/page-{slug}.php.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<main id="main" style="background:#f5f0e8;padding:64px 32px;font-family:'Spectral',Georgia,serif;">
    <div style="max-width:880px;margin:0 auto;">
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <header style="margin-bottom:36px;border-bottom:1px solid rgba(26,31,46,0.12);padding-bottom:18px;">
                    <h1 style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(34px,4.4vw,52px);line-height:1.1;color:#1a1f2e;margin:0;"><?php the_title(); ?></h1>
                </header>
                <div class="entry-content" style="line-height:1.75;color:#2a3144;font-size:17px;">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
