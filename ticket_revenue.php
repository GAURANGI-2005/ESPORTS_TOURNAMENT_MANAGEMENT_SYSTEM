<?php
$conn = mysqli_connect("127.0.0.1","root","","esports_db",3307);
$sql = "SELECT SUM(price) AS total_revenue FROM ticket";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$total_revenue = $row['total_revenue'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Total Ticket Revenue — Esports Tournament Management System</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Barlow:wght@300;400;500;600&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg:      #04040a;
    --surface: #0c0c18;
    --surface2:#111120;
    --border:  rgba(92,207,255,0.12);
    --border2: rgba(92,207,255,0.06);
    --cyan:    #5ccfff;
    --gold:    #f0c040;
    --red:     #ff4466;
    --green:   #30e8a0;
    --text:    #dde4f0;
    --muted:   #5a6080;
    --glow-c:  0 0 24px rgba(92,207,255,0.35);
    --glow-g:  0 0 24px rgba(240,192,64,0.35);
    --glow-gr: 0 0 24px rgba(48,232,160,0.35);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Barlow', sans-serif;
    background: var(--bg); color: var(--text);
    min-height: 100vh; overflow-x: hidden;
  }

  body::after {
    content: '';
    position: fixed; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none; z-index: 9998; opacity: 0.6;
  }

  .atmo {
    position: fixed; pointer-events: none; z-index: 0; border-radius: 50%;
  }
  .atmo-1 {
    width: 900px; height: 900px;
    background: radial-gradient(circle, rgba(48,232,160,0.06) 0%, transparent 65%);
    top: -250px; left: -200px;
    animation: af 14s ease-in-out infinite;
  }
  .atmo-2 {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(240,192,64,0.04) 0%, transparent 65%);
    bottom: -200px; right: -150px;
    animation: af 18s ease-in-out infinite reverse;
  }
  @keyframes af { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,50px)} }

  .grid-bg {
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(92,207,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(92,207,255,0.025) 1px, transparent 1px);
    background-size: 80px 80px;
    pointer-events: none; z-index: 0;
  }

  /* ── HEADER ── */
  header {
    position: relative; z-index: 100;
    height: 68px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 48px;
    background: rgba(4,4,10,0.92);
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(24px);
  }

  .brand { display: flex; align-items: center; gap: 16px; text-decoration: none; }

  .hex-logo {
    position: relative; width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .hex-logo svg {
    position: absolute; inset: 0; width: 100%; height: 100%;
    animation: hexSpin 20s linear infinite;
  }
  @keyframes hexSpin { to { transform: rotate(360deg); } }
  .hex-logo-inner {
    position: relative; z-index: 1;
    font-family: 'Orbitron', monospace;
    font-size: 13px; font-weight: 900;
    color: var(--cyan); text-shadow: var(--glow-c);
  }
  .brand-name {
    font-family: 'Orbitron', monospace;
    font-size: 15px; font-weight: 900; letter-spacing: 3px; color: var(--text);
  }
  .brand-name em {
    font-style: normal;
    background: linear-gradient(90deg, var(--cyan), var(--gold));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .brand-sub {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 3px; color: var(--muted);
    text-transform: uppercase; margin-top: 1px;
  }
  .h-clock {
    font-family: 'Orbitron', monospace;
    font-size: 13px; color: var(--muted); letter-spacing: 2px;
    border: 1px solid var(--border); padding: 6px 14px;
    background: var(--surface);
  }

  /* ── NAV ── */
  nav {
    position: relative; z-index: 100;
    display: flex; justify-content: center;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 48px;
  }
  nav a {
    position: relative;
    padding: 0 32px; height: 52px;
    display: flex; align-items: center; gap: 9px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 13px; font-weight: 600;
    letter-spacing: 2.5px; text-transform: uppercase;
    color: var(--muted); text-decoration: none;
    transition: color 0.25s;
  }
  nav a.active { color: var(--text); }
  nav a::after {
    content: '';
    position: absolute; bottom: 0; left: 32px; right: 32px; height: 2px;
    background: linear-gradient(90deg, var(--cyan), var(--gold));
    transform: scaleX(0); transform-origin: left;
    transition: transform 0.3s ease; box-shadow: var(--glow-c);
  }
  nav a:hover { color: var(--text); }
  nav a:hover::after, nav a.active::after { transform: scaleX(1); }

  /* ── BREADCRUMB ── */
  .breadcrumb {
    position: relative; z-index: 1;
    padding: 16px 48px;
    border-bottom: 1px solid var(--border2);
    display: flex; align-items: center; gap: 10px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: var(--muted);
  }
  .breadcrumb a { color: var(--muted); text-decoration: none; transition: color 0.2s; }
  .breadcrumb a:hover { color: var(--green); }
  .breadcrumb-cur { color: var(--green); }
  .breadcrumb-sep { color: var(--border); font-size: 14px; }

  /* ── MAIN ── */
  main {
    position: relative; z-index: 1;
    padding: 80px 48px 100px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    min-height: calc(100vh - 68px - 52px - 49px - 65px);
  }

  .page-eyebrow {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px;
    color: var(--green); text-transform: uppercase;
    margin-bottom: 48px;
    display: flex; align-items: center; gap: 10px;
  }
  .page-eyebrow::before {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--green); box-shadow: var(--glow-gr);
  }
  .page-eyebrow::after {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--green); box-shadow: var(--glow-gr);
  }

  /* ── REVENUE CARD ── */
  .revenue-card {
    position: relative;
    background: var(--surface);
    border: 1px solid var(--border);
    padding: 60px 80px 56px;
    text-align: center;
    overflow: hidden;
    max-width: 560px; width: 100%;
    animation: cardIn 0.6s cubic-bezier(0.34,1.56,0.64,1) both;
  }

  @keyframes cardIn {
    from { opacity: 0; transform: translateY(40px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* top accent */
  .revenue-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--green), var(--cyan), var(--gold));
    box-shadow: 0 0 20px rgba(48,232,160,0.4);
  }

  /* corner cut top-right */
  .revenue-card::after {
    content: '';
    position: absolute; top: 0; right: 0;
    width: 44px; height: 44px;
    background: var(--bg);
    clip-path: polygon(100% 0%, 0% 0%, 100% 100%);
    border-bottom: 1px solid var(--border);
    border-left: 1px solid var(--border);
  }

  /* corner cut bottom-left */
  .corner-bl {
    position: absolute; bottom: 0; left: 0;
    width: 44px; height: 44px;
    background: var(--bg);
    clip-path: polygon(0% 0%, 0% 100%, 100% 100%);
    border-top: 1px solid var(--border);
    border-right: 1px solid var(--border);
  }

  /* decorative ring behind number */
  .ring {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 240px; height: 240px;
    border: 1px solid rgba(48,232,160,0.06);
    border-radius: 50%;
    pointer-events: none;
  }
  .ring-2 {
    width: 320px; height: 320px;
    border-color: rgba(48,232,160,0.03);
  }

  .card-label {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px; text-transform: uppercase;
    color: var(--muted); margin-bottom: 24px;
    position: relative; z-index: 1;
  }

  .revenue-icon {
    font-size: 36px; margin-bottom: 20px;
    display: block; position: relative; z-index: 1;
    animation: iconFloat 3s ease-in-out infinite;
  }
  @keyframes iconFloat {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-6px); }
  }

  .revenue-amount {
    position: relative; z-index: 1;
    font-family: 'Orbitron', monospace;
    font-size: clamp(40px, 8vw, 72px);
    font-weight: 900; line-height: 1;
    color: var(--green);
    text-shadow: 0 0 30px rgba(48,232,160,0.4), 0 0 60px rgba(48,232,160,0.15);
    animation: amountGlow 3s ease-in-out infinite;
    letter-spacing: -2px;
  }
  @keyframes amountGlow {
    0%,100% { text-shadow: 0 0 20px rgba(48,232,160,0.3), 0 0 40px rgba(48,232,160,0.1); }
    50%      { text-shadow: 0 0 40px rgba(48,232,160,0.6), 0 0 80px rgba(48,232,160,0.2); }
  }

  /* null/zero state */
  .revenue-amount.empty {
    color: var(--muted);
    font-size: clamp(24px, 4vw, 40px);
    text-shadow: none;
    animation: none;
  }

  .currency-symbol {
    font-size: 0.45em;
    vertical-align: super;
    color: var(--gold);
    font-weight: 700;
    margin-right: 4px;
  }

  .revenue-divider {
    width: 60px; height: 1px;
    background: linear-gradient(90deg, transparent, var(--green), transparent);
    margin: 28px auto;
    position: relative; z-index: 1;
  }

  .query-badge {
    position: relative; z-index: 1;
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--surface2);
    border: 1px solid var(--border);
    padding: 8px 18px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }
  .qb-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green); }
  .qb-text { font-style: italic; color: var(--green); }

  /* ── FOOTER ── */
  footer {
    position: relative; z-index: 1;
    border-top: 1px solid var(--border);
    background: var(--surface);
    padding: 22px 48px;
    display: flex; align-items: center; justify-content: space-between;
  }
  .footer-brand {
    font-family: 'Orbitron', monospace;
    font-size: 11px; font-weight: 700; letter-spacing: 2px; color: var(--muted);
  }
  .footer-brand em {
    font-style: normal;
    background: linear-gradient(90deg, var(--cyan), var(--gold));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .footer-back {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted); text-decoration: none; transition: color 0.2s;
  }
  .footer-back:hover { color: var(--green); }

  @media (max-width: 700px) {
    header, nav, .breadcrumb, main, footer { padding-left: 20px; padding-right: 20px; }
    nav { justify-content: flex-start; overflow-x: auto; }
    nav a { padding: 0 18px; }
    .revenue-card { padding: 48px 32px 44px; }
  }
