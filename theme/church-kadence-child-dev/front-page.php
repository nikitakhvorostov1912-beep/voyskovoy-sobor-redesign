<?php
/**
 * Template Name: Главная страница
 *
 * Hero с иконо-баннером + цитата + расписание-CTA + быстрые ссылки + новости.
 * Использует новую DS components.css. Backend (CPT, AJAX, Customizer) — без изменений.
 */
get_header();

$slider = function_exists('church_get_slider_settings') ? church_get_slider_settings() : array('slides' => array());
$has_slider = !empty($slider['slides']);
$church_image = get_theme_mod('church_main_image');
?>

<!-- ===== HERO ===== -->
<section class="cc-hero" role="banner">
    <?php if ($has_slider) : ?>
        <?php echo do_shortcode('[church_home_slider]'); ?>
    <?php else : ?>
        <div class="cc-hero__bg cc-anim-burns" style="background-image: linear-gradient(135deg, var(--color-ink) 0%, var(--color-ink-soft) 100%);"></div>
        <div class="cc-hero__overlay"></div>
        <div class="cc-hero__content">
            <p class="cc-hero__kicker cc-anim-fade">
                <span><?php esc_html_e('Войсковой Собор · с 1872 года', 'church-kadence-child'); ?></span>
            </p>
            <h1 class="cc-hero__title cc-anim-up cc-delay-1">
                <?php esc_html_e('Александра', 'church-kadence-child'); ?>
                <span class="cc-hero__title-italic"><?php esc_html_e('Невского', 'church-kadence-child'); ?></span>
            </h1>
            <p class="cc-hero__place cc-anim-fade cc-delay-2"><?php esc_html_e('Краснодар · ул. Постовая 26', 'church-kadence-child'); ?></p>

            <blockquote class="cc-quote cc-anim-up cc-delay-3" style="margin-top: 3rem;">
                <span class="cc-quote__text"><?php esc_html_e('Не в силе Бог, а в правде', 'church-kadence-child'); ?></span>
                <cite><?php esc_html_e('— благоверный князь Александр Невский', 'church-kadence-child'); ?></cite>
            </blockquote>

            <div class="cc-anim-up cc-delay-4" style="margin-top: 3rem;">
                <a href="<?php echo esc_url(home_url('/schedule')); ?>" class="cc-btn cc-btn--gold cc-btn--large cc-anim-pulse cc-delay-5"><?php esc_html_e('Ближайшая служба', 'church-kadence-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/services-order')); ?>" class="cc-btn cc-btn--outline cc-btn--large" style="margin-left: 1rem; color: var(--color-gold-pale); border-color: var(--color-gold-pale);"><?php esc_html_e('Заказать требу', 'church-kadence-child'); ?></a>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php church_get_part('ornament-divider'); ?>

<!-- ===== О СОБОРЕ ===== -->
<section class="cc-section">
    <div class="cc-container">
        <div class="cc-grid cc-grid--2" style="align-items: center; gap: 4rem;">

            <div class="cc-anim-left">
                <?php if ($church_image) : ?>
                    <img src="<?php echo esc_url($church_image); ?>" alt="Войсковой собор Александра Невского" style="width: 100%; border: 1px solid var(--color-border-gold); border-radius: var(--radius); box-shadow: var(--shadow-card);">
                <?php else : ?>
                    <div style="aspect-ratio: 4/5; background: var(--color-paper-dark); border: 1px solid var(--color-border-gold); border-radius: var(--radius); display: grid; place-items: center; color: var(--color-gold);">
                        <?php church_get_part('sigil', array('size' => 96, 'color' => 'var(--color-gold)')); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="cc-anim-right cc-delay-1 cc-article">
                <p class="cc-section__kicker"><?php esc_html_e('О соборе', 'church-kadence-child'); ?></p>
                <h2><?php esc_html_e('Сердце православного Краснодара с 1872 года', 'church-kadence-child'); ?></h2>
                <?php
                $description = get_theme_mod('church_description');
                if ($description) {
                    echo wp_kses_post($description);
                } else {
                    echo '<p>' . esc_html__('Войсковой собор святого благоверного князя Александра Невского — первый каменный храм Екатеринодара, заложенный в 1853 году и освящённый в 1872 году как духовный центр Кубанского казачьего войска.', 'church-kadence-child') . '</p>';
                    echo '<p>' . esc_html__('Сегодня под высокими сводами собора звучит пение братского хора, совершаются ежедневные богослужения и продолжается дело духовного просвещения, которое полтора века неразрывно связывает Краснодар с памятью святого князя.', 'church-kadence-child') . '</p>';
                }
                ?>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin: 2rem 0; padding-top: 1.5rem; border-top: 1px solid var(--color-border-gold);">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--color-gold-dark);" class="cc-tabular">1872</div>
                        <div class="cc-uppercase" style="font-family: var(--font-ui); font-size: 0.75rem; color: var(--color-text-muted);"><?php esc_html_e('год освящения', 'church-kadence-child'); ?></div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--color-gold-dark);" class="cc-tabular">2006</div>
                        <div class="cc-uppercase" style="font-family: var(--font-ui); font-size: 0.75rem; color: var(--color-text-muted);"><?php esc_html_e('год возрождения', 'church-kadence-child'); ?></div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--color-gold-dark);" class="cc-tabular">43 м</div>
                        <div class="cc-uppercase" style="font-family: var(--font-ui); font-size: 0.75rem; color: var(--color-text-muted);"><?php esc_html_e('высота купола', 'church-kadence-child'); ?></div>
                    </div>
                </div>

                <a href="<?php echo esc_url(home_url('/history')); ?>" class="cc-btn cc-btn--ghost"><?php esc_html_e('История храма', 'church-kadence-child'); ?> →</a>
            </div>

        </div>
    </div>
