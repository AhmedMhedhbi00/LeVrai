<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:;">
    <title>@yield('title', 'LeVrai Streetwear')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    @yield('styles')
</head>



<body>

    @yield('content')

    <button id="scrollToTopBtn" aria-label="Torna su">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- CART POPUP -->
    <div id="cart-popup">
        <div class="cart-header">
            <h3>IL TUO CARRELLO</h3>
            <button class="cart-close"><i class="fas fa-times"></i></button>
        </div>

        <!-- STEP INDICATOR -->
        <div class="step-indicator">
            <div class="step-dot current" data-step="1">1</div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="2">2</div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="3">3</div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="4">4</div>
        </div>

        <!-- STEP 1: CARRELLO -->
        <div class="cart-step active" data-step="1">
            <div class="cart-items"></div>
            <div class="cart-footer">
                <div class="promo-box">
                    <input type="text" id="promo-code-input" placeholder="CODICE PROMO">
                    <button id="promo-apply-btn">APPLICA</button>
                </div>
                <div id="promo-feedback"></div>
                <div id="cart-promo-discount" style="display:none;"></div>
                <p>Totale: <strong id="cart-total">€0.00</strong></p>
                <button id="go-to-shipping" class="checkout-btn">PROCEDI <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- STEP 2: SPEDIZIONE -->
        <div class="cart-step" data-step="2">
            <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                <h4 style="font-family:'Bebas Neue',cursive;letter-spacing:2px;margin-bottom:1.5rem;">DATI DI SPEDIZIONE
                </h4>
                <form id="shipping-form">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><label>Nome</label><input type="text" id="ship-name" placeholder="Nome" required></div>
                        <div><label>Cognome</label><input type="text" id="ship-surname" placeholder="Cognome" required>
                        </div>
                        <div style="grid-column:1/-1"><label>Email</label><input type="email" id="ship-email"
                                placeholder="Email" required></div>
                        <div style="grid-column:1/-1"><label>Indirizzo</label><input type="text" id="ship-address"
                                placeholder="Via, numero" required></div>
                        <div><label>Città</label><input type="text" id="ship-city" placeholder="Città" required></div>
                        <div><label>CAP</label><input type="text" id="ship-zip" placeholder="00000" required></div>
                        <div style="grid-column:1/-1"><label>Telefono</label><input type="tel" id="ship-phone"
                                placeholder="+39 000 000 0000" required></div>
                    </div>
                </form>
            </div>
            <div class="cart-footer">
                <button data-back="1" class="btn-back"><i class="fas fa-arrow-left"></i> INDIETRO</button>
                <button id="go-to-payment" class="checkout-btn">CONTINUA <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- STEP 3: PAGAMENTO -->
        <div class="cart-step" data-step="3">
            <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                <h4 style="font-family:'Bebas Neue',cursive;letter-spacing:2px;margin-bottom:1.5rem;">METODO DI
                    PAGAMENTO</h4>
                <form id="payment-form">
                    <div class="pay-methods">
                        <label class="pay-option"><input type="radio" name="payment" value="card"> <i
                                class="fas fa-credit-card"></i> Carta di credito</label>
                        <label class="pay-option"><input type="radio" name="payment" value="applepay"> <i
                                class="fab fa-apple"></i> Apple Pay</label>
                        <label class="pay-option"><input type="radio" name="payment" value="googlepay"> <i
                                class="fab fa-google-pay"></i> Google Pay</label>
                        <label class="pay-option"><input type="radio" name="payment" value="cash"> <i
                                class="fas fa-truck"></i> Alla consegna</label>
                    </div>
                    <div id="card-fields-box" style="display:none;margin-top:1rem;gap:0.8rem;"> <input type="text"
                            id="card-number" placeholder="Numero carta (16 cifre)" maxlength="19">
                        <input type="text" id="card-name" placeholder="Intestatario">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;">
                            <input type="text" id="card-expiry" placeholder="MM/AA" maxlength="5">
                            <input type="text" id="card-cvv" placeholder="CVV" maxlength="4">
                        </div>
                    </div>
                </form>
            </div>
            <div class="cart-footer">
                <button data-back="2" class="btn-back"><i class="fas fa-arrow-left"></i> INDIETRO</button>
                <button id="go-to-confirm" class="checkout-btn">CONFERMA <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- STEP 4: RIEPILOGO -->
        <div class="cart-step" data-step="4">
            <div id="confirm-summary" style="padding:1.5rem; overflow-y:auto; flex:1;"></div>
            <div class="cart-footer">
                <button data-back="3" class="btn-back"><i class="fas fa-arrow-left"></i> INDIETRO</button>
                <button id="pay-now-btn" class="checkout-btn">PAGA ORA <i class="fas fa-lock"></i></button>
            </div>
        </div>

        <!-- STEP 5: SUCCESSO -->
        <div class="cart-step" data-step="5">
            <div
                style="padding:3rem 2rem; text-align:center; flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <i class="fas fa-check-circle" style="font-size:3rem;color:#3a86ff;margin-bottom:1.5rem;"></i>
                <h3 style="font-family:'Bebas Neue',cursive;font-size:1.8rem;letter-spacing:3px;margin-bottom:0.5rem;">
                    ORDINE CONFERMATO!</h3>
                <p style="color:#888;margin-bottom:0.5rem;">Ordine <strong id="order-number">#---</strong></p>
                <p style="color:#888;margin-bottom:2rem;">Totale: <strong id="order-total-final">€0.00</strong></p>
                <button id="success-close-btn" class="checkout-btn">CONTINUA LO SHOPPING</button>
            </div>
        </div>
    </div>
    <div class="cart-overlay"></div>
    @auth
    <!-- WISHLIST POPUP -->
    <div id="wishlist-popup">
        <div class="cart-header">
            <h3>LA TUA WISHLIST</h3>
            <button id="wishlist-close"><i class="fas fa-times"></i></button>
        </div>
        <div id="wishlist-items" style="padding:1rem; flex:1; overflow-y:auto;"></div>
    </div>
    <div id="wishlist-overlay"></div>
    @endauth

    <!-- TOAST -->
    <div id="toast-container"></div>
    @yield('scripts')
    <script src="{{ asset('assets/script.js') }}?v={{ filemtime(public_path('assets/script.js')) }}"></script>
</body>

</html>


<!-- DOPO -->