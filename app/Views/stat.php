<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Mes stats</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="app-page">

  <!-- Top Bar -->
  <div class="topbar">
    <span class="topbar-logo"><span>🍽️</span>FoodSwipe</span>
    <div class="topbar-actions">
      <a href="#" title="Se déconnecter" onclick="logout()">🚪</a>
    </div>
  </div>

  <!-- Stats Header -->
  <div class="stats-header">
    <h2>Mes statistiques 📊</h2>
    <p id="stats-subtitle">Vos plats préférés en un coup d'œil</p>
  </div>

  <!-- Stats Body -->
  <div class="stats-body">

    <!-- KPIs -->
    <div class="kpi-row">
      <div class="kpi-card">
        <span class="kpi-icon">❤️</span>
        <div class="kpi-value" id="kpi-liked"><?= $totalLiked ?></div>
        <div class="kpi-label">Aimés</div>
      </div>
      <div class="kpi-card">
        <span class="kpi-icon">👀</span>
        <div class="kpi-value" id="kpi-seen"><?= $totalSeen ?></div>
        <div class="kpi-label">Vus</div>
      </div>
      <div class="kpi-card">
        <span class="kpi-icon">⭐</span>
        <div class="kpi-value" id="kpi-super"><?= $totalSuper ?></div>
        <div class="kpi-label">Super Like</div>
      </div>
    </div>

    <!-- Category Bar Chart -->
    <div class="section-title">🥗 Répartition par catégorie</div>
    <div class="bar-chart" id="category-chart">
      <?php if (empty($categories)): ?>
        <p class="empty-placeholder">Aucune donnée encore</p>
      <?php else: ?>
        <?php 
        $maxCount = !empty($categories) ? max(array_column($categories, 'count')) : 1;
        ?>
        <?php foreach ($categories as $index => $cat): ?>
          <?php 
          $color = $catColors[$index % count($catColors)];
          $width = ($cat['count'] / $maxCount) * 100;
          ?>
          <div class="bar-row">
            <div class="bar-label"><?= esc($cat['nom']) ?></div>
            <div class="bar-track">
              <div class="bar-fill" style="width:<?= $width ?>%;background:<?= $color ?>"></div>
            </div>
            <div class="bar-count"><?= $cat['count'] ?></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Donut -->
    <div class="section-title">📈 Taux d'appréciation</div>
    <div class="donut-wrap">
      <svg viewBox="0 0 80 80" width="90" height="90" style="flex-shrink:0">
        <circle cx="40" cy="40" r="30" fill="none" stroke="#F0F0F0" stroke-width="12"/>
        <circle cx="40" cy="40" r="30" fill="none" stroke="url(#grad)" stroke-width="12"
                stroke-dasharray="0 188.5" stroke-linecap="round"
                transform="rotate(-90 40 40)" id="donut-arc"/>
        <defs>
          <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%"   stop-color="#FF6B6B"/>
            <stop offset="100%" stop-color="#FF8E53"/>
          </linearGradient>
        </defs>
        <text x="40" y="44" text-anchor="middle" font-size="14" font-weight="800"
              fill="#2D3748" id="donut-pct"><?= $totalSeen > 0 ? round(($totalLiked / $totalSeen) * 100) : 0 ?>%</text>
      </svg>
      <div class="donut-legend">
        <div class="legend-item">
          <div class="legend-dot" style="background:#FF6B6B"></div>Aimés
        </div>
        <div class="legend-item">
          <div class="legend-dot" style="background:#EEE"></div>Passés
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px" id="donut-label">
          <?php if ($totalSeen > 0): ?>
            <strong><?= $totalLiked ?></strong> aimé<?= $totalLiked > 1 ? 's' : '' ?> sur <strong><?= $totalSeen ?></strong> vus
          <?php else: ?>
            Swipez des plats<br>pour voir vos stats
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Liked List -->
    <div class="section-title">💖 Plats aimés</div>
    <div class="liked-list" id="liked-list">
      <?php if (empty($likedFoods)): ?>
        <div class="empty-placeholder">Vous n'avez encore aimé aucun plat 🍽️</div>
      <?php else: ?>
        <?php foreach ($likedFoods as $index => $food): ?>
          <div class="liked-item" style="animation-delay:<?= $index * 0.05 ?>s">
            <div class="liked-item-thumb">
              <?php if ($food['img'] && file_exists(FCPATH . $food['img'])): ?>
                <img src="<?= base_url($food['img']) ?>" alt="<?= esc($food['name']) ?>" 
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span class="liked-item-emoji" style="display:none"><?= esc($food['emoji']) ?></span>
              <?php else: ?>
                <span class="liked-item-emoji"><?= esc($food['emoji']) ?></span>
              <?php endif; ?>
            </div>
            <div class="liked-item-info">
              <div class="liked-item-name"><?= esc($food['name']) ?></div>
              <div class="liked-item-cat">
                <?php 
                $colorIndex = $index % count($catColors);
                $catColor = $catColors[$colorIndex];
                ?>
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:<?= $catColor ?>;margin-right:4px;vertical-align:middle"></span>
                <?= esc($food['cat']) ?> · ⏱ <?= esc($food['time']) ?> · 🔥 <?= esc($food['cal']) ?>
              </div>
            </div>
            <div class="liked-item-heart"><?= in_array($food['id'], $superIds) ? '⭐' : '❤️' ?></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

  <!-- Bottom Nav -->
  <div class="bottom-nav">
    <a href="/home">
      <span class="nav-icon">🔥</span>Découvrir
    </a>
    <a href="/ajouter">
      <span class="nav-icon">➕</span>Ajouter
    </a>
    <a href="/stat" class="active">
      <span class="nav-icon">📊</span>Mes stats
    </a>
  </div>

</div>

<script>
  function logout() {
    window.location.href = '/login';
  }

  // Mettre à jour le donut
  const totalSeen = <?= $totalSeen ?>;
  const totalLiked = <?= $totalLiked ?>;
  const pct = totalSeen > 0 ? Math.round((totalLiked / totalSeen) * 100) : 0;
  const circ = 2 * Math.PI * 30;
  const donutArc = document.getElementById('donut-arc');
  if (donutArc) {
    donutArc.setAttribute('stroke-dasharray', `${circ * pct / 100} ${circ}`);
  }
</script>

</body>
</html>