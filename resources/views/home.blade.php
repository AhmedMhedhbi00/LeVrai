@extends('layouts.app')

@section('title', 'LeVrai Streetwear — Autentico per Natura')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/style.css') }}">
@endsection

@section('content')

<!-- BANNER PROMO -->
<div class="promo-banner" id="promo-banner">
    <div class="promo-inner">
        <span class="promo-text">
            🔥 SPEDIZIONE GRATUITA sopra €80 — Usa il codice
            <strong>LEVRAI10</strong> per il <strong>-10%</strong>
        </span>
        <div class="promo-countdown" id="promo-countdown">
            Offerta scade in:
            <span id="p-hours">00</span>h
            <span id="p-minutes">00</span>m
            <span id="p-seconds">00</span>s
        </div>
    </div>
    <button class="promo-close" id="promo-close" aria-label="Chiudi banner">
        <i class="fas fa-times"></i>
    </button>
</div>

<!-- HEADER -->
@include('layouts.header')

<!-- HERO -->
<section class="hero-section" id="hero">
    <div class="hero-video-wrapper">
        <video autoplay loop muted playsinline class="hero-video">
            <source src="{{ asset('assets/images/Drake x Nike NOCTA.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
        <span class="hero-tag">— Nuova Collezione 2025 —</span>
        <h1 class="hero-title">
            STREET<br>
            <span class="hero-title-outline">WEAR</span><br>
            AUTENTICO.
        </h1>
        <p class="hero-sub">
            Moda urbana per chi non scende a compromessi.<br>
            Stile, qualità, identità.
        </p>
        <div class="hero-cta">
            @auth
            <a href="{{ route('shop') }}" class="btn-hero-primary">SHOP NOW <i class="fas fa-arrow-right"></i></a>
            @else
            <a href="{{ route('login') }}" class="btn-hero-primary">SHOP NOW <i class="fas fa-arrow-right"></i></a>
            @endauth
            <a href="#about-section" class="btn-hero-secondary">Chi siamo</a>
        </div>
    </div>
    <div class="hero-scroll">
        <span>SCROLL</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- CONTATORI -->
<section class="counters-section">
    <div class="counters-grid">
        <div class="counter-item" data-target="1200">
            <span class="counter-num" id="c-clienti">0</span>
            <span class="counter-plus">+</span>
            <span class="counter-label">Clienti Soddisfatti</span>
        </div>
        <div class="counter-divider"></div>
        <div class="counter-item" data-target="340">
            <span class="counter-num" id="c-ordini">0</span>
            <span class="counter-plus">+</span>
            <span class="counter-label">Ordini Completati</span>
        </div>
        <div class="counter-divider"></div>
        <div class="counter-item" data-target="80">
            <span class="counter-num" id="c-prodotti">0</span>
            <span class="counter-plus">+</span>
            <span class="counter-label">Prodotti Esclusivi</span>
        </div>
        <div class="counter-divider"></div>
        <div class="counter-item" data-target="4">
            <span class="counter-num" id="c-brand">0</span>
            <span class="counter-plus">+</span>
            <span class="counter-label">Brand Partner</span>
        </div>
    </div>
</section>

<!-- CHI SIAMO -->
<section id="about-section">
    <div class="about-container">
        <div class="about-image">
            <img src="{{ asset('assets/images/nike-nocta-drake.jpg') }}" alt="LeVrai Streetwear" loading="lazy">
            <div class="about-image-tag">EST. 2025</div>
        </div>
        <div class="about-text">
            <span class="section-tag">— Chi Siamo —</span>
            <h2 class="brutalist-title">
                NON SOLO<br>
                <span class="outline-text">MODA.</span>
            </h2>
            <p>Fondato nel 2025, <strong>LeVrai Streetwear</strong> nasce dalla passione per la moda urbana e
                l'autenticità.</p>
            <p>Crediamo nella sostenibilità e nella qualità. Materiali eco-friendly e processi produttivi responsabili.
            </p>
            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="btn-brutalist">
                SCOPRI LA COLLEZIONE <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
{{-- ═══════════════════════════════════════════════════
     DROP SECTION — card verticali affiancate
════════════════════════════════════════════════════ --}}
@php
$dropProdotti = isset($prodottiLeVrai) && $prodottiLeVrai->count() > 0
? $prodottiLeVrai->take(3)
: collect();
@endphp

