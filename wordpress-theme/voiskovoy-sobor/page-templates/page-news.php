<?php
/**
 * Template Name: Новости
 *
 * Шаблон страницы «Новости» — сгенерирован из docs/news.html.
 *
 * @package VoiskovoySobor
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<!-- Page-specific styles (из исходного HTML) -->

<style>
  :root {
    --parchment: #f5f0e8;
    --parchment-2: #ede5d6;
    --parchment-3: #e3d8c2;
    --ink: #1a1f2e;
    --ink-2: #232a3e;
    --ink-3: #2e364c;
    --gold: #c9a961;
    --gold-deep: #a88838;
    --gold-soft: #d8be7d;
    --burgundy: #8b2635;
    --burgundy-deep: #6e1d29;
    --muted: #5b5547;
    --rule: rgba(201, 169, 97, .35);
    --rule-strong: rgba(201, 169, 97, .65);
    --serif-display: "Cormorant Garamond", "Times New Roman", serif;
    --serif-body: "Spectral", Georgia, serif;
    --sans: "PT Sans", system-ui, sans-serif;
  }

  *, *::before, *::after { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; }
  body {
    font-family: var(--serif-body);
    color: var(--ink);
    background: var(--parchment);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    line-height: 1.5;
  }
  a { color: inherit; text-decoration: none; }
  img { max-width: 100%; display: block; }

  /* ============================================================
     HEADER
     ============================================================ */
  .site-header {
    position: sticky;
    top: 0;
    z-index: 50;
    background: var(--ink);
    color: var(--parchment);
    border-bottom: 1px solid rgba(201, 169, 97, .25);
  }
  .site-header__inner {
    max-width: 1720px;
    margin: 0 auto;
    padding: 0 32px;
    height: 84px;
    display: flex;
    align-items: center;
    gap: 40px;
  }
  .brand {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
  }
  .brand__sigil {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
  }
  .brand__name {
    font-family: var(--serif-display);
    font-weight: 500;
    font-size: 19px;
    line-height: 1.05;
    letter-spacing: .01em;
  }
  .brand__name small {
    display: block;
    font-family: var(--sans);
    font-size: 9.5px;
    font-weight: 400;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--gold);
    margin-top: 4px;
  }
  .nav {
    flex: 1;
    display: flex;
    justify-content: center;
    gap: 30px;
    font-family: var(--sans);
    font-size: 12px;
    letter-spacing: .18em;
    text-transform: uppercase;
  }
  .nav a {
    color: rgba(245, 240, 232, .82);
    padding: 8px 0;
    border-bottom: 1px solid transparent;
    transition: color .2s, border-color .2s;
  }
  .nav a:hover { color: var(--parchment); }
  .nav a.is-active {
    color: var(--gold);
    border-bottom-color: var(--gold);
  }
  .header-cta {
    flex-shrink: 0;
    background: var(--burgundy);
    color: var(--parchment);
    font-family: var(--sans);
    font-weight: 700;
    font-size: 11.5px;
    letter-spacing: .2em;
    text-transform: uppercase;
    padding: 13px 22px;
    border: 1px solid var(--burgundy);
    transition: background .2s, transform .2s;
  }
  .header-cta:hover {
    background: var(--burgundy-deep);
    border-color: var(--burgundy-deep);
  }
  .nav-toggle { display: none; }

  /* ============================================================
     HEADER RIBBON
     ============================================================ */
  .ribbon {
    background: var(--parchment-2);
    border-bottom: 1px solid var(--rule);
    position: relative;
    overflow: hidden;
  }
  .ribbon::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 12% 30%, rgba(201,169,97,.06), transparent 45%),
      radial-gradient(circle at 88% 70%, rgba(139,38,53,.04), transparent 50%);
    pointer-events: none;
  }
  .ribbon__inner {
    max-width: 1720px;
    margin: 0 auto;
    padding: 96px 32px 72px;
    text-align: center;
    position: relative;
  }
  .kicker {
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .32em;
    text-transform: uppercase;
    color: var(--gold-deep);
    display: inline-flex;
    align-items: center;
    gap: 18px;
  }
  .kicker::before, .kicker::after {
    content: "";
    width: 32px;
    height: 1px;
    background: var(--gold);
  }
  .ribbon h1 {
    font-family: var(--serif-display);
    font-weight: 500;
    font-size: 64px;
    line-height: 1.05;
    letter-spacing: -.005em;
    margin: 22px 0 26px;
    color: var(--ink);
  }
  .ribbon__quote {
    font-family: var(--serif-body);
    font-style: italic;
    font-weight: 300;
    font-size: 19px;
    color: var(--muted);
    max-width: 640px;
    margin: 0 auto;
    line-height: 1.55;
  }
  .ribbon__quote cite {
    font-style: normal;
    font-family: var(--sans);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--gold-deep);
    display: block;
    margin-top: 12px;
  }
  .ribbon__rule {
    width: 80px;
    height: 1px;
    background: var(--gold);
    margin: 56px auto 0;
    position: relative;
  }
  .ribbon__rule::before, .ribbon__rule::after {
    content: "";
    position: absolute;
    top: 50%;
    width: 6px; height: 6px;
    transform: translateY(-50%) rotate(45deg);
    background: var(--gold);
  }
  .ribbon__rule::before { left: -16px; }
  .ribbon__rule::after { right: -16px; }

  /* ============================================================
     NEWS GRID
     ============================================================ */
  .news {
    background: var(--parchment);
    padding: 96px 32px;
  }
  .news__inner {
    max-width: 1720px;
    margin: 0 auto;
  }
  .news__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }
  .card {
    background: var(--parchment);
    border: 1px solid var(--rule-strong);
    display: flex;
    flex-direction: column;
    transition: transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s, border-color .35s;
    position: relative;
  }
  .card::before {
    /* corner ornaments */
    content: "";
    position: absolute;
    inset: 6px;
    border: 1px solid transparent;
    pointer-events: none;
    transition: border-color .35s;
  }
  .card:hover {
    transform: translateY(-4px);
    border-color: var(--gold);
    box-shadow: 0 20px 40px -24px rgba(26, 31, 46, .35);
  }
  .card:hover::before { border-color: rgba(201, 169, 97, .25); }

  .card__image {
    aspect-ratio: 16 / 10;
    background: var(--ink-2);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .card__image::after {
    /* subtle vignette */
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at center, transparent 55%, rgba(0,0,0,.45));
    pointer-events: none;
  }
  .card__image svg {
    width: 56%;
    height: 80%;
    color: var(--gold);
    opacity: .92;
  }
  .card__body {
    padding: 26px 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex: 1;
  }
  .card__date {
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--gold-deep);
  }
  .card__title {
    font-family: var(--serif-display);
    font-weight: 600;
    font-size: 22px;
    line-height: 1.2;
    color: var(--ink);
    margin: 0;
    text-wrap: balance;
  }
  .card__text {
    font-family: var(--serif-body);
    font-weight: 400;
    font-size: 15px;
    line-height: 1.6;
    color: var(--muted);
    margin: 0;
    text-wrap: pretty;
  }
  .card__more {
    margin-top: auto;
    padding-top: 18px;
    font-family: var(--sans);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--ink);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    align-self: flex-start;
    border-bottom: 1px solid transparent;
    transition: color .2s, border-color .2s;
  }
  .card__more::after {
    content: "→";
    font-size: 13px;
    transition: transform .25s;
  }
  .card:hover .card__more { color: var(--burgundy); }
  .card:hover .card__more::after { transform: translateX(3px); }

  /* ============================================================
     SUBSCRIBE
     ============================================================ */
  .subscribe {
    background: var(--parchment);
    padding: 24px 32px 112px;
  }
  .subscribe__inner {
    max-width: 880px;
    margin: 0 auto;
    text-align: center;
    border-top: 1px solid var(--rule);
    padding-top: 80px;
  }
  .subscribe__lede {
    font-family: var(--serif-body);
    font-style: italic;
    font-weight: 300;
    font-size: 20px;
    color: var(--muted);
    margin: 24px 0 36px;
    line-height: 1.55;
    text-wrap: balance;
  }
  .subscribe__buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
  }
  .btn-outline {
    font-family: var(--sans);
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .22em;
    text-transform: uppercase;
    padding: 16px 36px;
    border: 1px solid var(--ink);
    color: var(--ink);
    background: transparent;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    transition: background .2s, color .2s, border-color .2s;
  }
  .btn-outline:hover {
    background: var(--ink);
    color: var(--parchment);
  }
  .btn-outline svg {
    width: 16px; height: 16px;
  }

  /* ============================================================
     FOOTER
     ============================================================ */
  .site-footer {
    background: var(--ink);
    color: rgba(245, 240, 232, .75);
    padding: 80px 32px 40px;
    border-top: 4px solid var(--gold);
  }
  .footer__inner {
    max-width: 1720px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr;
    gap: 64px;
  }
  .footer__col h4 {
    font-family: var(--sans);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .26em;
    text-transform: uppercase;
    color: var(--gold);
    margin: 0 0 22px;
  }
  .footer__brand {
    font-family: var(--serif-display);
    font-weight: 500;
    font-size: 24px;
    color: var(--parchment);
    line-height: 1.15;
    margin-bottom: 18px;
  }
  .footer__about {
    font-family: var(--serif-body);
    font-style: italic;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.65;
    color: rgba(245, 240, 232, .65);
    max-width: 360px;
  }
  .footer__list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-family: var(--sans);
    font-size: 13px;
  }
  .footer__list a {
    color: rgba(245, 240, 232, .75);
    transition: color .2s;
  }
  .footer__list a:hover { color: var(--gold); }
  .footer__contact {
    font-family: var(--serif-body);
    font-size: 14.5px;
    line-height: 1.75;
    color: rgba(245, 240, 232, .8);
  }
  .footer__contact strong {
    color: var(--parchment);
    font-weight: 500;
  }
  .footer__bottom {
    max-width: 1720px;
    margin: 64px auto 0;
    padding-top: 28px;
    border-top: 1px solid rgba(245, 240, 232, .1);
    display: flex;
    justify-content: space-between;
    font-family: var(--sans);
    font-size: 11px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(245, 240, 232, .5);
  }

  /* ============================================================
     MOBILE STICKY BAR
     ============================================================ */
  .mobile-bar { display: none; }

  /* ============================================================
     RESPONSIVE
     ============================================================ */
  @media (max-width: 1080px) {
    .news__grid { grid-template-columns: repeat(2, 1fr); }
    .nav { gap: 20px; font-size: 11px; }
    .footer__inner { grid-template-columns: 1fr 1fr; }
  }

  @media (max-width: 760px) {
    .site-header__inner {
      height: 64px;
      padding: 0 18px;
      gap: 16px;
    }
    .brand__sigil { width: 36px; height: 36px; }
    .brand__name { font-size: 15px; }
    .brand__name small { font-size: 8.5px; letter-spacing: .18em; }
    .nav { display: none; }
    .header-cta { display: none; }
    .nav-toggle {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: auto;
      width: 40px;
      height: 40px;
      background: transparent;
      border: 1px solid rgba(201,169,97,.4);
      color: var(--gold);
      cursor: pointer;
    }
    .nav-toggle svg { width: 18px; height: 18px; }

    .ribbon__inner { padding: 56px 22px 44px; }
    .ribbon h1 { font-size: 38px; margin: 18px 0 20px; }
    .ribbon__quote { font-size: 16px; }
    .ribbon__rule { margin-top: 36px; }

    .news { padding: 56px 22px 40px; }
    .news__grid { grid-template-columns: 1fr; gap: 24px; }
    .card__title { font-size: 20px; }

    .subscribe { padding: 8px 22px 120px; }
    .subscribe__inner { padding-top: 56px; }
    .subscribe__lede { font-size: 17px; }
    .btn-outline { padding: 14px 24px; font-size: 11px; flex: 1; justify-content: center; }

    .site-footer { padding: 56px 22px 32px; }
    .footer__inner { grid-template-columns: 1fr; gap: 40px; }
    .footer__bottom { flex-direction: column; gap: 10px; text-align: center; }

    .mobile-bar {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: var(--ink);
      border-top: 1px solid var(--gold);
      z-index: 60;
      box-shadow: 0 -8px 24px rgba(0,0,0,.2);
    }
    .mobile-bar a {
      padding: 14px 6px 12px;
      font-family: var(--sans);
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: rgba(245, 240, 232, .85);
      text-align: center;
      border-right: 1px solid rgba(245, 240, 232, .1);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }
    .mobile-bar a:last-child {
      border-right: none;
      background: var(--burgundy);
      color: var(--parchment);
    }
    .mobile-bar svg {
      width: 18px; height: 18px;
      color: var(--gold);
    }
    .mobile-bar a:last-child svg { color: var(--parchment); }
  }
