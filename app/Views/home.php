<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Découvrir</title>
  <link rel="stylesheet" href="/css/style.css"> 
</head>
<body>

<div class="app-page">

  <!-- Top Bar -->
  <div class="topbar">
    <span class="topbar-logo"><span>🍽️</span>FoodSwipe</span>
    <div class="topbar-actions">
      <a href="/stat" title="Mes stats">📊</a>
      <a href="#" title="Se déconnecter" onclick="logout()">🚪</a>
    </div>
  </div>

  <!-- Card Area -->
  <div class="card-area">
    <div class="card-stack" id="card-stack"></div>

    <div class="empty-state" id="empty-state">
      <div class="emoji">🍀</div>
      <h2>C'est tout pour l'instant !</h2>
      <p>Vous avez vu tous les plats disponibles.<br>Consultez vos stats !</p>
      <a href="/stat" class="btn-primary" style="max-width:200px;margin-top:8px;text-align:center;display:block">
        Voir mes stats 📊
      </a>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="action-btns" id="action-btns">
    <button class="action-btn btn-skip"  title="Pas intéressé" onclick="swipeLeft()">✕</button>
    <button class="action-btn btn-super" title="Super Like"    onclick="superLike()">⭐</button>
    <button class="action-btn btn-like"  title="J'aime"        onclick="swipeRight()">♥</button>
  </div>

  <!-- Bottom Nav -->
  <div class="bottom-nav">
    <a href="/home" class="active">
      <span class="nav-icon">🔥</span>Découvrir
    </a>
    <a href="/ajouter">
      <span class="nav-icon">➕</span>Ajouter
    </a>
    <a href="/stat">
      <span class="nav-icon">📊</span>Mes stats
    </a>
  </div>

</div>

