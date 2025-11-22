<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = Conversation::all();
        $users = User::all();

        if ($conversations->isEmpty() || $users->isEmpty()) {
            return; // Skip if required data doesn't exist
        }

        $firstConversation = $conversations->first();

        $messages = [
            [
                'conversation_id' => $firstConversation->id,
                'tenant_id' => $firstConversation->tenant_id,
                'sender_id' => $users->first()->id,
                'content' => 'Hello, I have a question about the property.',
            ],
        ];

        // Add second message if we have enough users
        if ($users->count() >= 2) {
            $messages[] = [
                'conversation_id' => $firstConversation->id,
                'tenant_id' => $firstConversation->tenant_id,
                'sender_id' => $users->skip(1)->first()->id,
                'content' => 'Sure, what would you like to know?',
            ];
        }

        // Add third message if we have multiple conversations and users
        if ($conversations->count() >= 2 && $users->count() >= 3) {
            $secondConversation = $conversations->skip(1)->first();
            $messages[] = [
                'conversation_id' => $secondConversation->id,
                'tenant_id' => $secondConversation->tenant_id,
                'sender_id' => $users->skip(2)->first()->id,
                'content' => 'When is the lease for unit A102 available?',
            ];
        }

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }
    }
}