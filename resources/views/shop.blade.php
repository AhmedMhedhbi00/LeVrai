@extends('layouts.app')

@section('title', 'Shop | LeVrai Streetwear')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/shop.css') }}">
@endsection


@section('scripts')
<script>
    window.prodotti = @json($prodottiFormatted);
    window.weatherData = @json($weather);
</script>
@endsection

@section('content')

@include('layouts.header')

<!-- HERO BANNER -->
<section class="hero-split">
    <div class="split-left">
        <div class="text-container">
            <span class="hero-tag">— New Season 2026</span>
            <h1 class="main-title">
                URBAN<br>
                <span class="outline-white">STREET</span><br>
                WEAR.
            </h1>
            <p>Identità urbana, qualità senza compromessi. Scopri il drop esclusivo di questa stagione.</p>
            <div class="cta-group">
                <a href="#shop-area" class="btn-main">SHOP THE DROP</a>
                <a href="#shop-area" class="btn-link">New Arrivals →</a>
            </div>
        </div>
    </div>
    <div class="split-right">
        <video autoplay loop muted playsinline class="video-side">
            <source src="{{ asset('assets/images/PromotionalVideo.mp4') }}" type="video/mp4">
        </video>
        <div class="media-overlay"></div>
    </div>
</section>

<!-- PERCHÉ LEVRAI -->
<section id="perche-levrai">
    <h2>PERCHÉ<br><span class="outline-dark">LEVRAI.</span></h2>
    <div class="perche-grid">
        <div class="perche-card">
            <i class="fas fa-shipping-fast"></i>
            <h3>SPEDIZIONE RAPIDA</h3>
            <p>Consegna in 24/48h in tutta Italia, tracciabile in tempo reale.</p>
        </div>
        <div class="perche-card">
            <i class="fas fa-sync-alt"></i>
            <h3>RESO FACILE</h3>
            <p>30 giorni per cambiare idea, nessuna domanda.</p>
        </div>
        <div class="perche-card">
            <i class="fas fa-medal"></i>
            <h3>QUALITÀ PREMIUM</h3>
            <p>Materiali selezionati e finiture curate per durare nel tempo.</p>
        </div>
        <div class="perche-card">
            <i class="fas fa-lock"></i>
            <h3>PAGAMENTI SICURI</h3>
            <p>Checkout protetto con crittografia avanzata.</p>
        </div>
    </div>
</section>

<!-- ============================================================
     LE VRAI — SEZIONE BRAND ESCLUSIVA
     Prodotti con brand "Le Vrai" presi dal DB
