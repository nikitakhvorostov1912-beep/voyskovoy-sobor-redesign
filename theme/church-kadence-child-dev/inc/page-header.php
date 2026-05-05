<?php
/**
 * Page Header — узкий header для inner-страниц
 *
 * @param string $kicker Малый префикс над заголовком (ALL CAPS).
 * @param string $title  Главный заголовок.
 * @param string $quote  Цитата (опц.).
 * @param string $cite   Подпись цитаты (опц.).
 * @param string $tone   'ink' (тёмно-синий), 'blood' (бордовый), 'paper-dark' (пергамент-альт). По умолчанию 'ink'.
 */
$kicker = $args['kicker'] ?? '';
$title  = $args['title']  ?? '';
$quote  = $args['quote']  ?? '';
$cite   = $args['cite']   ?? '';
$tone   = $args['tone']   ?? 'ink';

$bg_class = 'cc-section--' . $tone;
?>
<section class="cc-section <?php echo esc_attr($bg_class); ?>" style="padding: 4rem 0;">
    <div class="cc-container cc-text-center">
        <?php if ($kicker) : ?>
            <p class="cc-section__kicker cc-anim-fade" style="color: var(--color-gold);"><?php echo esc_html($kicker); ?></p>
        <?php endif; ?>
        <h1 class="cc-anim-up cc-delay-1" style="margin: 0; <?php if ($tone === 'ink') echo 'color: var(--color-text-on-dark);'; ?>"><?php echo esc_html($title); ?></h1>
        <?php if ($quote) : ?>
            <blockquote class="cc-quote cc-anim-up cc-delay-2" style="margin: 2rem auto 0; max-width: 50ch; color: <?php echo $tone === 'ink' ? 'var(--color-gold-pale)' : 'var(--color-text)'; ?>;">
                <span class="cc-quote__text"><?php echo esc_html($quote); ?></span>
                <?php if ($cite) : ?><cite><?php echo esc_html($cite); ?></cite><?php endif; ?>
            </blockquote>
        <?php endif; ?>
    </div>
</section>
