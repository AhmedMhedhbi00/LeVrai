<header class="ecommerce-header">
    <div class="header-container">

        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('assets/images/logoW.png') }}" alt="LeVrai" class="logo-img">
        </a>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                <li>
                    <a href="{{ route('home') }}#product-section">
                        <i class="fas fa-tshirt"></i> Shop
                    </a>
                </li>
                <li><a href="{{ route('home') }}#about-section"><i class="fas fa-info-circle"></i> Chi Siamo</a></li>
                <li><a href="{{ route('home') }}#store-locator"><i class="fas fa-map-marker-alt"></i> Store</a></li>
            </ul>
        </nav>

        <div class="user-actions">

            @auth
            <button class="wishlist-btn" id="wishlist-btn" aria-label="Wishlist">
                <i class="far fa-heart"></i>
                <span class="wishlist-badge" id="wishlist-badge">0</span>
            </button>

            <button class="cart-btn" aria-label="Carrello">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-badge">0</span>
            </button>

            <div class="user-dropdown">
                <button class="user-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span>{{ auth()->user()->firstname ?: auth()->user()->name }}</span>
                </button>
                <div class="user-menu">
                    <a href="{{ route('profilo') }}"><i class="fas fa-user"></i> Profilo</a>
                    <a href="{{ route('profilo') }}#orders"><i class="fas fa-box"></i> Ordini</a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" style="color:#3a86ff;"><i class="fas fa-shield-alt"></i>
                        Pannello Admin</a>
                    @endif
                    <hr style="border-color:#222;margin:0.5rem 0;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            style="background:none;border:none;color:#e63946;font-size:1rem;cursor:pointer;display:flex;align-items:center;gap:10px;width:100%;padding:0;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="user-btn" style="text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span>Accedi</span>
            </a>
            @endauth
        </div>

        <div class="mobile-menu-container">
            {{-- CARRELLO MOBILE: Solo se loggato --}}
            @auth
            <button class="cart-btn mobile-cart" aria-label="Carrello">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-badge">0</span>
            </button>
            @endauth

            <button class="mobile-menu-btn" aria-label="Menu" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</header>

<!-- MOBILE MENU -->
<div class="mobile-menu-overlay"></div>
<nav class="mobile-menu">
    <button class="mobile-menu-close"
        style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:#fff;font-size:1.5rem;cursor:pointer;">
        <i class="fas fa-times"></i>
    </button>
    <ul>
        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="{{ auth()->check() ? route('shop') : route('login') }}"><i class="fas fa-tshirt"></i> Shop</a></li>

        @auth
        <li><a href="{{ route('profilo') }}"><i class="fas fa-user"></i> Profilo</a></li>
        <li>
            <a href="#" id="wishlist-btn-menu"><i class="far fa-heart"></i> Wishlist
                <span id="wishlist-badge-menu" class="cart-badge">0</span>
            </a>
        </li>
        @if(auth()->user()->isAdmin())
        <li>
            <a href="{{ route('admin.dashboard') }}" style="color:#3a86ff;">
                <i class="fas fa-shield-alt"></i> Pannello Admin
            </a>
        </li>
        @endif
        <!-- {{-- Link al carrello aggiunto nel menu mobile per utilità dopo il login --}}
        <li>
            <a href="#" class="cart-btn"><i class="fas fa-shopping-bag"></i> Carrello</a>
        </li> -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="background:none;border:none;color:#e63946;font-family:'Bebas Neue',cursive;font-size:1.4rem;letter-spacing:2px;cursor:pointer;display:flex;align-items:center;gap:12px;padding:1rem 0;width:100%;">
                    <i class="fas fa-sign-out-alt" style="color:#3a86ff;font-size:1rem;width:20px;"></i> Logout
                </button>
            </form>
        </li>
        @else
        <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Accedi</a></li>
        <li><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Registrati</a></li>
        @endauth
    </ul>
</nav>