@extends('layouts.app')

@section('title', $prodotto->nome . ' | LeVrai Streetwear')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/shop.css') }}">
<style>
.prodotto-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 3rem 2rem 5rem;
}

.prodotto-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.8rem;
    letter-spacing: 3px;
    color: #555;
    text-decoration: none;
    margin-bottom: 2rem;
    transition: color 0.2s;
}

.prodotto-back:hover {
    color: #fff;
}

.prodotto-grid {
    display: flex;
    gap: 4rem;
    align-items: flex-start;
}

.prodotto-img-col {
    flex: 1;
    min-width: 0;
}

.prodotto-img-col img {
    width: 100%;
    aspect-ratio: 3/4;
    object-fit: cover;
    border: 1px solid #1a1a1a;
}

.prodotto-info-col {
    width: 380px;
    flex-shrink: 0;
}

.prodotto-brand {
    font-size: 0.7rem;
    letter-spacing: 4px;
    color: #555;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.prodotto-nome {
    font-family: 'Bebas Neue', cursive;
    font-size: clamp(2rem, 4vw, 3rem);
    letter-spacing: 2px;
    line-height: 1;
    margin-bottom: 1.5rem;
}

.prodotto-prezzi {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.prodotto-prezzo {
    font-family: 'Bebas Neue', cursive;
    font-size: 2rem;
    letter-spacing: 2px;
}

.prodotto-prezzo.scontato {
    color: #3a86ff;
}

.prodotto-prezzo-orig {
    font-size: 1rem;
    color: #444;
    text-decoration: line-through;
}

.prodotto-badge {
    display: inline-block;
    background: #e63946;
    color: #fff;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.8rem;
    letter-spacing: 2px;
    padding: 3px 10px;
    margin-bottom: 1.5rem;
}

.prodotto-taglie-label {
    font-size: 0.65rem;
    letter-spacing: 3px;
    color: #555;
    text-transform: uppercase;
    margin-bottom: 0.6rem;
}

.prodotto-taglie {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.taglia-btn {
    width: 48px;
    height: 48px;
    border: 1px solid #1a1a1a;
    background: #0a0a0a;
    color: #aaa;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.9rem;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.taglia-btn:hover {
    border-color: #fff;
    color: #fff;
}

.taglia-btn.selected {
    border-color: #3a86ff;
    color: #fff;
    background: rgba(58, 134, 255, 0.1);
}

.prodotto-qty {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 1.5rem;
}

.prodotto-qty label {
    font-size: 0.65rem;
    letter-spacing: 3px;
    color: #555;
    text-transform: uppercase;
    margin-right: 1rem;
}

.prodotto-qty button {
    width: 36px;
    height: 36px;
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
}

.prodotto-qty input {
    width: 48px;
    height: 36px;
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    border-left: none;
    border-right: none;
    color: #fff;
    text-align: center;
    font-size: 0.9rem;
}

.btn-aggiungi {
    width: 100%;
    padding: 1rem;
    background: #fff;
    color: #000;
    border: none;
    font-family: 'Bebas Neue', cursive;
    font-size: 1rem;
    letter-spacing: 4px;
    cursor: pointer;
    transition: background 0.2s;
    margin-bottom: 0.8rem;
}

.btn-aggiungi:hover {
    background: #ddd;
}

.btn-aggiungi:disabled {
    background: #1a1a1a;
    color: #555;
    cursor: not-allowed;
}

.prodotto-stock {
    font-size: 0.72rem;
    letter-spacing: 2px;
    color: #555;
    margin-bottom: 1.5rem;
}

.prodotto-stock.ok {
    color: #2e7d32;
}

.prodotto-stock.low {
    color: #e65100;
}

.prodotto-stock.esaurito {
    color: #e63946;
}

.prodotto-divider {
    border: none;
    border-top: 1px solid #1a1a1a;
    margin: 1.5rem 0;
}

.taglia-error {
    font-size: 0.72rem;
    color: #e63946;
    letter-spacing: 1px;
    margin-top: 0.5rem;
    display: none;
}

@media (max-width: 768px) {
    .prodotto-grid {
        flex-direction: column;
        gap: 2rem;
    }

    .prodotto-info-col {
        width: 100%;
    }
}
</style>
@endsection

@section('content')
@include('layouts.header')

<div class="prodotto-page">
    <a href="{{ route('shop') }}" class="prodotto-back">
        <i class="fas fa-arrow-left"></i> TORNA ALLO SHOP
    </a>

    <div class="prodotto-grid">

        {{-- IMMAGINE --}}
        <div class="prodotto-img-col">
            <img src="{{ asset('assets/images/prodotti/' . $prodotto->immagine) }}" alt="{{ $prodotto->nome }}"
                onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
        </div>

        {{-- INFO --}}
        <div class="prodotto-info-col">

            <div class="prodotto-brand">{{ strtoupper($prodotto->brand ?? $prodotto->categoria) }}</div>
            <h1 class="prodotto-nome">{{ $prodotto->nome }}</h1>

            @if($prodotto->sconto > 0)
            <div class="prodotto-badge">-{{ $prodotto->sconto }}% SCONTO</div>
            @endif

            <div class="prodotto-prezzi">
                <span class="prodotto-prezzo {{ $prodotto->sconto > 0 ? 'scontato' : '' }}">
                    €{{ number_format($prodotto->prezzo_scontato, 2) }}
                </span>
                @if($prodotto->sconto > 0)
                <span class="prodotto-prezzo-orig">€{{ number_format($prodotto->prezzo, 2) }}</span>
                @endif
            </div>

            {{-- STOCK --}}
            @if($prodotto->quantita <= 0) <div class="prodotto-stock esaurito"><i class="fas fa-times-circle"></i>
                ESAURITO
        </div>
        @elseif($prodotto->quantita <= 5) <div class="prodotto-stock low"><i class="fas fa-exclamation-circle"></i>
            ULTIMI {{ $prodotto->quantita }} DISPONIBILI
    </div>
    @else
    <div class="prodotto-stock ok"><i class="fas fa-check-circle"></i> DISPONIBILE</div>
    @endif

    <hr class="prodotto-divider">

    @if($prodotto->quantita > 0)
    {{-- TAGLIE --}}
    @php $taglie = $prodotto->tagliePivot->pluck('taglia')->toArray(); @endphp
    @if(count($taglie) > 0)
    <div class="prodotto-taglie-label">SELEZIONA TAGLIA</div>
    <div class="prodotto-taglie">
        @foreach($taglie as $t)
        <button class="taglia-btn" data-taglia="{{ $t }}">{{ $t }}</button>
        @endforeach
    </div>
    <div class="taglia-error" id="taglia-error">Seleziona una taglia prima di continuare</div>
    @endif

    {{-- QUANTITÀ --}}
    <div class="prodotto-qty">
        <label>QTÀ</label>
        <button type="button" id="qty-minus">−</button>
        <input type="number" id="qty-input" value="1" min="1" max="{{ $prodotto->quantita }}">
        <button type="button" id="qty-plus">+</button>
    </div>

    {{-- AGGIUNGI AL CARRELLO --}}
    <button class="btn-aggiungi" id="btn-aggiungi" data-id="{{ $prodotto->id }}">
        AGGIUNGI AL CARRELLO
    </button>
    @else
    <button class="btn-aggiungi" disabled>ESAURITO</button>
    @endif

</div>
</div>
</div>

@include('layouts.footer')
@endsection

@section('scripts')
<script>
(function() {
    let tagliaSelezionata = '';

    // Selezione taglia
    document.querySelectorAll('.taglia-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.taglia-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            tagliaSelezionata = this.dataset.taglia;
            document.getElementById('taglia-error').style.display = 'none';
            const addBtn = document.getElementById('btn-aggiungi');
            if (addBtn) addBtn.textContent = 'AGGIUNGI - ' + tagliaSelezionata;
        });
    });

    // Quantità +/-
    document.getElementById('qty-minus')?.addEventListener('click', function() {
        const input = document.getElementById('qty-input');
        input.value = Math.max(1, parseInt(input.value) - 1);
    });
    document.getElementById('qty-plus')?.addEventListener('click', function() {
        const input = document.getElementById('qty-input');
        const max = parseInt(input.max) || 99;
        input.value = Math.min(max, parseInt(input.value) + 1);
    });

    // Aggiungi al carrello
    document.getElementById('btn-aggiungi')?.addEventListener('click', function() {
        if (!tagliaSelezionata) {
            document.getElementById('taglia-error').style.display = 'block';
            return;
        }

        const id = this.dataset.id;
        const qty = document.getElementById('qty-input').value;
        const btn = this;

        btn.disabled = true;
        btn.textContent = 'AGGIUNGENDO...';

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('taglia', tagliaSelezionata);
        formData.append('quantita', qty);

        fetch(`/cart/add/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (res.ok || res.redirected) {
                    if (typeof updateCartBadges === 'function') updateCartBadges();
                    if (typeof pulseCartBadge === 'function') pulseCartBadge();
                    if (typeof showToast === 'function') showToast('Aggiunto al carrello! (' +
                        tagliaSelezionata + ')', 'success');
                    btn.textContent = '✓ AGGIUNTO';
                    btn.style.background = '#2e7d32';
                    btn.style.color = '#fff';
                    setTimeout(() => {
                        btn.textContent = 'AGGIUNGI - ' + tagliaSelezionata;
                        btn.style.background = '';
                        btn.style.color = '';
                        btn.disabled = false;
                    }, 2000);
                }
            })
            .catch(() => {
                if (typeof showToast === 'function') showToast('Errore. Riprova.', 'error');
                btn.textContent = 'AGGIUNGI AL CARRELLO';
                btn.disabled = false;
            });
    });
})();
</script>
@endsection