============================================================ -->
<section id="levrai-brand-section" class="lvb-section">

    {{-- ── HERO MANIFESTO ── --}}
    <div class="lvb-hero">
        <div class="lvb-hero-bg">
            <div class="lvb-noise"></div>
            <div class="lvb-scanlines"></div>
        </div>
        <div class="lvb-hero-inner">
            <div class="lvb-hero-left">
                <span class="lvb-eyebrow" data-lvb-anim="fade-up">— Collezione Proprietaria —</span>
                <h2 class="lvb-bigtitle" data-lvb-anim="fade-up" data-lvb-delay="100">
                    LE<br>
                    <em>VRAI</em><br>
                    BRAND.
                </h2>
                <p class="lvb-desc" data-lvb-anim="fade-up" data-lvb-delay="200">
                    Non un semplice negozio. <strong>Le Vrai</strong> è il nostro brand.<br>
                    Capi selezionati, curati e marchiati direttamente da noi.<br>
                    Autenticità garantita, stile senza compromessi.
                </p>
                <div class="lvb-hero-pills" data-lvb-anim="fade-up" data-lvb-delay="300">
                    <span class="lvb-pill">QUALITÀ CERTIFICATA</span>
                    <span class="lvb-pill">EDIZIONE LIMITATA</span>
                    <span class="lvb-pill active">SS26 DROP</span>
                </div>
                <a href="#lvb-grid" class="lvb-hero-cta" data-lvb-anim="fade-up" data-lvb-delay="400">
                    ESPLORA LA COLLEZIONE <i class="fas fa-arrow-down"></i>
                </a>
            </div>
            <div class="lvb-hero-right" data-lvb-anim="fade-left" data-lvb-delay="150">
                <div class="lvb-hero-frame">
                    <div class="lvb-frame-corner tl"></div>
                    <div class="lvb-frame-corner tr"></div>
                    <div class="lvb-frame-corner bl"></div>
                    <div class="lvb-frame-corner br"></div>
                    <div class="lvb-logo-giant">LV</div>
                    <div class="lvb-year-badge">EST.<br>2025</div>
                    <div class="lvb-stat-stack">
                        <div class="lvb-stat"><span class="lvb-stat-num">20</span><span
                                class="lvb-stat-label">PRODOTTI</span></div>
                        <div class="lvb-stat"><span class="lvb-stat-num">SS26</span><span
                                class="lvb-stat-label">STAGIONE</span></div>
                        <div class="lvb-stat"><span class="lvb-stat-num">100%</span><span
                                class="lvb-stat-label">AUTENTICO</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TICKER --}}
        <div class="lvb-ticker-bar">
            <div class="lvb-ticker-track">
                @foreach(range(1,4) as $__)
                <span>LE VRAI</span><span class="lvb-dot">✦</span>
                <span>AUTENTICO PER NATURA</span><span class="lvb-dot">✦</span>
                <span>BRAND ESCLUSIVO</span><span class="lvb-dot">✦</span>
                <span>SS26 COLLECTION</span><span class="lvb-dot">✦</span>
                <span>MADE FOR THE STREETS</span><span class="lvb-dot">✦</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── VALORI DEL BRAND ── --}}
    <div class="lvb-values-row">
        <div class="lvb-value-card" data-lvb-anim="fade-up" data-lvb-delay="0">
            <div class="lvb-value-icon"><i class="fas fa-gem"></i></div>
            <div class="lvb-value-num">01</div>
            <h4 class="lvb-value-title">QUALITÀ PREMIUM</h4>
            <p class="lvb-value-text">Ogni capo Le Vrai è selezionato con cura. Materiali superiori, finiture
                impeccabili.</p>
        </div>
        <div class="lvb-value-card" data-lvb-anim="fade-up" data-lvb-delay="100">
            <div class="lvb-value-icon"><i class="fas fa-fire"></i></div>
            <div class="lvb-value-num">02</div>
            <h4 class="lvb-value-title">DROP LIMITATI</h4>
            <p class="lvb-value-text">Quantità controllate per garantire esclusività. Quando finisce, finisce.</p>
        </div>
        <div class="lvb-value-card" data-lvb-anim="fade-up" data-lvb-delay="200">
            <div class="lvb-value-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="lvb-value-num">03</div>
            <h4 class="lvb-value-title">100% AUTENTICO</h4>
            <p class="lvb-value-text">Niente fake, niente copie. Il marchio Le Vrai è sinonimo di autenticità assoluta.
            </p>
        </div>
        <div class="lvb-value-card" data-lvb-anim="fade-up" data-lvb-delay="300">
            <div class="lvb-value-icon"><i class="fas fa-bolt"></i></div>
            <div class="lvb-value-num">04</div>
            <h4 class="lvb-value-title">IDENTITÀ URBANA</h4>
            <p class="lvb-value-text">Non seguiamo le mode. Le Vrai definisce il proprio linguaggio estetico.</p>
        </div>
    </div>

    {{-- ── GRIGLIA PRODOTTI LE VRAI ── --}}
    <div class="lvb-products-wrap" id="lvb-grid">
        <div class="lvb-products-head">
            <div class="lvb-products-head-left">
                <span class="lvb-eyebrow">— I Nostri Pezzi —</span>
                <h3 class="lvb-products-title">COLLEZIONE <em>LE VRAI</em></h3>
            </div>
            <div class="lvb-products-head-right">
                <span class="lvb-count-badge" id="lvb-count"></span>
                <div class="lvb-filter-pills">
                    <button class="lvb-fpill active" data-lvb-cat="tutti">Tutti</button>
                    <button class="lvb-fpill" data-lvb-cat="abbigliamento">Abbigliamento</button>
                    <button class="lvb-fpill" data-lvb-cat="altro">Accessori</button>
                </div>
            </div>
        </div>

        <div class="shop-product-grid cols-3" id="lvb-product-grid"></div>

        <div class="lvb-empty" id="lvb-empty" style="display:none;">
            <i class="fas fa-box-open"></i>
            <p>Nessun prodotto trovato per questa categoria.</p>
        </div>
    </div>

    {{-- ── FOOTER BAR ── --}}
    <div class="lvb-footer-bar">
        <span class="lvb-footer-tag">Le Vrai Streetwear — Il nostro brand, la nostra identità</span>
        <a href="#shop-area" class="lvb-footer-cta">
            VEDI TUTTI I BRAND <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</section>
