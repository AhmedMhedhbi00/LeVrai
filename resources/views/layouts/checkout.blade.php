@extends('layouts.app')

@section('title', 'Checkout | LeVrai Streetwear')

@section('styles')
<style>
body {
    background: #000;
    color: #fff;
}

/* ── TOP BAR ── */
.checkout-topbar {
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

.topbar-secure {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
    letter-spacing: 2px;
    color: #444;
    text-transform: uppercase;
}

.topbar-secure i {
    color: #3a86ff;
}

/* ── LAYOUT ── */
.checkout-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 3rem 2rem 5rem;
    display: flex;
    gap: 3rem;
    align-items: flex-start;
}

/* ── LEFT COLUMN (form) ── */
.checkout-form-col {
    flex: 1;
    min-width: 0;
}

.checkout-heading {
    margin-bottom: 2rem;
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
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    line-height: 1;
    letter-spacing: 3px;
    margin: 0;
}

.outline-text {
    -webkit-text-stroke: 1px #fff;
    color: transparent;
}

/* ── STEP TABS ── */
.checkout-steps {
    display: flex;
    gap: 0;
    margin-bottom: 2rem;
    border: 1px solid #1a1a1a;
}

.step-tab {
    flex: 1;
    padding: 0.8rem;
    text-align: center;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.75rem;
    letter-spacing: 2px;
    color: #333;
    background: #0a0a0a;
    border-right: 1px solid #1a1a1a;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    user-select: none;
}

.step-tab:last-child {
    border-right: none;
}

.step-tab.active {
    color: #fff;
    background: #111;
}

.step-tab.done {
    color: #3a86ff;
}

.step-tab .step-num {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 1px solid currentColor;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
}

.step-tab.done .step-num {
    background: #3a86ff;
    border-color: #3a86ff;
    color: #fff;
}

/* ── FORM PANELS ── */
.form-panel {
    display: none;
}

.form-panel.active {
    display: block;
}

.form-section-title {
    font-family: 'Bebas Neue', cursive;
    font-size: 1rem;
    letter-spacing: 3px;
    margin-bottom: 1.2rem;
    color: #aaa;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: #555;
    text-transform: uppercase;
}

.form-group input,
.form-group select {
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    color: #fff;
    padding: 0.75rem 1rem;
    font-size: 0.85rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: border-color 0.2s;
    width: 100%;
    box-sizing: border-box;
    -webkit-appearance: none;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #3a86ff;
}

.form-group input.error {
    border-color: #e63946;
}

.field-error {
    font-size: 0.65rem;
    color: #e63946;
    letter-spacing: 1px;
    display: none;
}

.field-error.visible {
    display: block;
}

/* ── PAYMENT METHODS ── */
.pay-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
    margin-bottom: 1.5rem;
}

.pay-option {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 1rem;
    border: 1px solid #1a1a1a;
    cursor: pointer;
    font-size: 0.8rem;
    transition: border-color 0.2s, background 0.2s;
}

.pay-option:hover {
    border-color: #333;
}

.pay-option input[type="radio"] {
    display: none;
}

.pay-option.selected {
    border-color: #3a86ff;
    background: rgba(58, 134, 255, 0.05);
}

.pay-option i {
    font-size: 1.1rem;
    color: #3a86ff;
}

/* ── STRIPE ELEMENT ── */
#stripe-card-element {
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    padding: 0.9rem 1rem;
    margin-bottom: 1rem;
    transition: border-color 0.2s;
}

#stripe-card-element.StripeElement--focus {
    border-color: #3a86ff;
}

#stripe-error {
    font-size: 0.75rem;
    color: #e63946;
    margin-bottom: 1rem;
    min-height: 1rem;
}

/* ── NAVIGATION BUTTONS ── */
.form-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    gap: 1rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.85rem;
    letter-spacing: 2px;
    color: #555;
    background: none;
    border: 1px solid #1a1a1a;
    padding: 0.8rem 1.5rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-back:hover {
    color: #fff;
    border-color: #333;
}

