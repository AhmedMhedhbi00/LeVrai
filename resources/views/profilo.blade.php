@extends('layouts.app')

@section('title', 'Profilo | LeVrai Streetwear')

@section('styles')
<style>
/* Mantieni i tuoi stili originali qui, sono ottimi */
body {
    background: #000;
    color: #fff;
}

.profile-topbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #000;
    border-bottom: 1px solid #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    height: 60px;
}

.topbar-logo {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.4rem;
    letter-spacing: 4px;
    color: #fff;
}

.topbar-logo span {
    color: #3a86ff;
}

.topbar-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.85rem;
    letter-spacing: 2px;
    color: #555;
    border: 1px solid #1a1a1a;
    padding: 0.45rem 1rem;
    transition: all 0.2s;
    cursor: pointer;
    background: none;
    text-decoration: none;
}

.topbar-back:hover {
    color: #fff;
    border-color: #fff;
}

.profile-layout {
    display: flex;
    min-height: calc(100vh - 60px);
}

.profile-sidebar {
    width: 280px;
    flex-shrink: 0;
    background: #0d0d0d;
    border-right: 1px solid #1a1a1a;
    display: flex;
    flex-direction: column;
    padding: 2.5rem 0;
    position: sticky;
    top: 60px;
    height: calc(100vh - 60px);
    overflow-y: auto;
}

.sidebar-avatar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1.5rem 2rem;
    border-bottom: 1px solid #1a1a1a;
    margin-bottom: 1.5rem;
}

.avatar-box {
    position: relative;
    width: 100px;
    height: 100px;
    margin-bottom: 1rem;
}

.avatar-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 2px solid #3a86ff;
    display: block;
    border-radius: 4px;
}

.avatar-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 28px;
    height: 28px;
    background: #3a86ff;
    border: 2px solid #000;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
}

#avatar-input {
    display: none;
}

.sidebar-name {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.3rem;
    letter-spacing: 2px;
    color: #fff;
    text-align: center;
}

.sidebar-role {
    font-size: 0.65rem;
    letter-spacing: 3px;
    color: #3a86ff;
    text-transform: uppercase;
    text-align: center;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    padding: 0 1rem;
    gap: 2px;
    flex: 1;
}

.sidebar-nav button {
    width: 100%;
    background: none;
    border: none;
    color: #555;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.95rem;
    letter-spacing: 2px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    text-align: left;
    border-left: 2px solid transparent;
}

.sidebar-nav button.active {
    color: #fff;
    background: rgba(58, 134, 255, 0.06);
    border-left-color: #3a86ff;
}

.profile-content {
    flex: 1;
    padding: 3rem;
    overflow-y: auto;
    max-width: 900px;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
    animation: fadeIn 0.2s ease;
}

.section-title {
    font-family: 'Bebas Neue', cursive;
    font-size: clamp(2rem, 4vw, 3rem);
    letter-spacing: 3px;
    color: #fff;
    line-height: 0.9;
    margin-bottom: 0.4rem;
}

.section-title span {
    -webkit-text-stroke: 1px #fff;
    color: transparent;
}

.section-sub {
    font-size: 0.8rem;
    color: #555;
    letter-spacing: 1px;
    margin-bottom: 2.5rem;
    text-transform: uppercase;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-top: 1px solid #1a1a1a;
    border-left: 1px solid #1a1a1a;
    margin-bottom: 2.5rem;
}

.stat-card {
    border-right: 1px solid #1a1a1a;
    border-bottom: 1px solid #1a1a1a;
    padding: 1.8rem 1.5rem;
    background: #0d0d0d;
}

.stat-value {
    font-family: 'Bebas Neue', cursive;
    font-size: 2.2rem;
    color: #fff;
    display: block;
    line-height: 1;
}

