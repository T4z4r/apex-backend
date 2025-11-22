<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::whereHas('roles', function($query) {
            $query->where('name', 'agent');
        })->get();

        $firstAgent = $agents->first();
        $secondAgent = $agents->skip(1)->first() ?? $firstAgent;

        $agentData = [
            [
                'user_id' => $firstAgent->id,
                'tenant_id' => $firstAgent->tenant_id,
                'agency_name' => 'Prime Properties Agency',
                'commission_rate' => 5.00,
                'verified_at' => now(),
                'docs' => json_encode(['license.pdf', 'certification.pdf']),
            ],
            [
                'user_id' => $secondAgent->id,
                'tenant_id' => $secondAgent->tenant_id,
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
