<?php

namespace App\Http\Controllers;

use App\Models\Ordine;
use App\Models\Prodotto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'prodotti_totali'   => Prodotto::count(),
            'prodotti_esauriti' => Prodotto::where('quantita', 0)->count(),
            'prodotti_scontati' => Prodotto::where('sconto', '>', 0)->count(),
            'ordini_totali'     => Ordine::count(),
            'ordini_oggi'       => Ordine::whereDate('created_at', today())->count(),
            'fatturato_totale'  => Ordine::sum('totale'),
            'fatturato_mese'    => Ordine::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('totale'),
            'utenti_totali'     => User::count(),
        ];

        $ultimi_ordini   = Ordine::with('user')->latest()->limit(8)->get();
        $prodotti_scarsi = Prodotto::where('quantita', '>', 0)
            ->where('quantita', '<=', 5)
            ->orderBy('quantita')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'ultimi_ordini', 'prodotti_scarsi'));
    }

    public function prodotti(Request $request)
    {
        $query = Prodotto::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(
                fn($s) =>
                $s->where('nome', 'like', "%$q%")
                    ->orWhere('brand', 'like', "%$q%")
                    ->orWhere('categoria', 'like', "%$q%")
            );
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('stato')) {
            match ($request->stato) {
                'disponibile' => $query->where('quantita', '>', 0),
                'esaurito'    => $query->where('quantita', 0),
                'scontato'    => $query->where('sconto', '>', 0),
                default       => null,
            };
        }

        $sort = in_array($request->get('sort'), ['id', 'nome', 'prezzo', 'quantita', 'categoria', 'brand'])
            ? $request->get('sort') : 'id';
        $query->orderBy($sort, $request->get('dir') === 'asc' ? 'asc' : 'desc');

        $prodotti  = $query->paginate(15)->withQueryString();
        $categorie = Prodotto::distinct()->pluck('categoria')->sort()->values();

        return view('admin.prodotti', compact('prodotti', 'categorie'));
    }

    public function crea()
    {
        return view('admin.prodotto-form', [
            'prodotto'  => null,
            'categorie' => ['abbigliamento', 'scarpe', 'altro'],
        ]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nome'      => 'required|string|max:200',
            'brand'     => 'nullable|string|max:100',
            'categoria' => 'required|string|max:100',
            'prezzo'    => 'required|numeric|min:0',
            'sconto'    => 'nullable|integer|min:0|max:100',
            'quantita'  => 'required|integer|min:0',
            'taglie'    => 'nullable|string|max:255',
            'immagine'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $sconto = (int) ($v['sconto'] ?? 0);
        $prezzo = (float) $v['prezzo'];

        DB::table('prodotti')->insert([
            'nome'            => $v['nome'],
            'brand'           => $v['brand'] ?? null,
            'categoria'       => $v['categoria'],
            'prezzo'          => $prezzo,
            'prezzo_scontato' => $sconto > 0 ? round($prezzo * (1 - $sconto / 100), 2) : $prezzo,
            'sconto'          => $sconto,
            'quantita'        => (int) $v['quantita'],
            'taglie'          => $this->normalizzaTaglie($v['taglie'] ?? null),
            'immagine'        => $request->hasFile('immagine') ? $this->uploadImmagine($request->file('immagine')) : null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('admin.prodotti')
            ->with('success', "Prodotto «{$v['nome']}» creato con successo!");
    }

    public function modifica($id)
    {
        return view('admin.prodotto-form', [
            'prodotto'  => Prodotto::findOrFail($id),
            'categorie' => ['abbigliamento', 'scarpe', 'altro'],
        ]);
    }

    public function update(Request $request, $id)
    {
        $prodotto = Prodotto::findOrFail($id);

        $v = $request->validate([
            'nome'      => 'required|string|max:200',
            'brand'     => 'nullable|string|max:100',
            'categoria' => 'required|string|max:100',
            'prezzo'    => 'required|numeric|min:0',
            'sconto'    => 'nullable|integer|min:0|max:100',
            'quantita'  => 'required|integer|min:0',
            'taglie'    => 'nullable|string|max:255',
            'immagine'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $sconto = (int) ($v['sconto'] ?? 0);
        $prezzo = (float) $v['prezzo'];

        $data = [
            'nome'            => $v['nome'],
            'brand'           => $v['brand'] ?? null,
            'categoria'       => $v['categoria'],
            'prezzo'          => $prezzo,
            'prezzo_scontato' => $sconto > 0 ? round($prezzo * (1 - $sconto / 100), 2) : $prezzo,
            'sconto'          => $sconto,
            'quantita'        => (int) $v['quantita'],
            'taglie'          => $this->normalizzaTaglie($v['taglie'] ?? null),
            'updated_at'      => now(),
        ];

        if ($request->hasFile('immagine')) {
            if ($prodotto->immagine && $prodotto->immagine !== 'default.jpg') {
                $old = public_path('assets/images/prodotti/' . $prodotto->immagine);
                if (file_exists($old)) unlink($old);
            }
            $data['immagine'] = $this->uploadImmagine($request->file('immagine'));
        }

        DB::table('prodotti')->where('id', $id)->update($data);

        return redirect()->route('admin.prodotti')
            ->with('success', "Prodotto «{$v['nome']}» aggiornato!");
    }

    public function elimina($id)
    {
        $prodotto = Prodotto::findOrFail($id);

        // Salvo il nome PRIMA di eliminare
        $nome = $prodotto->nome;

        // Elimino immagine dal disco
        if ($prodotto->immagine && $prodotto->immagine !== 'default.jpg') {
            $path = public_path('assets/images/prodotti/' . $prodotto->immagine);
            if (file_exists($path)) unlink($path);
        }

        // Elimino dal DB
        DB::table('prodotti')->where('id', $id)->delete();

        return redirect()->route('admin.prodotti')
            ->with('success', "Prodotto «{$nome}» eliminato.");
    }

    public function aggiornaQuantita(Request $request, $id)
    {
        $qty = (int) $request->validate([
            'quantita' => 'required|integer|min:0'
        ])['quantita'];

        DB::table('prodotti')->where('id', $id)->update([
            'quantita'   => $qty,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'  => true,
            'quantita' => $qty,
            'stato'    => $qty === 0 ? 'esaurito' : ($qty <= 5 ? 'scarso' : 'ok'),
        ]);
    }

    public function ordini(Request $request)
    {
        $query = Ordine::with('user')->latest();

        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas(
                'user',
                fn($u) =>
                $u->where('email', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%")
            );
        }

        return view('admin.ordini', [
            'ordini' => $query->paginate(20)->withQueryString()
        ]);
    }

    public function aggiornaStatoOrdine(Request $request, $id)
    {
        $stato = $request->validate([
            'stato' => 'required|in:pending,pagato,spedito,completato,annullato'
        ])['stato'];

        DB::table('ordini')->where('id', $id)->update([
            'stato'      => $stato,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'stato' => $stato]);
    }

    private function normalizzaTaglie(?string $taglie): ?string
    {
        if (empty($taglie)) return null;
        return implode(',', array_map(
            fn($t) => strtoupper(trim($t)),
            explode(',', $taglie)
        ));
    }

    private function uploadImmagine($file): string
    {
        $ext      = $file->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . time() . '.' . $ext;
        $dest = public_path('assets/images/prodotti');
        if (!is_dir($dest)) mkdir($dest, 0755, true);
        $file->move($dest, $filename);
        return $filename;
    }
}
