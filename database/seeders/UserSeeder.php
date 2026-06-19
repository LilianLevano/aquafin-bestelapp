<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::pluck('id', 'name');
        $siteIds = Site::all()->pluck('id')->toArray();
        $userData = [
            [
                'email' => 'admin@aquawerf.com',
                'role_id' => $roles['Admin'] ?? 1
            ],
            [
                'email' => 'technieker@aquawerf.com',
                'role_id' => $roles['Technieker'] ?? 2
            ],
            [
                'email' => 'manager@aquawerf.com',
                'role_id' => $roles['Manager'] ?? 3
            ],
            [
                'email' => 'magazijnier@aquawerf.com',
                'role_id' => $roles['Magazijnier'] ?? 4
            ]
        ];

        User::factory(count($userData))
            ->makeMany($userData)
            ->each(function ($user) use ($siteIds) {
                $user->site_id = $siteIds[array_rand($siteIds)];
                $user->save();
            });
    }
}
