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
            'title' => $validated['title'] ?? null
        ]);

        // Attach participants
        $conversation->participants()->attach($validated['participants']);
        $conversation->participants()->attach(Auth::id()); // Add self

        return response()->json($conversation->load('participants'), 201);
    }
}
