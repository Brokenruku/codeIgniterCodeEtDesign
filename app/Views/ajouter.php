<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Ajouter un plat</title>
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

  <!-- Form Body -->
  <div class="addfood-body">

    <!-- Preview Card -->
    <div class="preview-wrap">
      <div class="preview-label">Aperçu</div>
      <div class="preview-card" id="preview-card">
        <div class="preview-img" id="preview-img" style="background:linear-gradient(135deg,#FF6B6B22,#FF6B6B55)">
          <img id="preview-photo" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;object-position:center" />
          <span id="preview-emoji">🍽️</span>
        </div>
        <div class="food-card-info">
          <div class="food-card-top">
            <div class="food-card-name" id="preview-name">Nom du plat</div>
            <div class="food-card-rating">⭐ <span id="preview-rating">-</span></div>
          </div>
          <div class="food-card-meta">
            <span class="badge category" id="preview-cat">Catégorie</span>
            <span class="badge time" id="preview-time">⏱ --</span>
            <span class="badge cal"  id="preview-cal">🔥 -- kcal</span>
          </div>
          <div class="food-card-desc" id="preview-desc">Description du plat…</div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form class="addfood-form" id="addFoodForm" enctype="multipart/form-data">

      <!-- Image Upload -->
      <div class="form-group">
        <label>Photo du plat</label>
        <div class="upload-zone" id="upload-zone"
             onclick="document.getElementById('field-img').click()"
             ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)">
          <input type="file" id="field-img" name="image" accept="image/*" style="display:none" onchange="onImageUpload(event)" />
          <div class="upload-placeholder" id="upload-placeholder">
            <span class="upload-icon">📷</span>
            <p class="upload-text">Cliquer ou glisser une photo</p>
            <p class="upload-hint">JPG, PNG, WEBP · max 5 Mo</p>
          </div>
          <div class="upload-preview" id="upload-preview" style="display:none">
            <img id="upload-preview-img" src="" alt="Aperçu" />
            <button type="button" class="upload-remove" onclick="removeImage(event)" title="Supprimer la photo">✕</button>
          </div>
        </div>
      </div>

      <!-- Emoji Picker -->
      <div class="form-group">
        <label>Emoji du plat</label>
        <div class="emoji-grid" id="emoji-grid">
          <!-- injected by JS -->
        </div>
        <input type="hidden" id="field-emoji" name="emoji" value="🍽️" />
      </div>

      <!-- Name -->
      <div class="form-group">
        <label>Nom du plat <span class="required">*</span></label>
        <input type="text" id="field-name" name="name" placeholder="ex : Bœuf bourguignon" maxlength="40"
               oninput="syncPreview()" required />
      </div>

      <!-- Category -->
      <div class="form-group">
        <label>Catégorie <span class="required">*</span></label>
        <div class="select-wrap">
          <select id="field-cat" name="categorie" onchange="onCatChange()" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($categories as $categorie): ?>
              <option value="<?= esc($categorie['nom']) ?>"><?= esc($categorie['nom']) ?></option>
            <?php endforeach; ?>
            <option value="__custom__">Autre (préciser)</option>
          </select>
        </div>
        <input type="text" id="field-cat-custom" name="categorie_custom" placeholder="Votre catégorie…"
               style="display:none;margin-top:8px" oninput="syncPreview()" />
      </div>

      <!-- Time + Calories row -->
      <div class="form-row">
        <div class="form-group" style="flex:1">
          <label>Temps <span class="required">*</span></label>
          <div class="input-suffix-wrap">
            <input type="number" id="field-time" name="temps" placeholder="30" min="1" max="999"
                   oninput="syncPreview()" required />
            <span class="input-suffix">min</span>
          </div>
        </div>
        <div class="form-group" style="flex:1">
          <label>Calories <span class="required">*</span></label>
          <div class="input-suffix-wrap">
            <input type="number" id="field-cal" name="calorie" placeholder="500" min="1" max="9999"
                   oninput="syncPreview()" required />
            <span class="input-suffix">kcal</span>
          </div>
        </div>
      </div>

      <!-- Rating -->
      <div class="form-group">
        <label>Note  <span class="required">*</span></label>
        <div class="star-row">
          <input type="range" id="field-rating" name="note" min="1" max="5" step="0.1" value="4.0"
                 oninput="syncPreview()" />
          <div class="star-display">
            <span id="star-visual">★★★★☆</span>
            <span id="star-num" class="star-num">4.0</span>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label>Description</label>
        <textarea id="field-desc" name="description" placeholder="Décrivez votre plat en quelques mots…"
                  rows="3" maxlength="500" oninput="syncPreview()"></textarea>
        <div class="char-count"><span id="char-count">0</span>/500</div>
      </div>

      <p class="form-error" id="form-error"></p>

      <button type="submit" class="btn-primary">Ajouter le plat ✅</button>

    </form>

  </div>

  <!-- Bottom Nav -->
  <div class="bottom-nav">
    <a href="/home">
      <span class="nav-icon">🔥</span>Découvrir
    </a>
    <a href="/ajouter" class="active">
      <span class="nav-icon">➕</span>Ajouter
    </a>
    <a href="/stat">
      <span class="nav-icon">📊</span>Mes stats
    </a>
  </div>

