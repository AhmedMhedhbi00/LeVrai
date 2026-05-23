@extends('layouts.app')

@section('title', 'Carrello — LeVrai Streetwear')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/cart.css') }}">
@endsection

@section('content')
@include('layouts.header')

<main class="cart-page">
    <div class="cart-container">

        {{-- TITOLO --}}
        <div class="cart-heading">
            <span class="section-tag">— Il tuo ordine —</span>
            <h1 class="brutalist-title">IL TUO<br><span class="outline-text">CARRELLO.</span></h1>
        </div>

        @if(empty($cart))
        {{-- CARRELLO VUOTO --}}
        <div class="cart-empty">
            <i class="fas fa-shopping-bag"></i>
            <h2>Il carrello è vuoto</h2>
            <p>Non hai ancora aggiunto nulla. Esplora la collezione.</p>
            <a href="{{ route('shop') }}" class="btn-brutalist">
                VAI ALLO SHOP <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @else
        <div class="cart-layout">

            {{-- LISTA PRODOTTI --}}
            <div class="cart-items">
                @foreach($cart as $key => $item)
                <div class="cart-item">
                    <div class="cart-item-img">
                        <img src="{{ asset('assets/images/prodotti/' . $item['immagine']) }}" alt="{{ $item['nome'] }}"
                            onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                    </div>

                    <div class="cart-item-info">
                        <span class="cart-item-brand">{{ $item['brand'] ?? '' }}</span>
                        <h3 class="cart-item-name">{{ $item['nome'] }}</h3>
                        <span class="cart-item-taglia">Taglia: {{ $item['taglia'] }}</span>

                        <div class="cart-item-actions">
                            {{-- Quantità --}}
                            <form action="{{ route('cart.update') }}" method="POST" class="qty-form">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, -1)">−</button>
                                    <input type="number" name="quantita" value="{{ $item['quantita'] }}" min="1"
                                        class="qty-input" onchange="this.form.submit()">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
                                </div>
                            </form>

                            {{-- Rimuovi --}}
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" class="cart-remove-btn">
                                    <i class="fas fa-trash"></i> RIMUOVI
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="cart-item-price">
                        @if(($item['sconto'] ?? 0) > 0)
                        <span
                            class="cart-price-original">€{{ number_format($item['prezzo_originale'] ?? $item['prezzo'], 2) }}</span>
                        <span class="cart-badge">-{{ $item['sconto'] }}%</span>
                        @endif
                        <span
                            class="cart-price-current">€{{ number_format($item['prezzo'] * $item['quantita'], 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- RIEPILOGO --}}
            <div class="cart-summary">
                <div class="cart-summary-inner">
                    <h3 class="cart-summary-title">RIEPILOGO</h3>

                    <div class="cart-summary-rows">
                        <div class="cart-summary-row">
                            <span>Subtotale</span>
                            <span>€{{ number_format($totale, 2) }}</span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Spedizione</span>
                            <span>{{ $totale >= 80 ? 'GRATUITA' : '€4.99' }}</span>
                        </div>
                        @if($totale < 80) <div class="cart-summary-promo">
                            <i class="fas fa-info-circle"></i>
                            Aggiungi €{{ number_format(80 - $totale, 2) }} per la spedizione gratuita
                    </div>
                    @endif
                    <div class="cart-summary-divider"></div>
                    <div class="cart-summary-row total">
                        <span>TOTALE</span>
                        <span>€{{ number_format($totale + ($totale >= 80 ? 0 : 4.99), 2) }}</span>
                    </div>
                </div>

                <button id="btn-checkout" class="btn-checkout">
                    <i class="fas fa-lock"></i> PROCEDI AL PAGAMENTO
                </button>

                <div class="cart-summary-badges">
                    <span><i class="fas fa-shield-alt"></i> Pagamento sicuro</span>
                    <span><i class="fab fa-stripe"></i> Powered by Stripe</span>
                </div>

                <form action="{{ route('cart.clear') }}" method="POST" class="cart-clear-form">
                    @csrf
                    <button type="submit" class="cart-clear-btn">Svuota carrello</button>
                </form>
            </div>
        </div>

    </div>
    @endif

    </div>
</main>
{{-- MODAL CHECKOUT --}}
<div id="checkout-modal" class="checkout-modal-overlay" style="display:none;">
    <div class="checkout-modal">
        <button id="modal-close" class="modal-close-btn">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-left">
            <span class="modal-tag">— Riepilogo Ordine —</span>
            <h2 class="modal-title">CHECKOUT</h2>

            <div class="modal-items">
                @foreach($cart as $item)
                <div class="modal-item">
                    <img src="{{ asset('assets/images/prodotti/' . $item['immagine']) }}" alt="{{ $item['nome'] }}"
                        onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                    <div class="modal-item-info">
                        <span class="modal-item-name">{{ $item['nome'] }}</span>
                        <span class="modal-item-detail">{{ $item['taglia'] }} × {{ $item['quantita'] }}</span>
                    </div>
                    <span class="modal-item-price">€{{ number_format($item['prezzo'] * $item['quantita'], 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="modal-total-row">
                <span>TOTALE</span>
                <span>€{{ number_format($totale + ($totale >= 80 ? 0 : 4.99), 2) }}</span>
            </div>
        </div>

        <div class="modal-right">
            <span class="modal-tag">— Metodo di pagamento —</span>

            {{-- Selettore Metodi --}}
            <div class="payment-methods-grid"
                style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                <div class="method-option selected" data-method="carta_credito"
                    style="border: 1px solid #fff; padding: 10px; cursor: pointer; text-align: center;">
                    <i class="fas fa-credit-card"></i> CARTA
                </div>
                <div class="method-option" data-method="alla_consegna"
                    style="border: 1px solid #333; padding: 10px; cursor: pointer; text-align: center;">
                    <i class="fas fa-truck"></i> CONSEGNA
                </div>
            </div>

            <div id="payment-message" class="payment-message" style="display:none; margin-bottom: 15px;"></div>

            <div id="card-fields">
                <div class="stripe-field-group">
                    <label>NUMERO CARTA</label>
                    <input type="text" class="stripe-input-text" placeholder="4242 4242 4242 4242" maxlength="16">
                </div>
                <div class="stripe-field-row" style="display:flex; gap:10px; margin-top:10px;">
                    <div class="stripe-field-group" style="flex:1;">
                        <label>SCADENZA</label>
                        <input type="text" class="stripe-input-text" placeholder="12/26">
                    </div>
                    <div class="stripe-field-group" style="flex:1;">
                        <label>CVC</label>
                        <input type="text" class="stripe-input-text" placeholder="123">
                    </div>
                </div>
            </div>

            <button id="pay-now-btn" class="pay-btn" style="margin-top: 20px; width: 100%;">
                <span id="pay-btn-text">
                    <i class="fas fa-lock"></i>
                    PAGA €{{ number_format($totale + ($totale >= 80 ? 0 : 4.99), 2) }}
                </span>
                <span id="pay-btn-loading" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i> ELABORAZIONE...
                </span>
            </button>

            <div class="stripe-secure" style="margin-top: 15px; font-size: 12px; color: #888;">
                <i class="fas fa-shield-alt"></i> Pagamento sicuro crittografato
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
@endsection

@section('scripts')
<script>
// 1. Gestione Modal
const modal = document.getElementById('checkout-modal');
const btnCheckout = document.getElementById('btn-checkout');
const btnClose = document.getElementById('modal-close');

btnCheckout.addEventListener('click', () => {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
});

btnClose.addEventListener('click', closeModal);

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// 2. Selezione Metodo di Pagamento
const methodOptions = document.querySelectorAll('.method-option');
let metodoSelezionato = 'carta_credito';

methodOptions.forEach(opt => {
    opt.addEventListener('click', function() {
        methodOptions.forEach(o => o.style.borderColor = '#333');
        this.style.borderColor = '#fff';
        metodoSelezionato = this.getAttribute('data-method');

        // Nascondi i campi carta se scegli consegna
        const cardFields = document.getElementById('card-fields');
        cardFields.style.opacity = (metodoSelezionato === 'alla_consegna') ? '0.3' : '1';
    });
});

// 3. Funzioni Utility
function showMessage(msg, type = 'error') {
    const el = document.getElementById('payment-message');
    el.textContent = msg;
    el.style.display = 'block';
    el.style.color = type === 'success' ? '#4caf50' : '#ff5252';
}

function setLoading(loading) {
    const btn = document.getElementById('pay-now-btn');
    document.getElementById('pay-btn-text').style.display = loading ? 'none' : 'flex';
    document.getElementById('pay-btn-loading').style.display = loading ? 'flex' : 'none';
    btn.disabled = loading;
}

// 4. INVIO ORDINE
document.getElementById('pay-now-btn').addEventListener('click', async () => {
    setLoading(true);
    console.log('Inviando ordine con metodo:', metodoSelezionato);

    try {
        const response = await fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                metodo: metodoSelezionato
            })
        });

        const result = await response.json();

        if (result.success) {
            showMessage('✅ ORDINE COMPLETATO!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("profilo") }}';
            }, 2000);
        } else {
            showMessage(result.error || 'Errore durante il pagamento');
            setLoading(false);
        }
    } catch (e) {
        console.error(e);
        showMessage('Errore di connessione al server.');
        setLoading(false);
    }
});
</script>
@endsection

