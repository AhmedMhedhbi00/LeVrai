<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Lista ordini — risponde JSON se chiamato da JS (Accept: application/json o XMLHttpRequest),
     * altrimenti restituisce la view HTML.
     */
    public function index(Request $request)
    {
        $ordini = Ordine::with('prodotti')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Richiesta AJAX da script.js → JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(
                $ordini->map(fn($o) => [
                    'id'         => $o->id,
                    'totale'     => $o->totale,
                    'stato'      => $o->stato,
                    'created_at' => $o->created_at,
                    'prodotti'   => $o->prodotti->map(fn($p) => [
                        'ordine_id' => $o->id,
                        'nome'      => $p->nome,
                        'taglia'    => $p->pivot->taglia,
                        'quantita'  => $p->pivot->quantita,
                        'prezzo'    => $p->pivot->prezzo_unitario,
                    ]),
                ])
            );
        }

        // Navigazione normale → view HTML
        return view('layouts.ordini', compact('ordini'));
    }
}