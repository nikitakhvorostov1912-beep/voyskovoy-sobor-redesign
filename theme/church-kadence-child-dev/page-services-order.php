<?php
/**
 * Template Name: Заказ треб
 *
 * Каталог треб + один общий модал. Использует существующий backend AJAX и shortcode [church_service_order].
 */
get_header();
church_get_part('page-header', array(
    'kicker' => __('Жизнь прихода', 'church-kadence-child'),
    'title'  => __('Помолитесь о близких', 'church-kadence-child'),
    'quote'  => __('Молитесь друг за друга, чтобы исцелиться', 'church-kadence-child'),
    'cite'   => __('Послание ап. Иакова 5:16', 'church-kadence-child'),
    'tone'   => 'ink',
));
?>

<section class="cc-section">
    <div class="cc-container cc-container--narrow cc-article">
        <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
    </div>
</section>

<section class="cc-section cc-section--paper-dark">
    <div class="cc-container">
        <header class="cc-section__header">
            <h2 class="cc-section__title"><?php esc_html_e('Виды треб', 'church-kadence-child'); ?></h2>
        </header>

        <div class="cc-grid cc-grid--3">
            <?php
            $services = new WP_Query(array(
                'post_type'      => 'church_service',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ));
            if ($services->have_posts()) :
                while ($services->have_posts()) : $services->the_post();
                    $price      = get_post_meta(get_the_ID(), '_service_price', true);
                    $price_type = get_post_meta(get_the_ID(), '_service_price_type', true);
                    $price_text = number_format_i18n($price) . ' ₽';
                    if ($price_type === 'per_name')      { $price_text .= ' / ' . __('имя', 'church-kadence-child'); }
                    elseif ($price_type === 'per_10_names'){ $price_text .= ' / ' . __('10 имён', 'church-kadence-child'); }
                    ?>
                    <article class="cc-card">
                        <div class="cc-card__icon"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4 L16 28 M9 11 L23 11"/></svg></div>
                        <h3 class="cc-card__title"><?php the_title(); ?></h3>
                        <?php if (has_excerpt()) : ?>
                            <p class="cc-card__excerpt"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin: 1.5rem 0; padding-top: 1rem; border-top: 1px solid var(--color-border-gold);">
                            <span class="cc-card__meta"><?php esc_html_e('Стоимость', 'church-kadence-child'); ?></span>
                            <span class="cc-tabular" style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--color-gold-dark);"><?php echo esc_html($price_text); ?></span>
                        </div>
                        <button type="button" class="cc-btn cc-btn--gold" style="width: 100%;" data-cc-order data-service-id="<?php echo esc_attr(get_the_ID()); ?>" data-service-name="<?php echo esc_attr(get_the_title()); ?>"><?php esc_html_e('Заказать', 'church-kadence-child'); ?></button>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php church_get_part('ornament-divider'); ?>

<!-- ===== Один общий модал заказа ===== -->
<div class="cc-modal" id="cc-order-modal" hidden role="dialog" aria-modal="true" aria-labelledby="cc-order-title">
    <div class="cc-modal__overlay" data-cc-modal-close></div>
    <div class="cc-modal__panel">
        <button type="button" class="cc-modal__close" data-cc-modal-close aria-label="Закрыть">×</button>
        <h2 id="cc-order-title" class="cc-modal__title"></h2>
        <div class="cc-modal__content"></div>
    </div>
</div>

<style>
.cc-modal { position: fixed; inset: 0; z-index: 200; display: grid; place-items: center; padding: 2rem; }
.cc-modal[hidden] { display: none; }
.cc-modal__overlay { position: absolute; inset: 0; background: rgba(26,31,46,0.85); animation: cc-fade-in 200ms; }
.cc-modal__panel {
  position: relative; z-index: 1; max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto;
  background: var(--color-paper); border: 1px solid var(--color-border-gold); border-radius: var(--radius-lg);
  padding: 2.5rem; box-shadow: var(--shadow-card-hover); animation: cc-scale-in 300ms cubic-bezier(0.16,1,0.3,1);
}
.cc-modal__close { position: absolute; top: 0.75rem; right: 1rem; background: none; border: none; font-size: 2rem; line-height: 1; cursor: pointer; color: var(--color-text-muted); }
.cc-modal__title { font-family: var(--font-display); font-size: var(--text-h3); margin-bottom: 1.5rem; }
</style>

<script>
(function(){
    var modal   = document.getElementById('cc-order-modal');
    if (!modal) return;
    var titleEl = modal.querySelector('.cc-modal__title');
    var content = modal.querySelector('.cc-modal__content');
    var lastTrigger = null;

    function open(serviceId, serviceName) {
        lastTrigger = document.activeElement;
        titleEl.textContent = 'Заказ: ' + serviceName;
        content.innerHTML = '<p style="text-align:center;color:var(--color-text-muted);">Загрузка формы…</p>';
        modal.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';
        // Подгружаем форму через существующий shortcode [church_service_order service_id=N]
        var form = new FormData();
        form.append('action', 'church_load_service_form');
        form.append('service_id', serviceId);
        // Если AJAX-эндпоинт отсутствует — используем готовый shortcode прямо в DOM:
        var url = '<?php echo esc_url(home_url('/services-order/?cc_form=1')); ?>&service_id=' + encodeURIComponent(serviceId);
        // Пока используем простую форму-обёртку с shortcode (см. snippet ниже)
        content.innerHTML = '<p>Форма заказа для услуги ID ' + serviceId + ' будет загружена через <code>[church_service_order]</code>.</p><p style="margin-top:1rem;"><a class="cc-btn cc-btn--gold" href="?service_id=' + encodeURIComponent(serviceId) + '">Открыть форму</a></p>';
    }
    function close() {
        modal.setAttribute('hidden', '');
        document.body.style.overflow = '';
        if (lastTrigger) lastTrigger.focus();
    }
    document.querySelectorAll('[data-cc-order]').forEach(function(b){
        b.addEventListener('click', function(){
            open(b.dataset.serviceId, b.dataset.serviceName);
        });
    });
    document.querySelectorAll('[data-cc-modal-close]').forEach(function(b){ b.addEventListener('click', close); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape' && !modal.hasAttribute('hidden')) close(); });
})();
</script>

<?php get_footer(); ?>
