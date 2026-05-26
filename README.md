# LeVrai Streetwear — E-commerce Laravel

Progetto e-commerce sviluppato con Laravel 12, MySQL e Stripe.

## Stack Tecnologico
- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL (MAMP)
- **Frontend**: Blade, CSS, JavaScript vanilla
- **Pagamenti**: Stripe
- **Auth**: Laravel Auth + Google OAuth

## Funzionalità
- Shop con filtri per categoria, taglia e prezzo
- Carrello con gestione quantità e promo code
- Checkout multi-step con Stripe e pagamento alla consegna
- Autenticazione Google OAuth
- Wishlist
- Pannello Admin (prodotti, ordini, dashboard statistiche)
- Newsletter

## Installazione
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
