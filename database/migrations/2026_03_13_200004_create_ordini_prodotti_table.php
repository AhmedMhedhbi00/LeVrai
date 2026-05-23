<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordini_prodotti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordine_id')
                ->constrained('ordini')
                ->onDelete('cascade');
            $table->foreignId('prodotto_id')
                ->constrained('prodotti')
                ->onDelete('cascade');
            $table->integer('quantita')->default(1);
            $table->string('taglia', 10)->nullable();
            $table->decimal('prezzo_unitario', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordini_prodotti');
    }
};
