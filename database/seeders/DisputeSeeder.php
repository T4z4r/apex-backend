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

        $disputes = [
            [
                'lease_id' => $leases->first()->id,
                'raised_by' => $leases->first()->tenant_id,
                'issue' => 'Rent payment was deducted twice from my account.',
                'status' => 'open',
                'evidence' => json_encode(['receipt1.pdf', 'bank_statement.pdf']),
            ],
            [
                'lease_id' => $leases->skip(1)->first()->id ?? $leases->first()->id,
                'raised_by' => $leases->skip(1)->first()->tenant_id ?? $leases->first()->tenant_id,
                'issue' => 'Maintenance request for plumbing was not addressed.',
                'status' => 'in_review',
                'evidence' => json_encode(['maintenance_ticket.pdf']),
                'admin_resolution_notes' => 'Investigating with maintenance team.',
            ],
        ];

        foreach ($disputes as $disputeData) {
            Dispute::create($disputeData);
        }
    }
}