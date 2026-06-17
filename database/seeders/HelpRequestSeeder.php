<?php

namespace Database\Seeders;

use App\Models\HelpRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class HelpRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::get(['email', 'first_name', 'last_name'])->toArray();

        HelpRequest::factory(10)
            ->make()
            ->each(function ($helpRequest) use ($users) {
                $randomUser = $users[array_rand($users)];
                $helpRequest->email = $randomUser['email'];
                $helpRequest->first_name = $randomUser['first_name'];
                $helpRequest->last_name = $randomUser['last_name'];
                $helpRequest->save();
            });
    }
}
