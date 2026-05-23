/* ============================================================
   LEVRAI STREETWEAR — navigazione.js v3.0 FINALE
   Carrello: Backend Laravel Session
   Wishlist: localStorage
   ============================================================ */

/* =========================
   UTILS
========================= */
const $ = (sel) => document.querySelector(sel);
const $$ = (sel) => document.querySelectorAll(sel);

function showToast(msg, type = "success") {
    let container = $("#toast-container");
    if (!container) {
        container = document.createElement("div");
        container.id = "toast-container";
        document.body.appendChild(container);
    }
    const icons = {
        success: "check-circle",
        error: "times-circle",
        wishlist: "heart",
        remove: "minus-circle",
    };
    const icon = icons[type] || "info-circle";
    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas fa-${icon}"></i> ${msg}`;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add("show"));
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

function getCsrfToken() {
    const meta = $('meta[name="csrf-token"]');
    return meta ? meta.content : "";
}

/* =========================
   STATE
========================= */
let wishlist = JSON.parse(localStorage.getItem("lv_wishlist") || "[]");
let _promoCode = "";
let currentStep = 1;
const VALID_PROMOS = { LEVRAI10: 10 };
let _serverTotal = 0;

/* =========================
   WISHLIST
========================= */
function saveWishlist() {
    localStorage.setItem("lv_wishlist", JSON.stringify(wishlist));
}

function updateWishlistBadges() {
    const c = wishlist.length;
    ["wishlist-badge", "wishlist-badge-mobile", "wishlist-badge-menu"].forEach(
        (id) => {
            const el = $(`#${id}`);
            if (el) el.textContent = c;
        },
    );
}
function renderWishlistItems() {
    const container = $("#wishlist-items");
    if (!container) return;

    if (!wishlist.length) {
        container.innerHTML =
            '<p class="wishlist-empty">La tua wishlist è vuota.</p>';
        return;
    }

    container.innerHTML = `<div style="padding:2rem;text-align:center;color:#555;">
        <i class="fas fa-spinner fa-spin"></i>
    </div>`;

    // Carica i prodotti dal backend usando gli ID nella wishlist
    const ids = wishlist.join(",");
    fetch(`/api/prodotti/wishlist?ids=${ids}`, {
        headers: { "X-CSRF-TOKEN": getCsrfToken() },
    })
        .then((r) => r.json())
        .then((prods) => {
            if (!prods.length) {
                container.innerHTML =
                    '<p class="wishlist-empty">Prodotti non trovati.</p>';
                return;
            }
            container.innerHTML = prods
                .map(
                    (p) => `
            <div class="wishlist-item">
                <img src="/assets/images/prodotti/${p.immagine}" alt="${p.nome}">
                <div class="wishlist-item-info">
                    <span class="wishlist-item-name">${p.nome}</span>
                    <span class="wishlist-item-price">€${parseFloat(p.prezzo_scontato || p.prezzo).toFixed(2)}</span>
                </div>
                <button class="wishlist-remove" data-id="${p.id}">
                    <i class="fas fa-times"></i>
                </button>
            </div>`,
                )
                .join("");

            $$(".wishlist-remove").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const id = parseInt(btn.dataset.id);
                    wishlist = wishlist.filter((x) => x !== id);
                    saveWishlist();
                    updateWishlistUI();
                    renderWishlistItems();
                    showToast("Rimosso dalla wishlist", "remove");
                });
            });
        })
        .catch(() => {
            container.innerHTML =
                '<p class="wishlist-empty">Errore caricamento.</p>';
        });
}

function updateWishlistUI() {
    updateWishlistBadges();
    $$(".wish-btn").forEach((btn) => {
        const id = parseInt(btn.dataset.id);
        const icon = btn.querySelector("i");
        const inList = wishlist.includes(id);
        if (icon) {
            icon.classList.toggle("fas", inList);
            icon.classList.toggle("far", !inList);
        }
        btn.classList.toggle("active", inList);
    });
}

function openWishlistFn() {
    renderWishlistItems();
    $("#wishlist-popup")?.classList.add("active");
    $("#wishlist-overlay")?.classList.add("active");
    document.body.style.overflow = "hidden";
}

function closeWishlistFn() {
    $("#wishlist-popup")?.classList.remove("active");
    $("#wishlist-overlay")?.classList.remove("active");
    document.body.style.overflow = "";
}

/* =========================
   CARRELLO — BACKEND SESSION
========================= */
function updateCartBadges() {
    fetch("/api/cart", { headers: { "X-CSRF-TOKEN": getCsrfToken() } })
        .then((r) => r.json())
        .then((data) => {
            $$(".cart-badge").forEach((b) => (b.textContent = data.count || 0));
            _serverTotal = data.totale || 0;
        })
        .catch(() => {});
}

function pulseCartBadge() {
    $$(".cart-badge").forEach((b) => {
        b.classList.remove("pulse");
        void b.offsetWidth;
        b.classList.add("pulse");
        setTimeout(() => b.classList.remove("pulse"), 500);
    });
}

function openCartFn() {
    closeMobileMenu?.();
    $("#cart-popup")?.classList.add("active");
    $(".cart-overlay")?.classList.add("active");
    document.body.style.overflow = "hidden";
    goToStep(currentStep);
}

function closeCartFn() {
    $("#cart-popup")?.classList.remove("active");
    $(".cart-overlay")?.classList.remove("active");
    document.body.style.overflow = "";
}

function cartTotalWithPromo(totale) {
    if (!_promoCode || !VALID_PROMOS[_promoCode]) return totale;
    return totale - (totale * VALID_PROMOS[_promoCode]) / 100;
}

