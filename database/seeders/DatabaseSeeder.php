<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with production-ready essentials.
     */
    public function run(): void
    {
        // 1. Dynamic Subscription Plans
        $this->call(SubscriptionPlanSeeder::class);

        // 2. Demo Users, Profiles & Super Administrator
        $this->call(DemoDataSeeder::class);
    }
}
