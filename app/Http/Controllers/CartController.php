<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodotto;

class CartController extends Controller
{
    /**
     * Mostra il carrello
     */
    public function index()
    {
        $cart  = session()->get('cart', []);
        $totale = collect($cart)->sum(fn($i) => $i['prezzo'] * $i['quantita']);
        return view('cart', compact('cart', 'totale'));
    }

    /**
     * Aggiunge un prodotto al carrello
     */
    public function add(Request $request, $id)
    {
        $prodotto = Prodotto::findOrFail($id);

        $request->validate([
            'taglia'   => 'required',
            'quantita' => 'required|integer|min:1'
        ]);

        $cart    = session()->get('cart', []);
        $cartKey = $id . '_' . $request->taglia;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantita'] += $request->quantita;
        } else {
            $cart[$cartKey] = [
                'id'       => $prodotto->id,
                'nome'     => $prodotto->nome,
                'quantita' => $request->quantita,
                'prezzo'   => $prodotto->prezzo_scontato ?? $prodotto->prezzo,
                'prezzo_originale' => $prodotto->prezzo,
                'sconto'   => $prodotto->sconto ?? 0,
                'taglia'   => $request->taglia,
                'immagine' => $prodotto->immagine,
                'brand'    => $prodotto->brand ?? '',
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'message' => 'Prodotto aggiunto al carrello!']);
    }

    /**
     * Rimuove un prodotto dal carrello
     */
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $key  = $request->input('key');

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Aggiorna quantità
     */
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $key  = $request->input('key');
        $qty  = (int) $request->input('quantita', 1);

        if (isset($cart[$key]) && $qty > 0) {
            $cart[$key]['quantita'] = $qty;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Svuota il carrello
     */
    public function clear()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    public function getData()
    {
        $cart = session()->get('cart', []);
        $totale = collect($cart)->sum(fn($i) => $i['prezzo'] * $i['quantita']);
        return response()->json([
            'items' => array_values($cart),
            'totale' => $totale,
            'count' => collect($cart)->sum('quantita'),
        ]);
    }
}
