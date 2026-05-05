<?php
/**
 * Template Name: Расписание богослужений
 */
get_header();
church_get_part('page-header', array(
    'kicker' => __('Литургический календарь', 'church-kadence-child'),
    'title'  => __('Расписание богослужений', 'church-kadence-child'),
    'quote'  => __('Бдите и молитеся, да не внидете в напасть', 'church-kadence-child'),
    'cite'   => __('Евангелие от Матфея 26:41', 'church-kadence-child'),
    'tone'   => 'ink',
));
?>

<section class="cc-section">
    <div class="cc-container">

        <?php while (have_posts()) : the_post(); ?>
            <div class="cc-article" style="max-width: 720px; margin: 0 auto 3rem;">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>

        <header class="cc-section__header">
            <h2 class="cc-section__title"><?php esc_html_e('Ближайшие службы', 'church-kadence-child'); ?></h2>
        </header>

        <div class="cc-grid cc-grid--4">
            <?php echo do_shortcode('[church_schedule]'); ?>
        </div>

        <?php church_get_part('ornament-divider'); ?>

        <div class="cc-grid cc-grid--3">
            <div class="cc-card">
                <h3 class="cc-card__title"><?php esc_html_e('Часы работы храма', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('Ежедневно 7:00 – 19:00. Суббота, воскресенье и праздники — с 6:30.', 'church-kadence-child'); ?></p>
            </div>
            <div class="cc-card">
                <h3 class="cc-card__title"><?php esc_html_e('Исповедь', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('За 30 минут до Литургии и в течение службы. Накануне праздников — на всенощном бдении.', 'church-kadence-child'); ?></p>
            </div>
            <div class="cc-card">
                <h3 class="cc-card__title"><?php esc_html_e('Дежурный священник', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('Понедельник – пятница, 10:00 – 17:00. Беседа, духовный совет, требы.', 'church-kadence-child'); ?></p>
            </div>
        </div>

        <div class="cc-text-center" style="margin-top: 3rem;">
            <a href="<?php echo esc_url(home_url('/services-order')); ?>" class="cc-btn cc-btn--gold cc-btn--large"><?php esc_html_e('Заказать поминовение', 'church-kadence-child'); ?></a>
        </div>

    </div>
</section>

<?php get_footer(); ?>
