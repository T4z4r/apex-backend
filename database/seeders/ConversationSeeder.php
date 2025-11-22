<?php

namespace Database\Seeders;

use App\Models\Conversation;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = [
            [
                'tenant_id' => 1,
                'title' => 'General Inquiry about Property A101',
            ],
            [
                'tenant_id' => 1,
                'title' => 'Lease Discussion for Unit A102',
            ],
            [
                'tenant_id' => 1,
                'title' => 'Maintenance Follow-up',
            ],
        ];

        foreach ($conversations as $conversationData) {
            Conversation::create($conversationData);
        }
    }
}