function renderCartItems() {
    const container = $(".cart-items");
    const totalEl = $("#cart-total");
    if (!container) return;

    container.innerHTML = `<div style="padding:2rem;text-align:center;color:#555;"><i class="fas fa-spinner fa-spin"></i></div>`;

    fetch("/api/cart", { headers: { "X-CSRF-TOKEN": getCsrfToken() } })
        .then((r) => r.json())
        .then((data) => {
            const items = data.items || [];
            _serverTotal = data.totale || 0;
            $$(".cart-badge").forEach((b) => (b.textContent = data.count || 0));

            if (!items.length) {
                container.innerHTML = `<div class="cart-empty">
                    <i class="fas fa-shopping-bag"></i>
                    <p>Il tuo carrello e\' vuoto.</p>
                    <button class="btn-browse" onclick="closeCartFn()">CONTINUA LO SHOPPING</button>
                </div>`;
                if (totalEl) totalEl.textContent = "EUR 0,00";
                const promoEl = $("#cart-promo-discount");
                if (promoEl) promoEl.style.display = "none";
                return;
            }

            container.innerHTML = items
                .map((item) => {
                    const imgSrc = item.immagine
                        ? `/assets/images/prodotti/${item.immagine}`
                        : "/assets/images/placeholder.jpg";
                    return `<div class="cart-item" data-key="${item.id}_${item.taglia}">
                    <div class="cart-item-img"><img src="${imgSrc}" alt="${item.nome}"></div>
                    <div class="cart-item-info">
                        <span class="cart-item-name">${item.nome}</span>
                        ${item.taglia ? `<span class="cart-item-size">Taglia: ${item.taglia}</span>` : ""}
                        <div class="cart-item-qty">
                            <button class="qty-btn cart-qty-btn" data-action="minus" data-key="${item.id}_${item.taglia}"><i class="fas fa-minus"></i></button>
                            <span class="qty-val">${item.quantita}</span>
                            <button class="qty-btn cart-qty-btn" data-action="plus" data-key="${item.id}_${item.taglia}"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="cart-item-right">
                        <span class="cart-item-price">EUR ${(item.prezzo * item.quantita).toFixed(2)}</span>
                        <button class="cart-item-remove" data-key="${item.id}_${item.taglia}"><i class="fas fa-times"></i></button>
                    </div>
                </div>`;
                })
                .join("");

            const totaleFinale = cartTotalWithPromo(_serverTotal);
            if (totalEl) totalEl.textContent = `EUR ${totaleFinale.toFixed(2)}`;

            const promoEl = $("#cart-promo-discount");
            if (promoEl) {
                if (_promoCode && VALID_PROMOS[_promoCode]) {
                    const sconto = _serverTotal - totaleFinale;
                    promoEl.textContent = `PROMO -EUR ${sconto.toFixed(2)}`;
                    promoEl.style.display = "block";
                } else {
                    promoEl.style.display = "none";
                }
            }

            $$(".cart-item-remove").forEach((btn) => {
                btn.addEventListener("click", () => {
                    fetch("/api/cart/remove", {
                        method: "POST",
                        body: new URLSearchParams({
                            _token: getCsrfToken(),
                            key: btn.dataset.key,
                        }),
                    }).then(() => renderCartItems());
                });
            });

            $$(".cart-qty-btn").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const qtyEl = btn.parentElement.querySelector(".qty-val");
                    let qty = parseInt(qtyEl.textContent) || 1;
                    qty =
                        btn.dataset.action === "plus"
                            ? qty + 1
                            : Math.max(1, qty - 1);
                    fetch("/api/cart/update", {
                        method: "POST",
                        body: new URLSearchParams({
                            _token: getCsrfToken(),
                            key: btn.dataset.key,
                            quantita: qty,
                        }),
                    }).then(() => renderCartItems());
                });
            });
        })
        .catch(() => {
            container.innerHTML =
                '<p style="padding:2rem;color:#555;">Errore caricamento carrello.</p>';
        });
}

/* =========================
   CHECKOUT STEPS
========================= */
function goToStep(step) {
    currentStep = step;
    $$(".cart-step").forEach((s) => s.classList.remove("active"));
    $(`.cart-step[data-step="${step}"]`)?.classList.add("active");
    $$(".step-dot").forEach((dot) => {
        const n = parseInt(dot.dataset.step);
        dot.classList.toggle("done", n < step);
        dot.classList.toggle("current", n === step);
    });
    if (step === 1) renderCartItems();
    if (step === 4) renderConfirm();
}

function getShippingData() {
    const f = $("#shipping-form");
    if (!f) return null;
    const fields = [
        "ship-name",
        "ship-surname",
        "ship-email",
        "ship-address",
        "ship-city",
        "ship-zip",
        "ship-phone",
    ];
    const data = {};
    for (const field of fields) {
        const el = f.querySelector(`#${field}`);
        if (!el || !el.value.trim()) {
            showToast("Compila tutti i campi di spedizione", "error");
            return null;
        }
        data[field] = el.value.trim();
    }
    return data;
}

function getPaymentMethod() {
    const f = $("#payment-form");
    if (!f) return null;
    const checked = f.querySelector('input[name="payment"]:checked');
    if (!checked) {
        showToast("Seleziona un metodo di pagamento", "error");
        return null;
    }
    return checked.value;
}

function renderConfirm() {
    const box = $("#confirm-summary");
    if (!box) return;
    const ship = window._lvShipping || {};
    const method = window._lvPayMethod || "";
    const labels = {
        card: "Carta di credito",
        applepay: "Apple Pay",
        googlepay: "Google Pay",
        cash: "Alla consegna",
    };

    fetch("/api/cart", { headers: { "X-CSRF-TOKEN": getCsrfToken() } })
        .then((r) => r.json())
        .then((data) => {
            const items = data.items || [];
            const totale = cartTotalWithPromo(data.totale || 0);
            box.innerHTML = `
                <div class="confirm-section">
                    <h4>RIEPILOGO ORDINE</h4>
                    ${items
                        .map(
                            (i) => `
                        <div class="confirm-item">
                            <span>${i.nome}${i.taglia ? ` (${i.taglia})` : ""} x ${i.quantita}</span>
                            <span>EUR ${(i.prezzo * i.quantita).toFixed(2)}</span>
                        </div>`,
                        )
                        .join("")}
                    <div class="confirm-total"><span>TOTALE</span><span>EUR ${totale.toFixed(2)}</span></div>
                </div>
                <div class="confirm-section">
                    <h4>SPEDIZIONE</h4>
                    <p>${ship["ship-name"] || ""} ${ship["ship-surname"] || ""}</p>
                    <p>${ship["ship-address"] || ""}, ${ship["ship-city"] || ""} ${ship["ship-zip"] || ""}</p>
                    <p>${ship["ship-email"] || ""}</p>
                </div>
                <div class="confirm-section">
                    <h4>METODO DI PAGAMENTO</h4>
                    <p>${labels[method] || method}</p>
                </div>`;
        });
}

async function submitOrder() {
    const payBtn = $("#pay-now-btn");
    if (payBtn) {
        payBtn.disabled = true;
        payBtn.textContent = "ELABORAZIONE...";
    }
    try {
        // Recupera il metodo di pagamento selezionato
        const metodoEl = document.querySelector(
            'input[name="payment"]:checked',
        );
        const metodo = metodoEl ? metodoEl.value : "card";

        const res = await fetch("/api/checkout/process", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrfToken(),
            },
            body: JSON.stringify({ metodo: metodo }),
        });
        const data = await res.json();
        if (!data.success) throw new Error(data.error || "Errore checkout");

        updateCartBadges();
        goToStep(5);
        const orderNum = $("#order-number");
        const orderTot = $("#order-total-final");
        if (orderNum) orderNum.textContent = `#${data.ordine || "---"}`;
        if (orderTot)
            orderTot.textContent = `EUR ${parseFloat(data.totale || 0).toFixed(2)}`;
        if (typeof window.loadOrders === "function") window.loadOrders();
        showToast("Ordine completato con successo!", "success");
    } catch (err) {
        console.error(err);
        showToast(err.message || "Errore di connessione", "error");
        if (payBtn) {
            payBtn.disabled = false;
            payBtn.textContent = "PAGA ORA";
        }
    }
}

// =========================
// FILTRI PRODOTTI
// =========================

// 1. SELEZIONA TAGLIA
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".quick-size-btn");
    if (!btn) return;
    e.stopPropagation();
    const wrap = btn.closest(".product-wrap");
    if (!wrap) return;
    wrap.querySelectorAll(".quick-size-btn").forEach((b) =>
        b.classList.remove("selected"),
    );
    btn.classList.add("selected");
    const taglia = btn.dataset.taglia;
    const btnAdd = wrap.querySelector(".add-to-cart");
    if (btnAdd) {
        btnAdd.dataset.taglia = taglia;
        btnAdd.textContent = `AGGIUNGI - ${taglia}`;
        btnAdd.style.background = "#111";
    }
    const display = wrap.querySelector(".selected-size-display");
    const val = wrap.querySelector(".size-chosen-val");
    if (display && val) {
        val.textContent = taglia;
        display.style.display = "block";
    }
});

