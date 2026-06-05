<?php

namespace Database\Seeders;

use App\Models\Aanvraag;
use App\Models\Bestelling;
use App\Models\Category;
use App\Models\Materiaal;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        Role::factory()->sequence(

            ['name'=>'Admin'],
            ['name'=>'Technieker'],
            ['name'=>'Manager'],
            ['name'=>'Magazijnier'],

        )->count(4)->create();



        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'role_id' => 1,
            'password' => bcrypt('password'),

        ]);

        User::factory()->count(4)->create();

        Aanvraag::factory()->count(10)->create();

        Category::factory()->count(6)->create();

        Materiaal::factory()->sequence(
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
            ['name' => 'Flessen met perslucht / gas', 'category_id' => 6],
        )->count(98)->create();

        Site::factory()->sequence(

                ['locatie' => 'Aquafin', 'adres' => 'Dijkstraat 8, 2630 Aartselaar'],
                ['locatie' => 'Aquafin Labo Aalst', 'adres' => 'Spuimeersenweg 2, 9308 Aalst'],
                ['locatie' => 'Aquafin RWZI Mechelen-Noord', 'adres' => 'Blarenberglaan 31, 2800 Mechelen'],
                ['locatie' => 'Aquafin RWZI Antwerpen-Zuid', 'adres' => 'Kielsbroek 5, 2020 Antwerpen'],
                ['locatie' => 'Aquafin RWZI Aartselaar', 'adres' => 'Boomsesteenweg 1002, 2610 Antwerpen'],
                ['locatie' => 'Aquafin RWZI Burcht', 'adres' => 'Burchtse Weel 20, 2070 Beveren-Kruibeke-Zwijndrecht'],
                ['locatie' => 'Aquafin RWZI Kalmthout', 'adres' => 'Handelaar 21, 2920 Kalmthout'],
                ['locatie' => 'Aquafin RWZI Gent', 'adres' => 'Drongensesteenweg 254, 9000 Gent'],
                ['locatie' => 'Aquafin RWZI Evergem', 'adres' => 'Westbekesluis 1, 9940 Evergem'],
                ['locatie' => 'Aquafin RWZI Aalter', 'adres' => 'Brug-Zuid 47, 9880 Aalter'],
                ['locatie' => 'Aquafin RWZI Brugge', 'adres' => 'Pathoekeweg 45, 8000 Brugge'],
                ['locatie' => 'Aquafin rioolwaterzuiveringsinstallatie Harelbeke', 'adres' => 'Kortrijksesteenweg 308, 8530 Harelbeke'],
                ['locatie' => 'Aquafin', 'adres' => 'Langeleedstraat 14, 8670 Koksijde'],
                ['locatie' => 'Aquafin RWZI Liedekerke', 'adres' => 'Nijverheidszone Begijnenmeers 35, 1770 Liedekerke'],
                ['locatie' => 'Aquafin RWZI Wimmertingen', 'adres' => 'Grootstraat 41, 3500 Hasselt'],
                ['locatie' => 'Aquafin RWZI Houthalen-Helchteren', 'adres' => 'Centrum-Zuid 2097, 3530 Houthalen-Helchteren'],
                ['locatie' => 'Aquafin RWZI Genk', 'adres' => 'Diepenbekerbos 12, 3600 Genk'],

        )->count(17)->create();

        Bestelling::factory()->count(10)->create();

        DB::table('bestelling-materiaal')->insert([

            ['bestelling_id' => 1, 'materiaal_id' => 1],
            ['bestelling_id' => 1, 'materiaal_id' => 2],

        ]);

    }
}
