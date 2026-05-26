<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini_prodotti', function (Blueprint $table) {
            $table->decimal('prezzo_unitario', 10, 2)->after('ordine_id');
        });
    }

    public function down(): void
    {
        Schema::table('ordini_prodotti', function (Blueprint $table) {
            $table->dropColumn('prezzo_unitario');
        });
    }
};
