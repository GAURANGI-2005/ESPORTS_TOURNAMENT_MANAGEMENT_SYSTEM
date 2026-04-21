<?php
$conn = mysqli_connect("127.0.0.1","root","","esports_db",3307);
$sql = "SELECT player_name, gamer_tag, age, nationality, role 
        FROM player";
$result = mysqli_query($conn,$sql);

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}
$count = count($rows);

/* role → accent colour map */
$role_colors = [
    'duelist'    => '#ff4466',
    'controller' => '#a060ff',
    'initiator'  => '#f0c040',
    'sentinel'   => '#30e8a0',
    'support'    => '#5ccfff',
    'igl'        => '#ffaa30',
    'awper'      => '#ff4466',
    'rifler'     => '#5ccfff',
    'entry'      => '#ff4466',
    'lurker'     => '#a060ff',
];

function role_color($role, $map) {
    $key = strtolower(trim($role));
    return $map[$key] ?? '#5a6080';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Players List — Esports Tournament Management System</title>
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
    --text:    #dde4f0;
    --muted:   #5a6080;
    --glow-c:  0 0 24px rgba(92,207,255,0.35);
    --glow-g:  0 0 24px rgba(240,192,64,0.35);
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
    background: radial-gradient(circle, rgba(92,207,255,0.05) 0%, transparent 65%);
    top: -200px; left: -150px;
    animation: af 15s ease-in-out infinite;
  }
  .atmo-2 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(48,232,160,0.04) 0%, transparent 65%);
    bottom: -100px; right: -100px;
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
  .breadcrumb a:hover { color: var(--cyan); }
  .breadcrumb-cur { color: var(--cyan); }
  .breadcrumb-sep { color: var(--border); font-size: 14px; }

  /* ── MAIN ── */
  main {
    position: relative; z-index: 1;
    padding: 60px 48px 80px;
    max-width: 1200px; margin: 0 auto;
  }

  .page-top { margin-bottom: 44px; }

  .pt-eyebrow {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px;
    color: var(--cyan); text-transform: uppercase;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 10px;
  }
  .pt-eyebrow::before {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--cyan); box-shadow: var(--glow-c);
  }

  h1 {
    font-family: 'Orbitron', monospace;
    font-size: clamp(24px, 3.5vw, 44px);
    font-weight: 900; line-height: 1; letter-spacing: -1px;
  }
  h1 span {
    background: linear-gradient(90deg, var(--text) 30%, var(--cyan));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* ── TABLE PANEL ── */
  .table-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
    animation: panelIn 0.5s ease both;
  }
  @keyframes panelIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .table-panel::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--cyan), var(--green));
    box-shadow: 0 0 16px rgba(92,207,255,0.4);
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
    width: 100%; border-collapse: collapse; min-width: 700px;
  }

  thead tr {
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
  }

  thead th {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--muted); font-weight: 600;
    padding: 18px 22px; text-align: left;
    white-space: nowrap;
  }

  /* row number col */
  thead th:first-child,
  td:first-child { text-align: center; width: 60px; padding-left: 16px; padding-right: 16px; }

  /* age col */
  thead th:nth-child(4),
  td:nth-child(4) { text-align: center; width: 80px; }

  tbody tr {
    border-bottom: 1px solid var(--border2);
    transition: background 0.2s;
    animation: rowIn 0.4s ease both;
  }
  <?php for($i=1;$i<=30;$i++): ?>
  tbody tr:nth-child(<?= $i ?>) { animation-delay: <?= $i * 0.025 ?>s; }
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
    padding: 15px 22px;
    font-size: 14px; font-weight: 500;
    color: var(--text); white-space: nowrap;
  }

  /* row index */
  .td-idx {
    font-family: 'Orbitron', monospace;
    font-size: 10px; font-weight: 700;
    color: var(--muted); letter-spacing: 1px;
  }

  /* player name */
  .td-name { font-weight: 600; letter-spacing: 0.3px; }

  /* gamer tag */
  .td-tag {
    font-family: 'Orbitron', monospace;
    font-size: 12px; font-weight: 700;
    color: var(--cyan); letter-spacing: 1px;
    transition: color 0.2s, text-shadow 0.2s;
  }
  tbody tr:hover .td-tag {
    color: var(--gold);
    text-shadow: 0 0 10px rgba(240,192,64,0.4);
  }

  /* age */
  .age-val {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 14px; letter-spacing: 1px;
    color: var(--muted);
  }

  /* nationality */
  .nat-wrap {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 13px; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--muted);
  }
  .nat-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--cyan); opacity: 0.4; flex-shrink: 0;
    transition: opacity 0.2s;
  }
  tbody tr:hover .nat-dot { opacity: 1; }

  /* role badge */
  .role-badge {
    display: inline-flex; align-items: center;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; font-weight: 600; letter-spacing: 2px;
    text-transform: uppercase;
    padding: 4px 12px;
    border: 1px solid;
    transition: box-shadow 0.2s;
  }
  tbody tr:hover .role-badge {
    box-shadow: 0 0 10px color-mix(in srgb, var(--rb-color, #5a6080) 30%, transparent);
  }

  /* empty */
  .empty-row td {
    text-align: center; padding: 56px 22px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 13px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }

  /* ── TABLE FOOTER ── */
  .table-foot {
    padding: 16px 22px;
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
  .tf-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--cyan); }
  .tf-query {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: var(--muted);
  }
  .tf-query span { color: var(--cyan); font-style: italic; letter-spacing: 1px; }

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
  .footer-back:hover { color: var(--cyan); }

  @media (max-width: 700px) {
    header, nav, .breadcrumb, main, footer { padding-left: 20px; padding-right: 20px; }
    nav { justify-content: flex-start; overflow-x: auto; }
    nav a { padding: 0 18px; }
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
  <span class="breadcrumb-cur">Players List</span>
</div>

<!-- MAIN -->
<main>

  <div class="page-top">
    <!-- <div class="pt-eyebrow">Players — SELECT</div> -->
    <h1><span>Players List</span></h1>
  </div>

  <div class="table-panel">
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Player Name</th>
            <th>Gamer Tag</th>
            <th>Age</th>
            <th>Nationality</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($count === 0): ?>
            <tr class="empty-row"><td colspan="6">No players found</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $i => $row):
              $rc = role_color($row['role'], $role_colors);
            ?>
            <tr>
              <td class="td-idx"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></td>
              <td class="td-name"><?= htmlspecialchars($row['player_name']) ?></td>
              <td><span class="td-tag"><?= htmlspecialchars($row['gamer_tag']) ?></span></td>
              <td><span class="age-val"><?= htmlspecialchars($row['age']) ?></span></td>
              <td>
                <div class="nat-wrap">
                  <span class="nat-dot"></span>
                  <?= htmlspecialchars($row['nationality']) ?>
                </div>
              </td>
              <td>
                <span class="role-badge"
                  style="color:<?= $rc ?>;border-color:<?= $rc ?>22;background:<?= $rc ?>11;--rb-color:<?= $rc ?>;">
                  <?= htmlspecialchars($row['role']) ?>
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
        <?= $count ?> player<?= $count !== 1 ? 's' : '' ?> found
      </div>
      <!-- <div class="tf-query">Query: <span>SELECT player_name, gamer_tag, age, nationality, role FROM player</span></div> -->
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