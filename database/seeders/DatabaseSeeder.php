<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with initial data for all models.
     */
    public function run(): void
    {
        // 1. Seed tables with NO foreign key dependencies first
        $this->call([
            AddressSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            SiteSeeder::class
        ]);

        // 2. Seed tables that depend on the above
        $this->call([
            UserSeeder::class,
            OrderSeeder::class,
            HelpRequestSeeder::class,
            MaterialSeeder::class
        ]);
    }
}