</style>
</head>
<body>

<div class="grid-bg"></div>
<div class="atmo atmo-1"></div>
<div class="atmo atmo-2"></div>

<!-- HEADER -->
<header>
  <a href="index.html" class="brand">
    <div class="hex-logo">
      <svg viewBox="0 0 42 42" fill="none">
        <polygon points="21,2 38,11 38,31 21,40 4,31 4,11"
          stroke="rgba(92,207,255,0.35)" stroke-width="1" fill="none"/>
        <polygon points="21,6 34,13 34,29 21,36 8,29 8,13"
          stroke="rgba(92,207,255,0.15)" stroke-width="0.5" fill="none"/>
      </svg>
      <div class="hex-logo-inner">ET</div>
    </div>
    <div>
      <div class="brand-name">Esports <em>Tournament</em></div>
      <div class="brand-sub">Management System</div>
    </div>
  </a>
  <div class="h-clock" id="clock">00:00:00</div>
</header>

<!-- NAV -->
<nav>
  <a href="players_menu.html"><span>👤</span>Players</a>
  <a href="teams_menu.html"><span>🛡️</span>Teams</a>
  <a href="view_coaches.php"><span>🎯</span>Coaches</a>
  <a href="analytics_menu.html" class="active"><span>📊</span>Analytics</a>
