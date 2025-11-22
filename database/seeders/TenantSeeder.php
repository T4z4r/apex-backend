<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate([
            'domain' => 'default',
        ], [
            'name' => 'Default Tenant',
        ]);

        // Create subscription for default tenant if it doesn't exist (Enterprise plan)
        if (!$tenant->subscription) {
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => 3, // Enterprise plan
                'billing_cycle' => 'monthly',
                'status' => 'active',
            ]);
        }
    }
}