// 2. AGGIUNGI AL CARRELLO
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".add-to-cart");
    if (!btn) return;
    e.stopPropagation();
    const wrap = btn.closest(".product-wrap");
    const taglia = btn.dataset.taglia || "";
    const id = btn.dataset.id;
    if (!id) return;

    if (!taglia) {
        const card = wrap?.querySelector(".product-card");
        if (card) {
            card.classList.add("show-sizes");
            card.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }
        btn.classList.add("shake");
        const original = btn.textContent;
        btn.textContent = "SELEZIONA UNA TAGLIA";
        btn.style.background = "#e53935";
        setTimeout(() => {
            btn.classList.remove("shake");
            btn.textContent = original;
            btn.style.background = "";
        }, 2500);
        return;
    }

    const qty = parseInt(wrap?.querySelector(".qty-input")?.value) || 1;
    btn.textContent = "AGGIUNGENDO...";
    btn.disabled = true;
    btn.style.background = "#333";

    const formData = new FormData();
    formData.append("_token", getCsrfToken());
    formData.append("taglia", taglia);
    formData.append("quantita", qty);

    fetch(`/api/cart/add/${id}`, {
        method: "POST",
        body: formData,
        headers: { Accept: "application/json", "X-CSRF-TOKEN": getCsrfToken() },
    })
        .then((res) => {
            if (!res.ok) {
                // Errore HTTP (419 CSRF, 422 validazione, 500 server, ecc.)
                return res
                    .json()
                    .catch(() => ({}))
                    .then((body) => {
                        throw new Error(
                            body.message ||
                                body.error ||
                                `Errore ${res.status}`,
                        );
                    });
            }
            return res.json().catch(() => ({}));
        })
        .then(() => {
            updateCartBadges();
            pulseCartBadge();
            showToast(`Aggiunto al carrello! (${taglia})`, "success");
            btn.textContent = "AGGIUNTO";
            btn.style.background = "#2e7d32";
            setTimeout(() => {
                btn.textContent = `AGGIUNGI - ${taglia}`;
                btn.style.background = "#111";
                btn.disabled = false;
            }, 2000);
        })
        .catch((err) => {
            console.error("Errore add-to-cart:", err);
            showToast(err.message || "Errore. Riprova.", "error");
            btn.textContent = "AGGIUNGI AL CARRELLO";
            btn.disabled = false;
            btn.style.background = "";
        });
});

// 3. QTA +/-
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".qty-minus, .qty-plus");
    if (!btn) return;
    const control = btn.closest(".qty-control");
    if (!control) return;
    const input = control.querySelector(".qty-input");
    if (!input) return;
    const max = parseInt(input.max) || 99;
    let val = parseInt(input.value) || 1;
    if (btn.classList.contains("qty-plus")) val = Math.min(val + 1, max);
    if (btn.classList.contains("qty-minus")) val = Math.max(val - 1, 1);
    input.value = val;
});

// 4. MOBILE
document.addEventListener("click", function (e) {
    if (
        e.target.closest(".quick-size-btn") ||
        e.target.closest(".wish-btn") ||
        e.target.closest(".add-to-cart") ||
        e.target.closest(".qty-btn") ||
        e.target.closest(".qty-minus") ||
        e.target.closest(".qty-plus")
    )
        return;
    const card = e.target.closest(".product-card");
    if (!card || card.classList.contains("esaurito")) return;
    if (window.innerWidth <= 900) {
        card.classList.toggle("show-sizes");
    }
});

