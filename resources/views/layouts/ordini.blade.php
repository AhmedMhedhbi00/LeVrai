@extends('layouts.app')

@section('title', 'I Miei Ordini | LeVrai Streetwear')

@section('styles')
<style>
body {
    background: #000;
    color: #fff;
}

/* ── TOP BAR ── */
.orders-topbar {
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
    text-decoration: none;
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

/* ── LAYOUT ── */
.orders-layout {
    max-width: 900px;
    margin: 0 auto;
    padding: 3rem 2rem 5rem;
}

/* ── HEADER ── */
.orders-heading {
    margin-bottom: 2.5rem;
}

.section-tag {
    font-size: 0.7rem;
    letter-spacing: 4px;
    color: #555;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.5rem;
}

.brutalist-title {
    font-family: 'Bebas Neue', cursive;
    font-size: clamp(2.5rem, 6vw, 4rem);
    line-height: 1;
    letter-spacing: 3px;
    margin: 0;
}

.outline-text {
    -webkit-text-stroke: 1px #fff;
    color: transparent;
}

.orders-meta {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #1a1a1a;
}

.meta-stat {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.meta-label {
    font-size: 0.65rem;
    letter-spacing: 3px;
    color: #555;
    text-transform: uppercase;
}

.meta-value {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.6rem;
    letter-spacing: 2px;
    color: #fff;
}

.meta-value.blue {
    color: #3a86ff;
}

/* ── EMPTY STATE ── */
.orders-empty {
    text-align: center;
    padding: 5rem 2rem;
    border: 1px solid #1a1a1a;
}

.orders-empty i {
    font-size: 3rem;
    color: #222;
    display: block;
    margin-bottom: 1.5rem;
}

.orders-empty p {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.2rem;
    letter-spacing: 3px;
    color: #444;
    margin-bottom: 2rem;
}

.btn-brutalist {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.9rem;
    letter-spacing: 3px;
    color: #fff;
    background: #3a86ff;
    border: none;
    padding: 0.9rem 2rem;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-brutalist:hover {
    background: #2563eb;
}

/* ── ORDER CARD ── */
.order-card {
    border: 1px solid #1a1a1a;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: border-color 0.2s;
    overflow: hidden;
}

.order-card:hover {
    border-color: #333;
}

.order-card.open {
    border-color: #3a86ff;
}

.order-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.2rem 1.5rem;
    background: #0a0a0a;
}

.order-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.order-id {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.1rem;
    letter-spacing: 2px;
    color: #3a86ff;
}

.order-date {
    font-size: 0.75rem;
    color: #555;
    letter-spacing: 1px;
}

.order-status {
    font-size: 0.6rem;
    letter-spacing: 2px;
    padding: 0.2rem 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}

.status-pagato {
    background: rgba(58, 134, 255, 0.15);
    color: #3a86ff;
    border: 1px solid #3a86ff33;
}

.status-spedito {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    border: 1px solid #22c55e33;
}

.status-in_lavorazione {
    background: rgba(250, 204, 21, 0.15);
    color: #facc15;
    border: 1px solid #facc1533;
}

.status-annullato {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid #ef444433;
}

.order-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.order-total {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.3rem;
    letter-spacing: 2px;
}

.order-toggle {
    color: #555;
    font-size: 0.8rem;
    transition: transform 0.3s;
}

.order-card.open .order-toggle {
    transform: rotate(180deg);
}

/* ── ORDER BODY ── */
.order-body {
    display: none;
    border-top: 1px solid #1a1a1a;
}

.order-card.open .order-body {
    display: block;
}

.order-product-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #111;
    gap: 1rem;
}

.order-product-row:last-child {
    border-bottom: none;
}

.order-product-name {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #ddd;
}

.order-product-meta {
    font-size: 0.7rem;
    color: #555;
    letter-spacing: 1px;
}

.order-product-price {
    font-family: 'Bebas Neue', cursive;
    font-size: 1rem;
    letter-spacing: 1px;
    color: #aaa;
    white-space: nowrap;
}

.order-footer {
    display: flex;
    justify-content: flex-end;
    padding: 0.8rem 1.5rem;
    background: #050505;
    border-top: 1px solid #111;
    gap: 0.5rem;
    align-items: center;
}

.order-footer-label {
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: #555;
    text-transform: uppercase;
}

.order-footer-total {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.2rem;
    letter-spacing: 2px;
    color: #fff;
}

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
    .orders-layout {
        padding: 2rem 1rem 4rem;
    }

    .orders-topbar {
        padding: 0 1rem;
    }

    .orders-meta {
        gap: 1.2rem;
    }

    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .order-right {
        align-self: flex-end;
    }
}
</style>
@endsection

