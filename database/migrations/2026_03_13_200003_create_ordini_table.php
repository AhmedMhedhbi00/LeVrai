<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordini', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('totale', 10, 2);
            $table->enum('stato', ['pagato', 'in_lavorazione', 'spedito', 'completato', 'annullato', 'pending'])->default('pending');
            $table->string('metodo_pagamento', 50)->nullable();
            $table->string('payment_intent_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordini');
    }
};