/* =========================
   DOM READY
========================= */
document.addEventListener("DOMContentLoaded", function () {
    function animateOnScroll() {
        $$(".fullscreen-section").forEach((section) => {
            if (
                section.getBoundingClientRect().top <
                window.innerHeight * 0.8
            ) {
                section.querySelectorAll(".animated-text").forEach((el, i) => {
                    setTimeout(() => el.classList.add("visible"), i * 200);
                });
            }
        });
    }
    animateOnScroll();
    window.addEventListener("scroll", animateOnScroll);

    const header = $(".ecommerce-header");
    let lastScroll = 0;
    window.addEventListener("scroll", () => {
        const cur = window.pageYOffset;
        if (header)
            header.style.top = cur > lastScroll && cur > 100 ? "-80px" : "0";
        lastScroll = cur;
    });

    const userDropdown = $(".user-dropdown");
    const userBtn = $(".user-btn");
    const userMenu = $(".user-menu");
    if (userBtn && userDropdown && userMenu) {
        userBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle("open");
        });
        userMenu.addEventListener("click", (e) => e.stopPropagation());
        document.addEventListener("click", () =>
            userDropdown.classList.remove("open"),
        );
    }

    const menuBtn = $(".mobile-menu-btn");
    const mobileMenu = $(".mobile-menu");
    const mobileOverlay = $(".mobile-menu-overlay");
    const mobileClose = $(".mobile-menu-close");

    function openMobileMenu() {
        mobileMenu?.classList.add("active");
        mobileOverlay?.classList.add("active");
        menuBtn?.classList.add("open");
        menuBtn?.setAttribute("aria-expanded", "true");
        document.body.style.overflow = "hidden";
    }
    window.closeMobileMenu = function () {
        mobileMenu?.classList.remove("active");
        mobileOverlay?.classList.remove("active");
        menuBtn?.classList.remove("open");
        menuBtn?.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";
    };
    if (menuBtn) {
        menuBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            mobileMenu?.classList.contains("active")
                ? closeMobileMenu()
                : openMobileMenu();
        });
    }
    mobileClose?.addEventListener("click", closeMobileMenu);
    mobileOverlay?.addEventListener("click", closeMobileMenu);
    mobileMenu?.addEventListener("click", (e) => e.stopPropagation());

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeMobileMenu();
            closeCartFn();
            closeWishlistFn();
        }
    });

    const scrollBtn = $("#scrollToTopBtn");
    if (scrollBtn) {
        window.addEventListener("scroll", () => {
            window.scrollY > 350
                ? scrollBtn.classList.add("visible")
                : scrollBtn.classList.remove("visible");
        });
        scrollBtn.addEventListener("click", () => {
            const shopArea = document.getElementById("shop-area");
            if (shopArea) {
                const offsetTop = shopArea.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: "smooth",
                });
            } else {
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        });
    }

    const modal = $("#image-modal");
    const modalImg = $("#modal-img");
    if (modal && modalImg) {
        $$(".gallery-item img").forEach((img) => {
            img.addEventListener("click", () => {
                modal.style.display = "flex";
                modalImg.src = img.src;
            });
        });
        $("#close-modal")?.addEventListener(
            "click",
            () => (modal.style.display = "none"),
        );
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });
    }

    const newsletterForm = $("#newsletter-form");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const email = $("#newsletter-email");
            const msg = $("#newsletter-msg");
            if (!email || !email.value.trim()) return;
            fetch("/api/newsletter", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                },
                body: JSON.stringify({ email: email.value.trim() }),
            })
                .then((r) => r.json())
                .then((data) => {
                    if (msg) {
                        msg.textContent = data.success
                            ? "Iscrizione avvenuta!"
                            : data.message || "Errore";
                        msg.style.color = data.success ? "#4CAF50" : "#e63946";
                    }
                    if (data.success) newsletterForm.reset();
                })
                .catch((err) => {
                    console.error(err);
                    if (msg) {
                        msg.textContent = "Errore connessione";
                        msg.style.color = "#e63946";
                    }
                });
        });
    }

    function loadOrders() {
        fetch("/api/ordini", {
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((r) => {
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then((data) => {
                const tbody = $("#orders-list");
                if (!tbody) return;
                if (!data.length) {
                    tbody.innerHTML =
                        '<tr><td colspan="6" style="color:#555;padding:2rem;text-align:center">Nessun ordine</td></tr>';
                    return;
                }
                // Il controller ritorna ordini con prodotti annidati
                const rows = [];
                data.forEach((o) => {
                    if (o.prodotti && o.prodotti.length) {
                        o.prodotti.forEach((p) => {
                            rows.push(`
                            <tr>
                                <td>#${o.id}</td>
                                <td>${o.created_at ? o.created_at.substring(0, 10) : "-"}</td>
                                <td>${p.nome}</td>
                                <td>${p.taglia || "-"}</td>
                                <td>${p.quantita}</td>
                                <td>EUR ${parseFloat(p.prezzo).toFixed(2)}</td>
                            </tr>`);
                        });
                    } else {
                        rows.push(`
                        <tr>
                            <td>#${o.id}</td>
                            <td>${o.created_at ? o.created_at.substring(0, 10) : "-"}</td>
                            <td colspan="3" style="color:#555;">-</td>
                            <td>EUR ${parseFloat(o.totale).toFixed(2)}</td>
                        </tr>`);
                    }
                });
                tbody.innerHTML = rows.join("");
            })
            .catch((err) => {
                console.error("Errore caricamento ordini:", err);
                showToast("Errore nel caricamento ordini", "error");
            });
    }
    window.loadOrders = loadOrders;

    const ordersPopup = $("#orders-popup");
    const ordersOverlay = $(".orders-overlay");
    const ordersClose = $(".orders-close");
    function openOrders() {
        ordersPopup?.classList.add("active");
        ordersOverlay?.classList.add("active");
        loadOrders();
    }
    function closeOrders() {
        ordersPopup?.classList.remove("active");
        ordersOverlay?.classList.remove("active");
    }
    $("#orders-btn")?.addEventListener("click", openOrders);
    $("#orders-btn-mobile")?.addEventListener("click", () => {
        closeMobileMenu();
        openOrders();
    });
    ordersClose?.addEventListener("click", closeOrders);
    ordersOverlay?.addEventListener("click", closeOrders);

    function activateSize(type) {
        $$(".size-btn").forEach((b) => b.classList.remove("active"));
        $$(".size-icons, .size-table").forEach((el) =>
            el.classList.remove("active"),
        );
        $(`.size-btn[data-size="${type}"]`)?.classList.add("active");
        $(`#${type}`)?.classList.add("active");
        $(`#${type}-table`)?.classList.add("active");
    }
    const sizeBtns = $$(".size-btn");
    sizeBtns.forEach((btn) =>
        btn.addEventListener("click", () => activateSize(btn.dataset.size)),
    );
    if (sizeBtns.length) activateSize(sizeBtns[0].dataset.size);

    const cntEl = $(".countdown[data-date]");
    if (cntEl) {
        const end = new Date(cntEl.dataset.date);
        const pad = (n) => String(n).padStart(2, "0");
        function tickCnt() {
            const diff = end - new Date();
            if (diff <= 0) return;
            cntEl.querySelector(".days").textContent = pad(
                Math.floor(diff / 86400000),
            );
            cntEl.querySelector(".hours").textContent = pad(
                Math.floor((diff % 86400000) / 3600000),
            );
            cntEl.querySelector(".minutes").textContent = pad(
                Math.floor((diff % 3600000) / 60000),
            );
            cntEl.querySelector(".seconds").textContent = pad(
                Math.floor((diff % 60000) / 1000),
            );
        }
        tickCnt();
        setInterval(tickCnt, 1000);
    }

    const headerSearch = $("#search-input");
    const mobileSearchInp = $("#search-input-mobile");
    const filterSearch = $("#filter-search");
    const searchClear = $("#search-clear");
    function syncSearch(val) {
        if (headerSearch) headerSearch.value = val;
        if (mobileSearchInp) mobileSearchInp.value = val;
        if (filterSearch) filterSearch.value = val;
        if (searchClear) searchClear.style.display = val ? "flex" : "none";
        if (typeof applyFilters === "function") applyFilters();
    }
    headerSearch?.addEventListener("input", function () {
        syncSearch(this.value);
    });
    mobileSearchInp?.addEventListener("input", function () {
        syncSearch(this.value);
    });
    filterSearch?.addEventListener("input", function () {
        syncSearch(this.value);
    });
    searchClear?.addEventListener("click", () => syncSearch(""));

    document.addEventListener("click", (e) => {
        const btn = e.target.closest(".wish-btn");
        if (!btn) return;
        const id = parseInt(btn.dataset.id);
        if (wishlist.includes(id)) {
            wishlist = wishlist.filter((x) => x !== id);
            showToast("Rimosso dalla wishlist", "remove");
        } else {
            wishlist.push(id);
            showToast("Aggiunto alla wishlist", "wishlist");
        }
        saveWishlist();
        updateWishlistUI();
    });
    ["wishlist-btn", "wishlist-btn-mobile", "wishlist-btn-menu"].forEach(
        (id) => {
            $(`#${id}`)?.addEventListener("click", openWishlistFn);
        },
    );
    $("#wishlist-close")?.addEventListener("click", closeWishlistFn);
    $("#wishlist-overlay")?.addEventListener("click", closeWishlistFn);
    updateWishlistUI();

    $$(".cart-btn").forEach((b) => {
        b.addEventListener("click", () => {
            $("#cart-popup")?.classList.contains("active")
                ? closeCartFn()
                : openCartFn();
        });
    });
    $(".cart-close")?.addEventListener("click", closeCartFn);
    $(".cart-overlay")?.addEventListener("click", closeCartFn);
    $("#cart-popup")?.addEventListener("click", (e) => e.stopPropagation());

    const cartPopup = $("#cart-popup");
    if (cartPopup) {
        cartPopup.addEventListener("click", (e) => {
            if (e.target.closest("#go-to-shipping")) {
                fetch("/api/cart", {
                    headers: { "X-CSRF-TOKEN": getCsrfToken() },
                })
                    .then((r) => r.json())
                    .then((data) => {
                        if (!data.items || !data.items.length) {
                            showToast("Il carrello e\' vuoto", "error");
                            return;
                        }
                        goToStep(2);
                    });
                return;
            }
            if (e.target.closest("#go-to-payment")) {
                const d = getShippingData();
                if (!d) return;
                window._lvShipping = d;
                goToStep(3);
            }
            if (e.target.closest("#go-to-confirm")) {
                const m = getPaymentMethod();
                if (!m) return;
                window._lvPayMethod = m;
                goToStep(4);
            }
            if (e.target.closest("#pay-now-btn")) submitOrder();
            const backBtn = e.target.closest("[data-back]");
            if (backBtn) goToStep(parseInt(backBtn.dataset.back));
            if (e.target.closest("#success-close-btn")) {
                closeCartFn();
                setTimeout(() => goToStep(1), 400);
            }
        });

        cartPopup.addEventListener("change", (e) => {
            if (e.target.name === "payment") {
                const cardBox = $("#card-fields-box");
                if (cardBox)
                    cardBox.style.display =
                        e.target.value === "card" ? "grid" : "none";
            }
        });
    }

    const promoApplyBtn = $("#promo-apply-btn");
    const promoInput = $("#promo-code-input");
    const promoFeedback = $("#promo-feedback");
    const promoDiscount = $("#cart-promo-discount");

    function applyPromo() {
        if (!promoInput || !promoFeedback) return;
        const code = promoInput.value.trim().toUpperCase();
        if (!code) {
            promoFeedback.textContent = "";
            _promoCode = "";
            if (promoDiscount) promoDiscount.style.display = "none";
            renderCartItems();
            return;
        }
        if (VALID_PROMOS[code] !== undefined) {
            _promoCode = code;
            const pct = VALID_PROMOS[code];
            const sconto = (_serverTotal * pct) / 100;
            promoFeedback.innerHTML = `<span class="promo-ok">CODICE APPLICATO - -${pct}% (-EUR ${sconto.toFixed(2)})</span>`;
            showToast(`Codice promo applicato! -${pct}%`, "success");
        } else {
            _promoCode = "";
            promoFeedback.innerHTML =
                '<span class="promo-err">CODICE NON VALIDO</span>';
            if (promoDiscount) promoDiscount.style.display = "none";
        }
        renderCartItems();
    }

    promoApplyBtn?.addEventListener("click", applyPromo);
    promoInput?.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            applyPromo();
        }
    });

    if (window.weatherData) {
        const w = window.weatherData;
        const wCity = $("#weather-city");
        if (wCity) wCity.textContent = w.city;
        const wTemp = $("#weather-temp");
        if (wTemp) wTemp.textContent = `${w.temp}C`;
        const wDesc = $("#weather-desc");
        if (wDesc) wDesc.textContent = w.description;
        const wHum = $("#weather-humidity");
        if (wHum) wHum.textContent = w.humidity;
        const wWind = $("#weather-wind");
        if (wWind) wWind.textContent = w.wind;
        const wFeels = $("#weather-feels");
        if (wFeels) wFeels.textContent = `${w.feels_like}C`;
        const wIcon = $("#weather-icon");
        if (wIcon) wIcon.src = w.icon;
        $("#weather-loading")?.style.setProperty("display", "none");
        $("#weather-data")?.style.setProperty("display", "block");
    }

    updateCartBadges();
    renderCartItems();
    goToStep(1);
    if (typeof initFilters === "function") initFilters();

    window.scrollToCategory = (cat) => {
        const el = $(`#prodotti-${cat}`);
        if (el) el.scrollIntoView({ behavior: "smooth" });
    };

    window.changeQty = function (btn, delta) {
        const input = btn.parentElement.querySelector(".qty-input");
        const newVal = Math.max(1, parseInt(input.value) + delta);
        input.value = newVal;
        input.form.submit();
    };
});

