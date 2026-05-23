<footer>
    <div class="footer-container">

        <!-- BRAND -->
        <div class="footer-brand">
            <h3 class="footer-title">LEVRAI</h3>
            <p style="font-size:0.875rem;color:#888;line-height:1.6;">
                Moda urbana autentica per chi non scende a compromessi. Stile, qualità, identità.
            </p>
            <div class="social-icons">
                <a href="https://www.instagram.com/levraistreetwear?igsh=MTFrMW9tbnFycHBsag==" target="_blank"
                    rel="noopener" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <!-- LINK RAPIDI -->
        <div class="footer-links">
            <h4 class="footer-title">Link Rapidi</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ auth()->check() ? route('shop') : route('login') }}">Shop</a></li>
                <li><a href="{{ route('home') }}#about-section">Chi Siamo</a></li>
                <li><a href="{{ route('home') }}#recensioni">Recensioni</a></li>
                @auth
                <li><a href="{{ route('profilo') }}">Il mio profilo</a></li>
                @else
                <li><a href="{{ route('login') }}">Accedi</a></li>
                <li><a href="{{ route('register') }}">Registrati</a></li>
                @endauth
            </ul>
        </div>

        <!-- CONTATTI -->
        <div class="footer-contacts">
            <h4 class="footer-title">Contatti</h4>
            <ul>
                <li><a href="mailto:info@levraistreetwear.com"><i class="fas fa-envelope"
                            style="margin-right:6px;color:#3a86ff;"></i> info@levraistreetwear.com</a></li>
                <li><a href="tel:+393391997578"><i class="fas fa-phone" style="margin-right:6px;color:#3a86ff;"></i> +39
                        339 199 7578</a></li>
                <li style="color:#888;"><i class="fas fa-map-marker-alt" style="margin-right:6px;color:#3a86ff;"></i>
                    Via Palermo 7, Caltagirone (CT)</li>
            </ul>
        </div>

        <!-- ORARI -->
        <div class="footer-hours">
            <h4 class="footer-title">Orari Store</h4>
            <ul>
                <li>Lun — Ven: 10:00 — 22:00</li>
                <li>Sabato: 14:00 — 20:00</li>
                <li>Domenica: Chiusi</li>
            </ul>
        </div>

        <!-- NEWSLETTER -->
        <div class="footer-newsletter">
            <h4 class="footer-title">Newsletter</h4>
            <p class="newsletter-text">Iscriviti e ricevi il -10% sul primo ordine.</p>
            <form class="newsletter-form" id="newsletter-form-footer">
                <input type="email" id="newsletter-email-footer" placeholder="La tua email" required>
                <button type="submit">ISCRIVITI <i class="fas fa-arrow-right"></i></button>
                <p class="newsletter-msg" id="newsletter-msg-footer"></p>
            </form>
            <small class="newsletter-note">Niente spam. Solo drop esclusivi.</small>
        </div>

    </div>

    <div class="footer-copyright">
        © 2025 LeVrai Streetwear — Tutti i diritti riservati — P.IVA 00000000000
    </div>
</footer>

<script>
// Newsletter footer
document.getElementById('newsletter-form-footer')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email-footer').value.trim();
    const msg = document.getElementById('newsletter-msg-footer');
    if (!email) return;

    fetch('/newsletter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email
            })
        })
        .then(r => r.json())
        .then(data => {
            msg.textContent = data.success ? 'Iscrizione avvenuta! 📩' : data.message;
            msg.style.color = data.success ? '#4CAF50' : '#e63946';
            if (data.success) this.reset();
        })
        .catch(() => {
            msg.textContent = 'Errore connessione';
            msg.style.color = '#e63946';
        });
});
</script>