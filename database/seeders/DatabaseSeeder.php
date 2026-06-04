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


        Role::factory()->count(4)->create();

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
        Materiaal::factory()->count(30)->create();

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