/* ============================================================
   LEVRAI — SHOP SIDEBAR + FILTRI DINAMICI
   Sistema unificato, nessun conflitto
============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    // Esiste solo nella pagina shop
    if (!document.getElementById("filtered-grid")) return;

    /* ── STATO ── */
    const stato = {
        cat: "tutti",
        size: "tutte",
        price: 500,
        sort: "default",
        search: "",
    };

    /* ── ELEMENTI DOM ── */
    const filteredGrid = document.getElementById("filtered-grid");
    const noResults = document.getElementById("no-results");
    const resultsCount = document.getElementById("results-count");
    const mobileCount = document.getElementById("mobile-results-count");
    const toolbarLabel = document.getElementById("toolbar-category-label");
    const activeFiltersEl = document.getElementById("active-filters");
    const priceVal = document.getElementById("price-val");
    const priceInput = document.getElementById("filter-price");
    const searchInput = document.getElementById("filter-search");
    const searchClear = document.getElementById("search-clear");

    /* ── RENDER GRIGLIA ── */
    function renderGrid() {
        if (!window.prodotti) return;

        let lista = [...window.prodotti];

        if (stato.cat !== "tutti")
            lista = lista.filter(
                (p) => (p.categoria || "").toLowerCase() === stato.cat,
            );
        if (stato.search) {
            const q = stato.search.toLowerCase();
            lista = lista.filter((p) => p.nome.toLowerCase().includes(q));
        }
        if (stato.size !== "tutte")
            lista = lista.filter(
                (p) => Array.isArray(p.taglie) && p.taglie.includes(stato.size),
            );
        lista = lista.filter(
            (p) => parseFloat(p.prezzo_scontato || p.prezzo) <= stato.price,
        );

        if (stato.sort === "price-asc")
            lista.sort((a, b) => a.prezzo_scontato - b.prezzo_scontato);
        if (stato.sort === "price-desc")
            lista.sort((a, b) => b.prezzo_scontato - a.prezzo_scontato);
        if (stato.sort === "name")
            lista.sort((a, b) => a.nome.localeCompare(b.nome));
        if (stato.sort === "discount")
            lista.sort((a, b) => b.sconto - a.sconto);

        const n = lista.length;
        if (resultsCount)
            resultsCount.textContent = `${n} prodott${n === 1 ? "o" : "i"}`;
        if (mobileCount)
            mobileCount.textContent = `${n} prodott${n === 1 ? "o" : "i"}`;

        const labelMap = {
            tutti: "Tutti i prodotti",
            abbigliamento: "Abbigliamento",
            scarpe: "Scarpe",
            altro: "Accessori & Altro",
        };
        if (toolbarLabel)
            toolbarLabel.textContent =
                labelMap[stato.cat] || "Tutti i prodotti";

        renderActiveTags();

        filteredGrid.innerHTML = "";
        if (n === 0) {
            if (noResults) noResults.style.display = "flex";
            return;
        }
        if (noResults) noResults.style.display = "none";

        lista.forEach((p) =>
            filteredGrid.insertAdjacentHTML("beforeend", buildCard(p)),
        );

        // Reinizializza wishlist sulle nuove card
        if (typeof updateWishlistUI === "function") updateWishlistUI();
    }

    /* ── BUILD CARD ── */
    function buildCard(p) {
        const esaurito = p.quantita <= 0;
        const scontato = p.sconto > 0;
        const img = `/assets/images/prodotti/${p.immagine}`;
        const fallback = `/assets/images/placeholder.jpg`;

        const taglie =
            Array.isArray(p.taglie) && p.taglie.length
                ? p.taglie
                      .map(
                          (t) =>
                              `<button class="quick-size-btn" data-taglia="${t}">${t}</button>`,
                      )
                      .join("")
                : "";
        const quickBar =
            !esaurito && taglie
                ? `<div class="quick-add-bar"><span class="quick-add-label">SELEZIONA TAGLIA</span><div class="quick-sizes">${taglie}</div></div>`
                : "";

        const stockBadge = esaurito
            ? `<span class="stock-badge esaurito"><i class="fas fa-times-circle"></i> Esaurito</span>`
            : p.quantita <= 5
              ? `<span class="stock-badge low"><i class="fas fa-exclamation-circle"></i> Ultimi ${p.quantita} disponibili</span>`
              : `<span class="stock-badge ok"><i class="fas fa-check-circle"></i> Disponibile (${p.quantita})</span>`;

        const prezzoHTML = scontato
            ? `<span class="current-price scontato">€${fmt(p.prezzo_scontato)}</span><span class="original-price">€${fmt(p.prezzo)}</span>`
            : `<span class="current-price">€${fmt(p.prezzo_scontato || p.prezzo)}</span>`;

        const qtyRow = !esaurito
            ? `<div class="qty-row"><span class="qty-label">QTÀ</span><div class="qty-control"><button type="button" class="qty-btn qty-minus">−</button><input type="number" class="qty-input" value="1" min="1" max="${p.quantita}"><button type="button" class="qty-btn qty-plus">+</button></div></div>`
            : "";

        const addBtn = esaurito
            ? `<button class="btn-add btn-soldout" disabled>ESAURITO</button>`
            : `<button class="btn-add add-to-cart" data-id="${p.id}" data-nome="${p.nome}" data-prezzo="${p.prezzo_scontato || p.prezzo}" data-taglia="">AGGIUNGI AL CARRELLO</button>`;

        return `
        <div class="product-wrap">
            <div class="product-card ${esaurito ? "esaurito" : ""}" data-id="${p.id}">
                <div class="card-image-wrap">
                    ${scontato ? `<div class="badge-discount">-${p.sconto}%</div>` : ""}
                    ${esaurito ? `<div class="badge-soldout">Esaurito</div>` : ""}
                    <button class="wish-btn" data-id="${p.id}"><i class="far fa-heart"></i></button>
                    <img src="${img}" class="product-image" alt="${p.nome}" loading="lazy" onerror="this.src='${fallback}'">
                    ${quickBar}
                </div>
            </div>
            <div class="product-info">
                <div class="info-top">
                    <span class="card-brand">${(p.brand || p.categoria || "").toUpperCase()}</span>
                    <div class="card-prices">${prezzoHTML}</div>
                </div>
                <h3 class="product-title">${p.nome}</h3>
                <div class="selected-size-display" style="display:none;">
                    <span class="size-chosen-label">TAGLIA: </span>
                    <span class="size-chosen-val"></span>
                </div>
                <div class="stock-info">${stockBadge}</div>
                ${qtyRow}
                ${addBtn}
            </div>
        </div>`;
    }

    function fmt(n) {
        return parseFloat(n).toFixed(2).replace(".", ",");
    }

    /* ── TAG FILTRI ATTIVI ── */
    function renderActiveTags() {
        if (!activeFiltersEl) return;
        activeFiltersEl.innerHTML = "";
        const tags = [];
        if (stato.cat !== "tutti")
            tags.push({ label: stato.cat, key: "cat", reset: "tutti" });
        if (stato.size !== "tutte")
            tags.push({
                label: `Taglia ${stato.size}`,
                key: "size",
                reset: "tutte",
            });
        if (stato.price < 500)
            tags.push({
                label: `Max €${stato.price}`,
                key: "price",
                reset: 500,
            });
        if (stato.search)
            tags.push({ label: `"${stato.search}"`, key: "search", reset: "" });

        const mobileActiveCount = document.getElementById(
            "mobile-active-count",
        );
        if (mobileActiveCount) {
            mobileActiveCount.textContent = tags.length;
            mobileActiveCount.style.display = tags.length > 0 ? "flex" : "none";
        }

        tags.forEach((t) => {
            const el = document.createElement("span");
            el.className = "active-filter-tag";
            el.innerHTML = `${t.label} <button title="Rimuovi">×</button>`;
            el.querySelector("button").addEventListener("click", () => {
                stato[t.key] = t.reset;
                if (t.key === "price" && priceInput) {
                    priceInput.value = 500;
                    if (priceVal) priceVal.textContent = "€500";
                }
                if (t.key === "search" && searchInput) {
                    searchInput.value = "";
                    if (searchClear) searchClear.style.display = "none";
                }
                syncUI();
                renderGrid();
            });
            activeFiltersEl.appendChild(el);
        });
    }

    /* ── SYNC UI ── */
    function syncUI() {
        document
            .querySelectorAll(".sidebar-cat-btn")
            .forEach((b) =>
                b.classList.toggle("active", b.dataset.cat === stato.cat),
            );
        document
            .querySelectorAll(".cat-pill")
            .forEach((b) =>
                b.classList.toggle("active", b.dataset.cat === stato.cat),
            );
        document
            .querySelectorAll(".size-chip")
            .forEach((b) =>
                b.classList.toggle("active", b.dataset.size === stato.size),
            );
        document
            .querySelectorAll(".sort-btn")
            .forEach((b) =>
                b.classList.toggle("active", b.dataset.sort === stato.sort),
            );
        updateCatCounts();
    }

    function updateCatCounts() {
        if (!window.prodotti) return;
        const bycat = {};
        window.prodotti.forEach((p) => {
            const c = (p.categoria || "").toLowerCase();
            bycat[c] = (bycat[c] || 0) + 1;
        });
        const ids = {
            "count-tutti": window.prodotti.length,
            "count-abbigliamento": bycat["abbigliamento"] || 0,
            "count-scarpe": bycat["scarpe"] || 0,
            "count-altro": bycat["altro"] || 0,
        };
        Object.entries(ids).forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        });
    }

    /* ── EVENTI FILTRI ── */
    document.querySelectorAll(".sidebar-cat-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            stato.cat = btn.dataset.cat;
            syncUI();
            renderGrid();
        });
    });
    document.querySelectorAll(".cat-pill").forEach((btn) => {
        btn.addEventListener("click", () => {
            stato.cat = btn.dataset.cat;
            syncUI();
            renderGrid();
        });
    });
    document.querySelectorAll(".size-chip").forEach((btn) => {
        btn.addEventListener("click", () => {
            stato.size = btn.dataset.size;
            syncUI();
            renderGrid();
        });
    });
    document.querySelectorAll(".sort-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            stato.sort = btn.dataset.sort;
            syncUI();
            renderGrid();
        });
    });

    if (priceInput) {
        priceInput.addEventListener("input", () => {
            stato.price = parseInt(priceInput.value);
            if (priceVal) priceVal.textContent = `€${stato.price}`;
            renderGrid();
        });
    }

    let searchTimer;
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            stato.search = searchInput.value.trim();
            if (searchClear)
                searchClear.style.display = stato.search ? "block" : "none";
            clearTimeout(searchTimer);
            searchTimer = setTimeout(renderGrid, 280);
        });
    }
    if (searchClear) {
        searchClear.addEventListener("click", () => {
            stato.search = "";
            searchInput.value = "";
            searchClear.style.display = "none";
            renderGrid();
        });
    }

    function resetAll() {
        stato.cat = "tutti";
        stato.size = "tutte";
        stato.price = 500;
        stato.sort = "default";
        stato.search = "";
        if (searchInput) searchInput.value = "";
        if (searchClear) searchClear.style.display = "none";
        if (priceInput) priceInput.value = 500;
        if (priceVal) priceVal.textContent = "€500";
        syncUI();
        renderGrid();
    }
    document
        .querySelectorAll("#filter-reset, #no-results-reset")
        .forEach((btn) => btn?.addEventListener("click", resetAll));

    /* ── SIDEBAR MOBILE DRAWER ── */
    const sidebar = document.getElementById("shop-sidebar");
    const mobileToggle = document.getElementById("mobile-sidebar-toggle");
    const sidebarClose = document.getElementById("sidebar-close");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove("open");
        document.body.style.overflow = "";
    }

    mobileToggle?.addEventListener("click", openSidebar);
    sidebarClose?.addEventListener("click", closeSidebar);
    sidebarOverlay?.addEventListener("click", closeSidebar);

    // Chiudi cliccando fuori dalla sidebar
    document.addEventListener("click", (e) => {
        if (!sidebar || !sidebar.classList.contains("open")) return;
        if (
            !sidebar.querySelector(".sidebar-panel")?.contains(e.target) &&
            !mobileToggle?.contains(e.target)
        ) {
            closeSidebar();
        }
    });

    // Chiudi con ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeSidebar();
    });

    /* ── BLOCCHI COLLASSABILI ── */
    document.querySelectorAll(".sidebar-block-toggle").forEach((btn) => {
        btn.addEventListener("click", () => {
            const body = document.getElementById(btn.dataset.target);
            if (!body) return;
            const isOpen = !body.classList.contains("closed");
            body.classList.toggle("closed", isOpen);
            btn.classList.toggle("collapsed", isOpen);
        });
    });

    /* ── TOGGLE COLONNE ── */
    document.querySelectorAll(".grid-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            document
                .querySelectorAll(".grid-btn")
                .forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            filteredGrid.className = `shop-product-grid cols-${btn.dataset.cols}`;
        });
    });

    /* ── GLOBALE per le collezioni ── */
    window.setSidebarCategory = function (cat) {
        stato.cat = cat;
        syncUI();
        renderGrid();
        document
            .getElementById("shop-area")
            ?.scrollIntoView({ behavior: "smooth" });
    };

    /* ── INIT ── */
    syncUI();
    renderGrid();
});

