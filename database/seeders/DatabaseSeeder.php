<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Run: php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            DivisionSeeder::class, // Harus duluan (FK reference)
            UserSeeder::class,     // Users + events + work programs
        ]);
    }
}
