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
        Schema::table('kdramas', function (Blueprint $table) {
            // Voeg de 'user_id' kolom toe
            $table->foreignId('user_id')
                ->nullable() // Maak het nullable (optioneel)
                ->after('id') // Plaats het netjes na de 'id' kolom
                ->constrained() // Koppel het aan de 'id' op de 'users' tabel
                ->onDelete('set null'); // Als de user wordt verwijderd, zet dit veld op 'null'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kdramas', function (Blueprint $table) {
            // Verwijder de foreign key en de kolom in de omgekeerde volgorde
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
