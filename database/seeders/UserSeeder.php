<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create landlords
        $landlords = [
            [
                'name' => 'John Landlord',
                'email' => 'john.landlord@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Landlord',
                'email' => 'jane.landlord@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($landlords as $landlordData) {
            $landlord = User::create($landlordData);
            $landlord->assignRole('landlord');
        }

        // Create tenants
        $tenants = [
            [
                'name' => 'Alice Tenant',
                'email' => 'alice.tenant@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Tenant',
                'email' => 'bob.tenant@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Charlie Tenant',
                'email' => 'charlie.tenant@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = User::create($tenantData);
            $tenant->assignRole('tenant');
        }

        // Create agents
        $agents = [
            [
                'name' => 'David Agent',
                'email' => 'david.agent@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Eva Agent',
                'email' => 'eva.agent@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($agents as $agentData) {
            $agent = User::create($agentData);
            $agent->assignRole('agent');
        }
    }
}
