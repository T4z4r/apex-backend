<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::role('agent')->get();

        $agentData = [
            [
                'user_id' => $agents->first()->id,
                'agency_name' => 'Prime Properties Agency',
                'commission_rate' => 5.00,
                'verified_at' => now(),
                'docs' => json_encode(['license.pdf', 'certification.pdf']),
            ],
            [
                'user_id' => $agents->skip(1)->first()->id ?? $agents->first()->id,
                'agency_name' => 'Elite Realty Services',
                'commission_rate' => 4.50,
                'verified_at' => now(),
                'docs' => json_encode(['license.pdf']),
            ],
        ];

        foreach ($agentData as $data) {
            Agent::create($data);
        }
    }
}