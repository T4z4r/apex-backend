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
            'phone' => '+1234567890',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
            'tenant_id' => 1,
        ]);
        $admin->assignRole('admin');

        // Create landlords
        $landlords = [
            [
                'name' => 'John Landlord',
                'phone' => '+1234567891',
                'email' => 'john.landlord@example.com',
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'is_verified' => true,
                'tenant_id' => 1,
            ],
            [
                'name' => 'Jane Landlord',
                'phone' => '+1234567892',
                'email' => 'jane.landlord@example.com',
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'is_verified' => true,
                'tenant_id' => 1,
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
                'phone' => '+1234567893',
                'email' => 'alice.tenant@example.com',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'is_verified' => true,
                'tenant_id' => 1,
            ],
            [
                'name' => 'Bob Tenant',
                'phone' => '+1234567894',
                'email' => 'bob.tenant@example.com',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'is_verified' => true,
                'tenant_id' => 1,
            ],
            [
                'name' => 'Charlie Tenant',
                'phone' => '+1234567895',
                'email' => 'charlie.tenant@example.com',
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'is_verified' => true,
                'tenant_id' => 1,
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
                'phone' => '+1234567896',
                'email' => 'david.agent@example.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'is_verified' => true,
                'tenant_id' => 1,
            ],
            [
                'name' => 'Eva Agent',
                'phone' => '+1234567897',
                'email' => 'eva.agent@example.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'is_verified' => true,
                'tenant_id' => 1,
            ],
        ];

        foreach ($agents as $agentData) {
            $agent = User::create($agentData);
            $agent->assignRole('agent');
        }
    }
}
