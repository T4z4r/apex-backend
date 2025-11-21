<?php

namespace Database\Seeders;

use App\Models\Lease;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaseSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $tenants = User::role('tenant')->get();
        $landlords = User::role('landlord')->get();

        $leases = [
            [
                'unit_id' => $units->first()->id,
                'tenant_id' => $tenants->first()->id,
                'landlord_id' => $landlords->first()->id,
                'start_date' => now()->subMonths(1)->toDateString(),
                'end_date' => now()->addMonths(11)->toDateString(),
                'rent_amount' => 25000.00,
                'deposit_amount' => 25000.00,
                'payment_frequency' => 'monthly',
                'status' => 'active',
                'signed_at' => now()->subMonths(1),
            ],
            [
                'unit_id' => $units->skip(1)->first()->id ?? $units->first()->id,
                'tenant_id' => $tenants->skip(1)->first()->id ?? $tenants->first()->id,
                'landlord_id' => $landlords->first()->id,
                'start_date' => now()->subMonths(2)->toDateString(),
                'end_date' => now()->addMonths(10)->toDateString(),
                'rent_amount' => 35000.00,
                'deposit_amount' => 35000.00,
                'payment_frequency' => 'monthly',
                'status' => 'active',
                'signed_at' => now()->subMonths(2),
            ],
            [
                'unit_id' => $units->skip(2)->first()->id ?? $units->first()->id,
                'tenant_id' => $tenants->skip(2)->first()->id ?? $tenants->first()->id,
                'landlord_id' => $landlords->skip(1)->first()->id ?? $landlords->first()->id,
                'start_date' => now()->addMonths(1)->toDateString(),
                'end_date' => now()->addMonths(13)->toDateString(),
                'rent_amount' => 60000.00,
                'deposit_amount' => 60000.00,
                'payment_frequency' => 'monthly',
                'status' => 'pending',
            ],
        ];

        foreach ($leases as $leaseData) {
            Lease::create($leaseData);
        }
    }
}