// DROP LEVRAI, NON TOCCARE QUESTO FILE SE NON SAI COSA FAI!

// ============================================================
// LEVRAI STREETWEAR — DROPS COUNTDOWN ENGINE
// ============================================================
document.addEventListener("DOMContentLoaded", function () {
    const countdownElement = document.querySelector(".preview-countdown");
    if (!countdownElement) return;

    // Recupera la data impostata nell'attributo data-date dell'HTML
    const targetDateStr = countdownElement.getAttribute("data-date");
    const targetDate = new Date(targetDateStr).getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const difference = targetDate - now;

        if (difference <= 0) {
            // Quando il countdown scade
            countdownElement.innerHTML =
                "<div class='time-block'><span style='color: #00ff66;'>DROP LIVE NOW</span></div>";
            const badge = document.querySelector(".status-badge");
            if (badge)
                badge.innerHTML =
                    "<span class='pulse-dot' style='background: #00ff66;'></span> AVAILABLE";
            clearInterval(intervalId);
            return;
        }

        // Calcolo di giorni, ore, minuti
        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor(
            (difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
        );
        const minutes = Math.floor(
            (difference % (1000 * 60 * 60)) / (1000 * 60),
        );

        // Formatta i numeri aggiungendo lo zero iniziale se serve (es: 05 invece di 5)
        countdownElement.querySelector(".days").innerText =
            days < 10 ? "0" + days : days;
        countdownElement.querySelector(".hours").innerText =
            hours < 10 ? "0" + hours : hours;
        countdownElement.querySelector(".minutes").innerText =
            minutes < 10 ? "0" + minutes : minutes;
    }

    // Esegue il countdown subito e poi ogni minuto (o ogni secondo se preferisci)
    updateCountdown();
    const intervalId = setInterval(updateCountdown, 60000);
});

