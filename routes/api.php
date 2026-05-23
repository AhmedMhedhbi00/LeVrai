<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — LeVrai
|--------------------------------------------------------------------------
| Tutte le route API autenticate sono state spostate in web.php
| per utilizzare la sessione Laravel (auth:web + cookie di sessione).
| Questo file è mantenuto per compatibilità futura con Sanctum/token.
*/

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
