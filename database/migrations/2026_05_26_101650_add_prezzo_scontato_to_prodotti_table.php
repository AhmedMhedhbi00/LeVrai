<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodotti', function (Blueprint $table) {
            $table->decimal('prezzo_scontato', 10, 2)->nullable()->after('prezzo');
        });
    }

    public function down(): void
    {
        Schema::table('prodotti', function (Blueprint $table) {
            $table->dropColumn('prezzo_scontato');
        });
    }
};
