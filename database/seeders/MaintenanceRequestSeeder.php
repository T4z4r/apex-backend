<?php

namespace Database\Seeders;

use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MaintenanceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $leases = Lease::all();

        $maintenanceRequests = [
            [
                'unit_id' => $leases->first()->unit_id,
                'tenant_id' => $leases->first()->tenant_id,
                'landlord_id' => $leases->first()->landlord_id,
                'title' => 'Leaky faucet in kitchen',
                'description' => 'The kitchen faucet has been leaking for a few days. Please fix it.',
                'status' => 'open',
                'priority' => 'medium',
                'photos' => json_encode(['leak1.jpg', 'leak2.jpg']),
            ],
            [
                'unit_id' => $leases->skip(1)->first()->unit_id ?? $leases->first()->unit_id,
                'tenant_id' => $leases->skip(1)->first()->tenant_id ?? $leases->first()->tenant_id,
                'landlord_id' => $leases->skip(1)->first()->landlord_id ?? $leases->first()->landlord_id,
                'title' => 'Broken light fixture',
                'description' => 'The living room light fixture stopped working.',
                'status' => 'in_progress',
                'priority' => 'high',
                'photos' => json_encode(['light.jpg']),
                'assigned_to' => $leases->first()->landlord_id,
            ],
            [
                'unit_id' => $leases->first()->unit_id,
                'tenant_id' => $leases->first()->tenant_id,
                'landlord_id' => $leases->first()->landlord_id,
                'title' => 'Clogged drain',
                'description' => 'Bathroom drain is clogged and not draining properly.',
                'status' => 'resolved',
                'priority' => 'low',
                'resolved_at' => now()->subDays(2),
                'resolution_notes' => 'Used plunger and drain cleaner. Issue resolved.',
            ],
        ];

        foreach ($maintenanceRequests as $requestData) {
            MaintenanceRequest::create($requestData);
        }
    }
}