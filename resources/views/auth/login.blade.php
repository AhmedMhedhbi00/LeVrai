<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LeVrai Streetwear</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;900&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --primary: #3a86ff;
        --dark: #000000;
        --light: #ffffff;
        --grey: #888888;
        --border: #222222;
        --danger: #ff3333;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--dark);
        color: var(--light);
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* SINISTRA */
    .auth-left {
        background: var(--primary);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    .auth-left::before {
        content: 'LV';
        position: absolute;
        font-family: 'Bebas Neue', cursive;
        font-size: 40vw;
        color: rgba(0, 0, 0, 0.08);
        bottom: -10%;
        left: -5%;
        line-height: 1;
        pointer-events: none;
    }

    .left-logo {
        font-family: 'Bebas Neue', cursive;
        font-size: 1.8rem;
        color: var(--dark);
        letter-spacing: 4px;
    }

    .left-headline {
        position: relative;
        z-index: 1;
    }

    .left-headline h2 {
        font-family: 'Bebas Neue', cursive;
        font-size: clamp(3rem, 6vw, 5rem);
        color: var(--dark);
        line-height: 0.95;
        letter-spacing: 2px;
        margin-bottom: 1.5rem;
    }

    .left-headline p {
        font-size: 1rem;
        color: rgba(0, 0, 0, 0.65);
        max-width: 300px;
        line-height: 1.6;
    }

    .left-tag {
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(0, 0, 0, 0.5);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* DESTRA */
    .auth-right {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem 3rem;
        background: var(--dark);
        border-left: 1px solid var(--border);
        animation: slideIn 0.5s ease both;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-header {
        margin-bottom: 3rem;
    }

    .form-header h1 {
        font-family: 'Bebas Neue', cursive;
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        letter-spacing: 2px;
        line-height: 1;
        color: var(--light);
        margin-bottom: 0.5rem;
    }

    .form-header p {
        font-size: 0.9rem;
        color: var(--grey);
    }

    /* ALERT */
    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem;
        border-left: 3px solid var(--danger);
        background: rgba(255, 51, 51, 0.06);
        font-size: 0.875rem;
        color: #ff6b6b;
        margin-bottom: 2rem;
    }

    /* FORM */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--grey);
        margin-bottom: 0.6rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        background: transparent;
        border: none;
        border-bottom: 2px solid var(--border);
        color: var(--light);
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s;
        outline: none;
    }

    .input-wrapper input:focus {
        border-bottom-color: var(--primary);
    }

    .input-wrapper input::placeholder {
        color: #333;
    }

    .input-wrapper i.icon-left {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .input-wrapper:focus-within i.icon-left {
        color: var(--primary);
    }

    .toggle-password {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #444;
        cursor: pointer;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .toggle-password:hover {
        color: var(--light);
    }

    /* BTN */
    .btn-primary {
        width: 100%;
        padding: 1.1rem;
        background: var(--primary);
        color: var(--light);
        border: none;
        font-family: 'Bebas Neue', cursive;
        font-size: 1.2rem;
        letter-spacing: 3px;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary:hover {
        background: var(--light);
        color: var(--dark);
    }

    /* DIVIDER */
    .divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0 1.5rem;
        color: #333;
        font-size: 0.7rem;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    /* SOCIAL */
    .btn-social {
        width: 100%;
        padding: 0.9rem;
        background: transparent;
        border: 1px solid var(--border);
        color: var(--grey);
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 0.8rem;
        transition: all 0.2s;
        text-decoration: none;
        position: relative;
    }

    .btn-social:hover {
        border-color: var(--light);
        color: var(--light);
    }

    button.btn-social[disabled] {
        cursor: not-allowed;
        opacity: 0.4;
        pointer-events: none;
    }

    .btn-social .soon {
        position: absolute;
        right: 1rem;
        font-size: 0.65rem;
        color: #444;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* FOOTER */
    .form-footer {
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border);
        font-size: 0.85rem;
        color: var(--grey);
    }

    .form-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s;
    }

    .form-footer a:hover {
        color: var(--light);
    }

    @media (max-width: 768px) {
        body {
            grid-template-columns: 1fr;
        }

        .auth-left {
            display: none;
        }

        .auth-right {
            padding: 3rem 2rem;
        }
    }

    @media (max-width: 480px) {
        .auth-right {
            padding: 2rem 1.5rem;
        }
    }
    </style>
</head>

<body>

    <!-- SINISTRA -->
    <div class="auth-left">
        <div class="left-logo">LEVRAI</div>
        <div class="left-headline">
            <h2>STREET<br>WEAR<br>AUTENTICO</h2>
            <p>Moda urbana per chi non scende a compromessi. Stile, qualità, identità.</p>
        </div>
        <div class="left-tag">© 2025 LeVrai Streetwear</div>
    </div>

    <!-- DESTRA -->
    <div class="auth-right">
        <div class="form-header">
            <h1>BENTORNATO.</h1>
            <p>Accedi al tuo account per continuare</p>
        </div>

        {{-- ERRORI --}}
        @if ($errors->any())
        <div class="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope icon-left"></i>
                    <input type="email" name="email" placeholder="La tua email" autocomplete="email"
                        value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock icon-left"></i>
                    <input type="password" id="password" name="password" placeholder="La tua password"
                        autocomplete="current-password" required>
                    <button type="button" class="toggle-password" onclick="togglePwd('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                ACCEDI <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="divider">oppure</div>

        <a href="{{ route('google.redirect') }}" class="btn-social">
            <i class="fab fa-google"></i> Continua con Google
        </a>

        <button class="btn-social" disabled>
            <i class="fab fa-apple"></i> Continua con Apple
            <span class="soon">Presto</span>
        </button>

        <div class="form-footer">
            Non hai un account? <a href="{{ route('register') }}">Registrati ora →</a>
        </div>
    </div>

    <script>
    function togglePwd(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const username = this.querySelector('[name="email"]').value.trim();
        const password = this.querySelector('[name="password"]').value.trim();
        if (!username || !password) {
            e.preventDefault();
            alert('Compila tutti i campi.');
            return;
        }
        if (username.length < 3) {
            e.preventDefault();
            alert('Username troppo corto.');
            return;
        }
        if (password.length < 8) {
            e.preventDefault();
            alert('La password deve avere almeno 8 caratteri.');
        }
    });
    </script>
</body>

</html>