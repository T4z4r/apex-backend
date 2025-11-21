<?php

namespace Database\Seeders;

use App\Models\Dispute;
use App\Models\Lease;
use Illuminate\Database\Seeder;

class DisputeSeeder extends Seeder
{
    public function run(): void
    {
        $leases = Lease::all();

        if ($leases->isEmpty()) {
            return; // Skip if no leases exist
        }

        $firstLease = $leases->first();

        $disputes = [
            [
                'lease_id' => $firstLease->id,
                'raised_by' => $firstLease->tenant_id,
                'issue' => 'Rent payment was deducted twice from my account.',
                'status' => 'open',
                'evidence' => json_encode(['receipt1.pdf', 'bank_statement.pdf']),
            ],
        ];

        // Add second dispute if we have multiple leases
        if ($leases->count() >= 2) {
            $secondLease = $leases->skip(1)->first();
            $disputes[] = [
                'lease_id' => $secondLease->id,
                'raised_by' => $secondLease->tenant_id,
                'issue' => 'Maintenance request for plumbing was not addressed.',
                'status' => 'in_review',
                'evidence' => json_encode(['maintenance_ticket.pdf']),
                'admin_resolution_notes' => 'Investigating with maintenance team.',
            ];
        }

        foreach ($disputes as $disputeData) {
            Dispute::create($disputeData);
        }
    }
}