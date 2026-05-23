<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotti';

    protected $fillable = [
        'nome',
        'categoria',
        'brand',
        'prezzo',
        'prezzo_scontato',
        'sconto',
        'immagine',
        'quantita',
        'taglie',
    ];

    protected $casts = [
        'prezzo'          => 'float',
        'prezzo_scontato' => 'float',
        'sconto'          => 'integer',
        'quantita'        => 'integer',
    ];

    /**
     * Restituisce le taglie come array dalla stringa CSV.
     * Es: "S,M,L,XL" → ["S","M","L","XL"]
     */
    public function getTaglieArrayAttribute(): array
    {
        if (empty($this->attributes['taglie'])) {
            return [];
        }
        return array_values(array_filter(
            array_map('trim', explode(',', $this->attributes['taglie']))
        ));
    }

    /**
     * Prezzo effettivo da mostrare (scontato se esiste, altrimenti pieno).
     * NON sovrascrive il campo DB — usa getPrezzoFinaleAttribute
     * così non interferisce con create()/update().
     */
    public function getPrezzoFinaleAttribute(): float
    {
        $scontato = (float) ($this->attributes['prezzo_scontato'] ?? 0);
        $pieno    = (float) ($this->attributes['prezzo'] ?? 0);
        return $scontato > 0 ? $scontato : $pieno;
    }
}
