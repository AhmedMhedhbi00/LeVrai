<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfiloController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── PUBBLICHE ─────────────────────────────────────────────────────────
Route::get('/',              [ShopController::class, 'home'])->name('home');
Route::get('/prodotto/{id}', [ShopController::class, 'show'])->name('prodotto.show');

// ── AUTH ──────────────────────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── GOOGLE OAuth ──────────────────────────────────────────────────────
Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// ── NEWSLETTER ────────────────────────────────────────────────────────
Route::post('/api/newsletter', [NewsletterController::class, 'subscribe'])->name('api.newsletter');

// ── ADMIN ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/prodotti',                      [AdminController::class, 'prodotti'])->name('prodotti');
    Route::get('/prodotti/crea',                 [AdminController::class, 'crea'])->name('prodotti.crea');
    Route::post('/prodotti',                     [AdminController::class, 'store'])->name('prodotti.store');
    Route::get('/prodotti/{id}/modifica',        [AdminController::class, 'modifica'])->name('prodotti.modifica');
    Route::post('/prodotti/{id}/update',         [AdminController::class, 'update'])->name('prodotti.update');
    Route::post('/prodotti/{id}/elimina',        [AdminController::class, 'elimina'])->name('prodotti.elimina');
    Route::post('/prodotti/{id}/quantita',       [AdminController::class, 'aggiornaQuantita'])->name('prodotti.quantita');
    Route::get('/ordini',                        [AdminController::class, 'ordini'])->name('ordini');
    Route::post('/ordini/{id}/stato',            [AdminController::class, 'aggiornaStatoOrdine'])->name('ordini.stato');
});

// ── AREA RISERVATA ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/shop',     [ShopController::class, 'index'])->name('shop');
    Route::get('/checkout', fn() => view('layouts.checkout'))->name('checkout');
    Route::get('/profilo',           [ProfiloController::class, 'index'])->name('profilo');
    Route::post('/profilo/update',   [ProfiloController::class, 'updateProfile'])->name('profilo.update');
    Route::post('/profilo/password', [ProfiloController::class, 'changePassword'])->name('profilo.password');

    Route::prefix('api')->group(function () {
        Route::get('/prodotti', function () {
            $prodotti = App\Models\Prodotto::select('id', 'nome', 'brand', 'categoria', 'prezzo', 'prezzo_scontato', 'sconto', 'immagine', 'quantita', 'taglie')->get();
            return response()->json($prodotti->map(fn($p) => [
                'id'              => $p->id,
                'nome'            => $p->nome,
                'brand'           => $p->brand ?? '',
                'categoria'       => strtolower(trim($p->categoria ?? 'altro')),
                'prezzo'          => (float) $p->prezzo,
                'prezzo_scontato' => (float) $p->prezzo_scontato,
                'sconto'          => (int) ($p->sconto ?? 0),
                'immagine'        => $p->immagine ?? 'default.jpg',
                'quantita'        => (int) $p->quantita,
                'taglie'          => array_values(array_filter(array_map('trim', explode(',', $p->taglie ?? '')))),
            ]));
        });

        Route::get('/prodotti/wishlist', function () {
            $ids = array_filter(array_map('intval', explode(',', request('ids', ''))));
            if (empty($ids)) return response()->json([]);
            return response()->json(App\Models\Prodotto::select('id', 'nome', 'immagine', 'prezzo', 'prezzo_scontato', 'sconto', 'brand', 'categoria')->whereIn('id', $ids)->get());
        });

        Route::get('/cart',           [CartController::class, 'getData']);
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('api.cart.add');
        Route::post('/cart/remove',   [CartController::class, 'remove'])->name('api.cart.remove');
        Route::post('/cart/update',   [CartController::class, 'update'])->name('api.cart.update');
        Route::post('/cart/clear',    [CartController::class, 'clear'])->name('api.cart.clear');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('api.checkout.process');
        Route::post('/checkout/intent',  [CheckoutController::class, 'createIntent'])->name('api.checkout.intent');
        Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('api.checkout.confirm');
        Route::get('/ordini', [OrderController::class, 'index'])->name('api.ordini');
        Route::get('/user', fn(Request $r) => response()->json($r->user()));
    });
});