.btn-next {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Bebas Neue', cursive;
    font-size: 0.85rem;
    letter-spacing: 3px;
    color: #fff;
    background: #3a86ff;
    border: none;
    padding: 0.9rem 2rem;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-next:hover {
    background: #2563eb;
}

.btn-next:disabled {
    background: #1a1a1a;
    color: #555;
    cursor: not-allowed;
}

/* ── RIGHT COLUMN (summary) ── */
.checkout-summary-col {
    width: 340px;
    flex-shrink: 0;
    position: sticky;
    top: 80px;
}

.summary-box {
    border: 1px solid #1a1a1a;
    background: #080808;
}

.summary-title {
    font-family: 'Bebas Neue', cursive;
    font-size: 0.85rem;
    letter-spacing: 3px;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #1a1a1a;
    color: #aaa;
}

.summary-items {
    padding: 1rem 1.5rem;
    max-height: 300px;
    overflow-y: auto;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #111;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item-info {
    flex: 1;
}

.summary-item-name {
    font-size: 0.8rem;
    color: #ccc;
    margin-bottom: 0.2rem;
}

.summary-item-meta {
    font-size: 0.65rem;
    color: #555;
    letter-spacing: 1px;
}

.summary-item-price {
    font-family: 'Bebas Neue', cursive;
    font-size: 1rem;
    letter-spacing: 1px;
    white-space: nowrap;
    color: #aaa;
}

.summary-totals {
    padding: 1rem 1.5rem;
    border-top: 1px solid #1a1a1a;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.78rem;
    color: #555;
    margin-bottom: 0.6rem;
}

.summary-row.total {
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    margin-top: 0.8rem;
    padding-top: 0.8rem;
    border-top: 1px solid #1a1a1a;
}

.summary-row.total span:last-child {
    font-family: 'Bebas Neue', cursive;
    font-size: 1.3rem;
    letter-spacing: 2px;
}

/* ── SUCCESS ── */
#checkout-success {
    display: none;
    text-align: center;
    padding: 4rem 2rem;
    border: 1px solid #1a1a1a;
}

#checkout-success i {
    font-size: 3rem;
    color: #3a86ff;
    margin-bottom: 1.5rem;
    display: block;
}

#checkout-success h2 {
    font-family: 'Bebas Neue', cursive;
    font-size: 2rem;
    letter-spacing: 4px;
    margin-bottom: 0.5rem;
}

#checkout-success p {
    color: #555;
    font-size: 0.85rem;
    margin-bottom: 2rem;
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .checkout-page {
        flex-direction: column;
        padding: 2rem 1rem 4rem;
    }

    .checkout-summary-col {
        width: 100%;
        position: static;
        order: -1;
    }

    .pay-methods {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: 1;
    }
}
</style>
@endsection

@section('content')

{{-- TOP BAR --}}
<nav class="checkout-topbar">
    <a href="{{ route('home') }}" class="topbar-logo">LE<span>VRAI</span></a>
    <div class="topbar-secure">
        <i class="fas fa-lock"></i> Checkout sicuro
    </div>
</nav>

