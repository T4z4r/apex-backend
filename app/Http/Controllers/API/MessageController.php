<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // Send message in a conversation
    public function store(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Ensure user is a participant
        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('messages', 'public');
                $attachments[] = Storage::url($path);
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $validated['content'] ?? '',
            'attachments' => $attachments ? json_encode($attachments) : null
        ]);

        return response()->json($message, 201);
    }

    // List messages in a conversation
    public function index($conversationId)
    {
        $conversation = Conversation::with('messages.sender')->findOrFail($conversationId);

        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($conversation->messages, 200);
    }
}
