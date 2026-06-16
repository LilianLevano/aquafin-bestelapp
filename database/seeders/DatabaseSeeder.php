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
            ['name' => 'Bouten M6', 'category_id' => 1, 'image_path' => 'bouten-m6.webp', 'description' => 'Metrische zeskantbout M6, geschikt voor lichte constructies en bevestigingen.'],
            ['name' => 'Bouten M8', 'category_id' => 1, 'image_path' => 'bouten-m8.jpg', 'description' => 'Metrische zeskantbout M8, veelgebruikt in mechanische en constructieve toepassingen.'],
            ['name' => 'Bouten M10', 'category_id' => 1, 'image_path' => 'bouten-m10.jpg', 'description' => 'Metrische zeskantbout M10, voor middelzware constructies en machinebouw.'],
            ['name' => 'Bouten M12', 'category_id' => 1, 'image_path' => 'bouten-m12.jpg', 'description' => 'Metrische zeskantbout M12, geschikt voor zware industriële bevestigingen.'],
            ['name' => 'Bouten M16', 'category_id' => 1, 'image_path' => 'bouten-m16.jpg', 'description' => 'Metrische zeskantbout M16, voor zware belastingen in staalconstructies.'],
            ['name' => 'Bouten inox A2', 'category_id' => 1, 'image_path' => 'bouten-inox-a2.jpg', 'description' => 'Roestvrijstalen bout A2 (AISI 304), corrosiebestendig voor binnen- en buitentoepassingen.'],
            ['name' => 'Bouten inox A4', 'category_id' => 1, 'image_path' => 'bouten-inox-a4.webp', 'description' => 'Roestvrijstalen bout A4 (AISI 316), verhoogde corrosiebestendigheid voor zeewatermilieus.'],
            ['name' => 'Bouten verzinkt', 'category_id' => 1, 'image_path' => 'boutenverzinkt.webp', 'description' => 'Elektrolytisch verzinkte bout, beschermd tegen roest voor gebruik in vochtige omgevingen.'],
            ['name' => 'Zeskantmoeren', 'category_id' => 1, 'image_path' => 'zeskantmoeren.jpg', 'description' => 'Standaard zeskantmoer voor gebruik met metrische bouten en draadstangen.'],
            ['name' => 'Borgmoeren', 'category_id' => 1, 'image_path' => 'borgmoeren.jpg', 'description' => 'Zelfborgende moer met nylonring, voorkomt losdraaien door trillingen.'],
            ['name' => 'Flensmoeren', 'category_id' => 1, 'image_path' => 'flensmoeren.jpg', 'description' => 'Moer met geïntegreerde flens die de druk verdeelt over een groter oppervlak.'],
            ['name' => 'Sluitringen', 'category_id' => 1, 'image_path' => 'sluitringen.jpg', 'description' => 'Vlakke sluitring voor betere drukopname en bescherming van het werkstukoppervlak.'],
            ['name' => 'Veerringen', 'category_id' => 1, 'image_path' => 'veerringen.jpg', 'description' => 'Borgring met veerende werking, biedt weerstand tegen losdraaien door trillingen.'],
            ['name' => 'Tandringen', 'category_id' => 1, 'image_path' => 'tandringen.jpg', 'description' => 'Getande borgring die zich vastzet in het materiaal voor maximale borging.'],
            ['name' => 'Ankerbouten', 'category_id' => 1, 'image_path' => 'ankerbouten.jpg', 'description' => 'Verankeringsbouten voor bevestiging van constructies in beton of metselwerk.'],
            ['name' => 'Chemische ankers (Hilti HIT)', 'category_id' => 1, 'image_path' => 'chemische-ankers.jpg', 'description' => 'Chemisch verankeringssysteem van Hilti voor hoogbelaste bevestigingen in beton.'],
            ['name' => 'Keilbouten', 'category_id' => 1, 'image_path' => 'keilbouten.webp', 'description' => 'Expansiebout voor mechanische verankering in beton en steen.'],
            ['name' => 'Draadstang M6', 'category_id' => 1, 'image_path' => 'raadstang-m6.jpg', 'description' => 'Volledig gedraaide stang M6, op maat te knippen voor diverse bevestigingsoplossingen.'],
            ['name' => 'Draadstang M8', 'category_id' => 1, 'image_path' => 'draadstang-m8.jpg', 'description' => 'Volledig gedraaide stang M8 voor gebruik in lichte tot middelzware constructies.'],
            ['name' => 'Draadstang M10', 'category_id' => 1, 'image_path' => 'draadstang-m10.jpg', 'description' => 'Volledig gedraaide stang M10 voor middelzware constructie- en installatietoepassingen.'],
            ['name' => 'Draadstang M12', 'category_id' => 1, 'image_path' => 'draadstang-m12.jpg', 'description' => 'Volledig gedraaide stang M12 voor zware constructies en leidingophanging.'],
            ['name' => 'Draadstang M16', 'category_id' => 1, 'image_path' => 'draadstang-m16.jpg', 'description' => 'Volledig gedraaide stang M16 voor zware industriële verankeringstoepassingen.'],
            ['name' => 'Inslagmoeren', 'category_id' => 1, 'image_path' => 'inslagmoeren.avif', 'description' => 'Blindmoer die in hout of kunststof wordt ingeslagen voor verborgen bevestigingen.'],
            ['name' => 'Tapbouten', 'category_id' => 1, 'image_path' => 'tapbouten.jpg', 'description' => 'Bout met fijn draad voor gebruik in dunne materialen of metaalplaten.'],
            ['name' => 'Zeskantkopbouten', 'category_id' => 1, 'image_path' => 'zeskantkopbouten.jpg', 'description' => 'Standaard zeskantkopbout voor algemene constructie- en montagetoepassingen.'],
            ['name' => 'Inbusbouten', 'category_id' => 1, 'image_path' => 'inbusbouten.jpg', 'description' => 'Cilinderkopbout met inbus (zeskant) aandrijving, voor smalle ruimtes.'],
            ['name' => 'Torxschroeven', 'category_id' => 1, 'image_path' => 'torxschroeven.jpg', 'description' => 'Schroef met Torx-aandrijving, biedt meer aandrijfkracht en minder wegglijden.'],
            ['name' => 'Kruiskopschroeven', 'category_id' => 1, 'image_path' => 'kruiskopschroeven.webp', 'description' => 'Schroef met Philipps kruiskop, universeel toepasbaar in hout en metaal.'],
            ['name' => 'Zelftappende vijzen', 'category_id' => 1, 'image_path' => 'zelftappende-vijzen.jpg', 'description' => 'Schroef die eigen draad snijdt in dunne metaalplaten zonder voorboren.'],
            ['name' => 'Parkervijzen', 'category_id' => 1, 'image_path' => 'parkervijzen.jpg', 'description' => 'Zelftappende plaatschroef voor bevestiging in dunne staal- en aluminiumpanelen.'],
            ['name' => 'Spaanplaatschroeven', 'category_id' => 1, 'image_path' => 'spaanplaatschroeven.jpg', 'description' => 'Houtschroef met grof draad, geoptimaliseerd voor spaanplaat en MDF.'],
            ['name' => 'Slangenklemmen', 'category_id' => 1, 'image_path' => 'slangenklemmen.jpg', 'description' => 'Klem voor het afdichten en bevestigen van slangen op aansluitingen.'],

            // Categorie 2 - Persoonlijke beschermingsmiddelen
            ['name' => 'Veiligheidshelm (met kinband)', 'category_id' => 2, 'image_path' => 'veiligheidshelm.jpg', 'description' => 'Harde helm met kinband voor hoofdbescherming op de werf conform EN 397.'],
            ['name' => 'Oordoppen / gehoorkappen', 'category_id' => 2, 'image_path' => 'oordoppen-gehoorkappen.jpg', 'description' => 'Gehoorbescherming tegen lawaai, beschikbaar als oordop of kap conform EN 352.'],
            ['name' => 'Veiligheidsbril / gelaatsscherm', 'category_id' => 2, 'image_path' => 'veiligheidsbril-gelaatsscherm.webp', 'description' => 'Oogbescherming tegen spatten, stof en vliegende deeltjes conform EN 166.'],
            ['name' => 'Stofmaskers (FFP2, FFP3)', 'category_id' => 2, 'image_path' => 'stofmaskers.webp', 'description' => 'Filterend halfmasker FFP2/FFP3 voor bescherming tegen fijn stof en aerosolen.'],
            ['name' => 'Werkhandschoenen (snijvast, chemisch resistent, elektrisch geïsoleerd)', 'category_id' => 2, 'image_path' => 'werkhandschoenen.jpg', 'description' => 'Beschermende handschoenen beschikbaar in snijvaste, chemisch resistente of geïsoleerde uitvoering.'],
            ['name' => 'Veiligheidsschoenen (S3, antistatisch, stalen tip)', 'category_id' => 2, 'image_path' => 'veiligheidsschoenen.jpg', 'description' => 'S3-veiligheidsschoen met stalen neus, antistatisch en waterdicht conform EN ISO 20345.'],
            ['name' => 'Werklaarzen (PVC, nitril, met stalen zool)', 'category_id' => 2, 'image_path' => 'werklaarzen.jpg', 'description' => 'Werklaars in PVC of nitril met stalen zool voor natte en gevaarlijke werkomgevingen.'],
            ['name' => 'Regenkledij (jassen, broeken, capes)', 'category_id' => 2, 'image_path' => 'regenkledij.jpg', 'description' => 'Waterdichte werkkleding: jassen, broeken en capes voor weersomstandigheden.'],
            ['name' => 'Fluovesten / signalisatiekledij (EN ISO 20471)', 'category_id' => 2, 'image_path' => 'Fluovesten-signalisatiekledij.jpg', 'description' => 'Hoog-zichtbare signalisatiekledij conform EN ISO 20471 voor gevaarlijke werkomgevingen.'],
            ['name' => 'Overall (brandvertragend, antistatisch, waterafstotend)', 'category_id' => 2, 'image_path' => 'overall.jpg', 'description' => 'Werkoverall in brandvertragend, antistatisch of waterafstotend materiaal naar keuze.'],
            ['name' => 'Valharnas en lijn', 'category_id' => 2, 'image_path' => 'valharnas-en-lijn.jpg', 'description' => 'Volledig valharnas met veiligheidslijn voor werken op hoogte conform EN 361.'],
            ['name' => 'Gasdetectiemeter (O₂, CH₄, H₂S, CO)', 'category_id' => 2, 'image_path' => 'gasdetectiemeter.jpg', 'description' => 'Draagbare multigas-detector voor meting van O₂, methaan, H₂S en CO.'],
            ['name' => 'Handontsmetting / EHBO-kit', 'category_id' => 2, 'image_path' => 'handontsmetting-EHBO-kit.avif', 'description' => 'Eerste hulpset met handontsmetting, verbandmiddelen en noodmedicatie voor op de werf.'],
            ['name' => 'Klim- en valbeveiligingsmateriaal (harnas, lifeline, karabijnhaken)', 'category_id' => 2, 'image_path' => 'klim-en-valbeveiligingsmateriaal.jpg', 'description' => 'Volledig klimset met harnas, lifeline en karabijnhaken voor veilig werken op hoogte.'],

            // Categorie 3 - Gereedschappen
            ['name' => 'Dopsleutelsets (metrisch en inch)', 'category_id' => 3, 'image_path' => 'dopsleutelsets.jpg', 'description' => 'Complete dopsleutelset in metrische en inch-maten voor algemeen onderhoud.'],
            ['name' => 'Ringsleutels, steeksleutels', 'category_id' => 3, 'image_path' => 'ringsleutels-steeksleutels.png', 'description' => 'Ring- en steeksleutelset voor het aandraaien en losdraaien van bouten en moeren.'],
            ['name' => 'Momentsleutels', 'category_id' => 3, 'image_path' => 'momentsleutels.jpg', 'description' => 'Instelbare momentsleutel voor het aandraaien op exact voorgeschreven aandraaimoment.'],
            ['name' => 'Inbussleutels (los en in set)', 'category_id' => 3, 'image_path' => 'inbussleutels.jpg', 'description' => 'Inbussleutels (zeskant) beschikbaar los of als set voor inbusbouten.'],
            ['name' => 'Schroevendraaiers (plat, kruiskop, Torx, geïsoleerd)', 'category_id' => 3, 'image_path' => 'schroevendraaiers.jpg', 'description' => 'Schroevendraaiers in diverse uitvoeringen: plat, kruiskop, Torx en geïsoleerd tot 1000V.'],
            ['name' => 'Tangen (combinatie, waterpomptang, kniptang, punttang)', 'category_id' => 3, 'image_path' => 'tangen.jpg', 'description' => 'Tangenset met combinatietang, waterpomptang, kniptang en punttang.'],
            ['name' => 'Krimptang / kabelschoentang', 'category_id' => 3, 'image_path' => 'krimptang -kabelschoentang.jpg', 'description' => 'Tang voor het crimpen van kabelschoenen en verbindingsbuizen op elektrische kabels.'],
            ['name' => 'Kabelstripper', 'category_id' => 3, 'image_path' => 'kabelstripper.jpg', 'description' => 'Gereedschap voor het snel en precies strippen van kabelisolatie.'],
            ['name' => 'Hamer, kunststofhamer, moker', 'category_id' => 3, 'image_path' => 'hamer-kunststofhamer-moker.jpg', 'description' => 'Hamers in diverse uitvoeringen: staal, kunststof en moker voor zwaar slagwerk.'],
            ['name' => 'Breekijzer', 'category_id' => 3, 'image_path' => 'breekijzer.webp', 'description' => 'Stalen breekijzer voor het opheffen, losbreken en demonteren van constructiedelen.'],
            ['name' => 'Slijpmachine (haakse slijper)', 'category_id' => 3, 'image_path' => 'slijpmachine.jpg', 'description' => 'Haakse slijper voor het slijpen, snijden en ontroesten van metalen.'],
            ['name' => 'Accuboormachine / klopboormachine', 'category_id' => 3, 'image_path' => 'accuboormachine -klopboormachine.jpg', 'description' => 'Accu- of klopboormachine voor boren in hout, metaal en beton.'],
            ['name' => 'Schroefmachine', 'category_id' => 3, 'image_path' => 'schroefmachine.webp', 'description' => 'Elektrische schroefmachine voor snel en efficiënt plaatsen van schroeven.'],
            ['name' => 'Slagmoersleutel (pneumatisch of accu)', 'category_id' => 3, 'image_path' => 'slagmoersleutel.jpg', 'description' => 'Pneumatische of accugestuurde slagmoersleutel voor snel aan- en afdraaien van bouten.'],
            ['name' => 'Waterpas / laserwaterpas', 'category_id' => 3, 'image_path' => 'waterpas-laserwaterpas.webp', 'description' => 'Waterpas of lasernivelleerder voor het nauwkeurig horizontaal en verticaal uitlijnen.'],
            ['name' => 'Meetlint, rolmeter', 'category_id' => 3, 'image_path' => 'meetlint-rolmeter.jpg', 'description' => 'Rolmeter of meetlint voor het opmeten van afstanden op de werf.'],
            ['name' => 'Spanningstester / multimeter', 'category_id' => 3, 'image_path' => 'spanningstester-multimeter.jpg', 'description' => 'Elektrisch meettoestel voor het meten van spanning, stroom en weerstand.'],
            ['name' => 'Laskist en lasmateriaal (indien van toepassing)', 'category_id' => 3, 'image_path' => 'laskist-lasmateriaal.jpg', 'description' => 'Lasapparatuur en bijbehorend materiaal voor MIG/MAG-, TIG- en elektrodelassen.'],

            // Categorie 4 - Onderhoud & Dichtingen
            ['name' => 'Smeervet (foodgrade, EP2, lithium)', 'category_id' => 4, 'image_path' => 'smeervet.png', 'description' => 'Smeermiddel in foodgrade, EP2 of lithiumbasis voor lagering en mechanische onderdelen.'],
            ['name' => 'O-ringen (div. maten en types)', 'category_id' => 4, 'image_path' => 'o-ringen.jpeg', 'description' => 'Rubberen afdichtringen in diverse maten en materialen voor vloeistof- en gasdichting.'],
            ['name' => 'Pakkingen (papier, rubber, EPDM)', 'category_id' => 4, 'image_path' => 'pakkingen.jpg', 'description' => 'Vlakke afdichtpakking in papier, rubber of EPDM voor flenzen en verbindingen.'],
            ['name' => 'PTFE tape / Loctite', 'category_id' => 4, 'image_path' => 'ptfe-tape-loctite.jpg', 'description' => 'PTFE afdichtingstape of Loctite draadborging voor lekvrije schroefdraadverbindingen.'],
            ['name' => 'Slangen (PVC, PE, persslangen)', 'category_id' => 4, 'image_path' => 'slangen.jpg', 'description' => 'Flexibele slangen in PVC, PE of persuitvoering voor vloeistof- en luchtgeleiding.'],
            ['name' => 'PVC-fittingen, bochten, T-stukken', 'category_id' => 4, 'image_path' => 'pvc-fittingen-bochten-T-stukken.jpg', 'description' => 'PVC leidingfittingen: bochten, T-stukken en koppelingen voor drukleidingen.'],
            ['name' => 'Koppelingen (Geka, Gardena, Camlock)', 'category_id' => 4, 'image_path' => 'koppelingen.jpg', 'description' => 'Snelkoppelingen van het type Geka, Gardena of Camlock voor slang- en leidingaansluitingen.'],
            ['name' => 'V-snaren / kettingen', 'category_id' => 4, 'image_path' => 'v-snaren-kettingen.jpg', 'description' => 'Aandrijfriemen en kettingen voor transmissies in pompen en machines.'],
            ['name' => 'Kabels en wartels (M16–M32)', 'category_id' => 4, 'image_path' => 'kabels-en-wartels.webp', 'description' => 'Kabeldoorvoerwartels M16 tot M32 voor waterdichte kabelinvoer in kasten en motoren.'],
            ['name' => 'Aansluitdozen', 'category_id' => 4, 'image_path' => 'aansluitdozen.jpeg', 'description' => 'Elektrische aansluitdoos voor het verbinden en beschermen van kabelverbindingen.'],
            ['name' => 'Leidingsystemen (druk/afvoer)', 'category_id' => 4, 'image_path' => 'leidingsystemen.jpg', 'description' => 'Buizensystemen voor druk- en afvoerleidingen in riool- en waterbehandelingsinstallaties.'],
            ['name' => 'Pneumatische koppelingen', 'category_id' => 4, 'image_path' => 'pneumatische-koppelingen.jpg', 'description' => 'Snelkoppelingen voor persluchtleidingen in pneumatische installaties.'],
            ['name' => 'Trillingsdempers', 'category_id' => 4, 'image_path' => 'trillingsdempers.webp', 'description' => 'Rubberen trillingsdempers voor het isoleren van trillingen in pompen en motoren.'],

            // Categorie 5 - Riolering & Inspectie
            ['name' => 'Putdekselhaak / mangatopener', 'category_id' => 5, 'image_path' => 'putdekselhaak-mangatopener.jpg', 'description' => 'Haak of hefgereedschap voor het veilig openen van putdeksels en mangaten.'],
            ['name' => 'Rioolcamera / inspectiecamera', 'category_id' => 5, 'image_path' => 'rioolcamera-inspectiecamera.jpg', 'description' => 'Camera voor visuele inspectie van riool- en leidingwerk van binnenuit.'],
            ['name' => 'Gasdetectietoestellen (H₂S, CO, O₂)', 'category_id' => 5, 'image_path' => 'gasdetectietoestellen.jpg', 'description' => 'Draagbaar gasdetectietoestel voor meting van H₂S, CO en zuurstofgehalte in besloten ruimten.'],
            ['name' => 'Ontstoppingsveer / hogedrukreiniger', 'category_id' => 5, 'image_path' => 'ontstoppingsveer-hogedrukreiniger.jpg', 'description' => 'Mechanische ontstoppingsveer of hogedrukreiniger voor het vrijmaken van verstopte leidingen.'],
            ['name' => 'Slangenwagens', 'category_id' => 5, 'image_path' => 'slangenwagens.jpg', 'description' => 'Rijdende slangenwagen voor het ordelijk opbergen en afwikkelen van lange slangen.'],
            ['name' => 'Dompelpompen', 'category_id' => 5, 'image_path' => 'dompelpompen.jpg', 'description' => 'Elektrische dompelpomp voor het afpompen van water uit putten, kelders en riolering.'],
            ['name' => 'Rioolstoppen', 'category_id' => 5, 'image_path' => 'rioolstoppen.webp', 'description' => 'Opblaasbare of mechanische rioolstop voor het tijdelijk afdichten van leidingen.'],
            ['name' => 'Vlotterschakelaars', 'category_id' => 5, 'image_path' => 'vlotterschakelaars.jpg', 'description' => 'Vlotterschakelaar voor automatische niveauregeling van pompen in putten en bassins.'],
            ['name' => 'Niveaumeting (ultrasoon, radar)', 'category_id' => 5, 'image_path' => 'niveaumeting.png', 'description' => 'Ultrasoon of radar niveausensor voor contactloze vloeistofniveaumeting in tanks en putten.'],
            ['name' => 'Staalnamepotten', 'category_id' => 5, 'image_path' => 'staalnamepotten.jpg', 'description' => 'Hersluitbare staalnamepotten voor het nemen van watermonsters voor labo-analyse.'],
            ['name' => 'Monsternameapparatuur', 'category_id' => 5, 'image_path' => 'monsternameapparatuur.jpg', 'description' => 'Professionele apparatuur voor het automatisch of manueel nemen van watermonsters.'],

            // Categorie 6 - Verbruiksmateriaal & Diversen
            ['name' => 'Tie-wraps', 'category_id' => 6, 'image_path' => 'tie-wraps.jpg', 'description' => 'Nylon kabelbinders in diverse lengtes voor het bundelen en bevestigen van kabels.'],
            ['name' => 'Kabelschoenen', 'category_id' => 6, 'image_path' => 'kabelschoenen.jpg', 'description' => 'Gecrimpte kabelschoenen in diverse maten voor betrouwbare elektrische aansluitingen.'],
            ['name' => 'Markeringstape', 'category_id' => 6, 'image_path' => 'markeringstape.webp', 'description' => 'Zelfklevende markeringstape voor het afbakenen van gevaarlijke zones en leidingkleurcodering.'],
            ['name' => 'Siliconenkit / lijm', 'category_id' => 6, 'image_path' => 'siliconenkit-lijm.jpg', 'description' => 'Siliconenkit of constructielijm voor afdichting en verbinding van diverse materialen.'],
            ['name' => 'Rags (reinigingsdoekjes)', 'category_id' => 6, 'image_path' => 'rags.jpg', 'description' => 'Absorptiedoeken voor het reinigen van machines, gereedschap en werkoppervlakken.'],
            ['name' => "Spray's (WD-40, contactspray, kettingspray)", 'category_id' => 6, 'image_path' => "spray's.jpg", 'description' => 'Smeerspray, contactspray of kettingspray voor onderhoud en conservering van metalen.'],
            ['name' => 'Plakband (duct tape, isolatietape)', 'category_id' => 6, 'image_path' => 'plakband.jpg', 'description' => 'Duct tape of isolatietape voor tijdelijke reparaties en elektrische isolatie.'],
            ['name' => "Batterijen / accu's", 'category_id' => 6, 'image_path' => "batterijen-accu's.jpg", 'description' => "Vervangingsbatterijen en accu's voor meettoestellen, detectoren en draadloos gereedschap."],
            ['name' => 'Reserveonderdelen (motoren, PLC-onderdelen, relais)', 'category_id' => 6, 'image_path' => 'reserveonderdelen.jpg', 'description' => 'Kritische reserveonderdelen zoals motoren, PLC-modules en relais voor snelle interventie.'],
            ['name' => 'Flessen met perslucht / gas', 'category_id' => 6, 'image_path' => 'flessen-met-perslucht-gas.jpg', 'description' => 'Drukflessen met perslucht of inertgas voor pneumatische tools en beschermde atmosfeer.'],
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
