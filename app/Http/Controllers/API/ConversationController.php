<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    // List all conversations for the authenticated user
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['messages.sender'])->get();

        return response()->json($conversations, 200);
    }

    // Create a new conversation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id'
        ]);

        $conversation = Conversation::create([
            'title' => $validated['title'] ?? null,
            'tenant_id' => Auth::user()->tenant_id
        ]);

        // Attach participants
        $conversation->participants()->attach($validated['participants']);
        $conversation->participants()->attach(Auth::id()); // Add self

        return response()->json($conversation->load('participants'), 201);
    }

    // Show single conversation
    public function show($id)
    {
        $conversation = Conversation::with(['messages.sender', 'participants'])->findOrFail($id);

        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($conversation, 200);
    }

    // Update conversation title
    public function update(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255'
        ]);

        $conversation->update($validated);

        return response()->json($conversation, 200);
    }

    // Leave or delete conversation
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (!$conversation->participants->contains(Auth::id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // If no messages, delete conversation
        if ($conversation->messages()->count() === 0) {
            $conversation->delete();
            return response()->json(['message' => 'Conversation deleted'], 200);
        } else {
            // Otherwise, detach user
            $conversation->participants()->detach(Auth::id());
            return response()->json(['message' => 'Left conversation'], 200);
        }
    }
}