<section class="levrai-drop-preview">

    {{-- HEADER --}}
    <div class="drop-head">
        <div class="drop-head-left">
            <span class="drop-eyebrow">— Collezione Esclusiva —</span>
            <h2 class="drop-bigtitle">LE VRAI <em>DROP</em></h2>
        </div>
        <div class="drop-head-right">
            <div class="drop-season">SS25</div>
            <div class="drop-season-label">Stagione in corso</div>
        </div>
    </div>

    {{-- CARDS VERTICALI --}}
    <div class="drop-cards-grid">
        @forelse($dropProdotti as $i => $prodotto)
        <a href="{{ route('shop') }}" class="drop-card-v">

            {{-- IMMAGINE --}}
            <div class="drop-card-v-img">
                @if($i === 0)
                <div class="drop-card-v-badges">
                    <span class="drop-card-badge-new">NEW</span>
                    @if($prodotto->sconto > 0)
                    <span class="drop-card-badge-pct">-{{ $prodotto->sconto }}%</span>
                    @endif
                </div>
                @elseif($prodotto->sconto > 0)
                <div class="drop-card-v-badges">
                    <span class="drop-card-badge-pct">-{{ $prodotto->sconto }}%</span>
                </div>
                @endif
                <img src="{{ asset('assets/images/prodotti/' . $prodotto->immagine) }}" alt="{{ $prodotto->nome }}"
                    onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>

            {{-- INFO --}}
            <div class="drop-card-v-body">
                <div>
                    <div class="drop-card-num">0{{ $i + 1 }}</div>
                    <div class="drop-card-brand">{{ strtoupper($prodotto->brand ?? 'LeVrai') }}</div>
                    <div class="drop-card-name">{{ strtoupper($prodotto->nome) }}</div>
                </div>
                <div class="drop-card-bottom">
                    <div>
                        @if($prodotto->sconto > 0)
                        <div class="drop-card-price">€{{ number_format($prodotto->prezzo_scontato, 2) }}</div>
                        <div class="drop-card-price-old">€{{ number_format($prodotto->prezzo, 2) }}</div>
                        @else
                        <div class="drop-card-price">€{{ number_format($prodotto->prezzo, 2) }}</div>
                        @endif
                    </div>
                    <div class="drop-card-actions">
                        @if($prodotto->sconto > 0)
                        <span class="drop-card-badge">-{{ $prodotto->sconto }}%</span>
                        @endif
                        <span class="drop-card-arrow">SCOPRI →</span>
                    </div>
                </div>
            </div>

        </a>
        @empty
        <div class="drop-empty">
            <span class="drop-eyebrow">— Coming Soon —</span>
            <h2 class="drop-bigtitle" style="margin-top:1rem">IL PROSSIMO<br><em>DROP</em><br>È IN ARRIVO</h2>
        </div>
        @endforelse
    </div>

    {{-- FOOTER --}}
    <div class="drop-footer-bar">
        <span class="drop-footer-tag">LeVrai Streetwear — Autentico per natura</span>
        <a href="{{ route('shop') }}" class="drop-footer-cta">
            SCOPRI TUTTA LA COLLEZIONE <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</section>

<!-- COME FUNZIONA -->
<section class="how-section">
    <div class="how-container">
        <div class="how-header">
            <span class="section-tag">— Il Processo —</span>
            <h2 class="brutalist-title">COME<br><span class="outline-text">FUNZIONA.</span></h2>
        </div>
        <div class="how-steps">
            <div class="how-step">
                <div class="step-number">01</div>
                <div class="step-content">
                    <i class="fas fa-user-plus"></i>
                    <h3>REGISTRATI</h3>
                    <p>Crea il tuo account in 30 secondi e accedi all'esperienza LeVrai completa.</p>
                </div>
            </div>
            <div class="how-arrow"><i class="fas fa-arrow-right"></i></div>
            <div class="how-step">
                <div class="step-number">02</div>
                <div class="step-content">
                    <i class="fas fa-tshirt"></i>
                    <h3>SCEGLI</h3>
                    <p>Esplora la nostra collezione esclusiva e seleziona i capi che fanno per te.</p>
                </div>
            </div>
            <div class="how-arrow"><i class="fas fa-arrow-right"></i></div>
            <div class="how-step">
                <div class="step-number">03</div>
                <div class="step-content">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>RICEVI</h3>
                    <p>Pagamento sicuro e consegna in 24/48h direttamente a casa tua.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRODOTTI -->

