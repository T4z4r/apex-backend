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
            'attachments' => $attachments ? json_encode($attachments) : null,
            'tenant_id' => Auth::user()->tenant_id
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

    // Show single message
    public function show($conversationId, $messageId)
    {
        $message = Message::with('sender')->findOrFail($messageId);

        if ($message->conversation_id != $conversationId) {
            return response()->json(['message' => 'Message not in conversation'], 404);
        }

        $conversation = Conversation::findOrFail($conversationId);
        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($message, 200);
    }

    // Update message content
    public function update(Request $request, $conversationId, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->conversation_id != $conversationId) {
            return response()->json(['message' => 'Message not in conversation'], 404);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Can only edit own messages'], 403);
        }

        $validated = $request->validate([
            'content' => 'nullable|string'
        ]);

        $message->update($validated);

        return response()->json($message, 200);
    }

    // Delete message
    public function destroy($conversationId, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->conversation_id != $conversationId) {
            return response()->json(['message' => 'Message not in conversation'], 404);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Can only delete own messages'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted'], 200);
    }
}
