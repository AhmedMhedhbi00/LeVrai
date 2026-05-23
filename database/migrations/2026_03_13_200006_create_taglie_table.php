<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabella taglie (valori: S, M, L, XL, 42, 43…)
        Schema::create('taglie', function (Blueprint $table) {
            $table->id();
            $table->string('taglia', 10)->unique(); // es. "M", "42"
        });

        // Tabella pivot prodotto ↔ taglia con quantità per taglia
        Schema::create('prodotto_taglia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodotto_id')
                ->constrained('prodotti')
                ->onDelete('cascade');
            $table->foreignId('taglia_id')
                ->constrained('taglie')
                ->onDelete('cascade');
            $table->integer('quantita')->default(0);
            $table->unique(['prodotto_id', 'taglia_id']); // no duplicati
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodotto_taglia');
        Schema::dropIfExists('taglie');
    }
};
