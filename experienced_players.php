<?php
$conn = mysqli_connect("127.0.0.1","root","","esports_db",3307);
$sql = "SELECT player_name, age
FROM player
WHERE age > (SELECT AVG(age) FROM player)";
$result = mysqli_query($conn,$sql);

/* get average for display */
$avg_result = mysqli_query($conn, "SELECT AVG(age) AS avg_age FROM player");
$avg_row = mysqli_fetch_assoc($avg_result);
$avg_age = $avg_row ? round($avg_row['avg_age'], 1) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Experienced Players — Esports Tournament Management System</title>
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
    --amber:   #ffaa30;
    --text:    #dde4f0;
    --muted:   #5a6080;
    --glow-c:  0 0 24px rgba(92,207,255,0.35);
    --glow-a:  0 0 24px rgba(255,170,48,0.35);
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
    width: 800px; height: 800px;
    background: radial-gradient(circle, rgba(255,170,48,0.05) 0%, transparent 65%);
    top: -200px; right: -150px;
    animation: af 15s ease-in-out infinite;
  }
  .atmo-2 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(92,207,255,0.04) 0%, transparent 65%);
    bottom: -100px; left: -100px;
    animation: af 11s ease-in-out infinite reverse;
  }
  @keyframes af { 0%,100%{transform:translate(0,0)} 50%{transform:translate(35px,45px)} }

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
  .breadcrumb a:hover { color: var(--amber); }
  .breadcrumb-cur { color: var(--amber); }
  .breadcrumb-sep { color: var(--border); font-size: 14px; }

  /* ── MAIN ── */
  main {
    position: relative; z-index: 1;
    padding: 60px 48px 80px;
    max-width: 860px; margin: 0 auto;
  }

  /* page top */
  .page-top {
    margin-bottom: 44px;
    display: flex; align-items: flex-end; justify-content: space-between; gap: 24px;
  }

  .pt-left {}

  .pt-eyebrow {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px;
    color: var(--amber); text-transform: uppercase;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 10px;
  }
  .pt-eyebrow::before {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--amber); box-shadow: var(--glow-a);
  }

  h1 {
    font-family: 'Orbitron', monospace;
    font-size: clamp(22px, 3.5vw, 42px);
    font-weight: 900; line-height: 1; letter-spacing: -1px;
  }
  h1 span {
    background: linear-gradient(90deg, var(--text) 30%, var(--amber));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* avg age callout */
  .avg-callout {
    flex-shrink: 0;
    background: var(--surface);
    border: 1px solid rgba(255,170,48,0.2);
    padding: 18px 28px;
    text-align: center;
    position: relative;
    animation: panelIn 0.5s ease both;
  }
  .avg-callout::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--amber);
    box-shadow: var(--glow-a);
  }
  .avg-num {
    font-family: 'Orbitron', monospace;
    font-size: 32px; font-weight: 900; line-height: 1;
    color: var(--amber);
    text-shadow: 0 0 20px rgba(255,170,48,0.4);
  }
  .avg-label {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted); margin-top: 6px;
  }
  .avg-sub {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase;
    color: rgba(255,170,48,0.4); margin-top: 4px;
  }

  /* ── TABLE PANEL ── */
  .table-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
    animation: panelIn 0.5s ease both 0.1s;
  }
  @keyframes panelIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .table-panel::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--amber), var(--gold));
    box-shadow: 0 0 16px rgba(255,170,48,0.4);
  }
  .table-panel::after {
    content: '';
    position: absolute; bottom: 0; right: 0;
    width: 44px; height: 44px;
    background: var(--bg);
    clip-path: polygon(100% 0%, 100% 100%, 0% 100%);
    border-top: 1px solid var(--border);
    border-left: 1px solid var(--border);
  }

  .table-scroll { overflow-x: auto; }

  /* ── TABLE ── */
  table {
    width: 100%; border-collapse: collapse; min-width: 400px;
  }

  thead tr {
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
  }

  thead th {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--muted); font-weight: 600;
    padding: 18px 24px; text-align: left;
    white-space: nowrap;
  }

  /* rank col */
  thead th:first-child,
  td:first-child { text-align: center; width: 72px; }

  /* age col */
  thead th:last-child,
  td:last-child { text-align: center; width: 120px; }

  tbody tr {
    border-bottom: 1px solid var(--border2);
    transition: background 0.2s;
    animation: rowIn 0.4s ease both;
  }
  <?php for($i=1;$i<=20;$i++): ?>
  tbody tr:nth-child(<?= $i ?>) { animation-delay: <?= $i * 0.03 ?>s; }
  <?php endfor; ?>

  @keyframes rowIn {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
  }

  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: var(--surface2); }
  tbody tr:nth-child(even) { background: rgba(255,255,255,0.01); }
  tbody tr:nth-child(even):hover { background: var(--surface2); }

  td {
    padding: 16px 24px;
    font-size: 14px; font-weight: 500;
    color: var(--text); white-space: nowrap;
  }

  /* row number */
  .td-num {
    font-family: 'Orbitron', monospace;
    font-size: 11px; font-weight: 700;
    color: var(--muted); letter-spacing: 1px;
  }

  /* player name */
  .td-name {
    font-weight: 600; letter-spacing: 0.3px;
    display: flex; align-items: center; gap: 10px;
  }
  .name-star {
    font-size: 12px; opacity: 0.5;
    transition: opacity 0.2s;
  }
  tbody tr:hover .name-star { opacity: 1; }

  /* age display */
  .age-wrap {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  }
  .age-num {
    font-family: 'Orbitron', monospace;
    font-size: 14px; font-weight: 700;
    color: var(--amber);
    text-shadow: 0 0 10px rgba(255,170,48,0.3);
  }
  .age-above {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 9px; letter-spacing: 1px;
    color: var(--green); text-transform: uppercase;
    opacity: 0;
    transition: opacity 0.2s;
  }
  tbody tr:hover .age-above { opacity: 1; }

  /* empty state */
  .empty-row td {
    text-align: center;
    padding: 56px 24px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 13px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }

  /* ── TABLE FOOTER ── */
  .table-foot {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface2);
  }
  .tf-label {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
    display: flex; align-items: center; gap: 8px;
  }
  .tf-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--amber); }
  .tf-query {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }
  .tf-query span { color: var(--amber); font-style: italic; letter-spacing: 1px; }

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
    font-size: 11px; font-weight: 700;
    letter-spacing: 2px; color: var(--muted);
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
  .footer-back:hover { color: var(--amber); }

  @media (max-width: 700px) {
    header, nav, .breadcrumb, main, footer { padding-left: 20px; padding-right: 20px; }
    nav { justify-content: flex-start; overflow-x: auto; }
    nav a { padding: 0 18px; }
    .page-top { flex-direction: column; align-items: flex-start; }
    .avg-callout { width: 100%; }
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
  <a href="players_menu.html" class="active"><span>👤</span>Players</a>
  <a href="teams_menu.html"><span>🛡️</span>Teams</a>
  <a href="view_coaches.php"><span>🎯</span>Coaches</a>
  <a href="analytics_menu.html"><span>📊</span>Analytics</a>
</nav>

<!-- BREADCRUMB -->
<div class="breadcrumb">
  <a href="index.html">Home</a>
  <span class="breadcrumb-sep">›</span>
  <a href="players_menu.html">Players</a>
  <span class="breadcrumb-sep">›</span>
  <span class="breadcrumb-cur">Experienced Players</span>
</div>

<!-- MAIN -->
<main>

  <div class="page-top">
    <div class="pt-left">
      <!-- <div class="pt-eyebrow">Players — WHERE age &gt; (SUBQUERY)</div> -->
      <h1><span>Players Older Than Average</span></h1>
    </div>

    <?php if ($avg_age !== null): ?>
    <div class="avg-callout">
      <div class="avg-num"><?= $avg_age ?></div>
      <div class="avg-label">Average Age</div>
      <div class="avg-sub">SELECT AVG(age)</div>
    </div>
    <?php endif; ?>
  </div>

  <div class="table-panel">
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Player Name</th>
            <th>Age</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $count = 0;
          if (!$result || mysqli_num_rows($result) === 0) {
            echo "<tr class='empty-row'><td colspan='3'>No players above average age</td></tr>";
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              $count++;
              echo "<tr>
                <td class='td-num'>" . str_pad($count, 2, '0', STR_PAD_LEFT) . "</td>
                <td>
                  <span class='td-name'>
                    <span class='name-star'>⭐</span>
                    " . htmlspecialchars($row['player_name']) . "
                  </span>
                </td>
                <td>
                  <div class='age-wrap'>
                    <span class='age-num'>" . htmlspecialchars($row['age']) . "</span>
                    <span class='age-above'>↑ avg</span>
                  </div>
                </td>
              </tr>";
            }
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot">
      <div class="tf-label">
        <div class="tf-dot"></div>
        <?= $count ?> player<?= $count !== 1 ? 's' : '' ?> above average age
      </div>
      <!-- <div class="tf-query">Query: <span>WHERE age &gt; (SELECT AVG(age))</span></div> -->
    </div>
  </div>

</main>

<!-- FOOTER -->
<footer>
  <div class="footer-brand"><em>Esports</em> Tournament Management System</div>
  <a href="players_menu.html" class="footer-back">← Back to Players</a>
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