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
        $tenants = User::role('tenant')->get();
        $landlords = User::role('landlord')->get();

        $payments = [
            [
                'lease_id' => $leases->first()->id,
                'unit_id' => $leases->first()->unit_id,
                'payer_id' => $leases->first()->tenant_id,
                'payee_id' => $leases->first()->landlord_id,
                'amount' => 25000.00,
                'method' => 'mpesa',
                'reference' => 'REF001',
                'status' => 'completed',
                'transaction_date' => now()->subDays(5),
            ],
            [
                'lease_id' => $leases->skip(1)->first()->id ?? $leases->first()->id,
                'unit_id' => $leases->skip(1)->first()->unit_id ?? $leases->first()->unit_id,
                'payer_id' => $leases->skip(1)->first()->tenant_id ?? $leases->first()->tenant_id,
                'payee_id' => $leases->skip(1)->first()->landlord_id ?? $leases->first()->landlord_id,
                'amount' => 35000.00,
                'method' => 'bank',
                'reference' => 'REF002',
                'status' => 'completed',
                'transaction_date' => now()->subDays(10),
            ],
            [
                'lease_id' => $leases->first()->id,
                'unit_id' => $leases->first()->unit_id,
                'payer_id' => $leases->first()->tenant_id,
                'payee_id' => $leases->first()->landlord_id,
                'amount' => 25000.00,
                'method' => 'airtel',
                'reference' => 'REF003',
                'status' => 'pending',
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }
    }
}