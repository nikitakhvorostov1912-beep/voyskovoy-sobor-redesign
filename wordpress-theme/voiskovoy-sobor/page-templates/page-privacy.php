<?php
/**
 * Template Name: Положение о персональных данных
 *
 * Шаблон страницы «Положение о персональных данных» — сгенерирован из docs/privacy.html.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<!-- Page-specific styles (из исходного HTML) -->

<style>
  :root{
    --paper:#f5f0e8; --paper-2:#efe8dc;
    --ink:#1a1f2e; --ink-soft:#2a3144; --text-mute:#5b5345;
    --gold:#c9a961; --burgundy:#8b2635; --burgundy-dark:#6b1d29;
    --line:rgba(26,31,46,.12);
    --f-display:"Cormorant Garamond","Times New Roman",serif;
    --f-body:"Spectral",Georgia,serif;
    --f-ui:"PT Sans",system-ui,sans-serif;
  }
  *,*::before,*::after{box-sizing:border-box}
  html,body{margin:0;padding:0}
  body{
    font-family:var(--f-body);
    color:var(--ink);
    background:var(--paper);
    font-size:17px;
    line-height:1.7;
    -webkit-font-smoothing:antialiased;
  }
  a{color:var(--ink);text-decoration:underline;text-decoration-color:var(--gold);text-underline-offset:3px}
  a:hover{color:var(--burgundy)}

  /* unified header */
  .uheader{
    position:sticky;top:0;z-index:100;
    background:var(--ink);color:var(--paper);
    border-bottom:1px solid rgba(201,169,97,.25);
    font-family:var(--f-ui);
  }
  .uheader__inner{
    max-width:1720px;margin:0 auto;
    padding:0 32px;height:84px;
    display:flex;align-items:center;gap:32px;
  }
  .uheader__brand{
    display:flex;align-items:center;gap:14px;
    color:var(--paper);text-decoration:none;flex-shrink:0;
  }
  .uheader__sigil{width:42px;height:42px;flex-shrink:0}
  .uheader__name{
    font-family:var(--f-display);font-weight:500;font-size:19px;
    line-height:1.05;letter-spacing:.01em;color:var(--paper);
  }
  .uheader__name small{
    display:block;font-family:var(--f-ui);font-size:9.5px;
    font-weight:400;letter-spacing:.22em;text-transform:uppercase;
    color:var(--gold);margin-top:4px;
  }
  .uheader__nav{
    flex:1;display:flex;justify-content:center;gap:30px;
    font-size:12px;letter-spacing:.18em;text-transform:uppercase;
  }
  .uheader__nav a{
    color:rgba(245,240,232,.82);text-decoration:none;
    padding:8px 0;border-bottom:1px solid transparent;
    transition:color .2s,border-color .2s;
  }
  .uheader__nav a:hover{color:var(--paper)}
  .uheader__cta{
    flex-shrink:0;background:var(--burgundy);color:var(--paper);
    font-weight:700;font-size:11.5px;letter-spacing:.2em;
    text-transform:uppercase;padding:13px 22px;
    border:1px solid var(--burgundy);text-decoration:none;
    transition:background .2s;
  }
  .uheader__cta:hover{background:var(--burgundy-dark)}

  /* page */
  main{padding:64px 24px 80px}
  .wrap{max-width:760px;margin:0 auto}
  h1{
    font-family:var(--f-display);font-weight:500;
    font-size:clamp(34px,4.4vw,52px);line-height:1.1;
    margin:0 0 8px;letter-spacing:-.005em;
  }
  .sub{
    font-family:var(--f-ui);font-size:11px;letter-spacing:.28em;
    text-transform:uppercase;color:var(--gold);margin-bottom:36px;
  }
  h2{
    font-family:var(--f-display);font-weight:600;
    font-size:24px;margin:36px 0 12px;
  }
  p{margin:0 0 14px}
  .stub{
    background:var(--paper-2);
    border-left:3px solid var(--gold);
    padding:20px 24px;margin:24px 0;
    font-style:italic;color:var(--text-mute);
  }
  .back{
    display:inline-block;margin-top:32px;
    font-family:var(--f-ui);font-size:13px;letter-spacing:.18em;
    text-transform:uppercase;color:var(--ink);
    text-decoration:none;border-bottom:1px solid var(--gold);
    padding-bottom:2px;
  }
  .back:hover{color:var(--burgundy)}

  /* footer */
  footer{
    background:var(--ink);color:rgba(245,240,232,.78);
    padding:32px 24px;
    font-family:var(--f-ui);font-size:13px;
    text-align:center;
  }
  footer a{color:var(--gold)}
</style>

<main id="main">

<main>
  <div class="wrap">
    <p class="sub">Документ</p>
    <h1>Положение о&nbsp;персональных данных</h1>

    <div class="stub">
      Полный текст положения о&nbsp;персональных данных в&nbsp;настоящее время согласовывается с&nbsp;юристом епархии. До&nbsp;окончательной публикации данная страница содержит краткое описание принципов обработки данных.
    </div>

    <h2>Какие данные собираются</h2>
    <p>При подаче записок и&nbsp;пожертвований через сайт собора могут запрашиваться: имя жертвователя (для поминовения), адрес электронной почты (для уведомлений), номер телефона (для связи), сумма пожертвования, имена для&nbsp;поминовения.</p>

    <h2>Цели обработки</h2>
    <p>Данные используются исключительно для&nbsp;богослужебного поминовения, отправки квитанций о&nbsp;пожертвовании, обратной связи прихожанина с&nbsp;настоятелем или&nbsp;канцелярией собора. Имена для&nbsp;поминовения вносятся в&nbsp;соборные синодики и&nbsp;возносятся за&nbsp;богослужениями.</p>

    <h2>Передача третьим лицам</h2>
    <p>Данные не&nbsp;передаются третьим лицам, за&nbsp;исключением случаев, предусмотренных законодательством Российской Федерации. Платёжная информация (номер карты, CVV) на&nbsp;сайте не&nbsp;сохраняется — её&nbsp;обработка происходит на&nbsp;стороне платёжного шлюза.</p>

    <h2>Хранение и&nbsp;удаление</h2>
    <p>Имена для&nbsp;поминовения хранятся в&nbsp;соборных синодиках на&nbsp;период, соответствующий заказанной требе (молебен, сорокоуст 40&nbsp;дней, поминовение на&nbsp;полгода или&nbsp;на&nbsp;год). Контактные данные удаляются по&nbsp;требованию прихожанина.</p>

    <h2>Контакт по&nbsp;вопросам персональных данных</h2>
    <p>По&nbsp;всем вопросам обращения с&nbsp;персональными данными — на&nbsp;электронную почту собора <a href="mailto:nevskiy-sobor@mail.ru">nevskiy-sobor@mail.ru</a> или по&nbsp;телефону <a href="tel:+78612620020">+7&nbsp;(861)&nbsp;262-00-20</a>.</p>

    <a href="<?php echo esc_url(home_url("/donate")); ?>" class="back">← Вернуться к&nbsp;пожертвованию</a>
  </div>
</main>

</main>

<?php get_footer(); ?>