</style>
<style>
/* Force-show all scroll-reveal elements (JS-dependent animations are gone) */
.reveal, [class*="reveal"], .fade-in, .ts-event, .animate-on-scroll, [class*="-fade"], [class*="appear"] {
  opacity: 1 !important;
  transform: none !important;
  visibility: visible !important;
}
</style>
<style id="unified-header-css">
/* === Unified site header (overrides any per-page header CSS) === */
body header:not(.uheader) {
    display: none !important;
}
body .uheader,
body .uheader * {
    box-sizing: border-box;
}
body .uheader {
    position: sticky;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    width: 100%;
    background: #1a1f2e;
    color: #f5f0e8;
    border-bottom: 1px solid rgba(201,169,97,0.25);
    font-family: 'PT Sans', sans-serif;
}
body .uheader__inner {
    width: 100%;
    max-width: 1720px;
    margin: 0 auto;
    padding: 0 48px;
    height: 84px;
    display: flex;
    align-items: center;
    gap: 40px;
}
body .uheader__brand {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
    color: #f5f0e8;
    text-decoration: none;
}
body .uheader__brand:hover { color: #f5f0e8; }
body .uheader__sigil {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
}
body .uheader__name {
    font-family: 'Cormorant Garamond', 'Times New Roman', serif !important;
    font-weight: 500 !important;
    font-style: normal !important;
    font-size: 19px !important;
    line-height: 1.05 !important;
    letter-spacing: 0.01em !important;
    color: #f5f0e8 !important;
    text-transform: none !important;
    font-variant: normal !important;
}
body .uheader__name small {
    display: block !important;
    font-family: 'PT Sans', 'Arial', sans-serif !important;
    font-size: 9.5px !important;
    font-weight: 400 !important;
    font-style: normal !important;
    line-height: 1 !important;
    letter-spacing: 0.22em !important;
    text-transform: uppercase !important;
    color: #c9a961 !important;
    margin-top: 4px !important;
}
body .uheader__nav {
    flex: 1;
    display: flex;
    justify-content: center;
    gap: 30px;
    font-size: 12px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}
body .uheader__nav a {
    color: rgba(245,240,232,0.82);
    padding: 8px 0;
    border-bottom: 1px solid transparent;
    transition: color 0.2s, border-color 0.2s;
    text-decoration: none;
}
body .uheader__nav a:hover {
    color: #f5f0e8;
    text-decoration: none;
}
body .uheader__nav a.is-active {
    color: #c9a961;
    border-bottom-color: #c9a961;
}
body .uheader__cta {
    flex-shrink: 0;
    background: #8b2635;
    color: #f5f0e8;
    font-family: 'PT Sans', sans-serif;
    font-weight: 700;
    font-size: 11.5px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    padding: 13px 22px;
    border: 1px solid #8b2635;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
}
body .uheader__cta:hover {
    background: #6b1d29;
    border-color: #6b1d29;
    color: #f5f0e8;
    text-decoration: none;
}
@media (max-width: 1100px) {
    body .uheader__nav { gap: 20px; font-size: 11px; letter-spacing: 0.14em; }
    body .uheader__inner { padding: 0 20px; gap: 24px; }
    body .uheader__name { font-size: 17px; }
    body .uheader__name small { font-size: 9px; letter-spacing: 0.18em; }
}
@media (max-width: 860px) {
    body .uheader__nav { display: none; }
    body .uheader__inner { height: 70px; gap: 16px; }
}
</style>

<main id="main">

<!-- ===== HEADER ===== -->


<!-- ===== HEADER RIBBON ===== -->
<section class="ribbon">
  <div class="ribbon__inner">
    <span class="kicker">Жизнь прихода</span>
    <h1>Новости и объявления</h1>
    <p class="ribbon__quote">
      «Возвещайте день ото дня спасение Его»
      <cite>Псалом 95, стих 2</cite>
    </p>
    <div class="ribbon__rule" aria-hidden="true"></div>
  </div>
</section>

<!-- ===== NEWS GRID ===== -->
<section class="news">
  <div class="news__inner">
    <div class="news__grid">

      <!-- 1. Архиерейская Литургия — Купол с крестом -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- 8-pointed cross atop dome -->
            <g stroke-width="1.6">
              <line x1="100" y1="14" x2="100" y2="56"></line>
              <line x1="92" y1="22" x2="108" y2="22"></line>
              <line x1="88" y1="30" x2="112" y2="30"></line>
              <line x1="92" y1="46" x2="108" y2="42"></line>
            </g>
            <!-- Onion dome -->
            <path d="M70 96 C 70 70, 80 56, 100 56 C 120 56, 130 70, 130 96 Z"></path>
            <path d="M70 96 Q 100 88 130 96"></path>
            <!-- Drum -->
            <path d="M76 96 H 124 V 116 H 76 Z"></path>
            <line x1="88" y1="100" x2="88" y2="112"></line>
            <line x1="100" y1="100" x2="100" y2="112"></line>
            <line x1="112" y1="100" x2="112" y2="112"></line>
            <!-- Base / cornice -->
            <path d="M62 116 H 138" stroke-width="1.6"></path>
            <path d="M58 122 H 142"></path>
            <!-- Side smaller domes -->
            <path d="M40 130 C 40 120, 48 114, 56 114 C 64 114, 72 120, 72 130 Z"></path>
            <line x1="56" y1="106" x2="56" y2="114"></line>
            <line x1="51" y1="110" x2="61" y2="110"></line>
            <path d="M128 130 C 128 120, 136 114, 144 114 C 152 114, 160 120, 160 130 Z"></path>
            <line x1="144" y1="106" x2="144" y2="114"></line>
            <line x1="139" y1="110" x2="149" y2="110"></line>
            <!-- Ground line -->
            <line x1="32" y1="146" x2="168" y2="146"></line>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">Ближайшее воскресенье · соборное служение</span>
          <h3 class="card__title">Архиерейская Литургия</h3>
          <p class="card__text">В ближайшее воскресенье Божественную Литургию возглавит правящий архиерей. Начало — в 9:30, всенощное бдение накануне в 17:00.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">Читать далее</a>
        </div>
      </article>

      <!-- 2. Воскресная школа — Книга-Псалтирь -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- Open book / Psalter -->
            <path d="M40 50 L100 60 L100 130 L40 120 Z"></path>
            <path d="M160 50 L100 60 L100 130 L160 120 Z"></path>
            <!-- Spine -->
            <line x1="100" y1="60" x2="100" y2="130"></line>
            <!-- Ornate cross on left page -->
            <g stroke-width="1.4">
              <line x1="64" y1="74" x2="64" y2="100"></line>
              <line x1="56" y1="82" x2="72" y2="82"></line>
              <line x1="60" y1="78" x2="60" y2="78.1"></line>
              <circle cx="64" cy="82" r="7" fill="none" stroke-width="1"></circle>
            </g>
            <!-- Text lines on right page -->
            <line x1="112" y1="78" x2="148" y2="80"></line>
            <line x1="112" y1="86" x2="150" y2="88"></line>
            <line x1="112" y1="94" x2="146" y2="96"></line>
            <line x1="112" y1="102" x2="150" y2="104"></line>
            <line x1="112" y1="110" x2="142" y2="112"></line>
            <!-- Top decorative ribbon -->
            <path d="M30 50 Q 100 38 170 50" stroke-width="1"></path>
            <!-- Clasp -->
            <line x1="100" y1="64" x2="100" y2="58"></line>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">Воскресная школа</span>
          <h3 class="card__title">Открыт набор «Русский щит»</h3>
          <p class="card__text">Объявлен набор детей 7–14 лет в воскресную школу. Программа: Закон Божий, история Кубани, церковное пение и иконопись.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">Подробнее о наборе</a>
        </div>
      </article>

      <!-- 3. Сестричество — Сердце с крестом -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- Heart -->
            <path d="M100 134 C 60 108, 40 86, 40 64 C 40 48, 54 38, 70 38 C 82 38, 92 46, 100 58 C 108 46, 118 38, 130 38 C 146 38, 160 48, 160 64 C 160 86, 140 108, 100 134 Z"></path>
            <!-- Inner heart -->
            <path d="M100 122 C 70 102, 54 84, 54 66 C 54 54, 64 48, 74 48 C 84 48, 92 54, 100 64 C 108 54, 116 48, 126 48 C 136 48, 146 54, 146 66 C 146 84, 130 102, 100 122 Z" stroke-width="1" opacity=".55"></path>
            <!-- Cross inside heart -->
            <g stroke-width="1.6">
              <line x1="100" y1="62" x2="100" y2="106"></line>
              <line x1="86" y1="76" x2="114" y2="76"></line>
              <line x1="90" y1="98" x2="110" y2="94"></line>
            </g>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">Сестричество</span>
          <h3 class="card__title">Сбор гуманитарной помощи</h3>
          <p class="card__text">Сестричество прихода продолжает сбор для семей воинов. Принимаются продукты длительного хранения, тёплая одежда, средства гигиены.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">Как помочь</a>
        </div>
      </article>

      <!-- 4. Свт. Николай — Круг с крестом (нимб) -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- Outer halo / circle -->
            <circle cx="100" cy="80" r="56"></circle>
            <circle cx="100" cy="80" r="48" stroke-width="1" opacity=".6"></circle>
            <!-- Inner cross-in-circle (Christogram) -->
            <circle cx="100" cy="80" r="30"></circle>
            <g stroke-width="1.6">
              <line x1="100" y1="50" x2="100" y2="110"></line>
              <line x1="70" y1="80" x2="130" y2="80"></line>
            </g>
            <!-- Decorative rays -->
            <g stroke-width="1" opacity=".7">
              <line x1="100" y1="14" x2="100" y2="22"></line>
              <line x1="100" y1="138" x2="100" y2="146"></line>
              <line x1="34" y1="80" x2="42" y2="80"></line>
              <line x1="158" y1="80" x2="166" y2="80"></line>
              <line x1="50" y1="32" x2="56" y2="38"></line>
              <line x1="144" y1="122" x2="150" y2="128"></line>
              <line x1="150" y1="32" x2="144" y2="38"></line>
              <line x1="56" y1="122" x2="50" y2="128"></line>
            </g>
            <!-- Small decorative dot -->
            <circle cx="100" cy="80" r="2" fill="currentColor" stroke="none"></circle>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">Праздник</span>
          <h3 class="card__title">Память святителя Николая Чудотворца</h3>
          <p class="card__text">22 мая совершается память Святителя Николая Чудотворца. Литургия в 8:00, праздничный молебен по окончании богослужения.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">Программа праздника</a>
        </div>
      </article>

      <!-- 5. 9 Мая — Часы с крестом -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- Clock face -->
            <circle cx="100" cy="86" r="48"></circle>
            <circle cx="100" cy="86" r="42" stroke-width="1" opacity=".5"></circle>
            <!-- Hour ticks (12, 3, 6, 9 marked stronger; cross above 12) -->
            <g stroke-width="1.4">
              <line x1="100" y1="44" x2="100" y2="50"></line>
              <line x1="100" y1="122" x2="100" y2="128"></line>
              <line x1="58" y1="86" x2="64" y2="86"></line>
              <line x1="136" y1="86" x2="142" y2="86"></line>
            </g>
            <g stroke-width=".8" opacity=".55">
              <line x1="79" y1="50" x2="81" y2="54"></line>
              <line x1="121" y1="50" x2="119" y2="54"></line>
              <line x1="79" y1="122" x2="81" y2="118"></line>
              <line x1="121" y1="122" x2="119" y2="118"></line>
              <line x1="64" y1="65" x2="68" y2="67"></line>
              <line x1="64" y1="107" x2="68" y2="105"></line>
              <line x1="136" y1="65" x2="132" y2="67"></line>
              <line x1="136" y1="107" x2="132" y2="105"></line>
            </g>
            <!-- Hands pointing 10:00 (commemoration time) -->
            <line x1="100" y1="86" x2="100" y2="58" stroke-width="1.6"></line>
            <line x1="100" y1="86" x2="76" y2="74" stroke-width="1.6"></line>
            <circle cx="100" cy="86" r="2.5" fill="currentColor" stroke="none"></circle>
            <!-- Small 8-pointed cross above clock -->
            <g stroke-width="1.4">
              <line x1="100" y1="14" x2="100" y2="36"></line>
              <line x1="93" y1="20" x2="107" y2="20"></line>
              <line x1="90" y1="26" x2="110" y2="26"></line>
              <line x1="93" y1="32" x2="107" y2="30"></line>
            </g>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">9 мая</span>
          <h3 class="card__title">Поминовение павших воинов</h3>
          <p class="card__text">В День Победы в соборе будет совершена Великая панихида по всем воинам, павшим за Веру и Отечество. Начало в 10:00.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">Подробности</a>
        </div>
      </article>

      <!-- 6. 20 лет — Декоративный 8-конечный крест -->
      <article class="card">
        <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__image" aria-hidden="true">
          <svg viewBox="0 0 200 160" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="square" stroke-linejoin="miter">
            <!-- Decorative diamond frame -->
            <path d="M100 10 L 174 80 L 100 150 L 26 80 Z" stroke-width="1" opacity=".45"></path>
            <!-- Outer rectangle frame -->
            <rect x="48" y="20" width="104" height="120" stroke-width="1" opacity=".35"></rect>
            <!-- 8-pointed Russian Orthodox cross with ornate ends -->
            <g stroke-width="1.6">
              <!-- vertical -->
              <line x1="100" y1="22" x2="100" y2="138"></line>
              <!-- top bar (titulus) -->
              <line x1="86" y1="40" x2="114" y2="40"></line>
              <!-- main cross bar -->
              <line x1="74" y1="60" x2="126" y2="60"></line>
              <!-- slanted footrest -->
              <line x1="80" y1="108" x2="120" y2="100"></line>
            </g>
            <!-- decorative trefoil ends -->
            <g stroke-width="1.2">
              <circle cx="100" cy="22" r="3.5"></circle>
              <circle cx="100" cy="138" r="3.5"></circle>
              <circle cx="74" cy="60" r="3.5"></circle>
              <circle cx="126" cy="60" r="3.5"></circle>
              <circle cx="86" cy="40" r="2.5"></circle>
              <circle cx="114" cy="40" r="2.5"></circle>
            </g>
            <!-- inner diamonds at intersections -->
            <g stroke-width="1" opacity=".7">
              <path d="M100 56 L104 60 L100 64 L96 60 Z"></path>
              <path d="M100 36 L102 40 L100 44 L98 40 Z"></path>
            </g>
            <!-- small flourishes flanking -->
            <g stroke-width="1" opacity=".55">
              <path d="M40 80 Q 48 76 56 80"></path>
              <path d="M144 80 Q 152 76 160 80"></path>
            </g>
          </svg>
        </a>
        <div class="card__body">
          <span class="card__date">28 мая · юбилей</span>
          <h3 class="card__title">20 лет Великого освящения</h3>
          <p class="card__text">В этом году исполняется 20 лет с Великого освящения возрождённого собора, совершённого митрополитом Кириллом — ныне Святейшим Патриархом.</p>
          <a href="<?php echo esc_url(home_url("/news")); ?>" class="card__more">К юбилею</a>
        </div>
      </article>

    </div>
  </div>
</section>

<!-- ===== SUBSCRIBE ===== -->
<section class="subscribe">
  <div class="subscribe__inner">
    <span class="kicker">Подписаться на обновления</span>
    <p class="subscribe__lede">Все новости собора публикуются в официальных группах прихода</p>
    <div class="subscribe__buttons">
      <a class="btn-outline" href="https://vk.com/voyskovoysoborkrasnodar" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M2 5 h20 v14 h-20 z M5 8 c1 4 3 7 7 7 v-3 c0-1 0-1 1-1 c1 1 2 3 3 4 h3 c-1-2-3-4-4-5 c1-1 3-3 3-5 h-3 c0 2-2 3-3 4 v-4 z"></path>
        </svg>
        ВКонтакте
      </a>
      <a class="btn-outline" href="https://t.me/alexnewsobor" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M7 12 L17 7 L15 17 L11 14 L14 10 L9 13 Z" fill="currentColor"></path>
        </svg>
        Telegram
      </a>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->

</main>

<!-- Page-specific inline scripts -->

<script>
  // Smooth hover ripple on cards
  document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const r = card.getBoundingClientRect();
      const x = ((e.clientX - r.left) / r.width) * 100;
      const y = ((e.clientY - r.top) / r.height) * 100;
      card.style.setProperty('--mx', x + '%');
      card.style.setProperty('--my', y + '%');
    });
  });
</script>

<?php get_footer(); ?>