.stat-label {
    font-size: 0.62rem;
    color: #555;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.profile-card {
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.card-title {
    font-family: 'Bebas Neue', cursive;
    font-size: 0.8rem;
    letter-spacing: 3px;
    color: #3a86ff;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #1a1a1a;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.form-group label {
    font-size: 0.6rem;
    letter-spacing: 2px;
    color: #555;
    text-transform: uppercase;
}

.form-group input {
    width: 100%;
    padding: 0.7rem 0.8rem;
    background: #000;
    border: none;
    border-bottom: 2px solid #1a1a1a;
    color: #fff;
    outline: none;
}

.btn-save {
    background: #3a86ff;
    color: #fff;
    border: none;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.95rem;
    letter-spacing: 3px;
    padding: 0.85rem 2rem;
    cursor: pointer;
    margin-top: 1.5rem;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    font-family: 'Bebas Neue', cursive;
    letter-spacing: 1px;
}

.alert-success {
    background: rgba(58, 134, 255, 0.1);
    color: #3a86ff;
    border-left: 4px solid #3a86ff;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-top: 1px solid #1a1a1a;
    border-left: 1px solid #1a1a1a;
}

.info-item {
    border-right: 1px solid #1a1a1a;
    border-bottom: 1px solid #1a1a1a;
    padding: 1rem;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* --- RESPONSIVE E PERFORMANCE PRO --- */

@media (max-width: 992px) {
    .profile-sidebar {
        width: 80px;
        /* Sidebar ridotta a icone per tablet */
    }

    .sidebar-name,
    .sidebar-role,
    .sidebar-nav button span {
        display: none;
        /* Nascondiamo i testi, lasciamo le icone */
    }

    .sidebar-nav button {
        justify-content: center;
        padding: 1.5rem 0;
    }
}

@media (max-width: 768px) {
    .profile-layout {
        flex-direction: column;
        /* Sidebar sopra, contenuto sotto */
    }

    .profile-sidebar {
        width: 100%;
        height: auto;
        position: relative;
        top: 0;
        padding: 1rem 0;
        border-right: none;
        border-bottom: 1px solid #1a1a1a;
    }

    .sidebar-avatar-wrap {
        flex-direction: row;
        /* Avatar e Nome affiancati su iPhone */
        padding: 0 1.5rem 1rem;
        margin-bottom: 0.5rem;
        border-bottom: none;
        justify-content: flex-start;
        gap: 15px;
    }

    .avatar-box {
        width: 60px;
        height: 60px;
        margin-bottom: 0;
    }

    .sidebar-name {
        display: block;
        /* Torna visibile su mobile */
        text-align: left;
        font-size: 1.1rem;
    }

    .sidebar-role {
        display: block;
        text-align: left;
    }

    .sidebar-nav {
        flex-direction: row;
        /* Menu orizzontale a scorrimento */
        overflow-x: auto;
        padding: 0 1rem;
        gap: 10px;
        -webkit-overflow-scrolling: touch;
    }

    .sidebar-nav::-webkit-scrollbar {
        display: none;
        /* Nascondiamo la scrollbar brutta */
    }

    .sidebar-nav button {
        width: auto;
        white-space: nowrap;
        /* Non manda a capo il testo dei pulsanti */
        padding: 0.8rem 1.2rem;
        border-left: none;
        border-bottom: 2px solid transparent;
    }

    .sidebar-nav button span {
        display: inline;
        /* Il testo dei bottoni torna visibile */
    }

    .sidebar-nav button.active {
        border-left: none;
        border-bottom-color: #3a86ff;
        background: rgba(58, 134, 255, 0.1);
    }

    .sidebar-divider,
    form[action*="logout"] {
        display: none;
        /* Nascondiamo elementi extra per pulizia su mobile */
    }

    .profile-content {
        padding: 2rem 1.5rem;
    }

    .section-title {
        font-size: 2.2rem;
    }

    /* Grid che diventano a colonna singola */
    .stats-grid {
        grid-template-columns: 1fr;
        /* Una statistica per riga per leggibilità */
    }

    .form-grid,
    .info-grid {
        grid-template-columns: 1fr;
    }

    .btn-save {
        width: 100%;
        /* Bottone gigante facile da tappare */
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<!-- TOP BAR -->
<header class="profile-topbar">
    <div class="topbar-logo">LE<span>VRAI</span></div>
    <a href="{{ route('shop') }}" class="topbar-back">
        <i class="fas fa-arrow-left"></i> TORNA ALLO SHOP
    </a>
</header>

<div class="profile-layout">
    <!-- SIDEBAR -->
    <aside class="profile-sidebar">
        <div class="sidebar-avatar-wrap">
            <div class="avatar-box">
                {{-- LOGICA AVATAR GOOGLE VS LOCALE --}}
                <img id="avatar-preview"
                    src="{{ $user->profile_picture ? (Str::startsWith($user->profile_picture, 'http') ? $user->profile_picture : asset('assets/images/profilo/' . $user->profile_picture)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3a86ff&color=fff' }}"
                    alt="Avatar">
                <label class="avatar-edit-btn" for="avatar-input">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <div>
                <div class="sidebar-name">{{ strtoupper($user->name) }}</div>
                <div class="sidebar-role">{{ $user->role ?? 'CLIENTE' }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <button class="active" onclick="switchTab('overview', this)">
                <i class="fas fa-home"></i> PANORAMICA
            </button>
            <button onclick="switchTab('personal', this)">
                <i class="fas fa-user"></i> DATI PERSONALI
            </button>
            <button onclick="switchTab('security', this)">
                <i class="fas fa-lock"></i> SICUREZZA
            </button>
            <button onclick="switchTab('orders', this)">
                <i class="fas fa-box"></i> ORDINI ({{ $tot_ordini ?? 0 }})
            </button>
        </nav>

        <div style="padding: 2rem 1rem 0;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="background:none;border:none;color:#e63946;font-family:'Bebas Neue',cursive;letter-spacing:2px;cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i> LOGOUT
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="profile-content">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- TAB: PANORAMICA -->
        <div class="tab-panel active" id="tab-overview">
            <h1 class="section-title">CIAO,<br><span>{{ strtoupper(explode(' ', $user->name)[0]) }}</span></h1>
            <p class="section-sub">Riepilogo del tuo account streetwear</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-value">{{ $tot_ordini ?? 0 }}</span>
                    <span class="stat-label">Ordini</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">€{{ number_format($tot_spesa ?? 0, 0) }}</span>
                    <span class="stat-label">Spesi</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ $tot_prodotti ?? 0 }}</span>
                    <span class="stat-label">Pezzi</span>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-title"><i class="fas fa-id-card"></i> Info Account</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div style="font-size:0.6rem;color:#555;">EMAIL</div>
                        <div style="font-family:'Bebas Neue';">{{ $user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div style="font-size:0.6rem;color:#555;">MEMBRO DAL</div>
                        <div style="font-family:'Bebas Neue';">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TAB: DATI PERSONALI -->
        <div class="tab-panel" id="tab-personal">
            <h1 class="section-title">DATI<br><span>PERSONALI</span></h1>

            {{-- Mostra Errori di Validazione --}}
            @if ($errors->any())
            <div class="alert"
                style="background: rgba(230, 57, 70, 0.1); color: #e63946; border-left: 4px solid #e63946; margin-bottom: 2rem;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle"></i> {{ strtoupper($error) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('profilo.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" id="avatar-input" name="profile_picture" accept="image/*">

                <div class="profile-card">
                    <div class="card-title"><i class="fas fa-user"></i> Informazioni Base</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Cognome</label>
                            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Username (Display Name)</label>
                            <input type="text" name="username" value="{{ old('username', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="card-title"><i class="fas fa-map-marker-alt"></i> Indirizzo di Spedizione</div>
                    <div class="form-grid">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Indirizzo</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="form-group">
                            <label>Città</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}">
                        </div>
                        <div class="form-group">
                            <label>CAP</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save">AGGIORNA PROFILO</button>
            </form>
        </div>
        <!-- TAB: SICUREZZA -->
        <div class="tab-panel" id="tab-security">
            <h1 class="section-title">SICU<br><span>REZZA</span></h1>
            @if($user->password)
            <form method="POST" action="{{ route('profilo.password') }}">
                @csrf
                <div class="profile-card">
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label>Password Attuale</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Nuova Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                </div>
                <button type="submit" class="btn-save">AGGIORNA PASSWORD</button>
            </form>
            @else
            <div class="alert alert-success">Sei collegato con Google. Non hai bisogno di una password.</div>
            @endif
        </div>
        <!-- TAB: ORDINI -->
        <div class="tab-panel" id="tab-orders">
            <h1 class="section-title">I MIEI<br><span>ORDINI</span></h1>
            @if(isset($ordini) && count($ordini) > 0)
            @foreach($ordini as $ordine)
            <div class="profile-card" style="border-left: 4px solid #3a86ff;">
                <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-family: 'Bebas Neue'; color: #3a86ff; font-size: 1.2rem;">ORDINE
                            #{{ $ordine['id'] }}</div>
                        <div style="font-size: 0.7rem; color: #555;">
                            {{ \Carbon\Carbon::parse($ordine['created_at'])->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div
                        style="background: rgba(58, 134, 255, 0.1); color: #3a86ff; padding: 4px 12px; font-size: 0.7rem; font-family: 'Bebas Neue'; letter-spacing: 1px;">
                        {{ strtoupper($ordine['stato']) }}
                    </div>
                </div>

                <div
                    style="margin: 1rem 0; padding: 1rem 0; border-top: 1px solid #1a1a1a; border-bottom: 1px solid #1a1a1a;">
                    @foreach($ordine['prodotti'] as $p)
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.8rem;">
                        @if(!empty($p['immagine']))
                        <img src="{{ asset('assets/images/prodotti/' . $p['immagine']) }}" alt="{{ $p['nome'] }}"
                            style="width: 54px; height: 54px; object-fit: cover; border: 1px solid #1a1a1a; flex-shrink:0;">
                        @endif
                        <div style="flex:1; font-size: 0.82rem;">
                            <div style="color:#ccc;">{{ $p['nome'] }}</div>
                            <div style="color:#555; font-size:0.72rem; letter-spacing:1px;">
                                TAGLIA: {{ $p['taglia'] }} &nbsp;·&nbsp; QTÀ: {{ $p['quantita'] }}
                            </div>
                        </div>
                        <span style="color:#aaa; font-family:'Bebas Neue'; font-size:1rem;">
                            €{{ number_format($p['prezzo'] * $p['quantita'], 2) }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.7rem; color: #555;">TOTALE PAGATO</span>
                    <span
                        style="font-family: 'Bebas Neue'; font-size: 1.4rem;">€{{ number_format($ordine['totale'], 2) }}</span>
                </div>
            </div>
            @endforeach
            @else
            <div style="text-align: center; padding: 4rem 0; border: 1px dashed #1a1a1a;">
                <i class="fas fa-shopping-bag"
                    style="font-size: 2rem; color: #1a1a1a; margin-bottom: 1rem; display: block;"></i>
                <p style="color:#555; letter-spacing:2px; font-family: 'Bebas Neue';">NESSUN ORDINE TROVATO</p>
                <a href="{{ route('shop') }}" class="topbar-back" style="margin-top: 1rem;">VAI ALLO SHOP</a>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
// Aggiungi questo nel tuo <script> per gestire il cambio tab tramite URL (es: sito.it/profilo#orders)
window.addEventListener('hashchange', function() {
    const hash = window.location.hash.substring(1);
    if (hash) {
        const btn = document.querySelector(`button[onclick*="'${hash}'"]`);
        if (btn) btn.click();
    }
});

// Al caricamento della pagina
if (window.location.hash) {
    const initialTab = window.location.hash.substring(1);
    const btn = document.querySelector(`button[onclick*="'${initialTab}'"]`);
    if (btn) btn.click();
}

function switchTab(tab, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sidebar-nav button').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    if (btn) btn.classList.add('active');
}

document.getElementById('avatar-input').addEventListener('change', function() {
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
    reader.readAsDataURL(this.files[0]);
});
</script>
@endsection