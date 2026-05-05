<?php
/**
 * Template Name: Пожертвование
 *
 * Форма с quick-amounts + цели пожертвования + реквизиты для прямого перевода.
 * Реквизиты тянутся из Customizer (Phase 4).
 */
get_header();
church_get_part('page-header', array(
    'kicker' => __('Помощь храму', 'church-kadence-child'),
    'title'  => __('Помогите храму', 'church-kadence-child'),
    'quote'  => __('Доброхотного дателя любит Бог', 'church-kadence-child'),
    'cite'   => __('2 Кор. 9:7', 'church-kadence-child'),
    'tone'   => 'blood',
));

// Реквизиты (Customizer-fallback)
$req = array(
    'name'   => get_theme_mod('donate_legal_name', 'МПРПО Войсковой собор святого благоверного князя Александра Невского г. Краснодара'),
    'inn'    => get_theme_mod('donate_inn', '2310045896'),
    'kpp'    => get_theme_mod('donate_kpp', '231001001'),
    'rs'     => get_theme_mod('donate_rs', '40703 810 0 0000 0001 234'),
    'bank'   => get_theme_mod('donate_bank', 'ПАО «Сбербанк России»'),
    'bik'    => get_theme_mod('donate_bik', '040349602'),
    'ks'     => get_theme_mod('donate_ks', '30101 810 1 0000 0000 602'),
);
?>

