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
                'title' => 'General Inquiry about Property A101',
            ],
            [
                'title' => 'Lease Discussion for Unit A102',
            ],
            [
                'title' => 'Maintenance Follow-up',
            ],
        ];

        foreach ($conversations as $conversationData) {
            Conversation::create($conversationData);
        }
    }
}