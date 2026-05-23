<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ordine extends Model
{
    protected $table = 'ordini';

    protected $fillable = [
        'user_id',
        'totale',
        'stato',
        'metodo_pagamento',
        'payment_intent_id',
    ];

    protected $casts = [
        'totale' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prodotti(): BelongsToMany
    {
        return $this->belongsToMany(
            Prodotto::class,
            'ordini_prodotti',
            'ordine_id',
            'prodotto_id'
        )->withPivot('quantita', 'taglia', 'prezzo_unitario');
    }
}