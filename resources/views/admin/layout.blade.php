<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — LE VRAI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* ── RESET & ROOT ── */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --blue: #3a86ff;
      --red: #e63946;
      --green: #2ecc71;
      --orange: #f39c12;
      --black: #000;
      --white: #fff;
      --sidebar-w: 240px;
      --header-h: 60px;
      --bg: #f7f8fa;
      --card-bg: #fff;
      --border: #e8eaed;
      --text: #1a1a2e;
      --text-muted: #6b7280;
      --font: "Inter", sans-serif;
      --font-heading: "Bebas Neue", cursive;
      --shadow: 0 1px 4px rgba(0, 0, 0, .08);
      --shadow-md: 0 4px 20px rgba(0, 0, 0, .10);
      --radius: 8px;
    }

    body {
      font-family: var(--font);
      background: var(--bg);
      color: var(--text);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── SIDEBAR ── */
    .adm-sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: #0a0a0a;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
      transition: transform .3s ease;
    }

    .adm-logo {
      padding: 1.5rem 1.25rem 1rem;
      border-bottom: 1px solid #1a1a1a;
      font-family: var(--font-heading);
      font-size: 1.5rem;
      letter-spacing: 3px;
      color: #fff;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .adm-logo span {
      color: var(--blue);
      font-size: .6rem;
      letter-spacing: 2px;
      font-family: var(--font);
      display: block;
      margin-top: -4px
    }

    .adm-nav {
      flex: 1;
      padding: 1rem 0;
      overflow-y: auto
    }

    .adm-nav-section {
      padding: .5rem 1.25rem .25rem;
      font-size: .55rem;
      letter-spacing: 3px;
      color: #333;
      text-transform: uppercase
    }

    .adm-nav a {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .7rem 1.25rem;
      color: #555;
      text-decoration: none;
      font-size: .82rem;
      font-weight: 500;
      transition: all .15s;
      border-left: 3px solid transparent;
    }

    .adm-nav a:hover {
      color: #fff;
      background: #111;
      border-left-color: #333
    }

    .adm-nav a.active {
      color: #fff;
      background: #111;
      border-left-color: var(--blue)
    }

    .adm-nav a i {
      width: 16px;
      text-align: center;
      font-size: .85rem
    }

    .adm-sidebar-footer {
      padding: 1rem 1.25rem;
      border-top: 1px solid #1a1a1a;
      font-size: .72rem;
      color: #333;
    }

    .adm-sidebar-footer a {
      color: #555;
      text-decoration: none
    }

    .adm-sidebar-footer a:hover {
      color: #fff
    }

    /* ── MAIN ── */
    .adm-main {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh
    }

    /* ── TOPBAR ── */
    .adm-topbar {
      height: var(--header-h);
      background: var(--card-bg);
      border-bottom: var(--border) solid 1px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .adm-topbar-title {
      font-size: .95rem;
      font-weight: 600;
      color: var(--text)
    }

    .adm-topbar-right {
      display: flex;
      align-items: center;
      gap: 1rem
    }

    .adm-user-badge {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .82rem;
      color: var(--text-muted);
    }

    .adm-user-badge strong {
      color: var(--text)
    }

    .adm-btn-shop {
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: 1px;
      background: var(--blue);
      color: #fff;
      padding: .4rem 1rem;
      border-radius: 4px;
      text-decoration: none;
      transition: opacity .2s;
    }

    .adm-btn-shop:hover {
      opacity: .85
    }

    /* ── CONTENT ── */
    .adm-content {
      flex: 1;
      padding: 2rem
    }

    /* ── FLASH MESSAGES ── */
    .adm-flash {
      padding: .85rem 1.25rem;
      border-radius: var(--radius);
      margin-bottom: 1.5rem;
      font-size: .85rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: .6rem;
    }

    .adm-flash.success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #6ee7b7
    }

    .adm-flash.error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5
    }

    .adm-flash.warning {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fcd34d
    }

    /* ── STAT CARDS ── */
    .adm-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem
    }

    .adm-stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .adm-stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .adm-stat-icon.blue {
      background: #eff6ff;
      color: var(--blue)
    }

    .adm-stat-icon.red {
      background: #fff1f2;
      color: var(--red)
    }

    .adm-stat-icon.green {
      background: #f0fdf4;
      color: var(--green)
    }

    .adm-stat-icon.orange {
      background: #fffbeb;
      color: var(--orange)
    }

    .adm-stat-icon.purple {
      background: #faf5ff;
      color: #7c3aed
    }

    .adm-stat-icon.teal {
      background: #f0fdfa;
      color: #0d9488
    }

    .adm-stat-num {
      font-size: 1.6rem;
      font-weight: 700;
      line-height: 1;
      color: var(--text)
    }

    .adm-stat-label {
      font-size: .72rem;
      color: var(--text-muted);
      margin-top: 2px
    }

    /* ── CARDS ── */
    .adm-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }

    .adm-card-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .adm-card-title {
      font-size: .9rem;
      font-weight: 600
    }

    .adm-card-body {
      padding: 1.5rem
    }

    /* ── TABLE ── */
    .adm-table-wrap {
      overflow-x: auto
    }

    .adm-table {
      width: 100%;
      border-collapse: collapse;
      font-size: .82rem
    }

    .adm-table th {
      padding: .65rem 1rem;
      text-align: left;
      font-size: .65rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--text-muted);
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
      background: var(--bg);
    }

    .adm-table td {
      padding: .75rem 1rem;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    .adm-table tr:last-child td {
      border-bottom: none
    }

    .adm-table tr:hover td {
      background: #fafafa
    }

    .adm-table .prod-img {
      width: 46px;
      height: 46px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid var(--border);
    }

    /* ── BADGES ── */
    .adm-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .5px;
      padding: 3px 8px;
      border-radius: 20px;
      white-space: nowrap;
    }

    .adm-badge.ok {
      background: #d1fae5;
      color: #065f46
    }

    .adm-badge.warn {
      background: #fef3c7;
      color: #92400e
    }

    .adm-badge.danger {
      background: #fee2e2;
      color: #991b1b
    }

    .adm-badge.info {
      background: #eff6ff;
      color: #1d4ed8
    }

    .adm-badge.gray {
      background: #f3f4f6;
      color: #374151
    }

    .adm-badge.blue {
      background: #dbeafe;
      color: #1d4ed8
    }

    .adm-badge.purple {
      background: #ede9fe;
      color: #5b21b6
    }

    /* ── BUTTONS ── */
    .adm-btn {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .5rem 1rem;
      border-radius: 4px;
      font-size: .78rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      border: none;
      transition: all .15s;
      white-space: nowrap;
    }

    .adm-btn-primary {
      background: var(--blue);
      color: #fff
    }

    .adm-btn-primary:hover {
      background: #1a6bff
    }

    .adm-btn-success {
      background: var(--green);
      color: #fff
    }

    .adm-btn-success:hover {
      background: #27ae60
    }

    .adm-btn-danger {
      background: var(--red);
      color: #fff
    }

    .adm-btn-danger:hover {
      background: #c0392b
    }

    .adm-btn-outline {
      background: transparent;
      color: var(--text);
      border: 1px solid var(--border)
    }

    .adm-btn-outline:hover {
      background: var(--bg)
    }

    .adm-btn-sm {
      padding: .3rem .7rem;
      font-size: .72rem
    }

    .adm-btn-icon {
      padding: .4rem .5rem
    }

    /* ── FORM ── */
    .adm-form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem
    }

    .adm-form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.25rem
    }

    .adm-form-grid.cols3 {
      grid-template-columns: 1fr 1fr 1fr
    }

    .adm-form-group {
      display: flex;
      flex-direction: column;
      gap: .4rem
    }

    .adm-form-group.full {
      grid-column: 1/-1
    }

    .adm-label {
      font-size: .75rem;
      font-weight: 600;
      color: var(--text-muted);
      letter-spacing: .5px
    }

    .adm-input,
    .adm-select,
    .adm-textarea {
      padding: .6rem .85rem;
      border: 1px solid var(--border);
      border-radius: 4px;
      font-size: .85rem;
      font-family: var(--font);
      color: var(--text);
      background: #fff;
      transition: border-color .15s;
      width: 100%;
    }

    .adm-input:focus,
    .adm-select:focus,
    .adm-textarea:focus {
      outline: none;
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(58, 134, 255, .1);
    }

    .adm-textarea {
      min-height: 90px;
      resize: vertical
    }

    .adm-input-hint {
      font-size: .7rem;
      color: var(--text-muted)
    }

    .adm-error {
      font-size: .72rem;
      color: var(--red);
      margin-top: .2rem
    }

    .adm-errors-list {
      background: #fff1f2;
      border: 1px solid #fca5a5;
      border-radius: 6px;
      padding: 1rem 1.25rem;
      margin-bottom: 1rem
    }

    .adm-errors-list li {
      font-size: .8rem;
      color: #991b1b;
      padding: .15rem 0
    }

    /* ── IMAGE PREVIEW ── */
    .adm-img-preview {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid var(--border);
      margin-top: .5rem;
    }

    /* ── TOOLBAR / FILTERS ── */
    .adm-toolbar {
      display: flex;
      align-items: center;
      gap: .75rem;
      flex-wrap: wrap;
      margin-bottom: 1.5rem
    }

    .adm-search {
      display: flex;
      align-items: center;
      gap: .5rem;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: .4rem .75rem;
      flex: 1;
      min-width: 200px;
      max-width: 360px;
    }

    .adm-search input {
      border: none;
      outline: none;
      font-size: .85rem;
      width: 100%;
      background: transparent
    }

    .adm-search i {
      color: var(--text-muted);
      font-size: .8rem
    }

    /* ── PAGINATION ── */
    .adm-pagination {
      display: flex;
      align-items: center;
      gap: .4rem;
      padding: 1rem 0;
      justify-content: center
    }

    .adm-pagination a,
    .adm-pagination span {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 4px;
      font-size: .8rem;
      text-decoration: none;
      border: 1px solid var(--border);
      color: var(--text);
    }

    .adm-pagination span.current {
      background: var(--blue);
      color: #fff;
      border-color: var(--blue)
    }

    .adm-pagination a:hover {
      background: var(--bg)
    }

    /* ── MODAL CONFIRM ── */
    .adm-modal-bg {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .adm-modal-bg.open {
      display: flex
    }

    .adm-modal {
      background: #fff;
      border-radius: var(--radius);
      padding: 2rem;
      max-width: 400px;
      width: 90%;
      box-shadow: var(--shadow-md);
    }

    .adm-modal h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: .5rem
    }

    .adm-modal p {
      font-size: .85rem;
      color: var(--text-muted);
      margin-bottom: 1.5rem
    }

    .adm-modal-actions {
      display: flex;
      gap: .75rem;
      justify-content: flex-end
    }

    /* ── QTY INLINE EDIT ── */
    .qty-inline {
      display: flex;
      align-items: center;
      gap: .4rem;
    }

    .qty-inline input {
      width: 60px;
      padding: .3rem .4rem;
      border: 1px solid var(--border);
      border-radius: 4px;
      font-size: .82rem;
      text-align: center;
    }

    .qty-inline button {
      padding: .3rem .5rem;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: .7rem;
      font-weight: 600;
    }

    .qty-inline .save-qty {
      background: var(--green);
      color: #fff
    }

    .qty-inline .save-qty:hover {
      background: #27ae60
    }

    /* ── RESPONSIVE ── *//* ── RESPONSIVE ── */
