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

        if ($leases->isEmpty()) {
            return; // Skip if no leases exist
        }

        $firstLease = $leases->first();

        $maintenanceRequests = [
            [
                'unit_id' => $firstLease->unit_id,
                'tenant_id' => $firstLease->tenant_id,
                'landlord_id' => $firstLease->landlord_id,
                'title' => 'Leaky faucet in kitchen',
                'description' => 'The kitchen faucet has been leaking for a few days. Please fix it.',
                'status' => 'open',
                'priority' => 'medium',
                'photos' => json_encode(['leak1.jpg', 'leak2.jpg']),
            ],
        ];

        // Add second request if we have multiple leases
        if ($leases->count() >= 2) {
            $secondLease = $leases->skip(1)->first();
            $maintenanceRequests[] = [
                'unit_id' => $secondLease->unit_id,
                'tenant_id' => $secondLease->tenant_id,
                'landlord_id' => $secondLease->landlord_id,
                'title' => 'Broken light fixture',
                'description' => 'The living room light fixture stopped working.',
                'status' => 'in_progress',
                'priority' => 'high',
                'photos' => json_encode(['light.jpg']),
                'assigned_to' => $firstLease->landlord_id,
            ];
        }

        // Add third request (resolved)
        $maintenanceRequests[] = [
            'unit_id' => $firstLease->unit_id,
            'tenant_id' => $firstLease->tenant_id,
            'landlord_id' => $firstLease->landlord_id,
            'title' => 'Clogged drain',
            'description' => 'Bathroom drain is clogged and not draining properly.',
            'status' => 'resolved',
            'priority' => 'low',
            'resolved_at' => now()->subDays(2),
            'resolution_notes' => 'Used plunger and drain cleaner. Issue resolved.',
        ];

        foreach ($maintenanceRequests as $requestData) {
            MaintenanceRequest::create($requestData);
        }
    }
}