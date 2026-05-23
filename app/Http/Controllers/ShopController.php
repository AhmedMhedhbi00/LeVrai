<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    /**
     * Pagina Home
     */
    public function home()
    {
        try {
            $tutti = Prodotto::select('id', 'nome', 'brand', 'prezzo', 'prezzo_scontato', 'sconto', 'immagine', 'categoria', 'taglie')
                ->where('quantita', '>', 0)
                ->get();

            $scontatiPool = $tutti->where('sconto', '>', 0)->shuffle()->values();
            $normaliPool  = $tutti->where('sconto', '=', 0)->shuffle()->values();

            $quattrScontati = $scontatiPool->take(4);
            $altriNormali   = $normaliPool->take(8 - $quattrScontati->count());
            $prodotti       = $quattrScontati->merge($altriNormali)->shuffle()->values();

            $ultimi = Prodotto::select('id', 'nome', 'brand', 'prezzo', 'prezzo_scontato', 'sconto', 'immagine', 'categoria', 'taglie')
                ->where('quantita', '>', 0)
                ->orderByDesc('id')
                ->limit(3)
                ->get();

            $scontati = Prodotto::select('id', 'nome', 'brand', 'prezzo', 'prezzo_scontato', 'sconto', 'immagine', 'categoria', 'taglie')
                ->where('quantita', '>', 0)
                ->where('sconto', '>', 0)
                ->orderByDesc('sconto')
                ->orderByDesc('prezzo')
                ->limit(3)
                ->get();

            $scontato = $scontati->first();

            // Prodotti Le Vrai — cerca tutte le varianti del brand
            $prodottiLeVrai = Prodotto::select('id', 'nome', 'brand', 'prezzo', 'prezzo_scontato', 'sconto', 'immagine', 'categoria', 'taglie', 'quantita')
                ->where('quantita', '>', 0)
                ->where(function ($q) {
                    $q->whereRaw('LOWER(TRIM(brand)) = ?', ['le vrai'])
                        ->orWhereRaw('LOWER(TRIM(brand)) = ?', ['levrai'])
                        ->orWhereRaw('LOWER(TRIM(brand)) = ?', ['le_vrai']);
                })
                ->orderByDesc('id')
                ->limit(3)
                ->get();

            if ($prodottiLeVrai->isEmpty()) {
                $prodottiLeVrai = $ultimi;
            }
        } catch (\Exception $e) {
            Log::error("Errore ShopController@home: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $prodotti       = collect();
            $ultimi         = collect();
            $scontati       = collect();
            $scontato       = null;
            $prodottiLeVrai = collect();
        }

        return view('home', compact('prodotti', 'ultimi', 'scontati', 'scontato', 'prodottiLeVrai'));
    }

    /**
     * Pagina Shop
     */


    public function index()
    {
        \Log::info('Prodotti count: ' . Prodotto::count());

        try {
            // Prende TUTTI i prodotti dal DB — nessuna relazione pivot necessaria
            $prodotti = Prodotto::select(
                'id',
                'nome',
                'brand',
                'categoria',
                'prezzo',
                'prezzo_scontato',
                'sconto',
                'immagine',
                'quantita',
                'taglie'
            )->get();

            // Normalizza per il frontend JS (window.prodotti)
            $prodottiFormatted = $prodotti->map(function ($p) {
                return [
                    'id'              => $p->id,
                    'nome'            => $p->nome,
                    'brand'           => $p->brand ?? '',
                    'prezzo'          => (float) $p->prezzo,
                    'prezzo_scontato' => (float) ($p->getRawOriginal('prezzo_scontato') ?? $p->prezzo),
                    'sconto'          => (int) ($p->sconto ?? 0),
                    'immagine'        => $p->immagine ?? 'default.jpg',
                    'quantita'        => (int) $p->quantita,
                    'categoria'       => strtolower(trim($p->categoria ?? 'altro')),
                    // Converte "S,M,L,XL" → ["S","M","L","XL"]
                    'taglie'          => $p->taglie_array,
                ];
            })->values();

            // Raggruppa per categoria (per eventuale uso nella view)
            $prodottiPerCategoria = $prodottiFormatted->groupBy('categoria');

            $mappaCategorie = [
                'abbigliamento' => 'prodotti-abbigliamento',
                'scarpe'        => 'prodotti-scarpe',
                'altro'         => 'prodotti-altro',
            ];

            // Prodotti Le Vrai per la sezione brand dedicata
            $prodottiLeVrai = $prodottiFormatted->filter(function ($p) {
                return in_array(
                    strtolower(trim($p['brand'])),
                    ['le vrai', 'levrai', 'le_vrai']
                );
            })->values();

            $weather = $this->getWeatherData();
        } catch (\Exception $e) {
            Log::error("Errore ShopController@index: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $prodottiFormatted    = collect();
            $prodottiPerCategoria = collect();
            $mappaCategorie       = [];
            $prodottiLeVrai       = collect();
            $weather              = null;
        }

        return view('shop', compact(
            'prodottiFormatted',
            'prodottiPerCategoria',
            'mappaCategorie',
            'prodottiLeVrai',
            'weather'
        ));
    }

    /**
     * Redirect al prodotto nella shop
     */
    public function show($id)
    {
        $prodotto = Prodotto::findOrFail($id);
        return redirect()->route('shop', ['#prodotto-' . $id]);
    }

    /**
     * Meteo cachato 10 minuti
     */
    private function getWeatherData(): ?array
    {
        $apiKey = config('services.openweather.key');

        if (!$apiKey) {
            Log::warning('OpenWeather API key mancante');
            return null;
        }

        return Cache::remember('weather_caltagirone', 600, function () use ($apiKey) {
            try {
                $response = Http::timeout(5)->get(
                    'https://api.openweathermap.org/data/2.5/weather',
                    [
                        'q'     => 'Caltagirone,IT',
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang'  => 'it',
                    ]
                );

                if (!$response->successful()) {
                    throw new \Exception('HTTP ' . $response->status());
                }

                $data = $response->json();

                return [
                    'city'        => $data['name'] . ', IT',
                    'temp'        => round($data['main']['temp']),
                    'description' => $data['weather'][0]['description'],
                    'humidity'    => $data['main']['humidity'] . '%',
                    'wind'        => round($data['wind']['speed'] * 3.6) . ' km/h',
                    'feels_like'  => round($data['main']['feels_like']),
                    'icon'        => 'https://openweathermap.org/img/wn/' .
                        $data['weather'][0]['icon'] . '@2x.png',
                ];
            } catch (\Exception $e) {
                Log::error('Errore meteo: ' . $e->getMessage());
                return null;
            }
        });
    }
}
