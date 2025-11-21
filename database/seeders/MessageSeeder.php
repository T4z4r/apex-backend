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

        $messages = [
            [
                'conversation_id' => $conversations->first()->id,
                'sender_id' => $users->first()->id,
                'content' => 'Hello, I have a question about the property.',
                'attachments' => json_encode([]),
            ],
            [
                'conversation_id' => $conversations->first()->id,
                'sender_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'content' => 'Sure, what would you like to know?',
                'attachments' => json_encode([]),
            ],
            [
                'conversation_id' => $conversations->skip(1)->first()->id ?? $conversations->first()->id,
                'sender_id' => $users->skip(2)->first()->id ?? $users->first()->id,
                'content' => 'When is the lease for unit A102 available?',
                'attachments' => json_encode(['lease_doc.pdf']),
            ],
        ];

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }
    }
}