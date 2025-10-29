<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Belangrijk om deze toe te voegen

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Voeg de rollen 'admin' (ID 1) en 'user' (ID 2) toe
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