@section('content')

{{-- TOP BAR --}}
<nav class="orders-topbar">
    <a href="{{ route('home') }}" class="topbar-logo">LE<span>VRAI</span></a>
    <a href="{{ route('home') }}" class="topbar-back">
        <i class="fas fa-arrow-left"></i> TORNA ALLO SHOP
    </a>
</nav>

<div class="orders-layout">

    {{-- HEADING --}}
    <div class="orders-heading">
        <span class="section-tag">— Il tuo account —</span>
        <h1 class="brutalist-title">I MIEI<br><span class="outline-text">ORDINI.</span></h1>

        @if($ordini->isNotEmpty())
        <div class="orders-meta">
            <div class="meta-stat">
                <span class="meta-label">Ordini totali</span>
                <span class="meta-value blue">{{ $ordini->count() }}</span>
            </div>
            <div class="meta-stat">
                <span class="meta-label">Totale speso</span>
                <span class="meta-value">€{{ number_format($ordini->sum('totale'), 2) }}</span>
            </div>
            <div class="meta-stat">
                <span class="meta-label">Ultimo ordine</span>
                <span class="meta-value" style="font-size:1rem;letter-spacing:1px;">
                    {{ \Carbon\Carbon::parse($ordini->first()->created_at)->format('d/m/Y') }}
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- LISTA ORDINI --}}
    @if($ordini->isEmpty())
    <div class="orders-empty">
        <i class="fas fa-box-open"></i>
        <p>NESSUN ORDINE ANCORA</p>
        <a href="{{ route('shop') }}" class="btn-brutalist">
            VAI ALLO SHOP <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    @else
    @foreach($ordini as $ordine)
    <div class="order-card" onclick="this.classList.toggle('open')">

        <div class="order-header">
            <div class="order-header-left">
                <span class="order-id">#{{ $ordine->id }}</span>
                <span class="order-date">
                    {{ \Carbon\Carbon::parse($ordine->created_at)->format('d/m/Y — H:i') }}
                </span>
                <span class="order-status status-{{ $ordine->stato }}">
                    {{ strtoupper($ordine->stato) }}
                </span>
            </div>
            <div class="order-right">
                <span class="order-total">€{{ number_format($ordine->totale, 2) }}</span>
                <i class="fas fa-chevron-down order-toggle"></i>
            </div>
        </div>

        <div class="order-body">
            @foreach($ordine->prodotti as $p)
            <div class="order-product-row">
                <div>
                    <div class="order-product-name">{{ $p->nome }}</div>
                    <div class="order-product-meta">
                        Taglia: {{ $p->pivot->taglia ?? '—' }} &nbsp;·&nbsp;
                        Q.tà: {{ $p->pivot->quantita }}
                    </div>
                </div>
                <span class="order-product-price">
                    €{{ number_format($p->pivot->prezzo_unitario, 2) }}
                </span>
            </div>
            @endforeach

            <div class="order-footer">
                <span class="order-footer-label">Totale ordine</span>
                <span class="order-footer-total">€{{ number_format($ordine->totale, 2) }}</span>
            </div>
        </div>

    </div>
    @endforeach
    @endif

</div>

@endsection

@section('scripts')
<script>
// Apri il primo ordine di default se presente
document.addEventListener('DOMContentLoaded', function() {
    const first = document.querySelector('.order-card');
    if (first) first.classList.add('open');
});
</script>
@endsection