<?php

namespace Database\Seeders;

use App\Models\HelpRequest;
use App\Models\Order;
use App\Models\Category;
use App\Models\Material;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use App\Models\Address;
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
        // Create Roles
        $roleNames = [
            'Admin',
            'Technieker',
            'Manager',
            'Magazijnier'
        ];

        $roles = [];
        Role::factory(count($roleNames))
            ->makeMany()
            ->each(function ($role, $i) use ($roleNames, &$roles) {
                $name = $roleNames[$i];
                $role->name = $name;
                $role->save();
                $roles[$name] = $role->id;
            });

        // Create Addresses
        $addressData = [
            [
                'type' => 'worksite',
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
                'type' => 'worksite',
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
                'type' => 'worksite',
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
                'type' => 'worksite',
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

        $addressIds = Address::factory(count($addressData))
            ->createMany($addressData)
            ->pluck('id')
            ->toArray();

        // Create Sites
        $siteDescriptions = [
            'Aquafin',
            'Aquafin Labo Aalst',
            'Aquafin RWZI Mechelen-Noord',
            'Aquafin RWZI Antwerpen-Zuid',
            'Aquafin RWZI Aartselaar',
            'Aquafin RWZI Burcht',
            'Aquafin RWZI Kalmthout',
            'Aquafin RWZI Gent',
            'Aquafin RWZI Evergem',
            'Aquafin RWZI Aalter',
            'Aquafin RWZI Brugge',
            'Aquafin rioolwaterzuiveringsinstallatie Harelbeke',
            'Aquafin',
            'Aquafin RWZI Liedekerke',
            'Aquafin RWZI Wimmertingen',
            'Aquafin RWZI Houthalen-Helchteren',
            'Aquafin RWZI Genk'
        ];

        $addressCount = count($addressIds);
        $siteAttributes = array_map(
            function ($description, $i) use ($addressIds, $addressCount) {
                return [
                    'description' => $description,
                    'address_id' => $addressIds[$i % $addressCount],
                ];
            },
            $siteDescriptions,
            array_keys($siteDescriptions)
        );

        $siteIds = Site::factory(count($siteDescriptions))
            ->createMany($siteAttributes)
            ->pluck('id')
            ->toArray();

        // Create Users
        $userData = [
            [
                'email' => 'admin@aquawerf.com',
                'role_id' => $roles['Admin'] ?? 1
            ],
            [
                'email' => 'technieker@aquawerf.com',
                'role_id' => $roles['Technieker'] ?? 1
            ],
            [
                'email' => 'manager@aquawerf.com',
                'role_id' => $roles['Manager'] ?? 1
            ],
            [
                'email' => 'magazijnier@aquawerf.com',
                'role_id' => $roles['Magazijnier'] ?? 1
            ]
        ];

        $users = User::factory(count($userData))
            ->makeMany($userData)
            ->each(function ($user) use ($siteIds) {
                $user->site_id = $siteIds[array_rand($siteIds)];
                $user->save();
            });

        $userIds = $users
            ->pluck('id')
            ->toArray();

        // Create HelpRequests
        $helpRequestIds = HelpRequest::factory(10)
            ->make()
            ->each(function ($helpRequest) use ($users) {
                $randomUser = $users->random();
                $helpRequest->email = 'test@mail.com';
                $helpRequest->first_name = $randomUser->first_name;
                $helpRequest->last_name = $randomUser->last_name;
                $helpRequest->save();
            })
            ->pluck('id')
            ->toArray();

        // Create Categories
        $categoryIds = Category::factory(6)
            ->create()
            ->pluck('id')
            ->toArray();

        // Create Materials
        $materialData = [
            ['name' => 'Bouten M6', 'category_id' => 1],
            ['name' => 'Bouten M8', 'category_id' => 1],
            ['name' => 'Bouten M10', 'category_id' => 1],
            ['name' => 'Bouten M12', 'category_id' => 1],
            ['name' => 'Bouten M16', 'category_id' => 1],
            ['name' => 'Bouten inox A2', 'category_id' => 1],
            ['name' => 'Bouten inox A4', 'category_id' => 1],
            ['name' => 'Bouten verzinkt', 'category_id' => 1],
            ['name' => 'Zeskantmoeren', 'category_id' => 1],
            ['name' => 'Borgmoeren', 'category_id' => 1],
            ['name' => 'Flensmoeren', 'category_id' => 1],
            ['name' => 'Sluitringen', 'category_id' => 1],
            ['name' => 'Veerringen', 'category_id' => 1],
            ['name' => 'Tandringen', 'category_id' => 1],
            ['name' => 'Ankerbouten', 'category_id' => 1],
            ['name' => 'Chemische ankers (Hilti HIT)', 'category_id' => 1],
            ['name' => 'Keilbouten', 'category_id' => 1],
            ['name' => 'Draadstang M6', 'category_id' => 1],
            ['name' => 'Draadstang M8', 'category_id' => 1],
            ['name' => 'Draadstang M10', 'category_id' => 1],
            ['name' => 'Draadstang M12', 'category_id' => 1],
            ['name' => 'Draadstang M16', 'category_id' => 1],
            ['name' => 'Inslagmoeren', 'category_id' => 1],
            ['name' => 'Tapbouten', 'category_id' => 1],
            ['name' => 'Zeskantkopbouten', 'category_id' => 1],
            ['name' => 'Inbusbouten', 'category_id' => 1],
            ['name' => 'Torxschroeven', 'category_id' => 1],
            ['name' => 'Kruiskopschroeven', 'category_id' => 1],
            ['name' => 'Zelftappende vijzen', 'category_id' => 1],
            ['name' => 'Parkervijzen', 'category_id' => 1],
            ['name' => 'Spaanplaatschroeven', 'category_id' => 1],
            ['name' => 'Slangenklemmen', 'category_id' => 1],
            ['name' => 'Veiligheidshelm (met kinband)', 'category_id' => 2],
            ['name' => 'Oordoppen / gehoorkappen', 'category_id' => 2],
            ['name' => 'Veiligheidsbril / gelaatsscherm', 'category_id' => 2],
            ['name' => 'Stofmaskers (FFP2, FFP3)', 'category_id' => 2],
            ['name' => 'Werkhandschoenen (snijvast, chemisch resistent, elektrisch geïsoleerd)', 'category_id' => 2],
            ['name' => 'Veiligheidsschoenen (S3, antistatisch, stalen tip)', 'category_id' => 2],
            ['name' => 'Werklaarzen (PVC, nitril, met stalen zool)', 'category_id' => 2],
            ['name' => 'Regenkledij (jassen, broeken, capes)', 'category_id' => 2],
            ['name' => 'Fluovesten / signalisatiekledij (EN ISO 20471)', 'category_id' => 2],
            ['name' => 'Overall (brandvertragend, antistatisch, waterafstotend)', 'category_id' => 2],
            ['name' => 'Valharnas en lijn', 'category_id' => 2],
            ['name' => 'Gasdetectiemeter (O₂, CH₄, H₂S, CO)', 'category_id' => 2],
            ['name' => 'Handontsmetting / EHBO-kit', 'category_id' => 2],
            ['name' => 'Klim- en valbeveiligingsmateriaal (harnas, lifeline, karabijnhaken)', 'category_id' => 2],
            ['name' => 'Dopsleutelsets (metrisch en inch)', 'category_id' => 3],
            ['name' => 'Ringsleutels, steeksleutels', 'category_id' => 3],
            ['name' => 'Momentsleutels', 'category_id' => 3],
            ['name' => 'Inbussleutels (los en in set)', 'category_id' => 3],
            ['name' => 'Schroevendraaiers (plat, kruiskop, Torx, geïsoleerd)', 'category_id' => 3],
            ['name' => 'Tangen (combinatie, waterpomptang, kniptang, punttang)', 'category_id' => 3],
            ['name' => 'Krimptang / kabelschoentang', 'category_id' => 3],
            ['name' => 'Kabelstripper', 'category_id' => 3],
            ['name' => 'Hamer, kunststofhamer, moker', 'category_id' => 3],
            ['name' => 'Breekijzer', 'category_id' => 3],
            ['name' => 'Slijpmachine (haakse slijper)', 'category_id' => 3],
            ['name' => 'Accuboormachine / klopboormachine', 'category_id' => 3],
            ['name' => 'Schroefmachine', 'category_id' => 3],
            ['name' => 'Slagmoersleutel (pneumatisch of accu)', 'category_id' => 3],
            ['name' => 'Waterpas / laserwaterpas', 'category_id' => 3],
            ['name' => 'Meetlint, rolmeter', 'category_id' => 3],
            ['name' => 'Spanningstester / multimeter', 'category_id' => 3],
            ['name' => 'Laskist en lasmateriaal (indien van toepassing)', 'category_id' => 3],
            ['name' => 'Smeervet (foodgrade, EP2, lithium)', 'category_id' => 4],
            ['name' => 'O-ringen (div. maten en types)', 'category_id' => 4],
            ['name' => 'Pakkingen (papier, rubber, EPDM)', 'category_id' => 4],
            ['name' => 'PTFE tape / Loctite', 'category_id' => 4],
            ['name' => 'Slangen (PVC, PE, persslangen)', 'category_id' => 4],
            ['name' => 'PVC-fittingen, bochten, T-stukken', 'category_id' => 4],
            ['name' => 'Koppelingen (Geka, Gardena, Camlock)', 'category_id' => 4],
            ['name' => 'V-snaren / kettingen', 'category_id' => 4],
            ['name' => 'Kabels en wartels (M16–M32)', 'category_id' => 4],
            ['name' => 'Aansluitdozen', 'category_id' => 4],
            ['name' => 'Leidingsystemen (druk/afvoer)', 'category_id' => 4],
            ['name' => 'Pneumatische koppelingen', 'category_id' => 4],
            ['name' => 'Trillingsdempers', 'category_id' => 4],
            ['name' => 'Putdekselhaak / mangatopener', 'category_id' => 5],
            ['name' => 'Rioolcamera / inspectiecamera', 'category_id' => 5],
            ['name' => 'Gasdetectietoestellen (H₂S, CO, O₂)', 'category_id' => 5],
            ['name' => 'Ontstoppingsveer / hogedrukreiniger', 'category_id' => 5],
            ['name' => 'Slangenwagens', 'category_id' => 5],
            ['name' => 'Dompelpompen', 'category_id' => 5],
            ['name' => 'Rioolstoppen', 'category_id' => 5],
            ['name' => 'Vlotterschakelaars', 'category_id' => 5],
            ['name' => 'Niveaumeting (ultrasoon, radar)', 'category_id' => 5],
            ['name' => 'Staalnamepotten', 'category_id' => 5],
            ['name' => 'Monsternameapparatuur', 'category_id' => 5],
            ['name' => 'Tie-wraps', 'category_id' => 6],
            ['name' => 'Kabelschoenen', 'category_id' => 6],
            ['name' => 'Markeringstape', 'category_id' => 6],
            ['name' => 'Siliconenkit / lijm', 'category_id' => 6],
            ['name' => 'Rags (reinigingsdoekjes)', 'category_id' => 6],
            ['name' => 'Spray’s (WD-40, contactspray, kettingspray)', 'category_id' => 6],
            ['name' => 'Plakband (duct tape, isolatietape)', 'category_id' => 6],
            ['name' => 'Batterijen / accu’s', 'category_id' => 6],
            ['name' => 'Reserveonderdelen (motoren, PLC-onderdelen, relais)', 'category_id' => 6],
            ['name' => 'Flessen met perslucht / gas', 'category_id' => 6]
        ];

        $materialIds = Material::factory(count($materialData))
            ->createMany($materialData)
            ->pluck('id')
            ->toArray();

        // Create Orders
        $orderIds = Order::factory(10)
            ->make()
            ->each(function ($order) use ($userIds, $siteIds) {
                $order->user_id = $userIds[array_rand($userIds)];
                $order->site_id = $siteIds[array_rand($siteIds)];
                $order->save();
            })
            ->pluck('id')
            ->toArray();
    }
}