<section id="collezioni-scroll">
    <h2>COLLEZIONI<br><span class="outline-black">IN EVIDENZA.</span></h2>
    <div class="collezioni-wrapper">
        <div class="collezioni-track">

            <div class="collezione-card" data-category="abbigliamento" onclick="handleCollectionClick('abbigliamento')">
                <img src="{{ asset('assets/images/nocta nike 3.jpg') }}" alt="Urban Essentials" loading="lazy">
                <div class="collezione-overlay">
                    <h3>Urban Essentials</h3>
                    <button type="button">SCOPRI <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
                </div>
            </div>

            <div class="collezione-card card-manifesto">
                <div class="manifesto-top">
                    <span
                        style="color: #3a86ff; font-family: 'Bebas Neue', sans-serif; font-size: 1.2rem; letter-spacing: 2px;">LV
                        / CONCEPT</span>
                    <p
                        style="color: #fff; font-size: 1.1rem; line-height: 1.6; font-weight: 300; margin-top: 2rem; font-style: italic; font-family: sans-serif;">
                        "Distruggiamo la linea sottile che separa l'alta moda dalla cultura della strada. Pezzi
                        numerati, concepiti per l'asfalto."
                    </p>
                </div>
                <div class="manifesto-bottom">
                    <span style="color: #444; font-family: monospace; font-size: 0.8rem; letter-spacing: 1px;">© LE VRAI
                        STUDIO SRL</span>
                </div>
            </div>

            <div class="collezione-card" data-category="abbigliamento" onclick="handleCollectionClick('abbigliamento')">
                <img src="{{ asset('assets/images/section1.jpg') }}" alt="Active Street" loading="lazy">
                <div class="collezione-overlay">
                    <h3>Active Street</h3>
                    <button type="button">SCOPRI <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
                </div>
            </div>

            <div class="collezione-card" data-category="abbigliamento" onclick="handleCollectionClick('abbigliamento')">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
                    <source src="{{ asset('assets/images/PromotionalVideo.mp4') }}" type="video/mp4">
                </video>
                <div class="collezione-overlay">
                    <span
                        style="position: absolute; top: 2rem; left: 2rem; background: #e53935; color: #fff; padding: 0.2rem 0.6rem; font-family: sans-serif; font-size: 0.7rem; font-weight: bold; letter-spacing: 1px;">LIVE
                        LOOK</span>
                    <h3>Behind The Drop</h3>
                    <button type="button">VEDI LOOK <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
                </div>
            </div>

            <div class="collezione-card" data-category="scarpe" onclick="handleCollectionClick('scarpe')">
                <img src="{{ asset('assets/images/section2.jpg') }}" alt="Limited Edition" loading="lazy">
                <div class="collezione-overlay">
                    <h3>Limited Edition</h3>
                    <button type="button">SCOPRI <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
                </div>
            </div>

            <div class="collezione-card" data-category="altro" onclick="handleCollectionClick('altro')">
                <img src="{{ asset('assets/images/section3.jpg') }}" alt="Accessori Must-Have" loading="lazy">
                <div class="collezione-overlay">
                    <h3>Accessori Must-Have</h3>
                    <button type="button">SCOPRI <i class="fas fa-arrow-right" style="margin-left:6px;"></i></button>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- ============================================================
     SHOP AREA — Sidebar + Griglia dinamica