</section>

<!-- ===== РАСПИСАНИЕ БЛИЖАЙШИХ СЛУЖБ ===== -->
<section class="cc-section cc-section--paper-dark">
    <div class="cc-container">
        <header class="cc-section__header">
            <p class="cc-section__kicker"><?php esc_html_e('Богослужения', 'church-kadence-child'); ?></p>
            <h2 class="cc-section__title"><?php esc_html_e('Ближайшие службы', 'church-kadence-child'); ?></h2>
        </header>
        <div class="cc-grid cc-grid--4">
            <?php echo do_shortcode('[church_schedule]'); ?>
        </div>
        <p style="text-align: center; margin-top: 2.5rem;">
            <a href="<?php echo esc_url(home_url('/schedule')); ?>" class="cc-btn cc-btn--outline"><?php esc_html_e('Полное расписание', 'church-kadence-child'); ?></a>
        </p>
    </div>
</section>

<?php church_get_part('ornament-divider'); ?>

<!-- ===== БЫСТРЫЕ ССЫЛКИ ===== -->
<section class="cc-section">
    <div class="cc-container">
        <header class="cc-section__header">
            <p class="cc-section__kicker"><?php esc_html_e('Жизнь прихода', 'church-kadence-child'); ?></p>
            <h2 class="cc-section__title"><?php esc_html_e('Разделы сайта', 'church-kadence-child'); ?></h2>
        </header>
        <div class="cc-grid cc-grid--4">

            <a class="cc-card" href="<?php echo esc_url(home_url('/schedule')); ?>">
                <div class="cc-card__icon">
                    <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="7" width="22" height="20" rx="2"/><line x1="5" y1="13" x2="27" y2="13"/><line x1="11" y1="3" x2="11" y2="11"/><line x1="21" y1="3" x2="21" y2="11"/></svg>
                </div>
                <h3 class="cc-card__title"><?php esc_html_e('Расписание', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('Литургии, всенощные, требы — расписание на неделю', 'church-kadence-child'); ?></p>
                <span class="cc-card__cta"><?php esc_html_e('Смотреть', 'church-kadence-child'); ?></span>
            </a>

            <a class="cc-card" href="<?php echo esc_url(home_url('/services-order')); ?>">
                <div class="cc-card__icon">
                    <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4 L16 28 M9 11 L23 11"/><path d="M11 19 Q16 16 21 19" /></svg>
                </div>
                <h3 class="cc-card__title"><?php esc_html_e('Заказать требу', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('Молебен, панихида, поминовение — онлайн', 'church-kadence-child'); ?></p>
                <span class="cc-card__cta"><?php esc_html_e('Заказать', 'church-kadence-child'); ?></span>
            </a>

            <a class="cc-card" href="<?php echo esc_url(home_url('/donate')); ?>">
                <div class="cc-card__icon" style="color: var(--color-blood);">
                    <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 27 C 9 22, 4 17, 4 11 A 6 6 0 0 1 16 9 A 6 6 0 0 1 28 11 C 28 17, 23 22, 16 27 Z"/></svg>
                </div>
                <h3 class="cc-card__title"><?php esc_html_e('Пожертвование', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('Поддержать храм, программы социального служения', 'church-kadence-child'); ?></p>
                <span class="cc-card__cta"><?php esc_html_e('Помочь', 'church-kadence-child'); ?></span>
            </a>

            <a class="cc-card" href="<?php echo esc_url(home_url('/news')); ?>">
                <div class="cc-card__icon">
                    <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="6" width="22" height="20" rx="1"/><line x1="9" y1="12" x2="23" y2="12"/><line x1="9" y1="16" x2="23" y2="16"/><line x1="9" y1="20" x2="19" y2="20"/></svg>
                </div>
                <h3 class="cc-card__title"><?php esc_html_e('Новости', 'church-kadence-child'); ?></h3>
                <p class="cc-card__excerpt"><?php esc_html_e('События прихода, объявления, проповеди', 'church-kadence-child'); ?></p>
                <span class="cc-card__cta"><?php esc_html_e('Читать', 'church-kadence-child'); ?></span>
            </a>

        </div>
    </div>
</section>

<!-- ===== ПРИГЛАШЕНИЕ ===== -->
<section class="cc-section cc-section--ink">
    <div class="cc-container cc-container--narrow cc-text-center">
        <p class="cc-section__kicker" style="color: var(--color-gold);"><?php esc_html_e('Приглашение', 'church-kadence-child'); ?></p>
        <h2 class="cc-section__title cc-gold-shine" style="margin-bottom: 2rem;"><?php esc_html_e('Приходите в наш храм', 'church-kadence-child'); ?></h2>
        <p style="font-size: var(--text-lg); color: var(--color-text-on-dark); line-height: 1.7; margin-bottom: 2.5rem;">
            <?php esc_html_e('Каждого прихожанина и гостя мы рады видеть на наших богослужениях. Присоединяйтесь к молитве и духовному общению.', 'church-kadence-child'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/contacts')); ?>" class="cc-btn cc-btn--gold cc-btn--large"><?php esc_html_e('Как нас найти', 'church-kadence-child'); ?></a>
    </div>
</section>

<?php get_footer(); ?>