</nav>

<!-- BREADCRUMB -->
<div class="breadcrumb">
  <a href="index.html">Home</a>
  <span class="breadcrumb-sep">›</span>
  <a href="analytics_menu.html">Analytics</a>
  <span class="breadcrumb-sep">›</span>
  <span class="breadcrumb-cur">Total Ticket Revenue</span>
</div>

<!-- MAIN -->
<main>

  <!-- <div class="page-eyebrow">Analytics — SUM(price)</div> -->

  <div class="revenue-card">
    <div class="corner-bl"></div>
    <div class="ring"></div>
    <div class="ring ring-2"></div>

    <div class="card-label">Total Ticket Revenue</div>

    <span class="revenue-icon">🎟️</span>

    <?php if ($total_revenue !== null && $total_revenue > 0): ?>
      <div class="revenue-amount">
        <span class="currency-symbol">₹</span><?= number_format(floatval($total_revenue), 2) ?>
      </div>
    <?php else: ?>
      <div class="revenue-amount empty">No revenue data</div>
    <?php endif; ?>

    <div class="revenue-divider"></div>

    <div class="query-badge">
      <!-- <div class="qb-dot"></div> -->
      <!-- <span>Query:</span> -->
      <!-- <span class="qb-text">SELECT SUM(price) FROM ticket</span> -->
    </div>
  </div>

</main>

<!-- FOOTER -->
<footer>
  <div class="footer-brand"><em>Esports</em> Tournament Management System</div>
  <a href="analytics_menu.html" class="footer-back">← Back to Analytics</a>
</footer>

<script>
  function tick() {
    const n = new Date();
    document.getElementById('clock').textContent =
      [n.getHours(), n.getMinutes(), n.getSeconds()]
        .map(v => String(v).padStart(2,'0')).join(':');
  }
  tick(); setInterval(tick, 1000);
</script>
</body>
</html>