============================================================ -->
<section id="shop-area">

    <!-- MOBILE FILTER BAR -->
    <div class="mobile-filter-bar">
        <button class="mobile-filter-btn" id="mobile-sidebar-toggle">
            <i class="fas fa-sliders-h"></i> FILTRI
            <span class="mobile-active-count" id="mobile-active-count" style="display:none;">0</span>
        </button>
        <div class="mobile-cat-pills" id="mobile-cat-pills">
            <button class="cat-pill active" data-cat="tutti">Tutti</button>
            <button class="cat-pill" data-cat="abbigliamento">Abbigliamento</button>
            <button class="cat-pill" data-cat="scarpe">Scarpe</button>
            <button class="cat-pill" data-cat="altro">Accessori</button>
        </div>
        <span class="mobile-results-count" id="mobile-results-count">0 prodotti</span>
    </div>

    <div class="shop-layout">

        <!-- SIDEBAR -->
        <aside class="shop-sidebar" id="shop-sidebar">

            <!-- Overlay mobile -->
            <div class="sidebar-overlay" id="sidebar-overlay"></div>

            <div class="sidebar-panel" id="sidebar-panel">

                <div class="sidebar-header">
                    <span class="sidebar-title">FILTRI</span>
                    <button class="sidebar-close-btn" id="sidebar-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- CATEGORIE -->
                <div class="sidebar-block">
                    <button class="sidebar-block-toggle" data-target="block-cat">
                        CATEGORIA <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-block-body open" id="block-cat">
                        <ul class="sidebar-cat-list" id="sidebar-cat-list">
                            <li>
                                <button class="sidebar-cat-btn active" data-cat="tutti">
                                    <span class="cat-dot"></span>
                                    Tutti i prodotti
                                    <span class="cat-count-badge" id="count-tutti"></span>
                                </button>
                            </li>
                            <li>
                                <button class="sidebar-cat-btn" data-cat="abbigliamento">
                                    <span class="cat-dot"></span>
                                    Abbigliamento
                                    <span class="cat-count-badge" id="count-abbigliamento"></span>
                                </button>
                            </li>
                            <li>
                                <button class="sidebar-cat-btn" data-cat="scarpe">
                                    <span class="cat-dot"></span>
                                    Scarpe
                                    <span class="cat-count-badge" id="count-scarpe"></span>
                                </button>
                            </li>
                            <li>
                                <button class="sidebar-cat-btn" data-cat="altro">
                                    <span class="cat-dot"></span>
                                    Accessori & Altro
                                    <span class="cat-count-badge" id="count-altro"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CERCA -->
                <div class="sidebar-block">
                    <button class="sidebar-block-toggle" data-target="block-search">
                        CERCA <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-block-body open" id="block-search">
                        <div class="sidebar-search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="filter-search" placeholder="Nome prodotto...">
                            <button class="sidebar-search-clear" id="search-clear" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- TAGLIA -->
                <div class="sidebar-block">
                    <button class="sidebar-block-toggle" data-target="block-size">
                        TAGLIA <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-block-body open" id="block-size">
                        <div class="size-grid" id="filter-size">
                            <button class="size-chip active" data-size="tutte">Tutte</button>
                            <button class="size-chip" data-size="XS">XS</button>
                            <button class="size-chip" data-size="S">S</button>
                            <button class="size-chip" data-size="M">M</button>
                            <button class="size-chip" data-size="L">L</button>
                            <button class="size-chip" data-size="XL">XL</button>
                            <button class="size-chip" data-size="XXL">XXL</button>
                            <button class="size-chip" data-size="40">40</button>
                            <button class="size-chip" data-size="41">41</button>
                            <button class="size-chip" data-size="42">42</button>
                            <button class="size-chip" data-size="43">43</button>
                            <button class="size-chip" data-size="44">44</button>
                            <button class="size-chip" data-size="45">45</button>
                        </div>
                    </div>
                </div>

                <!-- PREZZO -->
                <div class="sidebar-block">
                    <button class="sidebar-block-toggle" data-target="block-price">
                        PREZZO <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-block-body open" id="block-price">
                        <div class="price-display">
                            <span>€0</span>
                            <strong id="price-val">€500</strong>
                        </div>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" min="0" max="500" value="500" step="10">
                        </div>
                    </div>
                </div>

                <!-- ORDINA -->
                <div class="sidebar-block">
                    <button class="sidebar-block-toggle" data-target="block-sort">
                        ORDINA PER <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-block-body open" id="block-sort">
                        <ul class="sort-list" id="filter-sort">
                            <li><button class="sort-btn active" data-sort="default">Rilevanza</button></li>
                            <li><button class="sort-btn" data-sort="price-asc">Prezzo crescente</button></li>
                            <li><button class="sort-btn" data-sort="price-desc">Prezzo decrescente</button></li>
                            <li><button class="sort-btn" data-sort="name">A – Z</button></li>
                            <li><button class="sort-btn" data-sort="discount">Maggior sconto</button></li>
                        </ul>
                    </div>
                </div>

                <!-- RESET -->
                <div class="sidebar-footer">
                    <button class="sidebar-reset-btn" id="filter-reset">
                        <i class="fas fa-undo"></i> Azzera filtri
                    </button>
                </div>

            </div><!-- /sidebar-panel -->
        </aside>

        <!-- MAIN GRIGLIA -->
        <div class="shop-main">
            <div class="shop-area-header">
                <h2 class="section-title-brutal">
                    <span>SHOP</span>
                    <span class="outline-black">AREA.</span>
                </h2>
            </div>
            <!-- Toolbar -->
            <div class="shop-toolbar">
                <div class="toolbar-left">
                    <span class="toolbar-title" id="toolbar-category-label">Tutti i prodotti</span>
                    <span class="toolbar-count" id="results-count">0 prodotti</span>
                </div>
                <div class="toolbar-right">
                    <!-- Tag filtri attivi -->
                    <div class="active-filters" id="active-filters"></div>
                    <!-- Toggle griglia -->
                    <div class="grid-toggle">
                        <button class="grid-btn active" data-cols="3" title="3 colonne">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="grid-btn" data-cols="4" title="4 colonne">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Griglia prodotti -->
            <div class="shop-product-grid cols-3" id="filtered-grid"></div>

            <!-- Nessun risultato -->
            <div id="no-results" class="no-results-msg" style="display:none;">
                <i class="fas fa-search"></i>
                <p>Nessun prodotto trovato.</p>
                <button class="sidebar-reset-btn" id="no-results-reset">
                    <i class="fas fa-undo"></i> Azzera filtri
                </button>
            </div>

        </div><!-- /shop-main -->
    </div><!-- /shop-layout -->
