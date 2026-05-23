<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdineProdotto extends Model
{
    protected $table = 'ordini_prodotti';

    protected $fillable = [
        'ordine_id',
        'prodotto_id',
        'nome',
        'taglia',
        'quantita',
        'prezzo'
    ];

    protected $casts = [
        'prezzo'   => 'float',
        'quantita' => 'integer',
    ];

    public function ordine()
    {
        return $this->belongsTo(Ordine::class, 'ordine_id');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto_id');
    }
}