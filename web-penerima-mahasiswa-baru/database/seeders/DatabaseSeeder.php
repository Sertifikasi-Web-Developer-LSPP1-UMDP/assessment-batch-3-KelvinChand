<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\JurusanSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JurusanSeeder::class,
            UserSeeder::class,
        ]);
    }
}
