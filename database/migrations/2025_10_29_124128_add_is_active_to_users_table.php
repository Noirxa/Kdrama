<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // We voegen alleen de 'is_active' kolom toe
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')
                ->default(true)       // Nieuwe gebruikers zijn standaard actief
                ->after('password');  // Plaatst de kolom na de 'password' kolom (optioneel)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Als we de migratie terugdraaien, verwijderen we de kolom weer
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