// -- Script LeVrai brand section --
(function () {
    const allProdotti = window.prodotti || [];
    // Filtra solo brand Le Vrai
    const levraiProdotti = allProdotti.filter((p) =>
        ["levrai", "le vrai", "le_vrai"].includes(
            (p.brand || "").toLowerCase().trim(),
        ),
    );

    const grid = document.getElementById("lvb-product-grid");
    const countEl = document.getElementById("lvb-count");
    const emptyEl = document.getElementById("lvb-empty");

    // Aggiorna il contatore
    if (countEl) countEl.textContent = levraiProdotti.length + " prodotti";

    // Funzione buildCard riusa la stessa logica della shop-area, pulita da ombre e bordi bianchi
    function buildLvbCard(p) {
        const esaurito = p.quantita <= 0;
        const scontato = p.sconto > 0;
        const img = `/assets/images/prodotti/${p.immagine}`;
        const fallback = `/assets/images/placeholder.jpg`;

        const taglie =
            Array.isArray(p.taglie) && p.taglie.length
                ? p.taglie
                      .map(
                          (t) =>
                              `<button class="quick-size-btn" data-taglia="${t}">${t}</button>`,
                      )
                      .join("")
                : "";
        const quickBar =
            !esaurito && taglie
                ? `<div class="quick-add-bar"><span class="quick-add-label">SELEZIONA TAGLIA</span><div class="quick-sizes">${taglie}</div></div>`
                : "";

        const stockBadge = esaurito
            ? `<span class="stock-badge esaurito"><i class="fas fa-times-circle"></i> Esaurito</span>`
            : p.quantita <= 5
              ? `<span class="stock-badge low"><i class="fas fa-exclamation-circle"></i> Ultimi ${p.quantita} disponibili</span>`
              : `<span class="stock-badge ok"><i class="fas fa-check-circle"></i> Disponibile (${p.quantita})</span>`;

        const fmt = (n) => parseFloat(n).toFixed(2).replace(".", ",");
        const prezzoHTML = scontato
            ? `<span class="current-price scontato">€${fmt(p.prezzo_scontato)}</span><span class="original-price">€${fmt(p.prezzo)}</span>`
            : `<span class="current-price">€${fmt(p.prezzo_scontato || p.prezzo)}</span>`;

        const qtyRow = !esaurito
            ? `<div class="qty-row"><span class="qty-label">QTÀ</span><div class="qty-control"><button type="button" class="qty-btn qty-minus">−</button><input type="number" class="qty-input" value="1" min="1" max="${p.quantita}" readonly><button type="button" class="qty-btn qty-plus">+</button></div></div>`
            : "";

        const addBtn = esaurito
            ? `<button class="btn-add btn-soldout" disabled style="background:#222; color:#555; border:none; cursor:not-allowed;">ESAURITO</button>`
            : `<button class="btn-add add-to-cart" data-id="${p.id}" data-nome="${p.nome}" data-prezzo="${p.prezzo_scontato || p.prezzo}" data-taglia="" style="cursor:pointer;">AGGIUNGI AL CARRELLO</button>`;

        // Controllo manuale delle classi e dei nodi per eliminare lo sfondo bianco nativo ed ereditato
        return `
        <div class="product-wrap lvb-card-wrap" data-lvb-prod-cat="${(p.categoria || "").toLowerCase()}" style="background:transparent; box-shadow:none; border:none; margin:0;">
            <div class="product-card${esaurito ? " esaurito" : ""}" data-id="${p.id}" style="background:#0a0a0a; border:1px solid #1c1c1c; box-shadow:none; border-radius:0;">
                <div class="card-image-wrap" style="background:#000; border-bottom:1px solid #1c1c1c;">
                    ${scontato ? `<div class="badge-discount">-${p.sconto}%</div>` : ""}
                    ${esaurito ? `<div class="badge-soldout">Esaurito</div>` : ""}
                    <div class="lvb-brand-watermark">LV</div>
                    <button class="wish-btn" data-id="${p.id}" style="cursor:pointer;"><i class="far fa-heart"></i></button>
                    <img src="${img}" class="product-image" alt="${p.nome}" loading="lazy" onerror="this.src='${fallback}'">
                    ${quickBar}
                </div>
            </div>
            <div class="product-info" style="background:#0a0a0a; border:none; padding: 1.25rem;">
                <div class="info-top">
                    <span class="card-brand lvb-brand-tag" style="color: #3a86ff;">LE VRAI</span>
                    <div class="card-prices">${prezzoHTML}</div>
                </div>
                <h3 class="product-title" style="color:#fff; font-family:'Bebas Neue', sans-serif; font-size:1.4rem;">${p.nome}</h3>
                <div class="selected-size-display" style="display:none; margin: 4px 0;">
                    <span class="size-chosen-label" style="color:#aaa; font-size:0.8rem;">TAGLIA: </span>
                    <span class="size-chosen-val" style="color:#fff; font-weight:bold; font-size:0.8rem;"></span>
                </div>
                <div class="stock-info" style="margin-bottom:10px;">${stockBadge}</div>
                ${qtyRow}
                ${addBtn}
            </div>
        </div>`;
    }

    function renderLvbGrid(cat) {
        if (!grid) return;
        let lista = levraiProdotti;
        if (cat && cat !== "tutti") {
            lista = lista.filter(
                (p) => (p.categoria || "").toLowerCase() === cat,
            );
        }
        grid.innerHTML = "";
        if (lista.length === 0) {
            if (emptyEl) emptyEl.style.display = "flex";
            return;
        }
        if (emptyEl) emptyEl.style.display = "none";
        lista.forEach((p) =>
            grid.insertAdjacentHTML("beforeend", buildLvbCard(p)),
        );

        // Forza il riallineamento CSS Grid lineare a 4 colonne via codice per sicurezza
        grid.style.display = "grid";
        grid.style.gridTemplateColumns = "repeat(4, minmax(0, 1fr))";
        grid.style.gap = "20px";

        // Re-init wishlist UI globale dello shop
        if (typeof updateWishlistUI === "function") updateWishlistUI();
        // Re-init listener interni e pulsanti
        initLvbCart();
        initLvbQuickSize();
    }

    function initLvbCart() {
        // .add-to-cart è gestito dal listener globale a delegazione (riga ~465).
        // .wish-btn è gestito dal listener globale a delegazione (riga ~848).
        // Qui gestiamo solo .qty-btn che nella griglia LVB non ha delegazione globale.
        grid.querySelectorAll(".qty-btn").forEach((btn) => {
            btn.removeEventListener("click", handleQty);
            btn.addEventListener("click", handleQty);
        });
    }

    function handleWish(e) {
        const btn = e.currentTarget;
        const id = parseInt(btn.dataset.id);

        // Sincronizzazione immediata con la logica della shop area
        if (typeof toggleWishlist === "function") {
            toggleWishlist(id);
        } else if (typeof wishlist !== "undefined") {
            const idx = wishlist.indexOf(id);
            if (idx > -1) {
                wishlist.splice(idx, 1);
                btn.querySelector("i").className = "far fa-heart";
                btn.classList.remove("active");
            } else {
                wishlist.push(id);
                btn.querySelector("i").className = "fas fa-heart";
                btn.classList.add("active");
            }
            localStorage.setItem("lv_wishlist", JSON.stringify(wishlist));
            if (typeof updateWishlistUI === "function") updateWishlistUI();
            if (typeof showToast === "function") {
                showToast(
                    idx > -1
                        ? "Rimosso dalla wishlist"
                        : "Aggiunto alla wishlist!",
                    idx > -1 ? "remove" : "wishlist",
                );
            }
        }
    }

    // Correzione matematica della gestione dei pulsanti + e - della quantità
    function handleQty(e) {
        const btn = e.currentTarget;
        const wrap = btn.closest(".qty-control");
        const input = wrap?.querySelector(".qty-input");
        if (!input) return;

        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        let v = parseInt(input.value) || 1;

        if (btn.classList.contains("qty-plus")) {
            if (v < max) v++;
        } else if (btn.classList.contains("qty-minus")) {
            if (v > min) v--;
        }
        input.value = v;
    }

    function initLvbQuickSize() {
        grid.querySelectorAll(".quick-size-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                const wrap = this.closest(".product-wrap");
                const sizeDisp = wrap?.querySelector(".selected-size-display");
                const sizeVal = wrap?.querySelector(".size-chosen-val");
                const addBtn = wrap?.querySelector(".add-to-cart");
                const t = this.dataset.taglia;
                wrap?.querySelectorAll(".quick-size-btn").forEach((b) =>
                    b.classList.remove("selected"),
                );
                this.classList.add("selected");
                if (sizeVal) sizeVal.textContent = t;
                if (sizeDisp) sizeDisp.style.display = "block";
                if (addBtn) {
                    addBtn.dataset.taglia = t;
                    addBtn.textContent = `AGGIUNGI - ${t}`;
                    addBtn.style.background = "#111";
                }
            });
        });
    }

    // Filter pills
    document.querySelectorAll(".lvb-fpill").forEach((pill) => {
        pill.addEventListener("click", function () {
            document
                .querySelectorAll(".lvb-fpill")
                .forEach((p) => p.classList.remove("active"));
            this.classList.add("active");
            renderLvbGrid(this.dataset.lvbCat);
        });
    });

    // Intersection Observer per animazioni
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((el) => {
                if (el.isIntersecting) {
                    el.target.classList.add("lvb-visible");
                    observer.unobserve(el.target);
                }
            });
        },
        {
            threshold: 0.12,
        },
    );

    document.querySelectorAll("[data-lvb-anim]").forEach((el) => {
        const delay = el.dataset.lvbDelay || 0;
        el.style.transitionDelay = delay + "ms";
        observer.observe(el);
    });

    // Render iniziale della griglia a 4 colonne Le Vrai
    renderLvbGrid("tutti");

    // Forza re-render wishlist dopo caricamento per sincronizzare lo stato visivo dei cuori
    setTimeout(() => {
        if (typeof updateWishlistUI === "function") updateWishlistUI();
    }, 300);
})();

