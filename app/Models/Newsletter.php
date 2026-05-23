<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter';

    protected $fillable = [
        'email',
        'codice_sconto',
        'percentuale',
        'scadenza'
    ];

    protected $casts = [
        'scadenza'    => 'date',
        'percentuale' => 'integer',
    ];
}