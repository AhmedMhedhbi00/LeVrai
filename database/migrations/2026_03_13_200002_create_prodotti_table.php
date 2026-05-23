<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodotti', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('categoria', 100);
            $table->string('brand', 100)->nullable();
            $table->decimal('prezzo', 10, 2);
            $table->decimal('prezzo_scontato', 10, 2)->nullable(); // ← AGGIUNTO
            $table->integer('sconto')->default(0);
            $table->string('immagine', 255)->nullable();
            $table->integer('quantita')->default(0);
            // RIMOSSA la colonna json('taglie') — ora è una relazione reale
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodotti');
    }
};
