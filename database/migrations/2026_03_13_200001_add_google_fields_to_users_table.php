<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname', 100)->nullable()->after('name');
            $table->string('lastname', 100)->nullable()->after('firstname');
            $table->string('phone', 20)->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('address', 255)->nullable()->after('birth_date');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('postal_code', 10)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('postal_code');
            $table->string('profile_picture', 255)->nullable()->after('country');
            $table->string('google_id', 100)->nullable()->after('profile_picture');
            $table->string('avatar', 255)->nullable()->after('google_id');
            $table->enum('role', ['user', 'admin'])->default('user')->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname',
                'lastname',
                'phone',
                'birth_date',
                'address',
                'city',
                'postal_code',
                'country',
                'profile_picture',
                'google_id',
                'avatar',
                'role'
            ]);
        });
    }
};