<section class="cc-section">
    <div class="cc-container">
        <div class="cc-grid cc-grid--2" style="gap: 4rem; align-items: start;">

            <div class="cc-anim-left cc-article">
                <h2><?php esc_html_e('Слово к жертвователю', 'church-kadence-child'); ?></h2>
                <p><?php esc_html_e('Войсковой собор живёт благодаря пожертвованиям прихожан и благотворителей. Каждое ваше приношение помогает служению храма: содержание клира, восстановление росписи, программа социального служения, воскресная школа, помощь нуждающимся.', 'church-kadence-child'); ?></p>
                <ul style="list-style: none; padding: 0; margin: 1.5rem 0;">
                    <?php foreach (array(
                        __('Восстановление росписи купола', 'church-kadence-child'),
                        __('Содержание воскресной школы «Русский щит»', 'church-kadence-child'),
                        __('Помощь многодетным семьям прихода', 'church-kadence-child'),
                        __('Реставрация икон и церковной утвари', 'church-kadence-child'),
                        __('Помянник усопших воинов', 'church-kadence-child'),
                    ) as $item) : ?>
                        <li style="padding-left: 2rem; position: relative; margin-bottom: 0.5rem;">
                            <span style="position: absolute; left: 0; color: var(--color-gold);">✓</span>
                            <?php echo esc_html($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="cc-card cc-anim-right cc-delay-1" style="padding: 2.5rem;">
                <form id="cc-donate-form" method="post" action="#">
                    <fieldset style="border: 0; padding: 0; margin: 0 0 1.5rem;">
                        <legend class="cc-card__meta" style="margin-bottom: 0.75rem;"><?php esc_html_e('Размер пожертвования', 'church-kadence-child'); ?></legend>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem;">
                            <?php foreach (array(100, 300, 500, 1000) as $i => $amt) : ?>
                                <label class="cc-amount<?php echo $amt === 500 ? ' is-active' : ''; ?>">
                                    <input type="radio" name="amount" value="<?php echo esc_attr($amt); ?>" <?php checked($amt, 500); ?> hidden>
                                    <span class="cc-tabular"><?php echo number_format_i18n($amt); ?> ₽</span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <input type="number" name="amount_custom" placeholder="<?php esc_attr_e('Другая сумма, ₽', 'church-kadence-child'); ?>" min="50" style="margin-top: 0.75rem; width: 100%; padding: 0.85em 1em; border: 1px solid var(--color-border-gold); border-radius: var(--radius-sm); font-family: var(--font-body);" autocomplete="off">
                    </fieldset>

                    <fieldset style="border: 0; padding: 0; margin: 0 0 1.5rem;">
                        <legend class="cc-card__meta" style="margin-bottom: 0.75rem;"><?php esc_html_e('Как будет назначено', 'church-kadence-child'); ?></legend>
                        <?php
                        $purposes = array(
                            'general'   => __('На общие нужды храма', 'church-kadence-child'),
                            'cupola'    => __('Восстановление росписи купола', 'church-kadence-child'),
                            'school'    => __('Содержание воскресной школы', 'church-kadence-child'),
                            'needy'     => __('Помощь нуждающимся', 'church-kadence-child'),
                            'helmsmen'  => __('Помянник усопших воинов', 'church-kadence-child'),
                        );
                        foreach ($purposes as $key => $label) : ?>
                            <label style="display: flex; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-gold); cursor: pointer;">
                                <input type="radio" name="purpose" value="<?php echo esc_attr($key); ?>" <?php checked($key, 'general'); ?>>
                                <span><?php echo esc_html($label); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>

                    <fieldset style="border: 0; padding: 0; margin: 0 0 1.5rem;">
                        <legend class="cc-card__meta" style="margin-bottom: 0.75rem;"><?php esc_html_e('Ваши данные', 'church-kadence-child'); ?></legend>
                        <input type="text"  name="name"  placeholder="<?php esc_attr_e('Имя — для поминовения (опционально)', 'church-kadence-child'); ?>" style="width: 100%; padding: 0.85em 1em; border: 1px solid var(--color-border-gold); border-radius: var(--radius-sm); margin-bottom: 0.5rem;">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Email — для квитанции', 'church-kadence-child'); ?>" required style="width: 100%; padding: 0.85em 1em; border: 1px solid var(--color-border-gold); border-radius: var(--radius-sm); margin-bottom: 0.5rem;">
                        <label style="display: flex; gap: 0.5rem; align-items: flex-start; padding: 0.5rem 0;">
                            <input type="checkbox" name="recurring" value="1">
                            <span><?php esc_html_e('Пожертвовать каждый месяц этой суммой', 'church-kadence-child'); ?></span>
                        </label>
                        <label style="display: flex; gap: 0.5rem; align-items: flex-start; padding: 0.5rem 0;">
                            <input type="checkbox" name="agree" value="1" required>
                            <span><?php esc_html_e('Согласие на обработку персональных данных', 'church-kadence-child'); ?></span>
                        </label>
                    </fieldset>

                    <button type="submit" class="cc-btn cc-btn--blood cc-btn--large" style="width: 100%; padding: 1.2em;">
                        <?php esc_html_e('Пожертвовать', 'church-kadence-child'); ?>
                    </button>
                    <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 0.75rem; text-align: center;"><?php esc_html_e('Платежи через защищённый шлюз. Данные карт не хранятся.', 'church-kadence-child'); ?></p>
                </form>
            </div>

        </div>
    </div>
</section>

<style>
.cc-amount { display: grid; place-items: center; padding: 1em 0.5em; border: 1.5px solid var(--color-border-gold); border-radius: var(--radius-sm); font-family: var(--font-display); font-size: 1.05rem; font-weight: 600; cursor: pointer; transition: all var(--transition); }
.cc-amount:hover { border-color: var(--color-gold-dark); }
.cc-amount.is-active { background: var(--color-gold); color: var(--color-ink); border-color: var(--color-gold); }
</style>

<?php church_get_part('ornament-divider'); ?>

<section class="cc-section cc-section--paper-dark" id="requisites">
    <div class="cc-container cc-container--narrow">
        <h2 class="cc-text-center" style="margin-bottom: 2rem;"><?php esc_html_e('Реквизиты для прямого перевода', 'church-kadence-child'); ?></h2>
        <table style="width: 100%; border-collapse: collapse;">
            <?php foreach (array(
                __('Получатель', 'church-kadence-child') => $req['name'],
                __('ИНН', 'church-kadence-child')        => $req['inn'],
                __('КПП', 'church-kadence-child')        => $req['kpp'],
                __('Расчётный счёт', 'church-kadence-child') => $req['rs'],
                __('Банк', 'church-kadence-child')       => $req['bank'],
                __('БИК', 'church-kadence-child')        => $req['bik'],
                __('Корр. счёт', 'church-kadence-child') => $req['ks'],
            ) as $label => $value) : ?>
                <tr style="border-bottom: 1px solid var(--color-border-gold);">
                    <th style="text-align: left; padding: 1rem 0; width: 35%; font-family: var(--font-ui); font-size: var(--text-sm); color: var(--color-text-muted); font-weight: 500; vertical-align: top;"><?php echo esc_html($label); ?></th>
                    <td style="padding: 1rem 0; font-family: var(--font-body); font-weight: 500;"><?php echo esc_html($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p style="text-align: center; margin-top: 2rem;">
            <button type="button" class="cc-btn cc-btn--outline" onclick="navigator.clipboard&&navigator.clipboard.writeText(document.querySelector('table').innerText).then(()=>this.textContent='Скопировано ✓')">
                <?php esc_html_e('Скопировать реквизиты', 'church-kadence-child'); ?>
            </button>
        </p>
    </div>
</section>

<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>

<?php get_footer(); ?>
