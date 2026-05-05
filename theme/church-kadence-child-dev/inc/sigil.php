<?php
/**
 * Sigil — двуглавый орёл войска
 * Используется в header.php (логотип) и footer.php (фирменный знак)
 *
 * Параметры через get_query_var() или $args:
 *   $size  — высота в px (по умолчанию 36)
 *   $color — CSS-переменная или цвет (по умолчанию --color-gold)
 *   $class — дополнительный класс
 */
$size  = $args['size']  ?? 36;
$color = $args['color'] ?? 'var(--color-gold)';
$class = $args['class'] ?? '';
?>
<svg
    class="cc-sigil <?php echo esc_attr($class); ?>"
    width="<?php echo esc_attr($size); ?>"
    height="<?php echo esc_attr($size); ?>"
    viewBox="0 0 64 64"
    xmlns="http://www.w3.org/2000/svg"
    role="img"
    aria-labelledby="sigil-title"
    fill="<?php echo esc_attr($color); ?>"
>
    <title id="sigil-title">Войсковой собор Александра Невского</title>
    <!-- Стилизованный двуглавый орёл войска (line-art) -->
    <g stroke="<?php echo esc_attr($color); ?>" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <!-- Шеи и тело -->
        <path d="M32 38 L32 22"/>
        <path d="M32 22 Q24 16 18 18 Q14 21 16 26 Q18 30 22 30"/>
        <path d="M32 22 Q40 16 46 18 Q50 21 48 26 Q46 30 42 30"/>
        <!-- Головы -->
        <circle cx="18" cy="14" r="3.2"/>
        <circle cx="46" cy="14" r="3.2"/>
        <!-- Клювы -->
        <path d="M14.5 14 L11 13"/>
        <path d="M49.5 14 L53 13"/>
        <!-- Крылья -->
        <path d="M22 28 Q12 32 8 42 Q14 38 18 38 Q14 44 14 50"/>
        <path d="M42 28 Q52 32 56 42 Q50 38 46 38 Q50 44 50 50"/>
        <!-- Хвост -->
        <path d="M28 38 L24 50"/>
        <path d="M32 38 L32 52"/>
        <path d="M36 38 L40 50"/>
        <!-- Корона над центром -->
        <path d="M28 8 L36 8 L34 4 L32 6 L30 4 Z"/>
    </g>
</svg>