</section>



<!-- GUIDA TAGLIE -->
<section class="size-guide">
    <div class="size-guide-header">
        <h2>GUIDA ALLE TAGLIE.</h2>
        <p>Seleziona il capo per visualizzare le misure corrette</p>
    </div>
    <div class="size-switch">
        <button class="size-btn active" data-size="shirt">MAGLIETTA</button>
        <button class="size-btn" data-size="pants">PANTALONI</button>
    </div>
    <div class="size-icons" id="shirt"></div>
    <div class="size-icons" id="pants"></div>
    <div class="size-table active" id="shirt-table">
        <table>
            <tr>
                <th>Taglia</th>
                <th>Petto (cm)</th>
                <th>Vita (cm)</th>
            </tr>
            <tr>
                <td>XS</td>
                <td>80–86</td>
                <td>66–71</td>
            </tr>
            <tr>
                <td>S</td>
                <td>86–91</td>
                <td>71–76</td>
            </tr>
            <tr>
                <td>M</td>
                <td>91–96</td>
                <td>76–81</td>
            </tr>
            <tr>
                <td>L</td>
                <td>96–101</td>
                <td>81–86</td>
            </tr>
            <tr>
                <td>XL</td>
                <td>101–106</td>
                <td>86–91</td>
            </tr>
            <tr>
                <td>XXL</td>
                <td>106–112</td>
                <td>91–97</td>
            </tr>
        </table>
    </div>
    <div class="size-table" id="pants-table">
        <table>
            <tr>
                <th>Taglia</th>
                <th>Vita (cm)</th>
                <th>Lunghezza (cm)</th>
            </tr>
            <tr>
                <td>XS</td>
                <td>66–71</td>
                <td>98</td>
            </tr>
            <tr>
                <td>S</td>
                <td>71–76</td>
                <td>100</td>
            </tr>
            <tr>
                <td>M</td>
                <td>76–81</td>
                <td>102</td>
            </tr>
            <tr>
                <td>L</td>
                <td>81–86</td>
                <td>104</td>
            </tr>
            <tr>
                <td>XL</td>
                <td>86–91</td>
                <td>106</td>
            </tr>
            <tr>
                <td>XXL</td>
                <td>91–97</td>
                <td>108</td>
            </tr>
        </table>
    </div>
</section>

<!-- LIMITED DROP -->
<section id="drop-section">
    <div class="drop-container">
        <div class="drop-content">
            <span class="drop-badge">LIMITED DROP</span>
            <h2>URBAN<br>ESSENTIALS<br>LIMITED.</h2>
            <p>Una collezione esclusiva, disponibile per pochi giorni. Qualità premium, quantità limitate.</p>
            <div class="countdown" data-date="2026-06-30T23:59:59">
                <div><span class="days">00</span><small>Giorni</small></div>
                <div><span class="hours">00</span><small>Ore</small></div>
                <div><span class="minutes">00</span><small>Min</small></div>
                <div><span class="seconds">00</span><small>Sec</small></div>
            </div>
        </div>
        <div class="drop-image">
            <img src="{{ asset('assets/images/nocta nike 3.jpg') }}" alt="Limited Drop" loading="lazy">
        </div>
    </div>
</section>

<!-- POPUP ORDINI -->
<div id="orders-popup" class="orders-popup">
    <div class="orders-header">
        <h3><i class="fas fa-box"></i> I MIEI ORDINI</h3>
        <button class="orders-close">&times;</button>
    </div>
    <div class="orders-body">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ordine</th>
                    <th>Prodotto</th>
                    <th>Taglia</th>
                    <th>Q.tà</th>
                    <th>Prezzo</th>
                </tr>
            </thead>
            <tbody id="orders-list"></tbody>
        </table>
    </div>
</div>
<div class="orders-overlay"></div>

@include('layouts.footer')

@endsection