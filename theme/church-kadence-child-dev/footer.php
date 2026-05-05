<?php
/**
 * Footer template — 3-колонный footer на тёмно-синем ink с тонкой текстурой
 */
$contacts = function_exists('church_get_contacts_settings') ? church_get_contacts_settings() : array(
    'address'  => 'г. Краснодар, ул. Постовая, 26',
    'phone'    => '+7 (861) 262-00-20',
    'email'    => 'nevskiy-sobor@mail.ru',
);
?>
</main>

<footer class="cc-footer" role="contentinfo">
    <div class="cc-container">
        <div class="cc-footer__grid">

            <div class="cc-footer__column">
                <div class="cc-header__sigil" style="margin-bottom: 1.5rem;">
                    <?php church_get_part('sigil', array('size' => 44, 'color' => 'var(--color-gold)')); ?>
                    <span class="cc-header__sigil-text">
                        <span class="cc-header__sigil-name">Войсковой Собор</span>
                        <span class="cc-header__sigil-sub">св. блгв. кн. Александра Невского</span>
                    </span>
                </div>
                <p style="font-style: italic; color: var(--color-gold-pale); margin-bottom: 1rem;">«Дом мой домом молитвы наречётся»</p>
                <address style="font-style: normal; color: rgba(245,240,232,0.85); line-height: 1.8;">
                    <?php echo esc_html($contacts['address']); ?><br>
                    <a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $contacts['phone'])); ?>"><?php echo esc_html($contacts['phone']); ?></a><br>
                    <a href="mailto:<?php echo esc_attr($contacts['email']); ?>"><?php echo esc_html($contacts['email']); ?></a>
                </address>
            </div>

            <div class="cc-footer__column">
                <h4>Приход</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">О соборе</a></li>
                    <li><a href="<?php echo esc_url(home_url('/schedule')); ?>">Расписание богослужений</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services')); ?>">Святыни и таинства</a></li>
                    <li><a href="<?php echo esc_url(home_url('/news')); ?>">Жизнь прихода</a></li>
                    <li><a href="<?php echo esc_url(home_url('/history')); ?>">История собора</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contacts')); ?>">Контакты</a></li>
                </ul>
            </div>

            <div class="cc-footer__column">
                <h4>Помощь храму</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/donate')); ?>">Пожертвовать</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services-order')); ?>">Заказать требу</a></li>
                    <li><a href="<?php echo esc_url(home_url('/donate#requisites')); ?>">Реквизиты для перевода</a></li>
                </ul>
                <h4 style="margin-top: 2rem;">Соцсети</h4>
                <ul>
                    <li><a href="https://vk.com/voyskovoysoborkrasnodar" target="_blank" rel="noopener">ВКонтакте</a></li>
                    <li><a href="https://t.me/alexnewsobor" target="_blank" rel="noopener">Telegram</a></li>
                </ul>
            </div>

        </div>

        <div class="cc-footer__bottom">
            &copy; <?php echo date('Y'); ?> МПРПО Войсковой собор святого благоверного князя Александра Невского ·
            <a href="<?php echo esc_url(home_url('/privacy')); ?>" style="color: rgba(245,240,232,0.6); border-bottom: none;">Политика конфиденциальности</a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
<script>
(function(){
    // Header sticky shadow on scroll
    var h = document.getElementById('cc-header');
    if (h) window.addEventListener('scroll', function(){
        h.classList.toggle('is-scrolled', window.scrollY > 30);
    }, { passive: true });

    // Mobile menu toggle
    var t = document.querySelector('.cc-header__toggle');
    var m = document.getElementById('cc-mobile-menu');
    if (t && m) {
        t.addEventListener('click', function(){
            var open = m.hasAttribute('hidden');
            if (open) m.removeAttribute('hidden'); else m.setAttribute('hidden','');
            t.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape' && !m.hasAttribute('hidden')) {
                m.setAttribute('hidden','');
                t.setAttribute('aria-expanded','false');
                t.focus();
            }
        });
    }
})();
</script>
</body>
</html>