<section id="product-section">
    <div class="section-header-brutalist">
        <span class="section-tag">— La Collezione —</span>
        <h2 class="brutalist-title">
            I NOSTRI<br>
            <span class="outline-text">PRODOTTI.</span>
        </h2>
    </div>
    <div class="product-container">
        @forelse($prodotti as $p)
        <div class="product-box">
            @if(($p['sconto'] ?? $p->sconto ?? 0) > 0)
            <div class="product-badge">-{{ $p['sconto'] ?? $p->sconto }}%</div>
            @endif

            <div class="product-image-container">
                <img src="{{ asset('assets/images/prodotti/' . ($p['immagine'] ?? $p->immagine)) }}"
                    alt="{{ $p['nome'] ?? $p->nome }}" loading="lazy">
                <div class="product-overlay">
                    <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="product-overlay-btn">
                        {{ auth()->check() ? 'VEDI PRODOTTO' : 'ACCEDI PER VEDERE' }}
                    </a>
                </div>
            </div>

            <div class="product-details">
                <span class="product-category">{{ $p['brand'] ?? $p->brand ?? $p['categoria'] ?? $p->categoria }}</span>
                <h3>{{ $p['nome'] ?? $p->nome }}</h3>
                <div class="price-container">
                    @php
                    $prezzo = $p['prezzo'] ?? $p->prezzo ?? 0;
                    $prezzo_scontato = $p['prezzo_scontato'] ?? $p->prezzo_scontato ?? null;
                    $sconto = $p['sconto'] ?? $p->sconto ?? 0;
                    $prezzoMostrato = ($prezzo_scontato && $sconto > 0) ? $prezzo_scontato : $prezzo;
                    @endphp
                    <span class="current-price">€{{ number_format($prezzoMostrato, 2) }}</span>
                    @if($sconto > 0 && $prezzo_scontato)
                    <span class="original-price">€{{ number_format($prezzo, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p class="no-products">Nuovi arrivi in arrivo a breve!</p>
        @endforelse
    </div>
    <div class="view-all-wrapper">
        <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="btn-brutalist">
            VEDI TUTTA LA COLLEZIONE <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- TRENDING NOW -->
<section id="trending-now">
    <div class="trending-inner">
        <div class="trending-header">
            <span class="section-tag trending-tag">— I più cercati —</span>
            <h2 class="brutalist-title white">TRENDING<br><span class="outline-text-white">NOW.</span></h2>
        </div>

        <div class="trending-list">
            @php
            $trendingItems = $prodotti->take(3)->values();
            @endphp

            @forelse($trendingItems as $i => $p)
            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="trending-item">
                <span class="trending-num">0{{ $i + 1 }}</span>

                <div class="trending-img">
                    <img src="{{ asset('assets/images/prodotti/' . ($p->immagine ?? $p['immagine'])) }}"
                        alt="{{ $p->nome ?? $p['nome'] }}" loading="lazy" onerror="this.style.display='none'">
                </div>

                <div class="trending-info">
                    <span class="trending-brand">{{ $p->brand ?? $p['brand'] ?? $p->categoria }}</span>
                    <h4 class="trending-name">{{ $p->nome ?? $p['nome'] }}</h4>
                    <div class="trending-price">
                        €{{ number_format($p->prezzo_scontato ?? $p->prezzo ?? $p['prezzo'] ?? 0, 2) }}
                        @if(($p->sconto ?? $p['sconto'] ?? 0) > 0)
                        <span class="trending-badge">-{{ $p->sconto ?? $p['sconto'] }}%</span>
                        @endif
                    </div>
                </div>

                <div class="trending-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @empty
            <p style="color:#555; padding: 2rem 0;">Nessun prodotto disponibile.</p>
            @endforelse
        </div>

        <div class="trending-side">
            <div class="trending-side-inner">
                <span class="trending-side-tag">DROP</span>
                <p class="trending-side-text">I pezzi più richiesti del momento. Quantità limitate.</p>
                <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="trending-side-btn">
                    SHOP ALL <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- RECENZIONI -->
{{-- ═══════════════════════════════════════════════════════
     COMMUNITY SECTION — ticker + recensioni + ig feed
════════════════════════════════════════════════════════ --}}
<section id="community-reviews" class="community-section">

    {{-- HEADER --}}
    <div class="cm-inner">
        <span class="cm-tag">— Social Proof —</span>
        <h2 class="cm-title">THE LEVRAI<br><span>COMMUNITY.</span></h2>
    </div>

    {{-- PLATFORM TICKER --}}
    <div class="platform-ticker-wrap">
        <div class="platform-ticker">
            @php
            $platforms = [
            ['icon' => 'fab fa-instagram', 'bg' => 'ig', 'name' => 'Instagram', 'stars' => true, 'sub' => '12K
            follower'],
            ['icon' => 'fab fa-google', 'bg' => 'goog', 'name' => 'Google Reviews', 'stars' => true, 'sub' => '4.9 · 340
            recensioni'],
            ['icon' => null, 'bg' => 'tp', 'name' => 'Trustpilot', 'stars' => true, 'sub' => 'Excellent · 4.8'],
            ['icon' => 'fab fa-whatsapp', 'bg' => 'wa', 'name' => 'WhatsApp', 'stars' => false, 'sub' => 'Supporto in
            tempo reale'],
            ];
            @endphp
            {{-- duplicati per loop infinito seamless --}}
            @foreach(array_merge($platforms, $platforms, $platforms) as $p)
            <div class="pt-item">
                <div class="pt-icon {{ $p['bg'] }}">
                    @if($p['icon'])
                    <i class="{{ $p['icon'] }}"></i>
                    @else
                    <span>✓</span>
                    @endif
                </div>
                <div>
                    <div class="pt-name">{{ $p['name'] }}</div>
                    @if($p['stars'])
                    <div class="pt-stars">★★★★★</div>
                    @else
                    <div class="pt-stars" style="color:#333">— — — — —</div>
                    @endif
                    <div class="pt-score">{{ $p['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RECENSIONI CAROUSEL --}}
    <div class="cm-inner">
        <div class="cm-sublabel">— Cosa dicono di noi —</div>
    </div>
    <div class="rev-track-wrap">
        <div class="rev-track">
            @php
            $recensioni = [
            ['initials'=>'MU','handle'=>'@marco_urban', 'platform'=>'Instagram', 'hl'=>true, 'testo'=>'La qualità è
            pazzesca. Arrivato in 24h a Milano. LeVrai è il mio nuovo punto di riferimento per il vero streetwear.'],
            ['initials'=>'AS','handle'=>'@ale_streetstyle','platform'=>'Trustpilot','hl'=>false, 'testo'=>'Pezzi
            introvabili altrove. Servizio clienti via WhatsApp super rapido per aiutarmi con la taglia giusta.'],
            ['initials'=>'LK','handle'=>'@luca_kicks', 'platform'=>'Instagram', 'hl'=>false, 'testo'=>'Imballaggio
            curatissimo e spedizione lampo. Finalmente uno shop italiano serio per il vero streetwear.'],
            ['initials'=>'ST','handle'=>'@sofia_trend', 'platform'=>'Google', 'hl'=>false, 'testo'=>'Design unico, non
            trovi questi pezzi da nessun\'altra parte. Calzata perfetta, stile inconfondibile.'],
            ['initials'=>'DR','handle'=>'@davide_raw', 'platform'=>'Trustpilot','hl'=>false, 'testo'=>'Ordine arrivato
            in meno di 24 ore. Il packaging brutalista è la ciliegina sulla torta.'],
            ['initials'=>'GF','handle'=>'@giulia_fit', 'platform'=>'Instagram', 'hl'=>false, 'testo'=>'Finalmente
            streetwear autentico in Italia. La collezione Nocta è semplicemente incredibile.'],
            ];
            @endphp
            {{-- duplicati per loop infinito seamless --}}
            @foreach(array_merge($recensioni, $recensioni) as $r)
            <div class="rev-card {{ $r['hl'] ? 'hl' : '' }}">
                <div class="rev-top">
                    <div class="rev-avatar {{ $r['hl'] ? 'blue' : '' }}">{{ $r['initials'] }}</div>
                    <div style="text-align:right">
                        <div class="rev-handle">{{ $r['handle'] }}</div>
                        <div class="rev-platform">{{ $r['platform'] }}</div>
                    </div>
                </div>
                <div class="rev-stars">★★★★★</div>
                <p class="rev-text">"{{ $r['testo'] }}"</p>
                <span class="rev-verified">✓ Acquisto verificato</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- INSTAGRAM FEED MOCK + CTA --}}
    <div class="cm-inner">
        <div class="cm-sublabel">— @levraistreetwear —</div>
        <div class="ig-grid">
            @foreach(['look 01','look 02','look 03','look 04','look 05','look 06'] as $i => $label)
            <a href="https://instagram.com/levraistreetwear" target="_blank" class="ig-tile ig-tile--{{ $i+1 }}">
                <span class="ig-tile-label">{{ $label }}</span>
                <div class="ig-overlay"><span class="ig-heart">♥</span></div>
            </a>
            @endforeach
        </div>
        <div class="ig-cta-row">
            <a href="https://instagram.com/levraistreetwear" target="_blank" class="ig-btn">
                <i class="fab fa-instagram"></i> SEGUICI SU INSTAGRAM
            </a>
            <span class="ig-tag">Tagga <strong>#LeVraiStreetwear</strong> per apparire qui</span>
        </div>
    </div>

</section>
<!-- BRAND TICKER -->
<section class="collaborations-section">
    <div class="section-header-brutalist centered">
        <span class="section-tag">— Partner —</span>
        <h2 class="brutalist-title">I NOSTRI<br><span class="outline-text">BRAND.</span></h2>
    </div>
    <div class="brands-ticker">
        <div class="brands-track">
            <img src="{{ asset('assets/images/logo-nike.png') }}" alt="Nike">
            <img src="{{ asset('assets/images/logo-nocta.png') }}" alt="Nocta">
            <img src="{{ asset('assets/images/Logo_Adidas.png') }}" alt="Adidas">
            <img src="{{ asset('assets/images/logo-nike.png') }}" alt="Nike">
            <img src="{{ asset('assets/images/logo-nocta.png') }}" alt="Nocta">
            <img src="{{ asset('assets/images/Logo_Adidas.png') }}" alt="Adidas">

        </div>
    </div>
</section>

<!-- STORE LOCATOR + METEO -->
<section class="store-locator" id="store-locator">
    <div class="store-wrapper">
        <div class="store-map">
            <iframe src="https://www.google.com/maps?q=Via+Palermo+7,+Caltagirone&output=embed" width="100%"
                height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
        </div>
        <div class="store-info">
            <span class="section-tag">— Vieni a trovarci —</span>
            <h2 class="brutalist-title dark">LEVRAI<br><span class="outline-text-dark">STORE.</span></h2>
            <address class="store-address">
                <div class="store-detail"><i class="fas fa-map-marker-alt"></i> Via Palermo, 7 — Caltagirone (CT)</div>
                <div class="store-detail"><i class="fas fa-phone"></i> +39 339 199 7578</div>
                <div class="store-detail"><i class="fas fa-envelope"></i> info@levraistreetwear.com</div>
            </address>

            <!-- WIDGET METEO -->
            <div class="weather-widget" id="weather-widget">
                <div class="weather-loading" id="weather-loading"><i class="fas fa-spinner fa-spin"></i> Caricamento
                    meteo...</div>
                <div class="weather-data" id="weather-data" style="display:none;">
                    <div class="weather-top">
                        <div class="weather-icon-wrap"><img id="weather-icon" src="" alt="meteo"></div>
                        <div class="weather-main">
                            <span class="weather-city" id="weather-city">Caltagirone</span>
                            <span class="weather-temp" id="weather-temp">--°C</span>
                            <span class="weather-desc" id="weather-desc">--</span>
                        </div>
                    </div>
                    <div class="weather-details">
                        <div class="weather-detail-item"><i class="fas fa-tint"></i><span
                                id="weather-humidity">--%</span><small>Umidità</small></div>
                        <div class="weather-detail-item"><i class="fas fa-wind"></i><span id="weather-wind">--
                                km/h</span><small>Vento</small></div>
                        <div class="weather-detail-item"><i class="fas fa-thermometer-half"></i><span
                                id="weather-feels">--°C</span><small>Percepita</small></div>
                    </div>
                </div>
                <div class="weather-error" id="weather-error" style="display:none;"><i
                        class="fas fa-exclamation-circle"></i> Meteo non disponibile</div>
            </div>

            <div class="store-hours">
                <div class="hours-group"><span class="hours-label">Lun — Ven</span><span class="hours-time">10:00 —
                        22:00</span></div>
                <div class="hours-group"><span class="hours-label">Sabato</span><span class="hours-time">14:00 —
                        20:00</span></div>
                <div class="hours-group"><span class="hours-label">Domenica</span><span class="hours-time">Chiusi</span>
                </div>
            </div>
            <a href="https://www.google.com/maps?q=Via+Palermo+7,+Caltagirone" target="_blank" class="btn-brutalist">
                INDICAZIONI <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section">
    <div class="newsletter-inner">
        <div class="newsletter-text">
            <span class="section-tag light">— Resta Aggiornato —</span>
            <h2 class="brutalist-title white">-10% SUL<br><span class="outline-text-white">PRIMO ORDINE.</span></h2>
            <p>Iscriviti alla newsletter e ricevi il tuo codice sconto subito.</p>
        </div>
        <form class="newsletter-form" id="newsletter-form">
            <div class="newsletter-input-group">
                <input type="email" id="newsletter-email" placeholder="LA TUA EMAIL" required>
                <button type="submit">ISCRIVITI <i class="fas fa-arrow-right"></i></button>
            </div>
            <p class="newsletter-msg" id="newsletter-msg"></p>
            <small>Niente spam. Solo drop esclusivi e offerte riservate.</small>
        </form>
    </div>
</section><!-- ULTIME NOVITÀ -->
<section id="latest-news">
    <div class="section-header-brutalist">
        <span class="section-tag">— Appena Arrivati & In Offerta —</span>
        <h2 class="brutalist-title">ULTIME<br><span class="outline-text">NOVITÀ.</span></h2>
    </div>

    <div class="novita-wrapper">

        {{-- COLONNA SINISTRA: Nuovi Arrivi --}}
        <div class="novita-col light">
            <div class="novita-col-header">
                <span class="novita-label new">
                    <span class="novita-label-dot"></span>
                    NUOVI ARRIVI
                </span>
                <span class="novita-count">{{ $ultimi->count() }} prodotti</span>
            </div>

            @forelse($ultimi as $i => $p)
            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="novita-item">
                <span class="novita-num">0{{ $i + 1 }}</span>
                <div class="novita-img-wrap">
                    <img src="{{ asset('assets/images/prodotti/' . $p->immagine) }}" alt="{{ $p->nome }}" loading="lazy"
                        onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                </div>
                <div class="novita-info">
                    <span class="novita-brand">{{ $p->brand ?? $p->categoria }}</span>
                    <h4 class="novita-name">{{ $p->nome }}</h4>
                    <div class="novita-price-row">
                        <span class="novita-price-current">
                            €{{ number_format($p->prezzo_scontato ?? $p->prezzo, 2) }}
                        </span>
                        @if(($p->sconto ?? 0) > 0)
                        <span class="novita-pill-sconto">-{{ $p->sconto }}%</span>
                        @endif
                    </div>
                </div>
                <div class="novita-cta">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @empty
            <p class="novita-empty">Nessun nuovo arrivo al momento.</p>
            @endforelse

            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="novita-footer-link">
                VEDI TUTTI I NUOVI ARRIVI <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- COLONNA DESTRA: In Offerta --}}
        <div class="novita-col dark">
            <div class="novita-col-header">
                <span class="novita-label offer">
                    <span class="novita-label-dot red"></span>
                    IN OFFERTA
                </span>
                <span class="novita-count dark">FINO AL -25%</span>
            </div>

            @forelse($scontati as $i => $p)
            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="novita-item dark">
                <span class="novita-num dark">0{{ $i + 1 }}</span>
                <div class="novita-img-wrap dark">
                    <img src="{{ asset('assets/images/prodotti/' . $p->immagine) }}" alt="{{ $p->nome }}" loading="lazy"
                        onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                    <div class="novita-img-overlay">
                        <span class="novita-sconto-tag">-{{ $p->sconto }}%</span>
                    </div>
                </div>
                <div class="novita-info">
                    <span class="novita-brand white">{{ $p->brand ?? $p->categoria }}</span>
                    <h4 class="novita-name white">{{ $p->nome }}</h4>
                    <div class="novita-price-row">
                        <span class="novita-price-current white">
                            €{{ number_format($p->prezzo_scontato ?? $p->prezzo, 2) }}
                        </span>
                        <span class="novita-price-original">
                            €{{ number_format($p->prezzo, 2) }}
                        </span>
                    </div>
                </div>
                <div class="novita-cta white">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @empty
            <p class="novita-empty white">Nessuna offerta attiva.</p>
            @endforelse

            <a href="{{ auth()->check() ? route('shop') : route('login') }}" class="novita-footer-link white">
                VEDI TUTTE LE OFFERTE <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>
<!-- FAQ -->
<section class="faq-section">
    <div class="faq-container">
        <div class="section-header-brutalist">
            <span class="section-tag">— Hai Dubbi? —</span>
            <h2 class="brutalist-title dark">DOMANDE<br><span class="outline-text-dark">FREQUENTI.</span></h2>
        </div>
        <div class="faq-list">
            @php
            $faqs = [
            ['q' => 'Come posso effettuare un ordine?', 'a' => 'Registrati, sfoglia la collezione, aggiungi i prodotti
            al carrello e completa il pagamento.'],
            ['q' => 'Quali sono i tempi di spedizione?', 'a' => 'Consegniamo in 24/48h in tutta Italia.'],
            ['q' => 'Posso restituire un prodotto?', 'a' => 'Sì, accettiamo resi entro 30 giorni dalla data di
            acquisto.'],
            ['q' => 'Quali metodi di pagamento accettate?', 'a' => 'Accettiamo carte di credito/debito, Apple Pay,
            Google Pay e pagamento alla consegna.'],
            ['q' => 'Come funziona il codice sconto newsletter?', 'a' => 'Iscrivendoti ricevi subito un codice sconto
            del -10% valido sul primo ordine.'],
            ['q' => 'Come posso contattare il supporto?', 'a' => 'Scrivici a info@levraistreetwear.com oppure chiamaci
            al +39 339 199 7578.'],
            ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="faq-item" id="faq-{{ $i }}">
                <button class="faq-question" onclick="toggleFaq({{$i}})">
                    <span>{{ $faq['q'] }}</span>
                    <i class="fas fa-plus faq-icon"></i>
                </button>
                <div class="faq-answer">
                    <p>{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section id="brand-manifesto"
    style="background: #000; padding: 40px 0; overflow: hidden; border-top: 2px solid #fff; border-bottom: 2px solid #fff;">
    <div class="marquee-container" style="display: flex; white-space: nowrap; overflow: hidden;">
        <div class="marquee-content" style="display: inline-block; animation: marquee 20s linear infinite;">
            <span
                style="color: #fff; font-size: 5rem; font-weight: 900; text-transform: uppercase; font-family: 'Archivo Black', sans-serif;">
                AUTENTICO PER NATURA — NO FAKES — DROP ESCLUSIVI — SPEDIZIONE 24H — LEVRAI STREETWEAR —
            </span>
        </div>
        <div class="marquee-content" style="display: inline-block; animation: marquee 20s linear infinite;">
            <span
                style="color: #fff; font-size: 5rem; font-weight: 900; text-transform: uppercase; font-family: 'Archivo Black', sans-serif;">
                AUTENTICO PER NATURA — NO FAKES — DROP ESCLUSIVI — SPEDIZIONE 24H — LEVRAI STREETWEAR —
            </span>
        </div>
    </div>
</section>

<style>
@keyframes marquee {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-50%);
    }
}

/* Su Mobile rendiamo il testo un po' più piccolo per non coprire tutto */
@media (max-width: 768px) {
    #brand-manifesto span {
        font-size: 3rem !important;
    }
}
</style>
<!-- FOOTER -->
@include('layouts.footer')
<script>
window.prodotti = @json($prodotti ?? []);
</script>

@endsection

@section('scripts')
<script>
// PROMO COUNTDOWN
(function() {
    const end = new Date();
    end.setHours(23, 59, 59, 0);

    function tick() {
        const diff = end - new Date();
        if (diff <= 0) return;
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('p-hours').textContent = pad(Math.floor(diff / 3600000));
        document.getElementById('p-minutes').textContent = pad(Math.floor((diff % 3600000) / 60000));
        document.getElementById('p-seconds').textContent = pad(Math.floor((diff % 60000) / 1000));
    }
    tick();
    setInterval(tick, 1000);
    document.getElementById('promo-close')?.addEventListener('click', () => {
        const b = document.getElementById('promo-banner');
        b.style.transition = 'height 0.3s ease, opacity 0.3s ease';
        b.style.height = '0';
        b.style.opacity = '0';
        setTimeout(() => b.remove(), 300);
    });
})();

// CONTATORI
function animateCounter(el, target) {
    let start = 0;
    const step = target / (2000 / 16);
    const t = setInterval(() => {
        start += step;
        if (start >= target) {
            el.textContent = target;
            clearInterval(t);
        } else el.textContent = Math.floor(start);
    }, 16);
}
const cs = document.querySelector('.counters-section');
if (cs) {
    new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            animateCounter(document.getElementById('c-clienti'), 1200);
            animateCounter(document.getElementById('c-ordini'), 340);
            animateCounter(document.getElementById('c-prodotti'), 80);
            animateCounter(document.getElementById('c-brand'), 4);
        }
    }, {
        threshold: 0.3
    }).observe(cs);
}

// RECENSIONI SLIDER
(function() {
    const track = document.getElementById('recensioni-track');
    if (!track) return;
    let current = 0;
    const cards = track.querySelectorAll('.recensione-card');
    const total = Math.floor(cards.length / 2);
    const cardW = () => cards[0].offsetWidth + 24;

    function goTo(i) {
        current = (i + total) % total;
        track.style.transform = `translateX(-${current * cardW()}px)`;
    }
    document.getElementById('rec-next')?.addEventListener('click', () => goTo(current + 1));
    document.getElementById('rec-prev')?.addEventListener('click', () => goTo(current - 1));
    setInterval(() => goTo(current + 1), 4000);
})();

// FAQ
function toggleFaq(index) {
    const item = document.getElementById('faq-' + index);
    const answer = item.querySelector('.faq-answer');
    const icon = item.querySelector('.faq-icon');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => {
        i.classList.remove('open');
        i.querySelector('.faq-answer').style.maxHeight = '0';
        i.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
    });
    if (!isOpen) {
        item.classList.add('open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(45deg)';
    }
}

// OPENWEATHER
(function() {
    const API_KEY = '6de47cd39a839ccdfbccc845e71e2cea';
    const URL =
        `https://api.openweathermap.org/data/2.5/weather?q=Caltagirone,IT&appid=${API_KEY}&units=metric&lang=it`;
    fetch(URL).then(r => r.json()).then(data => {
        document.getElementById('weather-city').textContent = data.name + ', IT';
        document.getElementById('weather-temp').textContent = Math.round(data.main.temp) + '°C';
        document.getElementById('weather-desc').textContent = data.weather[0].description;
        document.getElementById('weather-humidity').textContent = data.main.humidity + '%';
        document.getElementById('weather-wind').textContent = Math.round(data.wind.speed * 3.6) + ' km/h';
        document.getElementById('weather-feels').textContent = Math.round(data.main.feels_like) + '°C';
        document.getElementById('weather-icon').src =
            `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
        document.getElementById('weather-loading').style.display = 'none';
        document.getElementById('weather-data').style.display = 'block';
    }).catch(() => {
        document.getElementById('weather-loading').style.display = 'none';
        document.getElementById('weather-error').style.display = 'flex';
    });
})();
</script>
@endsection
