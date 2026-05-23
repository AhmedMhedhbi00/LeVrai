<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crea un PaymentIntent Stripe reale
     */
    public function createIntent(Request $request): JsonResponse
    {
        try {
            $cart = session('cart', []);

            if (empty($cart)) {
                return response()->json(['error' => 'Carrello vuoto'], 400);
            }

            // Calcola il totale dal carrello
            $totale = 0;
            foreach ($cart as $item) {
                $prezzo  = $item['prezzo_scontato'] ?? $item['prezzo'] ?? 0;
                $totale += $prezzo * ($item['quantita'] ?? 1);
            }

            if ($totale <= 0) {
                return response()->json(['error' => 'Totale non valido'], 400);
            }

            // Stripe vuole i centesimi (es. €12.50 → 1250)
            $intent = PaymentIntent::create([
                'amount'   => (int) round($totale * 100),
                'currency' => 'eur',
                'metadata' => [
                    'user_id' => auth()->id(),
                ],
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
                'totale'       => $totale,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe createIntent error: ' . $e->getMessage());
            return response()->json(['error' => 'Errore nella creazione del pagamento'], 500);
        }
    }

    /**
     * Conferma il pagamento e salva l'ordine nel DB
     */ public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            // 1. Controllo Idempotenza: L'ordine esiste già?
            $ordineEsistente = Ordine::where('payment_intent_id', $request->payment_intent_id)->first();
            if ($ordineEsistente) {
                return response()->json(['success' => true, 'ordine' => $ordineEsistente->id]);
            }

            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            if ($intent->status !== 'succeeded') {
                return response()->json(['error' => 'Pagamento non verificato'], 400);
            }

            $cart = session('cart', []);
            if (empty($cart)) return response()->json(['error' => 'Sessione scaduta'], 400);

            // 2. Transazione DB
            $ordine = DB::transaction(function () use ($cart, $request) {
                $totaleReale = 0;
                $itemsToAttach = [];

                foreach ($cart as $item) {
                    // Recupero il prodotto dal DB per sicurezza sul prezzo e stock
                    $prodotto = Prodotto::lockForUpdate()->find($item['id']);

                    if (!$prodotto || $prodotto->quantita < ($item['quantita'] ?? 1)) {
                        throw new \Exception("Prodotto {$prodotto->nome} non disponibile o stock insufficiente.");
                    }

                    $prezzoUnitario = $prodotto->prezzo_scontato ?? $prodotto->prezzo;
                    $totaleReale += $prezzoUnitario * ($item['quantita'] ?? 1);

                    $itemsToAttach[$prodotto->id] = [
                        'quantita' => $item['quantita'] ?? 1,
                        'taglia' => $item['taglia'] ?? null,
                        'prezzo_unitario' => $prezzoUnitario,
                    ];

                    // Scala stock
                    $prodotto->decrement('quantita', $item['quantita'] ?? 1);
                }

                // 3. Creazione Ordine
                $nuovoOrdine = Ordine::create([
                    'user_id' => auth()->id(),
                    'totale' => $totaleReale,
                    'stato' => 'pagato',
                    'metodo_pagamento' => 'stripe',
                    'payment_intent_id' => $request->payment_intent_id, // Fondamentale salvarlo
                ]);

                $nuovoOrdine->prodotti()->attach($itemsToAttach);

                return $nuovoOrdine;
            });

            session()->forget('cart');

            return response()->json([
                'success' => true,
                'ordine' => $ordine->id,
                'message' => 'Ordine confermato con successo!',
            ]);
        } catch (\Exception $e) {
            Log::error('Errore Checkout: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Processo di checkout fittizio ottimizzato per più metodi di pagamento
     */
    public function process(Request $request): JsonResponse
    {
        $cart = session()->get('cart', []);
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Sessione scaduta, effettua il login.'], 401);
        }

        if (empty($cart)) {
            return response()->json(['success' => false, 'error' => 'Il carrello è vuoto.'], 400);
        }

        try {
            return DB::transaction(function () use ($cart, $user, $request) {

                // 1. Recupero metodo di pagamento dal frontend
                // Se non inviato, default 'carta_credito'
                $metodoInviato = $request->input('metodo', 'carta_credito');

                // 2. Calcolo Totale
                $subtotale = array_reduce($cart, function ($carry, $item) {
                    return $carry + ($item['prezzo'] * $item['quantita']);
                }, 0);

                $spedizione = $subtotale >= 80 ? 0 : 4.99;
                $totaleFinale = $subtotale + $spedizione;

                // 3. Logica di stato dinamica
                // Se è alla consegna, mettiamo 'pending', altrimenti 'completato'
                $statoOrdine = ($metodoInviato === 'alla_consegna' || $metodoInviato === 'contrassegno')
                    ? 'pending'
                    : 'completato';

                // 4. Creazione Ordine
                $ordine = Ordine::create([
                    'user_id'          => $user->id,
                    'totale'           => $totaleFinale,
                    'stato'            => $statoOrdine,
                    'metodo_pagamento' => substr($metodoInviato, 0, 50),
                ]);

                // 5. Associazione Prodotti
                foreach ($cart as $item) {
                    $ordine->prodotti()->attach($item['id'], [
                        'quantita'        => $item['quantita'],
                        'prezzo_unitario' => $item['prezzo'],
                        'taglia'          => $item['taglia'] ?? 'N/D'
                    ]);

                    // Aggiornamento stock
                    $prodotto = Prodotto::find($item['id']);
                    if ($prodotto) {
                        $prodotto->decrement('quantita', $item['quantita']);
                    }
                }

                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'message' => 'Ordine ricevuto con successo!'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Errore Checkout LE VRAI: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Errore tecnico: ' . $e->getMessage()
            ], 500);
        }
    }
}