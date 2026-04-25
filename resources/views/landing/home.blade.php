<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VoyagePH – Premium Bus Travel</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink:       #0e1117;
      --ink-mid:   #1a2235;
      --ink-soft:  #2e3a52;
      --gold:      #b8912a;
      --gold-lt:   #d4a843;
      --gold-bg:   rgba(184,145,42,.08);
      --gold-line: rgba(184,145,42,.2);
      --red:       #c0392b;
      --bg:        #f9f7f4;
      --bg-2:      #f2ede6;
      --bg-3:      #ffffff;
      --border:    #e4ddd3;
      --border-dk: #ccc4b8;
      --muted:     #7a7468;
      --muted-lt:  #a09890;
      --text:      #1a1612;
      --nav-h:     70px;
      --radius:    14px;
      --shadow-sm: 0 2px 12px rgba(14,17,23,.06);
      --shadow-md: 0 8px 32px rgba(14,17,23,.1);
      --shadow-lg: 0 20px 60px rgba(14,17,23,.14);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Outfit', sans-serif;
      background: var(--bg);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ─────────────────────────────
       NAVBAR
    ───────────────────────────── */
    #nav {
      position: fixed; top: 0; left: 0; right: 0;
      height: var(--nav-h); z-index: 900;
      background: rgba(249,247,244,.92);
      backdrop-filter: blur(18px) saturate(1.4);
      border-bottom: 1px solid var(--border);
      transition: box-shadow .3s;
    }
    #nav.scrolled { box-shadow: var(--shadow-md); }

    .nav-wrap {
      max-width: 1260px; margin: 0 auto;
      height: 100%; display: flex;
      align-items: center; padding: 0 32px;
    }

    .logo {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0; margin-right: 44px;
    }
    .logo-mark {
      width: 38px; height: 38px; border-radius: 9px;
      background: var(--ink); display: flex;
      align-items: center; justify-content: center;
    }
    .logo-mark svg { width: 20px; height: 20px; fill: none; stroke: var(--gold-lt); stroke-width: 1.8; stroke-linecap: round; }
    .logo-wordmark {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem; font-weight: 800;
      color: var(--ink); letter-spacing: -.3px;
    }
    .logo-wordmark span { color: var(--gold); }

    .nav-links {
      display: flex; list-style: none; gap: 2px;
    }
    .nav-links a {
      text-decoration: none; color: var(--muted);
      font-size: .84rem; font-weight: 500;
      padding: 7px 14px; border-radius: 7px;
      transition: color .18s, background .18s;
      position: relative; white-space: nowrap;
    }
    .nav-links a:hover { color: var(--ink); background: var(--bg-2); }
    .nav-links a.active { color: var(--ink); font-weight: 600; }
    .nav-links a.active::after {
      content: ''; position: absolute;
      bottom: -1px; left: 14px; right: 14px;
      height: 2px; background: var(--gold);
      border-radius: 2px;
    }

    .nav-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

    .btn-login {
      background: none; border: 1.5px solid var(--border-dk);
      color: var(--ink-soft); padding: 8px 18px; border-radius: 8px;
      font-size: .83rem; font-weight: 600; cursor: pointer;
      font-family: 'Outfit', sans-serif; transition: all .18s;
    }
    .btn-login:hover { border-color: var(--ink); color: var(--ink); }

    .btn-book {
      background: var(--ink); color: #fff;
      border: none; padding: 9px 20px; border-radius: 8px;
      font-size: .83rem; font-weight: 700; cursor: pointer;
      font-family: 'Outfit', sans-serif; transition: all .2s;
      display: flex; align-items: center; gap: 6px;
    }
    .btn-book:hover { background: var(--ink-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(14,17,23,.2); }

    /* ─────────────────────────────
       HERO
    ───────────────────────────── */
    #hero {
      min-height: 100vh;
      padding: calc(var(--nav-h) + 72px) 32px 0;
      background:
        linear-gradient(170deg, #fff 0%, var(--bg) 60%);
      position: relative; overflow: hidden;
      display: flex; flex-direction: column;
      align-items: center;
    }

    /* decorative geometric lines */
    #hero::before {
      content: '';
      position: absolute; top: 0; right: 0;
      width: 55%; height: 100%;
      background:
        radial-gradient(ellipse 80% 60% at 80% 30%, rgba(184,145,42,.07) 0%, transparent 60%);
      pointer-events: none;
    }
    .hero-grid-lines {
      position: absolute; inset: 0; pointer-events: none; overflow: hidden;
    }
    .hero-grid-lines svg { width: 100%; height: 100%; opacity: .04; }

    .hero-content {
      max-width: 1260px; width: 100%;
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 64px; align-items: center;
      position: relative; z-index: 1;
    }

    .hero-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: .72rem; font-weight: 700; letter-spacing: 2.5px;
      text-transform: uppercase; color: var(--gold);
      margin-bottom: 22px;
    }
    .hero-eyebrow::before {
      content: ''; width: 28px; height: 1.5px; background: var(--gold);
    }

    .hero-heading {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.6rem, 4.5vw, 4.2rem);
      font-weight: 800; line-height: 1.08;
      letter-spacing: -.5px; color: var(--ink);
    }
    .hero-heading em { font-style: italic; color: var(--gold); }

    .hero-sub {
      color: var(--muted); font-size: 1rem;
      line-height: 1.75; margin-top: 22px;
      max-width: 420px;
    }

    .hero-actions {
      display: flex; gap: 12px; margin-top: 36px; flex-wrap: wrap;
    }
    .cta-primary {
      background: var(--ink); color: #fff;
      border: none; padding: 14px 32px;
      border-radius: 9px; font-size: .95rem;
      font-weight: 700; cursor: pointer;
      font-family: 'Outfit', sans-serif;
      transition: all .2s; display: flex; align-items: center; gap: 8px;
    }
    .cta-primary:hover { background: var(--ink-mid); transform: translateY(-2px); box-shadow: 0 10px 30px rgba(14,17,23,.2); }
    .cta-secondary {
      background: none; color: var(--ink);
      border: 1.5px solid var(--border-dk);
      padding: 14px 28px; border-radius: 9px;
      font-size: .95rem; font-weight: 600; cursor: pointer;
      font-family: 'Outfit', sans-serif; transition: all .2s;
    }
    .cta-secondary:hover { border-color: var(--ink); background: var(--bg-2); }

    .hero-stats {
      display: flex; gap: 36px; margin-top: 48px;
      padding-top: 32px; border-top: 1px solid var(--border);
    }
    .hstat-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem; font-weight: 800; color: var(--ink);
      display: block; line-height: 1;
    }
    .hstat-num sup { font-size: 1rem; color: var(--gold); }
    .hstat-label { font-size: .76rem; color: var(--muted); margin-top: 5px; font-weight: 500; }

    /* hero right: illustrated card stack */
    .hero-visual {
      position: relative; height: 460px;
    }
    .hv-card {
      position: absolute; border-radius: 18px;
      background: var(--bg-3); border: 1px solid var(--border);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
    }
    .hv-main {
      width: 100%; height: 320px; top: 40px; left: 0;
      background: linear-gradient(145deg, var(--ink) 0%, var(--ink-mid) 100%);
      color: #fff;
      display: flex; flex-direction: column; justify-content: flex-end;
      padding: 28px;
    }
    .hv-main::before {
      content: '🚌';
      position: absolute; top: 28px; left: 28px;
      font-size: 2.5rem; opacity: .15;
      transform: scale(5); transform-origin: left top;
      filter: blur(2px);
    }
    .hv-route-label { font-size: .68rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,.45); margin-bottom: 10px; }
    .hv-route { display: flex; align-items: center; gap: 14px; }
    .hv-city { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; }
    .hv-arrow { color: var(--gold-lt); font-size: 1.1rem; flex: 1; text-align: center; }
    .hv-meta { display: flex; justify-content: space-between; margin-top: 18px; }
    .hv-meta-item small { display: block; font-size: .67rem; color: rgba(255,255,255,.4); margin-bottom: 3px; }
    .hv-meta-item span { font-size: .88rem; font-weight: 600; }
    .hv-badge {
      position: absolute; top: 24px; right: 24px;
      background: var(--gold); color: var(--ink);
      font-size: .7rem; font-weight: 800;
      padding: 5px 12px; border-radius: 50px;
      letter-spacing: .5px;
    }

    .hv-float-1 {
      width: 200px; bottom: 30px; right: -20px;
      padding: 16px 18px;
    }
    .hv-float-2 {
      width: 180px; top: 0; right: 20px;
      padding: 14px 16px;
      background: var(--ink); color: #fff;
    }
    .float-title { font-size: .72rem; font-weight: 700; color: var(--muted); letter-spacing: .5px; margin-bottom: 8px; }
    .float-title-dk { font-size: .72rem; font-weight: 700; color: rgba(255,255,255,.4); letter-spacing: .5px; margin-bottom: 8px; }
    .float-val { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 800; color: var(--ink); }
    .float-val-lt { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 800; color: #fff; }
    .float-sub { font-size: .72rem; color: var(--muted); margin-top: 3px; }
    .float-sub-lt { font-size: .72rem; color: rgba(255,255,255,.4); margin-top: 3px; }
    .float-row { display: flex; align-items: center; gap: 8px; }
    .dot-green { width: 7px; height: 7px; border-radius: 50%; background: #34d399; animation: pulse 1.8s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* search widget */
    .search-widget {
      max-width: 860px; width: 100%;
      background: var(--bg-3);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow-lg);
      padding: 28px 32px 32px;
      margin-top: 72px;
      position: relative; z-index: 2;
    }
    .sw-tabs {
      display: flex; gap: 4px; margin-bottom: 22px;
    }
    .sw-tab {
      padding: 7px 18px; border-radius: 7px;
      font-size: .8rem; font-weight: 600;
      cursor: pointer; border: none;
      background: none; color: var(--muted);
      font-family: 'Outfit', sans-serif;
      transition: all .18s;
    }
    .sw-tab.active { background: var(--ink); color: #fff; }
    .sw-row {
      display: grid; grid-template-columns: 1fr 1fr 1fr auto;
      gap: 12px; align-items: end;
    }
    .sw-field { display: flex; flex-direction: column; gap: 6px; }
    .sw-field label {
      font-size: .68rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 1px;
      color: var(--muted);
    }
    .sw-field select, .sw-field input {
      background: var(--bg); border: 1.5px solid var(--border);
      border-radius: 9px; padding: 12px 14px;
      color: var(--text); font-size: .9rem;
      font-family: 'Outfit', sans-serif; outline: none;
      transition: border-color .18s;
      appearance: none;
    }
    .sw-field select:focus, .sw-field input:focus { border-color: var(--gold); }
    .sw-btn {
      background: var(--gold); border: none;
      color: #fff; padding: 13px 26px;
      border-radius: 9px; font-size: .9rem;
      font-weight: 700; cursor: pointer;
      font-family: 'Outfit', sans-serif;
      transition: all .2s; white-space: nowrap;
      display: flex; align-items: center; gap: 7px;
    }
    .sw-btn:hover { background: var(--gold-lt); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(184,145,42,.35); }

    /* hero bottom strip */
    .hero-strip {
      width: 100%; background: var(--ink);
      margin-top: 80px;
      padding: 20px 32px;
    }
    .strip-inner {
      max-width: 1260px; margin: 0 auto;
      display: flex; align-items: center;
      justify-content: space-between; gap: 24px;
      flex-wrap: wrap;
    }
    .strip-item {
      display: flex; align-items: center; gap: 10px;
      color: rgba(255,255,255,.55); font-size: .8rem;
    }
    .strip-item svg { width: 16px; height: 16px; stroke: var(--gold-lt); fill: none; stroke-width: 2; stroke-linecap: round; }
    .strip-item span { color: rgba(255,255,255,.85); font-weight: 500; }
    .strip-divider { width: 1px; height: 20px; background: rgba(255,255,255,.1); }

    /* ─────────────────────────────
       SECTION SHARED
    ───────────────────────────── */
    section { padding: 96px 32px; }
    .s-wrap { max-width: 1260px; margin: 0 auto; }

    .s-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: .7rem; font-weight: 700; letter-spacing: 2.5px;
      text-transform: uppercase; color: var(--gold); margin-bottom: 14px;
    }
    .s-eyebrow::before { content: ''; width: 20px; height: 1.5px; background: var(--gold); }

    h2.s-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 3.5vw, 2.9rem);
      font-weight: 800; color: var(--ink);
      line-height: 1.1; letter-spacing: -.3px;
    }
    h2.s-title em { font-style: italic; color: var(--gold); }
    .s-sub {
      color: var(--muted); font-size: .95rem;
      line-height: 1.75; margin-top: 14px; max-width: 500px;
    }
    .s-header-row {
      display: flex; justify-content: space-between;
      align-items: flex-end; margin-bottom: 52px; gap: 24px;
    }
    .see-all {
      text-decoration: none; color: var(--ink);
      font-size: .82rem; font-weight: 700;
      display: flex; align-items: center; gap: 6px;
      white-space: nowrap; border-bottom: 1.5px solid var(--ink);
      padding-bottom: 2px; transition: color .18s, border-color .18s;
    }
    .see-all:hover { color: var(--gold); border-color: var(--gold); }

    /* reveal */
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity .65s ease, transform .65s ease; }
    .reveal.in { opacity: 1; transform: none; }

    /* ─────────────────────────────
       POPULAR ROUTES
    ───────────────────────────── */
    #routes { background: var(--bg); }

    .routes-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .route-card {
      background: var(--bg-3);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 28px 24px;
      cursor: pointer; position: relative;
      transition: transform .25s, box-shadow .25s, border-color .25s;
      overflow: hidden;
    }
    .route-card::after {
      content: '';
      position: absolute; bottom: 0; left: 0; right: 0;
      height: 3px; background: var(--gold);
      transform: scaleX(0); transform-origin: left;
      transition: transform .28s;
    }
    .route-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--border-dk); }
    .route-card:hover::after { transform: scaleX(1); }

    .rc-tag {
      display: inline-block; padding: 3px 10px;
      border-radius: 4px; font-size: .68rem; font-weight: 700;
      letter-spacing: .5px; text-transform: uppercase;
      margin-bottom: 18px;
    }
    .tag-hot { background: rgba(192,57,43,.08); color: var(--red); }
    .tag-new { background: rgba(52,211,153,.1); color: #059669; }
    .tag-sale { background: var(--gold-bg); color: var(--gold); }

    .rc-cities {
      display: flex; align-items: center;
      gap: 10px; margin-bottom: 20px;
    }
    .rc-city { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--ink); }
    .rc-line {
      flex: 1; display: flex; align-items: center; gap: 4px;
    }
    .rc-line::before, .rc-line::after { content: ''; flex: 1; height: 1px; background: var(--border-dk); }
    .rc-line span { font-size: .7rem; color: var(--gold); }

    .rc-info {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 12px; padding-top: 18px;
      border-top: 1px solid var(--border);
    }
    .rc-info-item small { display: block; font-size: .67rem; color: var(--muted-lt); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
    .rc-info-item span { font-size: .88rem; font-weight: 600; color: var(--ink); }
    .rc-price { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 800; color: var(--gold); }
    .rc-price sub { font-family: 'Outfit', sans-serif; font-size: .7rem; color: var(--muted); font-weight: 400; }

    /* ─────────────────────────────
       WHY CHOOSE US
    ───────────────────────────── */
    #why { background: var(--ink); color: #fff; }
    #why .s-eyebrow { color: var(--gold-lt); }
    #why .s-eyebrow::before { background: var(--gold-lt); }
    #why h2.s-title { color: #fff; }
    #why .s-sub { color: rgba(255,255,255,.5); }

    .why-layout {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 80px; align-items: center;
    }
    .why-features { display: flex; flex-direction: column; gap: 28px; }

    .wf-item {
      display: flex; gap: 20px; align-items: flex-start;
      padding: 24px; border-radius: var(--radius);
      border: 1px solid rgba(255,255,255,.06);
      transition: border-color .22s, background .22s;
    }
    .wf-item:hover { border-color: rgba(184,145,42,.3); background: rgba(184,145,42,.04); }

    .wf-icon {
      width: 48px; height: 48px; border-radius: 12px;
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.08);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; flex-shrink: 0;
    }
    .wf-body h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 6px;
    }
    .wf-body p { font-size: .875rem; color: rgba(255,255,255,.45); line-height: 1.65; }

    /* right side: big numbers */
    .why-numbers {
      display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    .wn-card {
      padding: 32px 28px; border-radius: var(--radius);
      border: 1px solid rgba(255,255,255,.07);
      background: rgba(255,255,255,.03);
      transition: border-color .22s;
    }
    .wn-card:hover { border-color: rgba(184,145,42,.25); }
    .wn-card.accent { background: var(--gold); border-color: var(--gold); }
    .wn-card.accent .wn-num { color: var(--ink); }
    .wn-card.accent .wn-label { color: rgba(14,17,23,.65); }
    .wn-num {
      font-family: 'Playfair Display', serif;
      font-size: 2.8rem; font-weight: 800; color: #fff;
      line-height: 1;
    }
    .wn-num sup { font-size: 1.2rem; color: var(--gold-lt); }
    .wn-card.accent .wn-num sup { color: var(--ink-soft); }
    .wn-label { font-size: .8rem; color: rgba(255,255,255,.4); margin-top: 8px; line-height: 1.4; }

    /* ─────────────────────────────
       HOW IT WORKS
    ───────────────────────────── */
    #how { background: var(--bg-2); }

    .steps-grid {
      display: grid; grid-template-columns: repeat(4,1fr);
      gap: 24px; position: relative; margin-top: 56px;
    }
    .steps-grid::before {
      content: ''; position: absolute;
      top: 36px; left: 10%; right: 10%;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--border-dk) 20%, var(--border-dk) 80%, transparent);
      z-index: 0;
    }

    .step-card {
      position: relative; z-index: 1;
      text-align: center; padding: 0 12px;
    }
    .step-circle {
      width: 72px; height: 72px; border-radius: 50%;
      background: var(--bg-3); border: 1.5px solid var(--border-dk);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 22px; font-size: 1.5rem;
      box-shadow: var(--shadow-sm);
      position: relative; transition: border-color .2s, box-shadow .2s;
    }
    .step-card:hover .step-circle { border-color: var(--gold); box-shadow: 0 0 0 4px rgba(184,145,42,.1); }
    .step-num {
      position: absolute; top: -6px; right: -6px;
      width: 22px; height: 22px; border-radius: 50%;
      background: var(--ink); color: #fff;
      font-size: .65rem; font-weight: 800;
      display: flex; align-items: center; justify-content: center;
    }
    .step-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1rem; font-weight: 700;
      color: var(--ink); margin-bottom: 10px;
    }
    .step-card p { font-size: .84rem; color: var(--muted); line-height: 1.65; }

    /* ─────────────────────────────
       PROMOS
    ───────────────────────────── */
    #promos { background: var(--bg); }

    .promos-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 20px;
    }

    .promo-card {
      border-radius: var(--radius); overflow: hidden;
      position: relative; cursor: pointer;
      transition: transform .25s, box-shadow .25s;
      min-height: 240px;
      display: flex; flex-direction: column; justify-content: flex-end;
    }
    .promo-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

    .pc-bg {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 6rem; opacity: .08;
    }
    .pc-1 { background: linear-gradient(135deg, var(--ink) 0%, #2e3a52 100%); }
    .pc-2 { background: linear-gradient(135deg, #c0392b 0%, #922b21 100%); }
    .pc-3 { background: linear-gradient(135deg, #b8912a 0%, #7d6019 100%); }

    .pc-body {
      position: relative; z-index: 1;
      padding: 28px 24px; color: #fff;
    }
    .pc-tag {
      display: inline-block; background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.2);
      padding: 3px 10px; border-radius: 4px;
      font-size: .68rem; font-weight: 700;
      letter-spacing: 1px; text-transform: uppercase;
      margin-bottom: 12px;
    }
    .pc-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 800; line-height: 1.2; margin-bottom: 8px; }
    .pc-desc { font-size: .8rem; color: rgba(255,255,255,.65); line-height: 1.5; margin-bottom: 14px; }
    .pc-code {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.12); border: 1px dashed rgba(255,255,255,.3);
      padding: 6px 14px; border-radius: 6px;
      font-size: .78rem; font-weight: 700; letter-spacing: 1.5px;
    }

    /* ─────────────────────────────
       TESTIMONIALS
    ───────────────────────────── */
    #testimonials { background: var(--bg-2); }

    .testi-grid {
      display: grid; grid-template-columns: repeat(3,1fr);
      gap: 20px; margin-top: 52px;
    }

    .testi-card {
      background: var(--bg-3); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 28px;
      transition: transform .22s, box-shadow .22s;
    }
    .testi-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .testi-card.featured {
      background: var(--ink); border-color: var(--ink);
      color: #fff;
    }

    .tc-stars { color: var(--gold); font-size: .9rem; letter-spacing: 2px; margin-bottom: 14px; }
    .tc-text { font-size: .9rem; line-height: 1.75; color: var(--muted); margin-bottom: 22px; }
    .testi-card.featured .tc-text { color: rgba(255,255,255,.55); }
    .tc-author { display: flex; align-items: center; gap: 13px; }
    .tc-av {
      width: 40px; height: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: .85rem; font-weight: 800; flex-shrink: 0;
    }
    .av-a { background: #fde68a; color: #92400e; }
    .av-b { background: #d1fae5; color: #065f46; }
    .av-c { background: var(--ink); color: var(--gold-lt); }
    .av-d { background: #fee2e2; color: #991b1b; }
    .av-e { background: #e0e7ff; color: #3730a3; }
    .av-f { background: var(--gold-bg); color: var(--gold); border: 1px solid var(--gold-line); }
    .tc-name { font-weight: 700; font-size: .88rem; color: var(--ink); }
    .testi-card.featured .tc-name { color: #fff; }
    .tc-role { font-size: .72rem; color: var(--muted-lt); margin-top: 2px; }
    .testi-card.featured .tc-role { color: rgba(255,255,255,.35); }

    /* ─────────────────────────────
       FAQ
    ───────────────────────────── */
    #faq { background: var(--bg); }
    .faq-layout {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 64px; align-items: start;
    }

    .faq-cats { display: flex; gap: 8px; margin-bottom: 28px; flex-wrap: wrap; }
    .faq-cat {
      padding: 7px 16px; border-radius: 6px;
      font-size: .78rem; font-weight: 600; cursor: pointer;
      border: 1.5px solid var(--border); background: none;
      color: var(--muted); font-family: 'Outfit', sans-serif;
      transition: all .18s;
    }
    .faq-cat.active { background: var(--ink); color: #fff; border-color: var(--ink); }

    .faq-list { display: flex; flex-direction: column; gap: 4px; }
    .faq-item {
      border: 1px solid var(--border); border-radius: 10px;
      overflow: hidden; transition: border-color .2s;
    }
    .faq-item.open { border-color: var(--border-dk); }
    .faq-q {
      width: 100%; display: flex; align-items: center;
      justify-content: space-between; gap: 16px;
      padding: 18px 20px; background: none; border: none;
      font-family: 'Outfit', sans-serif; font-size: .9rem;
      font-weight: 600; color: var(--ink); cursor: pointer;
      text-align: left; transition: background .15s;
    }
    .faq-q:hover { background: var(--bg-2); }
    .faq-icon {
      width: 22px; height: 22px; border-radius: 50%;
      background: var(--bg-2); border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      font-size: .7rem; color: var(--muted); flex-shrink: 0;
      transition: background .2s, transform .25s;
    }
    .faq-item.open .faq-icon { background: var(--ink); color: #fff; transform: rotate(45deg); }
    .faq-a {
      max-height: 0; overflow: hidden;
      transition: max-height .3s ease, padding .3s ease;
      font-size: .875rem; color: var(--muted); line-height: 1.7;
      padding: 0 20px;
    }
    .faq-item.open .faq-a { max-height: 200px; padding: 0 20px 18px; }

    .faq-right {
      position: sticky; top: 96px;
      background: var(--ink); border-radius: 18px;
      padding: 40px 36px; color: #fff;
    }
    .faq-right h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem; font-weight: 800; margin-bottom: 10px;
    }
    .faq-right p { font-size: .875rem; color: rgba(255,255,255,.5); line-height: 1.7; margin-bottom: 28px; }
    .faq-contacts { display: flex; flex-direction: column; gap: 14px; }
    .faq-contact-item {
      display: flex; align-items: center; gap: 14px;
      padding: 16px; border-radius: 10px;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.07);
      text-decoration: none; color: #fff;
      transition: border-color .18s;
    }
    .faq-contact-item:hover { border-color: rgba(184,145,42,.3); }
    .fci-icon {
      width: 40px; height: 40px; border-radius: 9px;
      background: rgba(255,255,255,.07);
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
    }
    .fci-label small { display: block; font-size: .67rem; color: rgba(255,255,255,.35); margin-bottom: 2px; }
    .fci-label span { font-size: .88rem; font-weight: 600; }

    /* ─────────────────────────────
       FOOTER
    ───────────────────────────── */
    footer {
      background: var(--ink); color: rgba(255,255,255,.55);
      padding: 72px 32px 36px;
    }
    .footer-inner { max-width: 1260px; margin: 0 auto; }
    .footer-top {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 48px; margin-bottom: 56px;
    }
    .footer-brand p {
      font-size: .84rem; line-height: 1.75;
      margin-top: 16px; max-width: 280px;
    }
    .footer-newsletter { margin-top: 22px; }
    .footer-newsletter p { font-size: .78rem; margin-bottom: 10px; color: rgba(255,255,255,.4); }
    .nl-row { display: flex; gap: 8px; }
    .nl-input {
      flex: 1; background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 8px; padding: 10px 14px;
      color: #fff; font-family: 'Outfit', sans-serif;
      font-size: .82rem; outline: none;
    }
    .nl-input:focus { border-color: var(--gold-line); }
    .nl-btn {
      background: var(--gold); border: none;
      color: var(--ink); padding: 10px 16px;
      border-radius: 8px; font-weight: 700;
      font-size: .8rem; cursor: pointer;
      font-family: 'Outfit', sans-serif;
    }

    .footer-col h4 {
      font-family: 'Playfair Display', serif;
      font-size: .95rem; font-weight: 700;
      color: #fff; margin-bottom: 18px; letter-spacing: .2px;
    }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul a {
      text-decoration: none; color: rgba(255,255,255,.45);
      font-size: .82rem; transition: color .15s;
    }
    .footer-col ul a:hover { color: rgba(255,255,255,.85); }

    .footer-bottom {
      padding-top: 28px; border-top: 1px solid rgba(255,255,255,.07);
      display: flex; justify-content: space-between;
      align-items: center; gap: 24px; flex-wrap: wrap;
    }
    .footer-bottom p { font-size: .77rem; color: rgba(255,255,255,.3); }
    .footer-socials { display: flex; gap: 10px; }
    .soc-btn {
      width: 36px; height: 36px; border-radius: 8px;
      background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
      display: flex; align-items: center; justify-content: center;
      font-size: .85rem; cursor: pointer; text-decoration: none; color: rgba(255,255,255,.55);
      transition: background .18s, border-color .18s, color .18s;
    }
    .soc-btn:hover { background: var(--gold-bg); border-color: var(--gold-line); color: var(--gold-lt); }
    .footer-payments { display: flex; gap: 8px; align-items: center; }
    .pay-badge {
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
      border-radius: 5px; padding: 4px 10px;
      font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.5);
      letter-spacing: .5px;
    }

    /* ─────────────────────────────
       RESPONSIVE
    ───────────────────────────── */
    @media (max-width: 1024px) {
      .hero-content { grid-template-columns: 1fr; gap: 48px; }
      .hero-visual { display: none; }
      .why-layout { grid-template-columns: 1fr; gap: 48px; }
      .promos-grid { grid-template-columns: 1fr 1fr; }
      .faq-layout { grid-template-columns: 1fr; }
      .faq-right { position: static; }
      .footer-top { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
      .nav-links, .nav-right { display: none; }
      section { padding: 72px 20px; }
      .routes-grid { grid-template-columns: 1fr; }
      .steps-grid { grid-template-columns: 1fr 1fr; }
      .steps-grid::before { display: none; }
      .testi-grid { grid-template-columns: 1fr; }
      .promos-grid { grid-template-columns: 1fr; }
      .sw-row { grid-template-columns: 1fr 1fr; }
      .sw-btn { grid-column: 1/-1; }
      .hero-stats { gap: 24px; flex-wrap: wrap; }
      .why-numbers { grid-template-columns: 1fr 1fr; }
      .footer-top { grid-template-columns: 1fr; }
      .s-header-row { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav id="nav">
  <div class="nav-wrap">
    <a class="logo" href="#">
      <div class="logo-mark">
        <svg viewBox="0 0 24 24"><path d="M3 14V8a2 2 0 012-2h14a2 2 0 012 2v6M3 14h18M3 14l-1 3h20l-1-3M7 14v2m10-2v2M6 10h12"/></svg>
      </div>
      <span class="logo-wordmark">Voyage<span>PH</span></span>
    </a>
    <ul class="nav-links">
      <li><a href="#hero" class="active">Home</a></li>
      <li><a href="book-a-ticket.html">Book a Ticket</a></li>
      <li><a href="routes.html">Routes</a></li>
      <li><a href="promos-deals.html">Promos</a></li>
    </ul>
    <div class="nav-right">
      <button class="btn-login">Log In</button>
      <button class="btn-book">Book a Ticket →</button>
    </div>
  </div>
</nav>


<!-- ═══ HERO ═══ -->
<section id="hero">
  <div class="hero-grid-lines">
    <svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice">
      <defs><pattern id="grid" width="80" height="80" patternUnits="userSpaceOnUse"><path d="M 80 0 L 0 0 0 80" fill="none" stroke="#000" stroke-width="1"/></pattern></defs>
      <rect width="100%" height="100%" fill="url(#grid)"/>
    </svg>
  </div>

  <div class="hero-content">
    <!-- left -->
    <div>
      <div class="hero-eyebrow">Philippines' Premier Bus Line</div>
      <h1 class="hero-heading">
        First-Class<br>Travel Across<br><em>Every Road</em>
      </h1>
      <p class="hero-sub">
        VoyagePH delivers premium, safe, and on-time bus travel across Luzon and beyond. Book your seat online in minutes — no queues, no stress.
      </p>
      <div class="hero-actions">
        <button class="cta-primary">🎫 Book a Ticket</button>
        <button class="cta-secondary">View Routes →</button>
      </div>
      <div class="hero-stats">
        <div>
          <span class="hstat-num">200<sup>+</sup></span>
          <div class="hstat-label">Routes Covered</div>
        </div>
        <div>
          <span class="hstat-num">1.4<sup>M</sup></span>
          <div class="hstat-label">Passengers Served</div>
        </div>
        <div>
          <span class="hstat-num">98<sup>%</sup></span>
          <div class="hstat-label">On-Time Rate</div>
        </div>
      </div>
    </div>

    <!-- right: visual card -->
    <div class="hero-visual">
      <div class="hv-card hv-main">
        <div class="hv-badge">AVAILABLE</div>
        <div class="hv-route-label">Today's Featured Route</div>
        <div class="hv-route">
          <span class="hv-city">Manila</span>
          <span class="hv-arrow">——→</span>
          <span class="hv-city">Baguio</span>
        </div>
        <div class="hv-meta">
          <div class="hv-meta-item"><small>Departure</small><span>6:00 AM</span></div>
          <div class="hv-meta-item"><small>Duration</small><span>5h 30m</span></div>
          <div class="hv-meta-item"><small>Fare from</small><span>₱350</span></div>
          <div class="hv-meta-item"><small>Seats Left</small><span>12</span></div>
        </div>
      </div>
      <div class="hv-card hv-float-2">
        <div class="float-title-dk">Next Departure</div>
        <div class="float-row">
          <div class="dot-green"></div>
          <div class="float-val-lt" style="font-size:1.1rem">6:00 AM</div>
        </div>
        <div class="float-sub-lt">Manila → Baguio</div>
      </div>
      <div class="hv-card hv-float-1">
        <div class="float-title">Available Seats</div>
        <div class="float-val">12 <span style="font-size:.8rem;color:var(--muted);font-family:'Outfit',sans-serif;font-weight:400">/ 45</span></div>
        <div class="float-sub">Book before they run out</div>
      </div>
    </div>
  </div>

  <!-- Search Widget -->
  <div class="search-widget reveal">
    <div class="sw-tabs">
      <button class="sw-tab active" onclick="setTab(this)">One Way</button>
      <button class="sw-tab" onclick="setTab(this)">Round Trip</button>
      <button class="sw-tab" onclick="setTab(this)">Group</button>
    </div>
    <div class="sw-row">
      <div class="sw-field">
        <label>From</label>
        <select>
          <option>Manila (Cubao)</option>
          <option>Manila (Pasay)</option>
          <option>Quezon City</option>
          <option>Caloocan</option>
        </select>
      </div>
      <div class="sw-field">
        <label>To</label>
        <select>
          <option>Baguio City</option>
          <option>Vigan, Ilocos Sur</option>
          <option>Laoag, Ilocos Norte</option>
          <option>Tuguegarao, Cagayan</option>
          <option>Cabanatuan, Nueva Ecija</option>
        </select>
      </div>
      <div class="sw-field">
        <label>Departure Date</label>
        <input type="date"/>
      </div>
      <button class="sw-btn">Search Trips →</button>
    </div>
  </div>

  <!-- trust strip -->
  <div class="hero-strip">
    <div class="strip-inner">
      <div class="strip-item">
        <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        <span>LTO-Accredited Operators</span>
      </div>
      <div class="strip-divider"></div>
      <div class="strip-item">
        <svg viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Real-Time Departure Tracking</span>
      </div>
      <div class="strip-divider"></div>
      <div class="strip-item">
        <svg viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        <span>GCash · Maya · Card · OTC</span>
      </div>
      <div class="strip-divider"></div>
      <div class="strip-item">
        <svg viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        <span>Instant E-Ticket on SMS & Email</span>
      </div>
    </div>
  </div>
</section>


<!-- ═══ POPULAR ROUTES ═══ -->
<section id="routes">
  <div class="s-wrap">
    <div class="s-header-row reveal">
      <div>
        <div class="s-eyebrow">Destinations</div>
        <h2 class="s-title">Popular <em>Routes</em></h2>
        <p class="s-sub">Our most-booked trips — reserve early for the best fares and seat selection.</p>
      </div>
      <a href="#" class="see-all">View all routes →</a>
    </div>
    <div class="routes-grid">
      <div class="route-card reveal">
        <span class="rc-tag tag-hot">🔥 Best Seller</span>
        <div class="rc-cities">
          <span class="rc-city">Manila</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Baguio</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>5h 30m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>Every hour</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Aircon / Deluxe</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱350<sub>/seat</sub></span></div>
        </div>
      </div>
      <div class="route-card reveal">
        <span class="rc-tag tag-new">✦ New Route</span>
        <div class="rc-cities">
          <span class="rc-city">Cubao</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Vigan</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>8h 00m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>3x nightly</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Sleeper</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱580<sub>/seat</sub></span></div>
        </div>
      </div>
      <div class="route-card reveal">
        <span class="rc-tag tag-sale">Sale 20% Off</span>
        <div class="rc-cities">
          <span class="rc-city">Pasay</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Laoag</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>12h 00m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>Overnight</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Premium Sleeper</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱520<sub>/seat</sub></span></div>
        </div>
      </div>
      <div class="route-card reveal">
        <span class="rc-tag tag-hot">🔥 Popular</span>
        <div class="rc-cities">
          <span class="rc-city">QC</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Tuguegarao</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>9h 00m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>2x daily</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Aircon</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱490<sub>/seat</sub></span></div>
        </div>
      </div>
      <div class="route-card reveal">
        <span class="rc-tag tag-new">✦ New Route</span>
        <div class="rc-cities">
          <span class="rc-city">Manila</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Ilocos Sur</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>10h 30m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>Daily 9PM</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Deluxe</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱620<sub>/seat</sub></span></div>
        </div>
      </div>
      <div class="route-card reveal">
        <span class="rc-tag tag-sale">Sale 15% Off</span>
        <div class="rc-cities">
          <span class="rc-city">Manila</span>
          <div class="rc-line"><span>🚌</span></div>
          <span class="rc-city">Cabanatuan</span>
        </div>
        <div class="rc-info">
          <div class="rc-info-item"><small>Duration</small><span>3h 30m</span></div>
          <div class="rc-info-item"><small>Frequency</small><span>Hourly</span></div>
          <div class="rc-info-item"><small>Bus Type</small><span>Aircon</span></div>
          <div class="rc-info-item"><small>From</small><span class="rc-price">₱153<sub>/seat</sub></span></div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ═══ WHY CHOOSE US ═══ -->
<section id="why">
  <div class="s-wrap">
    <div class="why-layout">
      <div>
        <div class="s-eyebrow reveal">Why VoyagePH</div>
        <h2 class="s-title reveal">The smarter way<br>to <em>ride</em></h2>
        <p class="s-sub reveal">We're not just a bus company — we're your travel partner. Every detail is designed around your comfort, safety, and peace of mind.</p>
        <div class="why-features" style="margin-top:40px;">
          <div class="wf-item reveal">
            <div class="wf-icon">🛡️</div>
            <div class="wf-body">
              <h3>Verified & LTO-Accredited</h3>
              <p>All our buses and drivers are fully licensed, regularly inspected, and insured under LTFRB standards. Your safety is never an afterthought.</p>
            </div>
          </div>
          <div class="wf-item reveal">
            <div class="wf-icon">⚡</div>
            <div class="wf-body">
              <h3>Instant QR E-Ticket</h3>
              <p>Get your ticket instantly via SMS and email after payment. No printing, no lines — just scan your QR code at the terminal and board.</p>
            </div>
          </div>
          <div class="wf-item reveal">
            <div class="wf-icon">🔔</div>
            <div class="wf-body">
              <h3>Live Trip Notifications</h3>
              <p>Receive real-time alerts for departure reminders, gate changes, and delays — before you even leave home.</p>
            </div>
          </div>
          <div class="wf-item reveal">
            <div class="wf-icon">💳</div>
            <div class="wf-body">
              <h3>Flexible Payment Options</h3>
              <p>Pay via GCash, Maya, credit/debit card, or over-the-counter — whatever works for you, with zero hidden fees.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="why-numbers reveal">
        <div class="wn-card accent">
          <div class="wn-num">98<sup>%</sup></div>
          <div class="wn-label">On-Time Departure Rate</div>
        </div>
        <div class="wn-card">
          <div class="wn-num">200<sup>+</sup></div>
          <div class="wn-label">Routes across Luzon & Visayas</div>
        </div>
        <div class="wn-card">
          <div class="wn-num">1.4<sup>M</sup></div>
          <div class="wn-label">Happy passengers and counting</div>
        </div>
        <div class="wn-card">
          <div class="wn-num">4.9<sup>★</sup></div>
          <div class="wn-label">Average passenger rating</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ═══ HOW IT WORKS ═══ -->
<section id="how">
  <div class="s-wrap">
    <div style="text-align:center; max-width:520px; margin:0 auto;" class="reveal">
      <div class="s-eyebrow">Simple Process</div>
      <h2 class="s-title">Booked in <em>4 steps</em></h2>
      <p class="s-sub" style="margin:0 auto;">From search to seat confirmation — the entire process takes less than 2 minutes.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card reveal">
        <div class="step-circle">
          🔍
          <div class="step-num">1</div>
        </div>
        <h3>Search Your Trip</h3>
        <p>Enter your origin, destination, and travel date. See all available buses, times, and seat types instantly.</p>
      </div>
      <div class="step-card reveal">
        <div class="step-circle">
          💺
          <div class="step-num">2</div>
        </div>
        <h3>Pick Your Seat</h3>
        <p>Choose from regular, recliner, or sleeper seats. View the seat map and pick exactly where you want to sit.</p>
      </div>
      <div class="step-card reveal">
        <div class="step-circle">
          💳
          <div class="step-num">3</div>
        </div>
        <h3>Pay Securely</h3>
        <p>Complete payment via GCash, Maya, card, or OTC. All transactions are 256-bit SSL encrypted.</p>
      </div>
      <div class="step-card reveal">
        <div class="step-circle">
          🎫
          <div class="step-num">4</div>
        </div>
        <h3>Board & Ride</h3>
        <p>Show your QR e-ticket at the terminal gate. Sit back, relax, and enjoy the journey.</p>
      </div>
    </div>
  </div>
</section>


<!-- ═══ PROMOS ═══ -->
<section id="promos">
  <div class="s-wrap">
    <div class="s-header-row reveal">
      <div>
        <div class="s-eyebrow">Deals</div>
        <h2 class="s-title">Promos & <em>Offers</em></h2>
        <p class="s-sub">Limited-time fares and exclusive member deals — book fast before seats run out.</p>
      </div>
      <a href="#" class="see-all">All promos →</a>
    </div>
    <div class="promos-grid">
      <div class="promo-card pc-1 reveal">
        <div class="pc-bg">🚌</div>
        <div class="pc-body">
          <div class="pc-tag">Limited Offer</div>
          <div class="pc-title">Early Bird Fare —<br>Save up to 30%</div>
          <div class="pc-desc">Book 7 days in advance on any route and get up to 30% off your fare. Valid for all seat types.</div>
          <div class="pc-code">🏷️ EARLYBIRD30</div>
        </div>
      </div>
      <div class="promo-card pc-2 reveal">
        <div class="pc-bg">❤️</div>
        <div class="pc-body">
          <div class="pc-tag">Student Promo</div>
          <div class="pc-title">Student Discount — 20% Off</div>
          <div class="pc-desc">Show your valid school ID at booking. Available on all weekday trips.</div>
          <div class="pc-code">🎓 STUDENT20</div>
        </div>
      </div>
      <div class="promo-card pc-3 reveal">
        <div class="pc-bg">✨</div>
        <div class="pc-body">
          <div class="pc-tag">Members Only</div>
          <div class="pc-title">VIP Member Perks</div>
          <div class="pc-desc">Register now and unlock exclusive fares, priority boarding, and free seat upgrades.</div>
          <div class="pc-code">👑 JOIN FREE</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ═══ TESTIMONIALS ═══ -->
<section id="testimonials">
  <div class="s-wrap">
    <div style="text-align:center;max-width:500px;margin:0 auto 52px;" class="reveal">
      <div class="s-eyebrow">Passenger Reviews</div>
      <h2 class="s-title">Loved by <em>travelers</em></h2>
      <p class="s-sub" style="margin:14px auto 0;">4.9 ★ from over 14,000 verified reviews on Google and the app.</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card featured reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"Pinaka-convenient na bus booking experience ko. Nag-book ako ng Baguio trip in less than 2 minutes, dumating yung e-ticket sa Viber ko agad. Never na ko mag-pila sa terminal!"</p>
        <div class="tc-author">
          <div class="tc-av av-c">M</div>
          <div><div class="tc-name" style="color:#fff">Maria Santos</div><div class="tc-role">Quezon City · Verified Passenger</div></div>
        </div>
      </div>
      <div class="testi-card reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"The seat selection feature is a game changer. I always get window seats now without rushing to the terminal hours early. The bus was clean and comfortable too!"</p>
        <div class="tc-author">
          <div class="tc-av av-b">J</div>
          <div><div class="tc-name">Juan Dela Cruz</div><div class="tc-role">Marikina · Monthly Traveler</div></div>
        </div>
      </div>
      <div class="testi-card reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"Used VoyagePH for our family Vigan trip. Naka-group book kami ng 5 seats together, smooth yung whole process. The overnight bus was super comfortable."</p>
        <div class="tc-author">
          <div class="tc-av av-d">R</div>
          <div><div class="tc-name">Rico Fernandez</div><div class="tc-role">Mandaluyong · Family Traveler</div></div>
        </div>
      </div>
      <div class="testi-card reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"As a solo female traveler, knowing the operators are verified and the buses are regularly inspected makes me feel so much safer. Highly recommend to everyone."</p>
        <div class="tc-author">
          <div class="tc-av av-e">L</div>
          <div><div class="tc-name">Liza Cruz</div><div class="tc-role">Taguig · Solo Traveler</div></div>
        </div>
      </div>
      <div class="testi-card reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"GCash payment is so easy. The live notifications told me exactly when to leave my house. On-time departure, clean bus, friendly driver. 10/10 experience!"</p>
        <div class="tc-author">
          <div class="tc-av av-a">A</div>
          <div><div class="tc-name">Ana Reyes</div><div class="tc-role">Pasig · Student Traveler</div></div>
        </div>
      </div>
      <div class="testi-card reveal">
        <div class="tc-stars">★★★★★</div>
        <p class="tc-text">"Bilang OFW, ang gaan ng mag-book ng ticket para sa aking pamilya kahit nasa ibang bansa ako. Salamat VoyagePH — napaka-reliable ng serbisyo ninyo!"</p>
        <div class="tc-author">
          <div class="tc-av av-f">B</div>
          <div><div class="tc-name">Ben Valdez</div><div class="tc-role">OFW · Province Traveler</div></div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ═══ FAQ ═══ -->
<section id="faq">
  <div class="s-wrap">
    <div class="faq-layout">
      <div>
        <div class="s-eyebrow reveal">FAQ</div>
        <h2 class="s-title reveal">Common <em>Questions</em></h2>
        <p class="s-sub reveal">Everything you need to know about booking, safety, and our buses.</p>

        <div class="faq-cats reveal" style="margin-top:28px;">
          <button class="faq-cat active" onclick="filterFaq(this,'all')">All</button>
          <button class="faq-cat" onclick="filterFaq(this,'booking')">Booking</button>
          <button class="faq-cat" onclick="filterFaq(this,'safety')">Trust & Safety</button>
          <button class="faq-cat" onclick="filterFaq(this,'maintenance')">Maintenance</button>
        </div>

        <div class="faq-list reveal" id="faqList">

          <div class="faq-item" data-cat="booking">
            <button class="faq-q" onclick="toggleFaq(this)">
              How do I book a ticket on VoyagePH?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">Search your route, choose your departure time and seat type, then pay via GCash, Maya, card, or OTC. Your QR e-ticket is sent to your SMS and email instantly after payment.</div>
          </div>

          <div class="faq-item" data-cat="booking">
            <button class="faq-q" onclick="toggleFaq(this)">
              Can I cancel or reschedule my booking?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">Yes. You can cancel or reschedule your trip up to 24 hours before departure from your "My Booking" page. Cancellations made within 24 hours of departure are subject to a rebooking fee.</div>
          </div>

          <div class="faq-item" data-cat="booking">
            <button class="faq-q" onclick="toggleFaq(this)">
              What payment methods are accepted?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">We accept GCash, Maya (PayMaya), Visa/Mastercard credit and debit cards, and over-the-counter payment via partner payment centers like 7-Eleven, Bayad Center, and SM Bills Payment.</div>
          </div>

          <div class="faq-item" data-cat="safety">
            <button class="faq-q" onclick="toggleFaq(this)">
              Are your buses and drivers verified and accredited?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">Absolutely. All VoyagePH buses are registered with the LTFRB (Land Transportation Franchising and Regulatory Board) and drivers hold valid Professional Driver's Licenses. We conduct background checks on all drivers before onboarding.</div>
          </div>

          <div class="faq-item" data-cat="safety">
            <button class="faq-q" onclick="toggleFaq(this)">
              Is my personal and payment information secure?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">Yes. Our platform uses 256-bit SSL encryption for all transactions. We are PCI-DSS compliant and we never store your card details on our servers. Your personal data is protected under the Data Privacy Act of 2012.</div>
          </div>

          <div class="faq-item" data-cat="maintenance">
            <button class="faq-q" onclick="toggleFaq(this)">
              How often are your buses inspected and maintained?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">All VoyagePH buses undergo a mandatory pre-trip inspection before every departure. Full preventive maintenance is conducted every 5,000 km, and major overhauls are performed annually in compliance with LTO and LTFRB standards.</div>
          </div>

          <div class="faq-item" data-cat="maintenance">
            <button class="faq-q" onclick="toggleFaq(this)">
              What safety features do your buses have?
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-a">Our fleet is equipped with seatbelts for all passengers, GPS tracking, CCTV cameras, fire extinguishers, and first aid kits. Speed limiters are installed in all buses and monitored remotely from our operations center.</div>
          </div>

        </div>
      </div>

      <!-- Right contact card -->
      <div class="faq-right reveal">
        <h3>Still have questions?</h3>
        <p>Our support team is available 24/7 to help you with bookings, refunds, and anything else you need.</p>
        <div class="faq-contacts">
          <a href="#" class="faq-contact-item">
            <div class="fci-icon">📞</div>
            <div class="fci-label"><small>Hotline (24/7)</small><span>(02) 8888-VOYAGE</span></div>
          </a>
          <a href="#" class="faq-contact-item">
            <div class="fci-icon">💬</div>
            <div class="fci-label"><small>Live Chat</small><span>Chat with us now</span></div>
          </a>
          <a href="#" class="faq-contact-item">
            <div class="fci-icon">📧</div>
            <div class="fci-label"><small>Email Support</small><span>support@voyageph.com</span></div>
          </a>
          <a href="#" class="faq-contact-item">
            <div class="fci-icon">📘</div>
            <div class="fci-label"><small>Facebook Page</small><span>@VoyagePHOfficial</span></div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        <a class="logo" href="#" style="filter:brightness(1.2)">
          <div class="logo-mark" style="border-color:rgba(212,168,67,.3)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#d4a843" stroke-width="1.8" stroke-linecap="round"><path d="M3 14V8a2 2 0 012-2h14a2 2 0 012 2v6M3 14h18M3 14l-1 3h20l-1-3M7 14v2m10-2v2M6 10h12"/></svg>
          </div>
          <span style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:-.3px;">Voyage<span style="color:#d4a843">PH</span></span>
        </a>
        <p>Philippines' premier bus company — connecting cities with comfort, safety, and reliability since 2018.</p>
        <div class="footer-newsletter">
          <p>Get promo alerts and travel updates:</p>
          <div class="nl-row">
            <input class="nl-input" type="email" placeholder="your@email.com"/>
            <button class="nl-btn">Subscribe</button>
          </div>
        </div>
      </div>
      <div class="footer-col">
        <h4>Travel</h4>
        <ul>
          <li><a href="#">Book a Ticket</a></li>
          <li><a href="#">View Routes</a></li>
          <li><a href="#">Promos & Deals</a></li>
          <li><a href="#">Group Booking</a></li>
          <li><a href="#">Schedule</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Account</h4>
        <ul>
          <li><a href="#">Register</a></li>
          <li><a href="#">Log In</a></li>
          <li><a href="#">My Bookings</a></li>
          <li><a href="#">My Profile</a></li>
          <li><a href="#">Notifications</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="#">About VoyagePH</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Help / FAQ</a></li>
          <li><a href="#">Cancellation Policy</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2026 VoyagePH. All rights reserved. Made with ❤️ in the Philippines.</p>
      <div class="footer-payments">
        <span style="font-size:.68rem;color:rgba(255,255,255,.25);margin-right:6px;">We accept</span>
        <span class="pay-badge">GCash</span>
        <span class="pay-badge">Maya</span>
        <span class="pay-badge">Visa</span>
        <span class="pay-badge">MC</span>
        <span class="pay-badge">OTC</span>
      </div>
      <div class="footer-socials">
        <a class="soc-btn" href="#">f</a>
        <a class="soc-btn" href="#">𝕏</a>
        <a class="soc-btn" href="#">in</a>
        <a class="soc-btn" href="#">📷</a>
      </div>
    </div>
  </div>
</footer>


<script>
  /* nav scroll */
  window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('scrolled', scrollY > 10);
  }, {passive:true});

  /* active nav link */
  const secs = document.querySelectorAll('section[id]');
  const navAs = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let cur = '';
    secs.forEach(s => { if (scrollY >= s.offsetTop - 100) cur = s.id; });
    navAs.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
  }, {passive:true});

  /* search tabs */
  function setTab(el) {
    document.querySelectorAll('.sw-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
  }

  /* faq toggle */
  function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  }

  /* faq filter */
  function filterFaq(btn, cat) {
    document.querySelectorAll('.faq-cat').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.faq-item').forEach(item => {
      item.style.display = (cat === 'all' || item.dataset.cat === cat) ? 'block' : 'none';
      item.classList.remove('open');
    });
  }

  /* scroll reveal */
  const ro = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); ro.unobserve(e.target); } });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
</script>
</body>
</html>
