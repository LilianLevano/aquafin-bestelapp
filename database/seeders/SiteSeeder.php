<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addressIds = Address::all()->whereIn('type', ['depot'])->pluck('id', 'id');
        $siteData = [
            [
                'description' => 'Limburg',
                'longitude' => 5.3325,
                'latitude' => 50.9311,
                'address_id' => $addressIds['16']
            ],
            [
                'description' => 'Oost-Vlaanderen',
                'longitude' => 3.7174,
                'latitude' => 51.0362,
                'address_id' => $addressIds['1']
            ],
            [
                'description' => 'West-Vlaanderen',
                'longitude' => 3.2247,
                'latitude' => 51.0536,
                'address_id' => $addressIds['11']
            ],
            [
                'description' => 'Antwerpen',
                'longitude' => 4.4025,
                'latitude' => 51.2194,
                'address_id' => $addressIds['4']
            ]
        ];

        Site::factory(count($addressIds))->createMany($siteData);
    }
}
