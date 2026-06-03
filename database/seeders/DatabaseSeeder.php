<?php

namespace Database\Seeders;

use App\Models\Aanvraag;
use App\Models\Role;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'is_admin' => true,
        ]);

        User::factory()->count(4)->create();

        Aanvraag::factory()->count(10)->create();
    }
}
