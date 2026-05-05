<?php
/**
 * Template Name: История храма
 *
 * Long-form статья с reading-progress, drop-cap, цитатами и timeline.
 */
get_header();
?>

<section class="cc-hero cc-hero--medium" style="min-height: 60vh;">
    <div class="cc-hero__bg cc-anim-burns" style="background: linear-gradient(180deg, var(--color-ink) 0%, var(--color-ink-soft) 70%, var(--color-paper-dark) 100%);"></div>
    <div class="cc-hero__overlay" style="background: linear-gradient(to top, rgba(26,31,46,0.92), rgba(26,31,46,0.55));"></div>
    <div class="cc-hero__content">
        <p class="cc-hero__kicker cc-anim-fade"><span><?php esc_html_e('Летопись прихода', 'church-kadence-child'); ?></span></p>
        <h1 class="cc-hero__title cc-anim-up cc-delay-1" style="font-size: clamp(2.5rem, 2rem + 2vw, 4rem);">
            <?php esc_html_e('История Войскового Собора', 'church-kadence-child'); ?>
        </h1>
        <p class="cc-anim-fade cc-delay-2" style="font-family: var(--font-body); font-style: italic; font-size: var(--text-lg); margin-top: 1.5rem; color: var(--color-gold-pale); max-width: 700px; margin-left: auto; margin-right: auto;">
            <?php esc_html_e('От первого камня в 1853 году до возрождения в 2006-м — путь главного храма Кубанского казачества.', 'church-kadence-child'); ?>
        </p>
        <p class="cc-hero__place cc-anim-fade cc-delay-3" style="margin-top: 2rem;">
            <?php esc_html_e('12 минут чтения', 'church-kadence-child'); ?> · <?php esc_html_e('Автор: иерей А. Кравченко', 'church-kadence-child'); ?>
        </p>
    </div>
</section>

<div class="cc-progress" id="cc-progress"></div>

<section class="cc-section">
    <div class="cc-container cc-container--narrow cc-article">
        <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>

        <?php if (! get_the_content()) : ?>
            <p>В истории русской церковной архитектуры Войсковой собор святого благоверного князя Александра Невского занимает особое место — как духовный центр Кубанского казачьего войска и как храм, прошедший через все испытания XX века.</p>
            <p>Идея храма для Кубанского войска зрела ещё с 1842 года, когда казачество получило в дар икону святого благоверного князя Александра Невского. Закладка первого камня состоялась в 1853 году по проекту архитектора И.Д. Черника.</p>
            <blockquote class="cc-quote" style="margin: 3rem auto;">
                <span class="cc-quote__text">Не в силе Бог, а в правде</span>
                <cite>— благоверный князь Александр Невский</cite>
            </blockquote>
            <p>Освящение собора состоялось в 1872 году. Век служения, разрушение в 1932-м, возрождение на новом месте в 2006-м — каждый период оставил свой след в памяти прихода.</p>
        <?php endif; ?>

        <?php church_get_part('ornament-divider'); ?>

        <h2 class="cc-text-center" style="margin: 3rem 0 2rem;"><?php esc_html_e('Ключевые даты', 'church-kadence-child'); ?></h2>

        <ol style="list-style: none; padding: 0; position: relative;">
            <?php
            $timeline = array(
                array('year' => '1842', 'event' => 'Икона Александра Невского передана Кубанскому войску'),
                array('year' => '1853', 'event' => 'Закладка первого камня собора'),
                array('year' => '1872', 'event' => 'Освящение собора'),
                array('year' => '1932', 'event' => 'Разрушение собора в годы безбожия'),
                array('year' => '2003', 'event' => 'Закладка камня нового собора при митрополите Исидоре'),
                array('year' => '2006', 'event' => 'Освящение Патриархом Алексием II'),
            );
            foreach ($timeline as $i => $t) :
                $align = $i % 2 ? 'right' : 'left';
                ?>
                <li class="cc-anim-<?php echo $align; ?>" style="display: grid; grid-template-columns: 1fr 100px 1fr; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                    <div style="text-align: <?php echo $align === 'left' ? 'right' : 'left'; ?>; <?php echo $align === 'left' ? 'grid-column: 1;' : 'grid-column: 3;'; ?>">
                        <div style="font-family: var(--font-display); font-size: 1.75rem; font-weight: 700; color: var(--color-gold-dark);" class="cc-tabular"><?php echo esc_html($t['year']); ?></div>
                        <p style="font-style: italic; color: var(--color-text-muted);"><?php echo esc_html($t['event']); ?></p>
                    </div>
                    <div style="grid-column: 2; display: grid; place-items: center; height: 100%; border-left: 2px solid var(--color-gold);">
                        <span style="display: block; width: 14px; height: 14px; background: var(--color-gold); border-radius: 50%; border: 2px solid var(--color-paper);"></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<script>
(function(){
    var p = document.getElementById('cc-progress');
    if (!p) return;
    function upd(){
        var h = document.documentElement;
        var max = h.scrollHeight - h.clientHeight;
        if (max <= 0) return;
        p.style.width = ((h.scrollTop / max) * 100) + '%';
    }
    window.addEventListener('scroll', upd, { passive: true });
    upd();
})();
</script>

<?php get_footer(); ?>
