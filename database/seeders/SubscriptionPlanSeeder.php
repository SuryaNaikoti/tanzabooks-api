<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Basic',
                'tagline' => 'Basic Plan for Limited Access',
                'price' => 0.00,
                'max_tanzabooks' => "5",
                'max_groups' => "5",
                'max_folders' => "5",
                'max_shares' => "5",
            ],
            [
                'name' => 'Premium',
                'tagline' => 'Best Plan with Unlimited Access',
                'price' => 49.00,
                'max_tanzabooks' => "Unlimited",
                'max_groups' => "Unlimited",
                'max_folders' => "Unlimited",
                'max_shares' => "Unlimited",
            ]
        ]);
    }
}