<!-- script per pagamento reale  -->
<!-- <script>
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();

const style = {
    base: {
        color: '#0a0a0a',
        fontFamily: "'Inter', sans-serif",
        fontSize: '15px',
        '::placeholder': {
            color: '#aaa'
        },
    },
    invalid: {
        color: '#e53935'
    }
};

const cardNumber = elements.create('cardNumber', {
    style
});
const cardExpiry = elements.create('cardExpiry', {
    style
});
const cardCvc = elements.create('cardCvc', {
    style
});

cardNumber.mount('#card-number-element');
cardExpiry.mount('#card-expiry-element');
cardCvc.mount('#card-cvc-element');

// Apri modal
document.getElementById('btn-checkout').addEventListener('click', () => {
    document.getElementById('checkout-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
});

// Chiudi modal
document.getElementById('modal-close').addEventListener('click', closeModal);
document.getElementById('checkout-modal').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) closeModal();
});

function closeModal() {
    document.getElementById('checkout-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function showMessage(msg, type = 'error') {
    const el = document.getElementById('payment-message');
    el.textContent = msg;
    el.className = 'payment-message ' + type;
    el.style.display = 'block';
    setTimeout(() => el.style.display = 'none', 6000);
}

function setLoading(loading) {
    document.getElementById('pay-btn-text').style.display = loading ? 'none' : 'flex';
    document.getElementById('pay-btn-loading').style.display = loading ? 'flex' : 'none';
    document.getElementById('pay-btn').disabled = loading;
}

// Pagamento
document.getElementById('pay-btn').addEventListener('click', async () => {
    setLoading(true);

    try {
        // 1. Crea PaymentIntent
        const res = await fetch('{{ route("checkout.intent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        });

        const data = await res.json();

        if (!res.ok || data.error) {
            showMessage(data.error || 'Errore nella creazione del pagamento.');
            setLoading(false);
            return;
        }

        // 2. Conferma pagamento con Stripe
        const {
            paymentIntent,
            error
        } = await stripe.confirmCardPayment(
            data.clientSecret, {
                payment_method: {
                    card: cardNumber,
                    billing_details: {
                        name: document.getElementById('cardholder-name').value || 'Cliente'
                    }
                }
            }
        );

        if (error) {
            showMessage(error.message);
            setLoading(false);
            return;
        }

        if (paymentIntent.status === 'succeeded') {
            // 3. Conferma ordine nel backend
            const confirm = await fetch('{{ route("checkout.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntent.id
                })
            });

            const confirmData = await confirm.json();

            if (confirmData.success) {
                showMessage('✅ ' + confirmData.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("ordini") }}';
                }, 2000);
            } else {
                showMessage(confirmData.error || 'Errore nella conferma.');
                setLoading(false);
            }
        }

    } catch (e) {
        showMessage('Errore di connessione. Riprova.');
        setLoading(false);
    }
});
</script> -->
@endsection