</div>

<!-- Success Toast -->
<div class="toast" id="toast">✅ Plat ajouté avec succès !</div>

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

  /* ── Emoji grid ── */
  const EMOJIS = [
    '🍕','🍔','🌮','🌯','🍜','🍝','🍣','🍱','🍛','🍲',
    '🥘','🍚','🥗','🍳','🥞','🧆','🥙','🫔','🍢','🍱',
    '🥩','🍗','🥚','🧀','🥓','🌭','🥪','🫕','🍮','🧁',
    '🎂','🍰','🍩','🍪','🍫','🍦','🍧','🍨','🥧','🍡',
    '🍷','🥂','🍺','🧋','🥤','☕','🍵','🥛','🍹','🧃',
  ];

  const CAT_COLORS = [
    '#FF6B6B','#FF8E53','#FFC371','#4ECDC4','#45B7D1',
    '#96CEB4','#DDA0DD','#FF69B4','#20B2AA','#9370DB','#F08080','#3CB371',
  ];
  
  const CAT_LIST = <?= json_encode(array_column($categories, 'nom')) ?>;
  CAT_LIST.push('Autre');
  
  const catColor = cat => {
    const i = CAT_LIST.indexOf(cat);
    return CAT_COLORS[i >= 0 ? i : CAT_COLORS.length - 1];
  };

  let selectedEmoji = '🍽️';
  let uploadedImageFile = null;

  /* ── Image upload ── */
  function onImageUpload(e) {
    const file = e.target.files[0];
    if (file) processImageFile(file);
  }

  function onDragOver(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.add('drag-over');
  }

  function onDragLeave(e) {
    document.getElementById('upload-zone').classList.remove('drag-over');
  }

  function onDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processImageFile(file);
  }

  function processImageFile(file) {
    const err = document.getElementById('form-error');
    if (file.size > 5 * 1024 * 1024) {
      err.textContent = 'L\'image dépasse 5 Mo. Choisissez un fichier plus léger.';
      err.classList.add('visible');
      return;
    }
    err.classList.remove('visible');

    uploadedImageFile = file;
    
    const reader = new FileReader();
    reader.onload = function(ev) {
      document.getElementById('upload-preview-img').src = ev.target.result;
      document.getElementById('upload-placeholder').style.display = 'none';
      document.getElementById('upload-preview').style.display    = 'block';
      syncPreview();
    };
    reader.readAsDataURL(file);
  }

  function removeImage(e) {
    e.stopPropagation();
    uploadedImageFile = null;
    document.getElementById('field-img').value = '';
    document.getElementById('upload-preview-img').src = '';
    document.getElementById('upload-preview').style.display    = 'none';
    document.getElementById('upload-placeholder').style.display = 'flex';
    syncPreview();
  }

  const grid = document.getElementById('emoji-grid');
  EMOJIS.forEach(em => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'emoji-btn';
    btn.textContent = em;
    btn.onclick = () => selectEmoji(em, btn);
    grid.appendChild(btn);
  });

  function selectEmoji(em, btn) {
    document.querySelectorAll('.emoji-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedEmoji = em;
    document.getElementById('field-emoji').value = em;
    syncPreview();
  }

  /* ── Category custom field ── */
  function onCatChange() {
    const sel    = document.getElementById('field-cat');
    const custom = document.getElementById('field-cat-custom');
    custom.style.display = sel.value === '__custom__' ? 'block' : 'none';
    if (sel.value === '__custom__') {
      custom.required = true;
    } else {
      custom.required = false;
    }
    syncPreview();
  }

  function getCategory() {
    const sel = document.getElementById('field-cat');
    if (sel.value === '__custom__') {
      return document.getElementById('field-cat-custom').value.trim();
    }
    return sel.value;
  }

  /* ── Live preview sync ── */
  function syncPreview() {
    const name    = document.getElementById('field-name').value.trim()  || 'Nom du plat';
    const cat     = getCategory()                                         || 'Catégorie';
    const time    = document.getElementById('field-time').value;
    const cal     = document.getElementById('field-cal').value;
    const rating  = parseFloat(document.getElementById('field-rating').value).toFixed(1);
    const desc    = document.getElementById('field-desc').value.trim()   || 'Description du plat…';
    const emoji   = selectedEmoji;
    const col     = catColor(cat);

    // Photo vs emoji dans l'aperçu
    const previewPhoto = document.getElementById('preview-photo');
    const previewEmoji = document.getElementById('preview-emoji');
    if (uploadedImageFile) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        previewPhoto.src = ev.target.result;
      };
      reader.readAsDataURL(uploadedImageFile);
      previewPhoto.style.display   = 'block';
      previewEmoji.style.display   = 'none';
      document.getElementById('preview-img').style.background = 'none';
    } else {
      previewPhoto.style.display   = 'none';
      previewEmoji.style.display   = 'block';
      document.getElementById('preview-img').style.background =
        `linear-gradient(135deg,${col}22,${col}55)`;
    }

    document.getElementById('preview-emoji').textContent  = emoji;
    document.getElementById('preview-name').textContent   = name;
    document.getElementById('preview-cat').textContent    = cat;
    document.getElementById('preview-time').textContent   = `⏱ ${time || '--'} min`;
    document.getElementById('preview-cal').textContent    = `🔥 ${cal  || '--'} kcal`;
    document.getElementById('preview-rating').textContent = rating;
    document.getElementById('preview-desc').textContent   = desc;

    // Stars
    const stars = Math.round(parseFloat(rating));
    document.getElementById('star-visual').textContent = '★'.repeat(stars) + '☆'.repeat(5 - stars);
    document.getElementById('star-num').textContent    = rating;

    // Char count
    const len = document.getElementById('field-desc').value.length;
    document.getElementById('char-count').textContent = len;
  }

  /* ── Submit vers la base de données ── */
  async function submitFood(e) {
    e.preventDefault();
    
    const err = document.getElementById('form-error');
    const name = document.getElementById('field-name').value.trim();
    const cat = getCategory();
    const time = document.getElementById('field-time').value;
    const cal = document.getElementById('field-cal').value;
    const rating = parseFloat(document.getElementById('field-rating').value).toFixed(1);
    const desc = document.getElementById('field-desc').value.trim();
    const emoji = selectedEmoji;

    if (!name || !cat || !time || !cal) {
      err.textContent = 'Veuillez remplir tous les champs obligatoires.';
      err.classList.add('visible');
      return;
    }
    
    if (cat === '__custom__' || cat === '') {
      err.textContent = 'Veuillez sélectionner ou saisir une catégorie valide.';
      err.classList.add('visible');
      return;
    }
    
    err.classList.remove('visible');

    // Créer FormData pour l'envoi
    const formData = new FormData();
    formData.append('name', name);
    formData.append('categorie', cat);
    formData.append('temps', time);
    formData.append('calorie', cal);
    formData.append('note', rating);
    formData.append('description', desc);
    formData.append('emoji', emoji);
    
    if (uploadedImageFile) {
      formData.append('image', uploadedImageFile);
    }

    try {
      const response = await fetch('/ajouter/save', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        showToast();
        setTimeout(() => { window.location.href = '/home'; }, 1400);
      } else {
        let errorMsg = '';
        if (result.errors) {
          errorMsg = Object.values(result.errors).join(', ');
        } else {
          errorMsg = 'Erreur lors de l\'ajout du plat';
        }
        err.textContent = errorMsg;
        err.classList.add('visible');
      }
    } catch (error) {
      err.textContent = 'Erreur de connexion au serveur';
      err.classList.add('visible');
    }
  }

  function showToast() {
    const t = document.getElementById('toast');
    t.classList.add('visible');
    setTimeout(() => t.classList.remove('visible'), 2500);
  }

  /* ── Attach submit event ── */
  document.getElementById('addFoodForm').addEventListener('submit', submitFood);

  /* ── Init preview ── */
  syncPreview();
</script>

</body>
</html>