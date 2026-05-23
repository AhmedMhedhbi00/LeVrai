<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodotti', function (Blueprint $table) {
            if (!Schema::hasColumn('prodotti', 'taglie')) {
                $table->string('taglie')->nullable()->after('quantita');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prodotti', function (Blueprint $table) {
            $table->dropColumn('taglie');
        });
    }
};
