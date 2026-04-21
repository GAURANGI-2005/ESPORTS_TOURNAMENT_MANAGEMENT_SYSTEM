<?php
$conn = mysqli_connect("127.0.0.1","root","","esports_db",3307);
$sql = "SELECT tournament.tournament_name, game.game_name
FROM tournament
JOIN game ON tournament_id = game.game_id";
$result = mysqli_query($conn,$sql);

$rows = [];
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
}
$count = count($rows);

// count unique games
$unique_games = $count > 0 ? count(array_unique(array_column($rows, 'game_name'))) : 0;

// game → colour map for badges
$game_colors = [
    'valorant'           => 'FF4466',
    'cs2'                => '5CCFFF',
    'counter-strike 2'   => '5CCFFF',
    'league of legends'  => 'F0C040',
    'lol'                => 'F0C040',
    'dota 2'             => 'FF4466',
    'fortnite'           => '30E8A0',
    'pubg'               => 'FFAA30',
    'apex legends'       => 'FF4466',
    'overwatch'          => 'FFAA30',
    'rocket league'      => 'A060FF',
    'fifa'               => '30E8A0',
    'call of duty'       => 'FFAA30',
];

function game_color($name, $map) {
    $key = strtolower(trim($name));
    foreach ($map as $k => $v) {
        if (str_contains($key, $k)) return $v;
    }
    return '5A6080'; // muted fallback
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Games Used in Tournament — Esports Tournament Management System</title>
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
    --purple:  #a060ff;
    --amber:   #ffaa30;
    --text:    #dde4f0;
    --muted:   #5a6080;
    --glow-c:  0 0 24px rgba(92,207,255,0.35);
    --glow-p:  0 0 24px rgba(160,96,255,0.35);
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
    background: radial-gradient(circle, rgba(160,96,255,0.05) 0%, transparent 65%);
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
  .breadcrumb a:hover { color: var(--purple); }
  .breadcrumb-cur { color: var(--purple); }
  .breadcrumb-sep { color: var(--border); font-size: 14px; }

  /* ── MAIN ── */
  main {
    position: relative; z-index: 1;
    padding: 60px 48px 80px;
    max-width: 960px; margin: 0 auto;
  }

  .page-top {
    margin-bottom: 44px;
    display: flex; align-items: flex-end; justify-content: space-between; gap: 24px;
  }

  .pt-eyebrow {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px;
    color: var(--purple); text-transform: uppercase;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 10px;
  }
  .pt-eyebrow::before {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--purple); box-shadow: var(--glow-p);
  }

  h1 {
    font-family: 'Orbitron', monospace;
    font-size: clamp(22px, 3vw, 40px);
    font-weight: 900; line-height: 1; letter-spacing: -1px;
  }
  h1 span {
    background: linear-gradient(90deg, var(--text) 30%, var(--purple));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* unique games callout */
  .game-callout {
    flex-shrink: 0;
    background: var(--surface);
    border: 1px solid rgba(160,96,255,0.2);
    padding: 18px 28px;
    text-align: center;
    position: relative;
    animation: panelIn 0.5s ease both;
  }
  .game-callout::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--purple); box-shadow: var(--glow-p);
  }
  .game-num {
    font-family: 'Orbitron', monospace;
    font-size: 36px; font-weight: 900; line-height: 1;
    color: var(--purple);
    text-shadow: 0 0 20px rgba(160,96,255,0.4);
  }
  .game-label {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted); margin-top: 6px;
  }
  .game-sub {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase;
    color: rgba(160,96,255,0.4); margin-top: 4px;
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
    background: linear-gradient(90deg, var(--purple), var(--cyan));
    box-shadow: 0 0 16px rgba(160,96,255,0.4);
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

  table {
    width: 100%; border-collapse: collapse; min-width: 460px;
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

  thead th:first-child,
  td:first-child { text-align: center; width: 72px; }

  tbody tr {
    border-bottom: 1px solid var(--border2);
    transition: background 0.2s;
    animation: rowIn 0.4s ease both;
  }
  <?php for($i=1;$i<=20;$i++): ?>
  tbody tr:nth-child(<?= $i ?>) { animation-delay: <?= $i * 0.04 ?>s; }
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
    padding: 17px 24px;
    font-size: 14px; font-weight: 500;
    color: var(--text); white-space: nowrap;
  }

  .td-idx {
    font-family: 'Orbitron', monospace;
    font-size: 11px; font-weight: 700;
    color: var(--muted); letter-spacing: 1px;
  }

  /* tournament name */
  .td-tournament {
    display: flex; align-items: center; gap: 10px;
    font-weight: 600; letter-spacing: 0.3px;
  }
  .t-icon { font-size: 16px; transition: transform 0.25s; }
  tbody tr:hover .t-icon { transform: scale(1.2); }

  /* game badge — colour driven by real game name */
  .game-badge {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 12px; font-weight: 700; letter-spacing: 2px;
    text-transform: uppercase;
    padding: 5px 14px;
    border: 1px solid;
    transition: box-shadow 0.2s;
  }
  .game-dot {
    width: 6px; height: 6px; border-radius: 50%;
    flex-shrink: 0;
  }
  tbody tr:hover .game-badge {
    box-shadow: 0 0 12px color-mix(in srgb, var(--gb-color, #5a6080) 30%, transparent);
  }

  /* empty */
  .empty-row td {
    text-align: center; padding: 56px 24px;
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
  .tf-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--purple); }
  .tf-query {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: var(--muted);
  }
  .tf-query span { color: var(--purple); font-style: italic; letter-spacing: 1px; }

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
  .footer-back:hover { color: var(--purple); }

  @media (max-width: 700px) {
    header, nav, .breadcrumb, main, footer { padding-left: 20px; padding-right: 20px; }
    nav { justify-content: flex-start; overflow-x: auto; }
    nav a { padding: 0 18px; }
    .page-top { flex-direction: column; align-items: flex-start; }
    .game-callout { width: 100%; }
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
  <span class="breadcrumb-cur">Games Used in Tournament</span>
</div>

<!-- MAIN -->
<main>

  <div class="page-top">
    <div class="pt-left">
      <!-- <div class="pt-eyebrow">Analytics — tournament JOIN game</div> -->
      <h1><span>Games Used in Tournament</span></h1>
    </div>

    <?php if($unique_games > 0): ?>
    <div class="game-callout">
      <div class="game-num"><?= $unique_games ?></div>
      <div class="game-label">Unique Game<?= $unique_games !== 1 ? 's' : '' ?></div>
      <div class="game-sub">DISTINCT game_name</div>
    </div>
    <?php endif; ?>
  </div>

  <div class="table-panel">
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Tournament</th>
            <th>Game</th>
          </tr>
        </thead>
        <tbody>
          <?php if($count === 0): ?>
            <tr class="empty-row"><td colspan="3">No data found</td></tr>
          <?php else: ?>
            <?php foreach($rows as $i => $row):
              $gc = game_color($row['game_name'], $game_colors);
            ?>
            <tr>
              <td class="td-idx"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></td>
              <td>
                <div class="td-tournament">
                  <span class="t-icon">🏆</span>
                  <?= htmlspecialchars($row['tournament_name']) ?>
                </div>
              </td>
              <td>
                <span class="game-badge"
                  style="color:#<?= $gc ?>;border-color:#<?= $gc ?>33;background:#<?= $gc ?>11;--gb-color:#<?= $gc ?>;">
                  <span class="game-dot" style="background:#<?= $gc ?>;"></span>
                  <?= htmlspecialchars($row['game_name']) ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot">
      <div class="tf-label">
        <div class="tf-dot"></div>
        <?= $count ?> record<?= $count !== 1 ? 's' : '' ?> returned
      </div>
      <div class="tf-query">Query: <span>tournament JOIN game ON game_id</span></div>
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