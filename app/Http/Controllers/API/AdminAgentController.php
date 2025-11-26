<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;

class AdminAgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Agent::with('user.tenant');

        if ($request->has('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('verified_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('verified_at');
            }
        }

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $agents = $query->paginate(15);

        return response()->json($agents);
    }

    public function show($id)
    {
        $agent = Agent::with('user.tenant')->findOrFail($id);
        return response()->json($agent);
    }

    public function verify($id)
    {
        $agent = Agent::findOrFail($id);

        if ($agent->verified_at) {
            return response()->json(['message' => 'Agent is already verified'], 400);
        }

        $agent->verified_at = now();
        $agent->is_verified = true;
        $agent->save();

        return response()->json([
            'message' => 'Agent verified successfully',
            'agent' => $agent->load('user.tenant')
        ]);
    }

    public function unverify($id)
    {
        $agent = Agent::findOrFail($id);

        if (!$agent->verified_at) {
            return response()->json(['message' => 'Agent is not verified'], 400);
        }

        $agent->verified_at = null;
        $agent->is_verified = false;
        $agent->save();

        return response()->json([
            'message' => 'Agent verification removed',
            'agent' => $agent->load('user.tenant')
        ]);
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $validated = $request->validate([
            'agency_name' => 'sometimes|string|max:255',
            'commission_rate' => 'sometimes|numeric|min:0|max:100',
            'is_verified' => 'sometimes|boolean'
        ]);

        if (isset($validated['is_verified'])) {
            $agent->is_verified = $validated['is_verified'];
            $agent->verified_at = $validated['is_verified'] ? now() : null;
            unset($validated['is_verified']);
        }

        $agent->update($validated);

        return response()->json($agent->load('user.tenant'));
    }

    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return response()->json(['message' => 'Agent deleted successfully']);
    }

    public function stats()
    {
        $stats = [
            'total_agents' => Agent::count(),
            'verified_agents' => Agent::where('is_verified', true)->count(),
            'pending_verification' => Agent::where('is_verified', false)->count(),
            'avg_commission_rate' => Agent::avg('commission_rate'),
        ];

        return response()->json($stats);
    }
}
