<?php
/**
 * Template Name: Контакты
 *
 * Split-layout: контакты слева + карта справа. Использует Customizer-настройки.
 */
get_header();
$c = function_exists('church_get_contacts_settings') ? church_get_contacts_settings() : array(
    'address'  => 'г. Краснодар, ул. Постовая, 26',
    'phone'    => '+7 (861) 262-00-20',
    'email'    => 'nevskiy-sobor@mail.ru',
    'schedule' => 'Пн-Вс: 7:00–19:00',
    'map'      => '',
);
church_get_part('page-header', array(
    'kicker' => __('Контакты', 'church-kadence-child'),
    'title'  => __('Как нас найти', 'church-kadence-child'),
    'quote'  => __('Где двое или трое собраны во имя Моё, там Я посреди них', 'church-kadence-child'),
    'cite'   => __('Евангелие от Матфея 18:20', 'church-kadence-child'),
    'tone'   => 'ink',
));
?>

<section class="cc-section">
    <div class="cc-container">
        <div class="cc-grid cc-grid--2" style="grid-template-columns: 1fr 1.3fr; gap: 3rem; align-items: start;">

            <div class="cc-card cc-anim-left" style="padding: 2.5rem;">
                <h2 style="margin-bottom: 0.5rem;"><?php esc_html_e('Войсковой Собор', 'church-kadence-child'); ?></h2>
                <p style="font-style: italic; color: var(--color-text-muted); margin-bottom: 1.5rem;"><?php esc_html_e('святого благоверного князя Александра Невского', 'church-kadence-child'); ?></p>

                <?php church_get_part('ornament-divider'); ?>

                <p style="font-size: var(--text-sm); font-style: italic; color: var(--color-text-muted); margin-bottom: 1.5rem;">
                    <?php esc_html_e('Местная религиозная организация православный приход войскового собора святого благоверного князя Александра Невского г. Краснодара Екатеринодарской и Кубанской епархии Русской Православной Церкви.', 'church-kadence-child'); ?>
                </p>

                <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem;">
                    <li style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" style="flex-shrink:0;margin-top:3px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span><?php echo esc_html($c['address']); ?></span>
                    </li>
                    <li style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" style="flex-shrink:0;margin-top:3px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.33 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $c['phone'])); ?>"><?php echo esc_html($c['phone']); ?></a>
                    </li>
                    <li style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" style="flex-shrink:0;margin-top:3px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:<?php echo esc_attr($c['email']); ?>"><?php echo esc_html($c['email']); ?></a>
                    </li>
                    <li style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5" style="flex-shrink:0;margin-top:3px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span><?php echo esc_html($c['schedule']); ?></span>
                    </li>
                </ul>

                <?php church_get_part('ornament-divider'); ?>

                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="https://vk.com/voyskovoysoborkrasnodar" target="_blank" rel="noopener" class="cc-btn cc-btn--outline cc-btn--small">ВКонтакте</a>
                    <a href="https://t.me/alexnewsobor" target="_blank" rel="noopener" class="cc-btn cc-btn--outline cc-btn--small">Telegram</a>
                </div>
            </div>

            <div class="cc-anim-right cc-delay-1">
                <?php if (!empty($c['map']) && (!isset($c['show_map']) || $c['show_map'])) : ?>
                    <div style="border: 1px solid var(--color-border-gold); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-card);">
                        <?php echo wp_kses_post($c['map']); ?>
                    </div>
                <?php else : ?>
                    <iframe
                        src="https://yandex.ru/map-widget/v1/?ll=38.971200%2C45.014800&z=16&l=map&pt=38.971200%2C45.014800%2Cpm2rdm"
                        width="100%" height="500" frameborder="0" allowfullscreen
                        style="border: 1px solid var(--color-border-gold); border-radius: var(--radius); display: block;"
                        title="<?php esc_attr_e('Расположение собора на карте', 'church-kadence-child'); ?>"></iframe>
                <?php endif; ?>
                <p style="margin-top: 1rem; text-align: right;">
                    <a href="https://yandex.ru/maps/?text=<?php echo urlencode('Войсковой собор Александра Невского Краснодар Постовая 26'); ?>" target="_blank" rel="noopener" class="cc-btn cc-btn--ghost"><?php esc_html_e('Построить маршрут', 'church-kadence-child'); ?> →</a>
                </p>
            </div>

        </div>
    </div>
</section>

<?php while (have_posts()) : the_post(); ?>
    <?php if (get_the_content()) : ?>
        <section class="cc-section cc-section--paper-dark">
            <div class="cc-container cc-container--narrow cc-article">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
