<?php
$conn = mysqli_connect("127.0.0.1","root","","esports_db",3307);
$sql = "SELECT player.player_name, SUM(matchstats.kills) AS total_kills
FROM player
JOIN matchstats ON player.player_id = matchstats.player_id
GROUP BY player.player_name
ORDER BY total_kills DESC";
$result = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Top Players by Kills — Esports Tournament Management System</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Barlow:wght@300;400;500;600&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg:      #04040a;
    --surface: #0c0c18;
    --surface2:#111120;
    --surface3:#161628;
    --border:  rgba(92,207,255,0.12);
    --border2: rgba(92,207,255,0.06);
    --cyan:    #5ccfff;
    --gold:    #f0c040;
    --red:     #ff4466;
    --green:   #30e8a0;
    --amber:   #ffaa30;
    --purple:  #a060ff;
    --text:    #dde4f0;
    --muted:   #5a6080;
    --glow-c:  0 0 24px rgba(92,207,255,0.35);
    --glow-g:  0 0 24px rgba(240,192,64,0.35);
    --glow-r:  0 0 24px rgba(255,68,102,0.35);
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
    background: radial-gradient(circle, rgba(255,68,102,0.05) 0%, transparent 65%);
    top: -250px; right: -150px;
    animation: af 14s ease-in-out infinite;
  }
  .atmo-2 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(240,192,64,0.04) 0%, transparent 65%);
    bottom: -100px; left: -100px;
    animation: af 18s ease-in-out infinite reverse;
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
  .breadcrumb a:hover { color: var(--red); }
  .breadcrumb-cur { color: var(--red); }
  .breadcrumb-sep { color: var(--border); font-size: 14px; }

  /* ── MAIN ── */
  main {
    position: relative; z-index: 1;
    padding: 60px 48px 80px;
    max-width: 900px; margin: 0 auto;
  }

  .page-top { margin-bottom: 44px; }

  .pt-eyebrow {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 4px;
    color: var(--red); text-transform: uppercase;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 10px;
  }
  .pt-eyebrow::before {
    content: '';
    display: inline-block; width: 24px; height: 1px;
    background: var(--red); box-shadow: var(--glow-r);
  }

  h1 {
    font-family: 'Orbitron', monospace;
    font-size: clamp(24px, 3.5vw, 44px);
    font-weight: 900; line-height: 1; letter-spacing: -1px;
  }
  h1 span {
    background: linear-gradient(90deg, var(--text) 30%, var(--red));
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

  /* top accent bar */
  .table-panel::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--red), var(--gold));
    box-shadow: 0 0 16px rgba(255,68,102,0.4);
  }

  /* corner cut */
  .table-panel::after {
    content: '';
    position: absolute; bottom: 0; right: 0;
    width: 44px; height: 44px;
    background: var(--bg);
    clip-path: polygon(100% 0%, 100% 100%, 0% 100%);
    border-top: 1px solid var(--border);
    border-left: 1px solid var(--border);
  }

  /* ── TABLE ── */
  table {
    width: 100%; border-collapse: collapse;
  }

  thead tr {
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
  }

  thead th {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 11px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--muted); font-weight: 600;
    padding: 18px 28px; text-align: left;
  }

  thead th:last-child { text-align: right; }

  /* rank col */
  thead th:first-child { width: 72px; text-align: center; }

  tbody tr {
    border-bottom: 1px solid var(--border2);
    transition: background 0.2s;
    animation: rowIn 0.4s ease both;
  }

  @keyframes rowIn {
    from { opacity: 0; transform: translateX(-10px); }
    to   { opacity: 1; transform: translateX(0); }
  }

  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: var(--surface2); }

  /* top 3 highlight */
  tbody tr:nth-child(1) { background: rgba(240,192,64,0.04); }
  tbody tr:nth-child(2) { background: rgba(192,192,192,0.03); }
  tbody tr:nth-child(3) { background: rgba(205,127,50,0.03); }
  tbody tr:nth-child(1):hover { background: rgba(240,192,64,0.08); }
  tbody tr:nth-child(2):hover { background: rgba(192,192,192,0.06); }
  tbody tr:nth-child(3):hover { background: rgba(205,127,50,0.06); }

  td {
    padding: 18px 28px;
    font-size: 14px; font-weight: 500;
    color: var(--text);
  }

  /* rank cell */
  .td-rank {
    text-align: center;
    font-family: 'Orbitron', monospace;
    font-size: 12px; font-weight: 700;
    width: 72px;
  }

  .rank-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    font-family: 'Orbitron', monospace;
    font-size: 11px; font-weight: 900;
  }

  .rank-1 { color: #ffd700; text-shadow: 0 0 10px rgba(255,215,0,0.6); }
  .rank-2 { color: #c0c0c0; text-shadow: 0 0 10px rgba(192,192,192,0.4); }
  .rank-3 { color: #cd7f32; text-shadow: 0 0 10px rgba(205,127,50,0.4); }
  .rank-n { color: var(--muted); }

  /* crown for rank 1 */
  .rank-crown { font-size: 18px; display: block; line-height: 1; }

  /* player name cell */
  .td-player { font-weight: 600; letter-spacing: 0.3px; }

  tbody tr:nth-child(1) .td-player { color: #ffd700; }
  tbody tr:nth-child(2) .td-player { color: #d0d0d8; }
  tbody tr:nth-child(3) .td-player { color: #cd8a50; }

  /* kills cell */
  .td-kills {
    text-align: right;
    font-family: 'Orbitron', monospace;
    font-size: 15px; font-weight: 900;
    color: var(--red);
    text-shadow: 0 0 12px rgba(255,68,102,0.3);
  }

  tbody tr:nth-child(1) .td-kills {
    color: #ffd700;
    text-shadow: 0 0 14px rgba(255,215,0,0.5);
    font-size: 17px;
  }

  /* kill bar visual */
  .kill-bar-wrap {
    display: flex; align-items: center; justify-content: flex-end; gap: 12px;
  }
  .kill-bar {
    height: 3px; border-radius: 2px;
    background: var(--red); opacity: 0.4;
    min-width: 4px;
    transition: opacity 0.3s;
  }
  tbody tr:nth-child(1) .kill-bar { background: #ffd700; opacity: 0.6; }
  tbody tr:nth-child(2) .kill-bar { background: #c0c0c0; opacity: 0.4; }
  tbody tr:nth-child(3) .kill-bar { background: #cd7f32; opacity: 0.4; }
  tbody tr:hover .kill-bar { opacity: 0.8; }

  /* empty state */
  .empty-row td {
    text-align: center;
    padding: 48px 28px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 13px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }

  /* ── TABLE FOOTER ROW ── */
  .table-foot {
    padding: 16px 28px;
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
  .tf-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green); }
  .tf-query {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--muted);
  }
  .tf-query span {
    color: var(--cyan); font-style: italic; letter-spacing: 1px;
  }

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
    color: var(--muted); text-decoration: none;
    transition: color 0.2s;
  }
  .footer-back:hover { color: var(--red); }

  @media (max-width: 700px) {
    header, nav, .breadcrumb, main, footer { padding-left: 20px; padding-right: 20px; }
    nav { justify-content: flex-start; overflow-x: auto; }
    nav a { padding: 0 18px; }
    td, thead th { padding: 14px 16px; }
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
  <span class="breadcrumb-cur">Top Players by Kills</span>
</div>

<!-- MAIN -->
<main>

  <div class="page-top">
    <!-- <div class="pt-eyebrow">Analytics — SUM + ORDER BY</div> -->
    <h1><span>Top Players by Kills</span></h1>
  </div>

  <div class="table-panel">
    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Player</th>
          <th>Total Kills</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rank = 0;
        $max_kills = null;

        /* get max kills for bar scaling */
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $rows[] = $row;
        }
        if (!empty($rows)) {
          $max_kills = $rows[0]['total_kills'];
        }

        if (empty($rows)) {
          echo "<tr class='empty-row'><td colspan='3'>No data found</td></tr>";
        } else {
          foreach ($rows as $row) {
            $rank++;
            $kills = intval($row['total_kills']);
            $bar_width = $max_kills > 0 ? round(($kills / $max_kills) * 80) : 0;

            $rank_class = 'rank-n';
            $rank_display = str_pad($rank, 2, '0', STR_PAD_LEFT);
            if ($rank === 1) { $rank_class = 'rank-1'; $rank_display = '👑'; }
            elseif ($rank === 2) { $rank_class = 'rank-2'; }
            elseif ($rank === 3) { $rank_class = 'rank-3'; }

            echo "<tr>
              <td class='td-rank'>
                <span class='rank-badge {$rank_class}'>{$rank_display}</span>
              </td>
              <td class='td-player'>" . htmlspecialchars($row['player_name']) . "</td>
              <td class='td-kills'>
                <div class='kill-bar-wrap'>
                  <div class='kill-bar' style='width:{$bar_width}px'></div>
                  " . number_format($kills) . "
                </div>
              </td>
            </tr>";
          }
        }
        ?>
      </tbody>
    </table>

    <div class="table-foot">
      <div class="tf-label"><div class="tf-dot"></div><?php echo count($rows); ?> players ranked</div>
      <!-- <div class="tf-query">Query: <span>SUM(kills) GROUP BY player ORDER BY total_kills DESC</span></div> -->
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