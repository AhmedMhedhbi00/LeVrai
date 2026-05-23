<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Assicurati che sia questo
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfiloController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Aggiungiamo un attributo dinamico per il nome completo se mancasse nel DB
        $user->full_name = trim($user->firstname . ' ' . $user->lastname) ?: $user->name;

        $ordini_raw = Ordine::with('prodotti')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $ordini = $ordini_raw->map(function ($o) {
            return [
                'id'         => $o->id,
                'totale'     => $o->totale,
                'stato'      => $o->stato,
                'created_at' => $o->created_at,
                'prodotti'   => $o->prodotti->map(fn($p) => [
                    'nome'     => $p->nome,
                    'immagine' => $p->immagine,
                    'taglia'   => $p->pivot->taglia ?? '-',
                    'quantita' => $p->pivot->quantita ?? 1,
                    'prezzo'   => $p->pivot->prezzo_unitario ?? $p->prezzo,
                ])->toArray(),
            ];
        });

        $tot_ordini   = $ordini->count();
        $tot_spesa    = $ordini->sum('totale');
        $tot_prodotti = $ordini->sum(fn($o) => collect($o['prodotti'])->sum('quantita'));

        return view('profilo', compact('user', 'ordini', 'tot_ordini', 'tot_spesa', 'tot_prodotti'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'username'  => 'required|min:3|unique:users,name,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only([
            'firstname',
            'lastname',
            'email',
            'phone',
            'birth_date',
            'address',
            'city',
            'postal_code',
            'country'
        ]);

        $data['name'] = $request->username;

        if ($request->hasFile('profile_picture')) {
            $file     = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/profilo'), $filename);
            $data['profile_picture'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profilo aggiornato con successo!');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Se l'utente non ha una password (login con Google), non può usare questo form
        if (!$user->password) {
            return back()->withErrors(['password' => 'Gli utenti Google non possono cambiare password qui.']);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password aggiornata!');
    }
}