<script>
  /*  
  if (localStorage.getItem('fs_logged') !== 'true') {
    window.location.href = 'login.html';
  }

  function logout() {
    localStorage.setItem('fs_logged', 'false');
    window.location.href = 'login.html';
  }
  */

  /* ── Data from PHP (Base de données) ── */
  const ALL_FOODS = <?= json_encode($foods ?? []) ?>;

  const CAT_COLORS = [
    '#FF6B6B','#FF8E53','#FFC371','#4ECDC4','#45B7D1',
    '#96CEB4','#DDA0DD','#FF69B4','#20B2AA','#9370DB','#F08080','#3CB371',
  ];
  
  const customs  = JSON.parse(localStorage.getItem('fs_custom_foods') || '[]');
  const FOODS    = [...ALL_FOODS, ...customs];
  const ALL_CATS = [...new Set(FOODS.map(f => f.cat))];
  const catColor = cat => CAT_COLORS[ALL_CATS.indexOf(cat) % CAT_COLORS.length];

  /* ── State (persisted in localStorage) ── */
  function loadState() {
    const seenIds    = JSON.parse(localStorage.getItem('fs_seen')  || '[]');
    const likedIds   = JSON.parse(localStorage.getItem('fs_liked') || '[]');
    const superIds   = JSON.parse(localStorage.getItem('fs_super') || '[]');
    const deck       = FOODS.filter(f => !seenIds.includes(f.id));
    return { deck, likedIds, superIds, seenIds };
  }

  function saveSwipe(food, action) {
    const seenIds  = JSON.parse(localStorage.getItem('fs_seen')  || '[]');
    const likedIds = JSON.parse(localStorage.getItem('fs_liked') || '[]');
    const superIds = JSON.parse(localStorage.getItem('fs_super') || '[]');

    seenIds.push(food.id);
    if (action === 'like' || action === 'super') likedIds.push(food.id);
    if (action === 'super') superIds.push(food.id);

    localStorage.setItem('fs_seen',  JSON.stringify(seenIds));
    localStorage.setItem('fs_liked', JSON.stringify(likedIds));
    localStorage.setItem('fs_super', JSON.stringify(superIds));
  }

  /* ── Render ── */
  let { deck } = loadState();

  function renderDeck() {
    const stack = document.getElementById('card-stack');
    const empty = document.getElementById('empty-state');
    const btns  = document.getElementById('action-btns');

    stack.innerHTML = '';

    if (deck.length === 0) {
      empty.classList.add('visible');
      btns.style.display = 'none';
      return;
    }

    empty.classList.remove('visible');
    btns.style.display = 'flex';

    deck.slice(0, 3).reverse().forEach(food => {
      stack.appendChild(buildCard(food));
    });

    attachDrag(stack.querySelector('.food-card:last-child'), deck[0]);
  }

  function buildCard(food) {
    const col     = catColor(food.cat);
    const el      = document.createElement('div');
    el.className  = 'food-card';
    el.dataset.id = food.id;
    const imgHTML = food.img
      ? `<img src="<?= base_url() ?>/${food.img}" alt="${food.name}" class="food-card-photo" onerror="this.parentElement.innerHTML='<span class=food-card-emoji>${food.emoji}</span>'">`
      : `<span class="food-card-emoji">${food.emoji}</span>`;
    el.innerHTML = `
      <div class="food-card-img" style="background:linear-gradient(135deg,${col}22,${col}55)">${imgHTML}</div>
      <div class="stamp stamp-like">J'aime ❤️</div>
      <div class="stamp stamp-nope">Nope 👎</div>
      <div class="food-card-info">
        <div class="food-card-top">
          <div class="food-card-name">${escapeHtml(food.name)}</div>
          <div class="food-card-rating">⭐ ${food.rating}</div>
        </div>
        <div class="food-card-meta">
          <span class="badge category">${escapeHtml(food.cat)}</span>
          <span class="badge time">⏱ ${food.time}</span>
          <span class="badge cal">🔥 ${food.cal}</span>
        </div>
        <div class="food-card-desc">${escapeHtml(food.desc || '')}</div>
      </div>`;
    return el;
  }

  // Fonction utilitaire pour échapper le HTML et éviter les XSS
  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  /* ── Drag ── */
  function attachDrag(card, food) {
    if (!card) return;
    let startX = 0, startY = 0, currentX = 0, dragging = false;
    const likeStamp = card.querySelector('.stamp-like');
    const nopeStamp = card.querySelector('.stamp-nope');

    function onStart(e) {
      dragging = true;
      startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
      startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
      card.style.transition = 'none';
    }
    function onMove(e) {
      if (!dragging) return;
      const x = (e.type === 'touchmove' ? e.touches[0].clientX : e.clientX) - startX;
      const y = (e.type === 'touchmove' ? e.touches[0].clientY : e.clientY) - startY;
      currentX = x;
      card.style.transform = `translateX(${x}px) translateY(${y * .2}px) rotate(${x / 18}deg)`;
      const pct = Math.min(Math.abs(x) / 80, 1);
      likeStamp.style.opacity = x > 20  ? pct : 0;
      nopeStamp.style.opacity = x < -20 ? pct : 0;
    }
    function onEnd() {
      if (!dragging) return;
      dragging = false;
      likeStamp.style.opacity = 0;
      nopeStamp.style.opacity = 0;
      if      (currentX >  90) performSwipe('right', food);
      else if (currentX < -90) performSwipe('left',  food);
      else {
        card.style.transition = 'transform .4s cubic-bezier(.22,.61,.36,1)';
        card.style.transform  = '';
      }
      currentX = 0;
    }

    card.addEventListener('mousedown',   onStart);
    card.addEventListener('touchstart',  onStart, { passive: true });
    window.addEventListener('mousemove', onMove);
    window.addEventListener('touchmove', onMove,  { passive: true });
    window.addEventListener('mouseup',   onEnd);
    window.addEventListener('touchend',  onEnd);
  }

  /* ── Swipe ── */
  function performSwipe(dir, food) {
    const stack = document.getElementById('card-stack');
    const top   = stack.querySelector('.food-card:last-child');
    if (!top) return;

    top.classList.add(dir === 'right' ? 'swiping-right' : 'swiping-left');
    const action = dir === 'right' ? 'like' : 'skip';
    saveSwipe(food, action);

    setTimeout(() => {
      deck.shift();
      renderDeck();
    }, 320);
  }

  function swipeRight() { if (deck.length) performSwipe('right', deck[0]); }
  function swipeLeft()  { if (deck.length) performSwipe('left',  deck[0]); }

  function superLike() {
    if (!deck.length) return;
    const food = deck[0];
    const stack = document.getElementById('card-stack');
    const top   = stack.querySelector('.food-card:last-child');
    if (!top) return;
    top.classList.add('swiping-right');
    saveSwipe(food, 'super');
    setTimeout(() => { deck.shift(); renderDeck(); }, 320);
  }

  /* ── Init ── */
  renderDeck();
</script>

</body>
</html>