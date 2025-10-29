<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roep de RoleSeeder en UserSeeder aan
        $this->call([
            RoleSeeder::class, // Deze moet EERST uitgevoerd worden
            UserSeeder::class, // Deze als TWEEDE, omdat hij afhankelijk is van de rollen
        ]);
    }
}