// collezione Le Vrai - END
function handleCollectionClick(category) {
    // Comunica con la sidebar dello shop
    if (typeof setSidebarCategory === "function") {
        setSidebarCategory(category);
    }

    // Scorre fluidamente fino alla sezione del catalogo per far vedere il cambio prodotti
    const shopArea =
        document.getElementById("shop-area") ||
        document.getElementById("levrai-brand-section");
    if (shopArea) {
        shopArea.scrollIntoView({ behavior: "smooth", block: "start" });
    }
}

function renderLvbMarquee() {
    const marqueeTrack = document.getElementById("lvb-marquee-dyn-track");
    if (!marqueeTrack) return;

    // Prendiamo i prodotti Le Vrai reali filtrati all'inizio dello script
    // Ne usiamo un massimo di 5 o 6 per non appesantire l'animazione
    const prodottiSpotlight = levraiProdotti.slice(0, 6);

    if (prodottiSpotlight.length === 0) {
        // Se non ci sono prodotti, nascondiamo l'intera sezione marquee
        const marqueeSec = document.getElementById("lvb-marquee-section");
        if (marqueeSec) marqueeSec.style.display = "none";
        return;
    }

    const fmt = (n) => parseFloat(n).toFixed(2).replace(".", ",");
    let htmlContent = "";

    // Genera il codice HTML per ogni prodotto reale nel database
    prodottiSpotlight.forEach((p) => {
        const prezzoFinale =
            p.sconto > 0 ? p.prezzo_scontato : p.prezzo_scontato || p.prezzo;
        const imgPath = `/assets/images/prodotti/${p.immagine}`;
        const fallbackImg = `/assets/images/placeholder.jpg`;
        const badgeHTML =
            p.sconto > 0
                ? `<span class="marquee-card-badge limited">-${p.sconto}%</span>`
                : p.quantita <= 5 && p.quantita > 0
                  ? `<span class="marquee-card-badge">ULTIMI</span>`
                  : "";

        htmlContent += `
                <div class="marquee-product-card" onclick="handleCollectionClick('${(p.categoria || "abbigliamento").toLowerCase()}')">
                    <div class="marquee-img-box">
                        <img src="${imgPath}" alt="${p.nome}" onerror="this.src='${fallbackImg}'" loading="lazy">
                        ${badgeHTML}
                    </div>
                    <div class="marquee-product-details">
                        <h4>${p.nome.toUpperCase()}</h4>
                        <span class="marquee-price">€${fmt(prezzoFinale)}</span>
                    </div>
                </div>
            `;
    });

    // Raddoppiamo l'HTML iniettato per creare il loop fluido senza interruzioni visive
    marqueeTrack.innerHTML = htmlContent + htmlContent;
}
