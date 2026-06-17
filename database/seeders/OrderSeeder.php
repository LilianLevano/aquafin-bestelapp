<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersSites = User::pluck('site_id', 'id')->toArray();

        Order::factory(4)
            ->make()
            ->each(function ($order) use ($usersSites) {
                $randomUser = $usersSites[array_rand($usersSites)];
                $site = $usersSites[$randomUser];

                $order->user_id = $randomUser;
                $order->site_id = $site;
                $order->save();
            });
    }
}