<div class="checkout-page">

    {{-- ── FORM COLONNA ── --}}
    <div class="checkout-form-col">

        <div class="checkout-heading">
            <span class="section-tag">— Quasi fatto —</span>
            <h1 class="brutalist-title">COMPLETA<br><span class="outline-text">L'ORDINE.</span></h1>
        </div>

        {{-- STEP TABS --}}
        <div class="checkout-steps">
            <div class="step-tab active" id="tab-1">
                <span class="step-num">1</span> Spedizione
            </div>
            <div class="step-tab" id="tab-2">
                <span class="step-num">2</span> Pagamento
            </div>
            <div class="step-tab" id="tab-3">
                <span class="step-num">3</span> Conferma
            </div>
        </div>

        {{-- STEP 1: SPEDIZIONE --}}
        <div class="form-panel active" id="panel-1">
            <p class="form-section-title">DATI DI SPEDIZIONE</p>

            <div class="form-grid">
                <div class="form-group">
                    <label for="ship-name">Nome *</label>
                    <input type="text" id="ship-name" placeholder="Mario" autocomplete="given-name">
                    <span class="field-error" id="err-name">Inserisci il nome</span>
                </div>
                <div class="form-group">
                    <label for="ship-surname">Cognome *</label>
                    <input type="text" id="ship-surname" placeholder="Rossi" autocomplete="family-name">
                    <span class="field-error" id="err-surname">Inserisci il cognome</span>
                </div>
                <div class="form-group full">
                    <label for="ship-email">Email *</label>
                    <input type="email" id="ship-email" placeholder="mario@email.com" autocomplete="email"
                        value="{{ auth()->user()->email ?? '' }}">
                    <span class="field-error" id="err-email">Inserisci un'email valida</span>
                </div>
                <div class="form-group full">
                    <label for="ship-address">Indirizzo *</label>
                    <input type="text" id="ship-address" placeholder="Via Roma, 1" autocomplete="street-address">
                    <span class="field-error" id="err-address">Inserisci l'indirizzo</span>
                </div>
                <div class="form-group">
                    <label for="ship-city">Città *</label>
                    <input type="text" id="ship-city" placeholder="Milano" autocomplete="address-level2">
                    <span class="field-error" id="err-city">Inserisci la città</span>
                </div>
                <div class="form-group">
                    <label for="ship-zip">CAP *</label>
                    <input type="text" id="ship-zip" placeholder="20100" maxlength="5" autocomplete="postal-code">
                    <span class="field-error" id="err-zip">CAP non valido (5 cifre)</span>
                </div>
                <div class="form-group full">
                    <label for="ship-phone">Telefono</label>
                    <input type="tel" id="ship-phone" placeholder="+39 000 000 0000" autocomplete="tel">
                </div>
            </div>

            <div class="form-nav">
                <a href="{{ route('cart.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> CARRELLO
                </a>
                <button class="btn-next" id="go-step-2">
                    CONTINUA <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- STEP 2: PAGAMENTO --}}
        <div class="form-panel" id="panel-2">
            <p class="form-section-title">METODO DI PAGAMENTO</p>

            <div class="pay-methods">
                <label class="pay-option selected" id="opt-card">
                    <input type="radio" name="payment" value="card" checked>
                    <i class="fas fa-credit-card"></i> Carta di credito
                </label>
                <label class="pay-option" id="opt-applepay">
                    <input type="radio" name="payment" value="applepay">
                    <i class="fab fa-apple"></i> Apple Pay
                </label>
                <label class="pay-option" id="opt-googlepay">
                    <input type="radio" name="payment" value="googlepay">
                    <i class="fab fa-google-pay"></i> Google Pay
                </label>
                <label class="pay-option" id="opt-cash">
                    <input type="radio" name="payment" value="cash">
                    <i class="fas fa-truck"></i> Alla consegna
                </label>
            </div>

            {{-- Form carta fittizio (solo visivo) --}}
            <div id="card-fields-wrapper">
                <div class="form-grid" style="margin-bottom:0.5rem;">
                    <div class="form-group full">
                        <label>Numero carta</label>
                        <input type="text" placeholder="4242 4242 4242 4242" maxlength="19"
                            oninput="this.value=this.value.replace(/\D/g,'').replace(/(\d{4})/g,'$1 ').trim()">
                    </div>
                    <div class="form-group">
                        <label>Scadenza</label>
                        <input type="text" placeholder="MM/AA" maxlength="5"
                            oninput="this.value=this.value.replace(/\D/g,'').replace(/^(\d{2})(\d)/,'$1/$2')">
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" placeholder="123" maxlength="3"
                            oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                </div>
                <div id="stripe-error" style="font-size:0.75rem;color:#e63946;min-height:1rem;margin-bottom:0.5rem;">
                </div>
            </div>

            <div class="form-nav">
                <button class="btn-back" id="back-step-1">
                    <i class="fas fa-arrow-left"></i> INDIETRO
                </button>
                <button class="btn-next" id="go-step-3">
                    CONTINUA <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- STEP 3: CONFERMA --}}
        <div class="form-panel" id="panel-3">
            <p class="form-section-title">RIEPILOGO FINALE</p>

            <div id="confirm-details"
                style="border:1px solid #1a1a1a; padding:1.5rem; margin-bottom:1.5rem; font-size:0.82rem; line-height:1.8; color:#aaa;">
                {{-- Popolato da JS --}}
            </div>

            <div id="stripe-error-confirm"></div>

            <div class="form-nav">
                <button class="btn-back" id="back-step-2">
                    <i class="fas fa-arrow-left"></i> INDIETRO
                </button>
                <button class="btn-next" id="pay-now-btn">
                    <i class="fas fa-lock"></i> PAGA ORA
                </button>
            </div>
        </div>

        {{-- SUCCESS --}}
        <div id="checkout-success">
            <i class="fas fa-check-circle"></i>
            <h2>ORDINE CONFERMATO!</h2>
            <p>Ordine <strong id="success-order-id">#---</strong> &nbsp;·&nbsp; Totale: <strong
                    id="success-total">€0.00</strong></p>
            <a href="{{ route('shop') }}" class="btn-next"
                style="display:inline-flex;margin:0 auto;text-decoration:none;">
                CONTINUA LO SHOPPING <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>

    {{-- ── RIEPILOGO ORDINE ── --}}
    <div class="checkout-summary-col">
        <div class="summary-box">
            <div class="summary-title">IL TUO ORDINE</div>

            <div class="summary-items" id="summary-items-list">
                @php
                $cart = session('cart', []);
                $subtotale = 0;
                @endphp

                @forelse($cart as $key => $item)
                @php $subtotale += $item['prezzo'] * $item['quantita']; @endphp
                <div class="summary-item">
                    <div class="summary-item-info">
                        <div class="summary-item-name">{{ $item['nome'] }}</div>
                        <div class="summary-item-meta">
                            {{ $item['brand'] ?? '' }} · Taglia {{ $item['taglia'] }} · Q.tà {{ $item['quantita'] }}
                        </div>
                    </div>
                    <span class="summary-item-price">
                        €{{ number_format($item['prezzo'] * $item['quantita'], 2) }}
                    </span>
                </div>
                @empty
                <p style="color:#555;font-size:0.8rem;padding:1rem 0;">Nessun prodotto nel carrello.</p>
                @endforelse
            </div>

            <div class="summary-totals">
                <div class="summary-row">
                    <span>Subtotale</span>
                    <span>€{{ number_format($subtotale, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Spedizione</span>
                    <span>{{ $subtotale >= 80 ? 'GRATUITA' : '€4.99' }}</span>
                </div>
                <div class="summary-row total">
                    <span>TOTALE</span>
                    @php $totale = $subtotale >= 80 ? $subtotale : $subtotale + 4.99; @endphp
                    <span>€{{ number_format($totale, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
(function() {
    /* ── STEP NAVIGATION ── */
    let currentStep = 1;
    let paymentMethod = 'card';

    function showStep(n) {
        document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.step-tab').forEach(t => {
            const tabN = parseInt(t.id.replace('tab-', ''));
            t.classList.remove('active', 'done');
            if (tabN < n) t.classList.add('done');
            if (tabN === n) t.classList.add('active');
        });
        const panel = document.getElementById('panel-' + n);
        if (panel) panel.classList.add('active');
        currentStep = n;
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    /* ── VALIDAZIONE JS STEP 1 ── */
    function validateShipping() {
        let ok = true;
        const fields = [{
                id: 'ship-name',
                err: 'err-name',
                check: v => v.trim().length >= 2
            },
            {
                id: 'ship-surname',
                err: 'err-surname',
                check: v => v.trim().length >= 2
            },
            {
                id: 'ship-email',
                err: 'err-email',
                check: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)
            },
            {
                id: 'ship-address',
                err: 'err-address',
                check: v => v.trim().length >= 5
            },
            {
                id: 'ship-city',
                err: 'err-city',
                check: v => v.trim().length >= 2
            },
            {
                id: 'ship-zip',
                err: 'err-zip',
                check: v => /^\d{5}$/.test(v.trim())
            },
        ];
        fields.forEach(f => {
            const input = document.getElementById(f.id);
            const errEl = document.getElementById(f.err);
            const valid = f.check(input.value);
            input.classList.toggle('error', !valid);
            errEl.classList.toggle('visible', !valid);
            if (!valid) ok = false;
        });
        return ok;
    }

    /* ── PAYMENT METHOD TOGGLE ── */
    document.querySelectorAll('.pay-option').forEach(opt => {
        opt.addEventListener('click', function() {
            document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            paymentMethod = this.querySelector('input').value;
            // Nasconde il finto form carta se si sceglie altro
            const cardWrapper = document.getElementById('card-fields-wrapper');
            if (cardWrapper) cardWrapper.style.display = paymentMethod === 'card' ? 'block' :
            'none';
        });
    });

    /* ── STEP 1 → 2 ── */
    document.getElementById('go-step-2').addEventListener('click', function() {
        if (!validateShipping()) return;
        showStep(2);
    });

    /* ── STEP 2 → 3: popola riepilogo ── */
    document.getElementById('go-step-3').addEventListener('click', function() {
        const name = document.getElementById('ship-name').value;
        const surname = document.getElementById('ship-surname').value;
        const address = document.getElementById('ship-address').value;
        const city = document.getElementById('ship-city').value;
        const zip = document.getElementById('ship-zip').value;

        const subtotale = {
            {
                $subtotale ?? 0
            }
        };
        const spedizione = subtotale >= 80 ? 0 : 4.99;
        const totale = subtotale + spedizione;

        const metodiLabel = {
            card: 'Carta di credito',
            applepay: 'Apple Pay',
            googlepay: 'Google Pay',
            cash: 'Alla consegna'
        };

        document.getElementById('confirm-details').innerHTML = `
            <strong style="color:#fff;letter-spacing:2px;font-family:'Bebas Neue',cursive;">DESTINATARIO</strong><br>
            ${name} ${surname}<br>
            ${address}, ${zip} ${city}<br><br>
            <strong style="color:#fff;letter-spacing:2px;font-family:'Bebas Neue',cursive;">METODO DI PAGAMENTO</strong><br>
            ${metodiLabel[paymentMethod] || paymentMethod}<br><br>
            <strong style="color:#fff;letter-spacing:2px;font-family:'Bebas Neue',cursive;">TOTALE</strong><br>
            €${totale.toFixed(2)}
        `;

        showStep(3);
    });

    /* ── PAGA ORA (fittizio) ── */
    document.getElementById('pay-now-btn').addEventListener('click', async function() {
        const btn = this;
        const errEl = document.getElementById('stripe-error-confirm');
        errEl.textContent = '';
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ELABORAZIONE…';

        try {
            const res = await fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    metodo: paymentMethod
                })
            });

            const data = await res.json();
            if (!data.success) throw new Error(data.error || 'Errore durante il pagamento');

            /* Mostra schermata di successo */
            document.querySelectorAll('.form-panel, .checkout-steps, .checkout-heading').forEach(el => {
                el.style.display = 'none';
            });
            const successEl = document.getElementById('checkout-success');
            successEl.style.display = 'block';

        } catch (err) {
            errEl.style.color = '#e63946';
            errEl.style.fontSize = '0.8rem';
            errEl.style.marginBottom = '1rem';
            errEl.textContent = err.message;
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> PAGA ORA';
        }
    });

    /* ── BACK BUTTONS ── */
    document.getElementById('back-step-1').addEventListener('click', () => showStep(1));
    document.getElementById('back-step-2').addEventListener('click', () => showStep(2));

    /* ── Empty cart redirect ── */
    @if(empty(session('cart', [])))
    window.location.href = '{{ route("cart.index") }}';
    @endif
})();
</script>
@endsection