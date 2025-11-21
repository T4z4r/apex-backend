<?php

namespace Database\Seeders;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $leases = Lease::all();

        if ($leases->isEmpty()) {
            return; // Skip if no leases exist
        }

        $firstLease = $leases->first();

        $payments = [
            [
                'lease_id' => $firstLease->id,
                'unit_id' => $firstLease->unit_id,
                'payer_id' => $firstLease->tenant_id,
                'payee_id' => $firstLease->landlord_id,
                'amount' => 25000.00,
                'method' => 'mpesa',
                'reference' => 'REF001',
                'status' => 'completed',
                'transaction_date' => now()->subDays(5),
            ],
        ];

        // Add second payment if we have multiple leases
        if ($leases->count() >= 2) {
            $secondLease = $leases->skip(1)->first();
            $payments[] = [
                'lease_id' => $secondLease->id,
                'unit_id' => $secondLease->unit_id,
                'payer_id' => $secondLease->tenant_id,
                'payee_id' => $secondLease->landlord_id,
                'amount' => 35000.00,
                'method' => 'bank',
                'reference' => 'REF002',
                'status' => 'completed',
                'transaction_date' => now()->subDays(10),
            ];
        }

        // Add third payment (pending)
        $payments[] = [
            'lease_id' => $firstLease->id,
            'unit_id' => $firstLease->unit_id,
            'payer_id' => $firstLease->tenant_id,
            'payee_id' => $firstLease->landlord_id,
            'amount' => 25000.00,
            'method' => 'airtel',
            'reference' => 'REF003',
            'status' => 'pending',
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }
    }
}