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
        // 1. Maak de 'roles' tabel voor de rol-definities
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Wordt 1 voor admin, 2 voor user
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 2. Maak de 'users' tabel met de verwijzing (foreign key) naar 'roles'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id') // Voeg de role_id kolom toe
            ->default(2)          // Standaard is 'user' (ID 2)
            ->constrained();     // Koppel aan 'id' op 'roles' tabel
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        // Belangrijk: drop 'users' VOORDAT je 'roles' dropt
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
