<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for small property managers',
                'monthly_price' => 29.99,
                'yearly_price' => 299.99,
                'max_properties' => 5,
                'max_units' => 50,
                'max_users' => 5,
                'features' => ['basic_reporting', 'email_support'],
                'trial_days' => 14,
            ],
            [
                'name' => 'Professional',
                'description' => 'For growing property management businesses',
                'monthly_price' => 79.99,
                'yearly_price' => 799.99,
                'max_properties' => 25,
                'max_units' => 250,
                'max_users' => 25,
                'features' => ['advanced_reporting', 'priority_support', 'api_access'],
                'trial_days' => 30,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Unlimited everything for large operations',
                'monthly_price' => 199.99,
                'yearly_price' => 1999.99,
                'max_properties' => -1, // unlimited
                'max_units' => -1,
                'max_users' => -1,
                'features' => ['all_features', 'dedicated_support', 'custom_integrations'],
                'trial_days' => 30,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}