@media(max-width:900px) {
  .adm-sidebar {
    transform: translateX(-100%);
  }
  .adm-sidebar.open {
    transform: translateX(0);
  }
  .adm-main {
    margin-left: 0;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
  }
  .adm-topbar {
    padding: 0 1rem;
  }
  .adm-content {
    padding: 1rem;
    overflow-x: hidden;
  }
  .adm-form-grid {
    grid-template-columns: 1fr;
  }
  .adm-form-grid.cols3 {
    grid-template-columns: 1fr;
  }
  .adm-hamburger {
    display: flex !important;
  }
  .adm-stats {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* ── MOBILE SMALL ── */
@media(max-width:480px) {
  .adm-stats {
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
  }
  .adm-stat-card {
    padding: 1rem;
  }
  .adm-stat-num {
    font-size: 1.3rem;
  }
  .adm-topbar-title {
    font-size: .82rem;
  }
  .adm-btn-shop span,
  .adm-user-badge strong {
    display: none;
  }
  .adm-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .adm-table {
    min-width: 540px;
  }
  .adm-toolbar {
    flex-direction: column;
    align-items: stretch;
  }
  .adm-search {
    max-width: 100%;
  }
  .adm-card-body {
    padding: 1rem;
  }
}
    .adm-hamburger {
      display: none;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      cursor: pointer;
      border: 1px solid var(--border);
      border-radius: 4px;
      font-size: 1rem;
      color: var(--text);
      background: transparent;
    }
  </style>
</head>

<body>

  {{-- SIDEBAR --}}
  <aside class="adm-sidebar" id="adm-sidebar">
    <div class="adm-logo">
      <div>
        LE VRAI
        <span>Pannello Admin</span>
      </div>
    </div>

    <nav class="adm-nav">
      <div class="adm-nav-section">Generale</div>
      <a href="{{ route('admin.dashboard') }}"
        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>

      <div class="adm-nav-section">Catalogo</div>
      <a href="{{ route('admin.prodotti') }}" class="{{ request()->routeIs('admin.prodotti*') ? 'active' : '' }}">
        <i class="fas fa-tshirt"></i> Prodotti
      </a>
      <a href="{{ route('admin.prodotti.crea') }}"
        class="{{ request()->routeIs('admin.prodotti.crea') ? 'active' : '' }}">
        <i class="fas fa-plus-circle"></i> Aggiungi prodotto
      </a>

      <div class="adm-nav-section">Vendite</div>
      <a href="{{ route('admin.ordini') }}" class="{{ request()->routeIs('admin.ordini') ? 'active' : '' }}">
        <i class="fas fa-shopping-bag"></i> Ordini
      </a>
    </nav>

    <div class="adm-sidebar-footer">
      Loggato come <strong>{{ auth()->user()->name }}</strong><br>
      <a href="{{ route('shop') }}"><i class="fas fa-store"></i> Vai al sito</a>
      &nbsp;·&nbsp;
      <a href="#" onclick="document.getElementById('logout-form').submit()"><i class="fas fa-sign-out-alt"></i>
        Esci</a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
        @csrf
      </form>
    </div>
  </aside>

  {{-- MAIN --}}
  <div class="adm-main">
    {{-- TOPBAR --}}
    <header class="adm-topbar">
      <div style="display:flex;align-items:center;gap:1rem">
        <button class="adm-hamburger" id="adm-hamburger" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
        <span class="adm-topbar-title">@yield('page-title', 'Dashboard')</span>
      </div>
      <div class="adm-topbar-right">
        <a href="{{ route('shop') }}" class="adm-btn-shop" target="_blank">
          <i class="fas fa-external-link-alt"></i> Vai allo shop
        </a>
        <div class="adm-user-badge">
          <i class="fas fa-user-shield" style="color:var(--blue)"></i>
          <strong>{{ auth()->user()->name }}</strong>
        </div>
      </div>
    </header>

    {{-- CONTENUTO --}}
    <main class="adm-content">
      @if(session('success'))
      <div class="adm-flash success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
      @endif
      @if(session('error'))
      <div class="adm-flash error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
      @endif

      @yield('content')
    </main>
  </div>

  {{-- MODAL CONFIRM ELIMINA --}}
  <div class="adm-modal-bg" id="confirm-modal">
    <div class="adm-modal">
      <h3><i class="fas fa-trash" style="color:var(--red)"></i> Conferma eliminazione</h3>
      <p id="confirm-modal-text">Sei sicuro di voler eliminare questo prodotto? L'azione non è reversibile.</p>
      <div class="adm-modal-actions">
        <button class="adm-btn adm-btn-outline" onclick="closeModal()">Annulla</button>
        <a href="#" id="confirm-modal-btn" class="adm-btn adm-btn-danger">
          <i class="fas fa-trash"></i> Elimina
        </a>
      </div>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('adm-sidebar').classList.toggle('open');
    }

    function confirmDelete(url, nome) {
      document.getElementById('confirm-modal-text').textContent =
        `Sei sicuro di voler eliminare il prodotto "${nome}"? L'immagine sarà rimossa definitivamente.`;
      document.getElementById('confirm-modal-btn').href = url;
      document.getElementById('confirm-modal').classList.add('open');
    }

    function closeModal() {
      document.getElementById('confirm-modal').classList.remove('open');
    }
    document.getElementById('confirm-modal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    // Aggiornamento quantità inline via AJAX
    function saveQty(id, btn) {
      const input = btn.closest('.qty-inline').querySelector('input');
      const qty = parseInt(input.value);
      if (isNaN(qty) || qty < 0) return;
      btn.textContent = '...';
      fetch(`/admin/prodotti/${id}/quantita`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            quantita: qty
          })
        })
        .then(r => r.json())
        .then(data => {
          btn.textContent = '✓';
          btn.style.background = '#2ecc71';
          // Aggiorna badge stato stock nella riga
          const row = btn.closest('tr');
          if (row) {
            const badge = row.querySelector('.stock-badge');
            if (badge) {
              badge.className = 'adm-badge ' + (data.stato === 'ok' ? 'ok' : data.stato === 'scarso' ?
                'warn' : 'danger');
              badge.textContent = qty === 0 ? 'Esaurito' : `${qty} pz`;
            }
          }
          setTimeout(() => {
            btn.textContent = 'Salva';
            btn.style.background = '';
          }, 1500);
        })
        .catch(() => {
          btn.textContent = 'Errore';
          btn.style.background = '#e63946';
        });
    }
  </script>

  @stack('scripts')
</body>

</html>
