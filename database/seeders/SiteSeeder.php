<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            ['description' => 'Limburg',         'longitude' => 5.3325, 'latitude' => 50.9311],
            ['description' => 'Oost-Vlaanderen', 'longitude' => 3.7174, 'latitude' => 51.0362],
            ['description' => 'West-Vlaanderen', 'longitude' => 3.2247, 'latitude' => 51.0536],
            ['description' => 'Antwerpen',        'longitude' => 4.4025, 'latitude' => 51.2194],
        ];

        foreach ($sites as $site) {
            DB::table('sites')->insertOrIgnore($site);
        }
    }
}
