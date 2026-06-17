<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addressData = [
            [
                'type' => 'depot',
                'house_number' => '8',
                'street' => 'Dijkstraat',
                'postal_code' => '2630',
                'city' => 'Aartselaar',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '2',
                'street' => 'Spuimeersenweg',
                'postal_code' => '9308',
                'city' => 'Aalst',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '31',
                'street' => 'Blarenberglaan',
                'postal_code' => '2800',
                'city' => 'Mechelen',
                'country_iso' => "BE"
            ],
            [
                'type' => 'depot',
                'house_number' => '5',
                'street' => 'Kielsbroek',
                'postal_code' => '2020',
                'city' => 'Antwerpen',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '1002',
                'street' => 'Boomsesteenweg',
                'postal_code' => '2610',
                'city' => 'Antwerpen',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '20',
                'street' => 'Burchtse Weel',
                'postal_code' => '2070',
                'city' => 'Beveren-Kruibeke-Zwijndrecht',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '21',
                'street' => 'Handelaar',
                'postal_code' => '2920',
                'city' => 'Kalmthout',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '254',
                'street' => 'Drongensesteenweg',
                'postal_code' => '9000',
                'city' => 'Gent',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '1',
                'street' => 'Westbekesluis',
                'postal_code' => '9940',
                'city' => 'Evergem',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '47',
                'street' => 'Brug-Zuid',
                'postal_code' => '9880',
                'city' => 'Aalter',
                'country_iso' => "BE"
            ],
            [
                'type' => 'depot',
                'house_number' => '45',
                'street' => 'Pathoekeweg',
                'postal_code' => '8000',
                'city' => 'Brugge',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '308',
                'street' => 'Kortrijksesteenweg',
                'postal_code' => '8530',
                'city' => 'Harelbeke',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '14',
                'street' => 'Langeleedstraat',
                'postal_code' => '8670',
                'city' => 'Koksijde',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '35',
                'street' => 'Nijverheidszone Begijnenmeers',
                'postal_code' => '1770',
                'city' => 'Liedekerke',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '41',
                'street' => 'Grootstraat',
                'postal_code' => '3500',
                'city' => 'Hasselt',
                'country_iso' => "BE"
            ],
            [
                'type' => 'depot',
                'house_number' => '2097',
                'street' => 'Centrum-Zuid',
                'postal_code' => '3530',
                'city' => 'Houthalen-Helchteren',
                'country_iso' => "BE"
            ],
            [
                'type' => 'worksite',
                'house_number' => '12',
                'street' => 'Diepenbekerbos',
                'postal_code' => '3600',
                'city' => 'Genk',
                'country_iso' => "BE"
            ]
        ];

        Address::factory(count($addressData))->createMany($addressData);
    }
}
