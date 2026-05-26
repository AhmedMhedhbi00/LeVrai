<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordini_prodotti', function (Blueprint $table) {
            $table->string('nome')->default('')->change();
            $table->decimal('prezzo', 10, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ordini_prodotti', function (Blueprint $table) {
            $table->string('nome')->nullable(false)->change();
        });
    }
};
