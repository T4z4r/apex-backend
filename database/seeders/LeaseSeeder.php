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
        $tenants = User::whereHas('roles', function($query) {
            $query->where('name', 'tenant');
        })->get();
        $landlords = User::whereHas('roles', function($query) {
            $query->where('name', 'landlord');
        })->get();

        if ($units->isEmpty() || $tenants->isEmpty() || $landlords->isEmpty()) {
            return; // Skip if required data doesn't exist
        }

        $firstLandlord = $landlords->first();
        $leases = [
            [
                'unit_id' => $units->first()->id,
                'tenant_id' => $firstLandlord->tenant_id,
                'landlord_id' => $firstLandlord->id,
                'start_date' => now()->subMonths(1)->toDateString(),
                'end_date' => now()->addMonths(11)->toDateString(),
                'rent_amount' => 25000.00,
                'deposit_amount' => 25000.00,
                'payment_frequency' => 'monthly',
                'status' => 'active',
                'signed_at' => now()->subMonths(1),
            ],
        ];

        // Add second lease if we have enough data
        if ($units->count() >= 2 && $tenants->count() >= 2) {
            $leases[] = [
                'unit_id' => $units->skip(1)->first()->id,
                'tenant_id' => $firstLandlord->tenant_id,
                'landlord_id' => $firstLandlord->id,
                'start_date' => now()->subMonths(2)->toDateString(),
                'end_date' => now()->addMonths(10)->toDateString(),
                'rent_amount' => 35000.00,
                'deposit_amount' => 35000.00,
                'payment_frequency' => 'monthly',
                'status' => 'active',
                'signed_at' => now()->subMonths(2),
            ];
        }

        // Add third lease if we have enough data
        if ($units->count() >= 3 && $tenants->count() >= 3 && $landlords->count() >= 2) {
            $secondLandlord = $landlords->skip(1)->first();
            $leases[] = [
                'unit_id' => $units->skip(2)->first()->id,
                'tenant_id' => $secondLandlord->tenant_id,
                'landlord_id' => $secondLandlord->id,
                'start_date' => now()->addMonths(1)->toDateString(),
                'end_date' => now()->addMonths(13)->toDateString(),
                'rent_amount' => 60000.00,
                'deposit_amount' => 60000.00,
                'payment_frequency' => 'monthly',
                'status' => 'pending',
            ];
        }

        foreach ($leases as $leaseData) {
            Lease::create($leaseData);
        }
    }
}
