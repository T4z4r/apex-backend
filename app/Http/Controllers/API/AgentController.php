<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    // Register as an agent (landlord or user)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'agent') {
            return response()->json(['message' => 'Only agent users can register as agent'], 403);
        }

        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'docs.*' => 'nullable|file|max:5120'
        ]);

        $docsUrls = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $path = $doc->store('agents', 'public');
                $docsUrls[] = Storage::url($path);
            }
        }

        $agent = Agent::create([
            'user_id' => $user->id,
            'agency_name' => $validated['agency_name'],
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'docs' => $docsUrls ? json_encode($docsUrls) : null,
            'tenant_id' => Auth::user()->tenant_id
        ]);

        return response()->json($agent, 201);
    }

    // Admin verifies an agent
    public function verify($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $agent = Agent::findOrFail($id);
        $agent->verified_at = now();
        $agent->save();

        return response()->json(['message' => 'Agent verified successfully', 'agent' => $agent], 200);
    }

    // List all agents (optional filtering)
    public function index()
    {
        $agents = Agent::with('user')->get();
        return response()->json($agents, 200);
    }

    // Show single agent
    public function show($id)
    {
        $agent = Agent::with('user')->findOrFail($id);
        return response()->json($agent, 200);
    }

    // Update agent details
    public function update(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $agent->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'agency_name' => 'sometimes|required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'docs.*' => 'nullable|file|max:5120'
        ]);

        $docsUrls = $agent->docs ? json_decode($agent->docs, true) : [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $path = $doc->store('agents', 'public');
                $docsUrls[] = Storage::url($path);
            }
        }

        $agent->update([
            'agency_name' => $validated['agency_name'] ?? $agent->agency_name,
            'commission_rate' => $validated['commission_rate'] ?? $agent->commission_rate,
            'docs' => $docsUrls ? json_encode($docsUrls) : $agent->docs
        ]);

        return response()->json($agent, 200);
    }

    // Delete agent profile
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $agent->user_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent profile deleted'], 200);
    }
}
