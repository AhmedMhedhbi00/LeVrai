<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodotto;
use App\Models\Taglia;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Assicurati che le taglie esistano nella tabella 'taglie'
        $taglieDefault = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43', '44', '45', 'Taglia unica', 'One Size'];
        foreach ($taglieDefault as $t) {
            Taglia::firstOrCreate(['taglia' => $t]);
        }

        // 2. Cicla sui prodotti che hai GIA' nel database
        $prodottiEsistenti = Prodotto::all();

        foreach ($prodottiEsistenti as $prodotto) {
            // Se la colonna 'taglie' nel DB non è vuota (es: "S,M,L,XL")
            if (!empty($prodotto->taglie)) {
                // Trasforma la stringa "S,M,L" in un array ['S', 'M', 'L']
                $arrayTaglie = explode(',', $prodotto->taglie);

                foreach ($arrayTaglie as $nomeTaglia) {
                    $nomeTaglia = trim($nomeTaglia); // Pulisce spazi bianchi

                    $taglia = Taglia::where('taglia', $nomeTaglia)->first();

                    if ($taglia) {
                        // Collega il prodotto alla taglia nella tabella pivot
                        $prodotto->tagliePivot()->syncWithoutDetaching([
                            $taglia->id => ['quantita' => 5] // Imposta una quantità default per taglia
                        ]);
                    }
                }
            }
        }
    }
}