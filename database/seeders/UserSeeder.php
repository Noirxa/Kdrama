<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Voor het hashen van het wachtwoord
use App\Models\User;                  // Je User model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Maak de eerste Admin gebruiker aan
        User::create([
            'role_id'  => 1, // 1 = Admin rol
            'name'     => 'Admin User',
            'email'    => 'admin@gmail.com', // Pas dit e-mailadres aan
            'email_verified_at' => now(), // Optioneel: zet de gebruiker direct op geverifieerd
            'password' => Hash::make('12345678'), // Verander 'password' naar een sterk standaard wachtwoord
        ]);

        // Optioneel: Maak een paar test 'regular users' aan
        User::create([
            'role_id'  => 2, // 2 = Gewone gebruiker rol
            'name'     => 'Test User',
            'email'    => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Optioneel: Gebruik een Factory om veel testgebruikers te maken
        // \App\Models\User::factory(10)->create();
        // (Dit werkt, omdat 'role_id' in je migratie al standaard op 2 staat)
    }
}
