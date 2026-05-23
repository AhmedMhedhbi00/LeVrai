<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Taglia extends Model
{
    public $timestamps = false;

    protected $table = 'taglie'; // ← AGGIUNTO: forza il nome corretto

    protected $fillable = ['taglia'];

    public function prodotti(): BelongsToMany
    {
        return $this->belongsToMany(
            Prodotto::class,
            'prodotto_taglia',
            'taglia_id',
            'prodotto_id'
        )->withPivot('quantita');
    }
}
