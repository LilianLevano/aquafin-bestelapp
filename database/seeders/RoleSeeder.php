<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleNames = [
            'Admin',
            'Technieker',
            'Manager',
            'Magazijnier'
        ];

        Role::factory(count($roleNames))
            ->makeMany()
            ->each(function ($role, $i) use ($roleNames) {
                $role->name = $roleNames[$i];
                $role->save();
            });
    }
}
