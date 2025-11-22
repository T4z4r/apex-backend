<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            TenantSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            UnitSeeder::class,
            AgentSeeder::class,
            LeaseSeeder::class,
            PaymentSeeder::class,
            MaintenanceRequestSeeder::class,
            DisputeSeeder::class,
            ConversationSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
