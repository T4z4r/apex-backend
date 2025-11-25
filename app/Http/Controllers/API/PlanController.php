<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    // List all active plans
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        return response()->json($plans, 200);
    }

    // Get single plan
    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json($plan, 200);
    }

    // Create plan (admin only)
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'max_properties' => 'required|integer|min:0',
            'max_units' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'trial_days' => 'nullable|integer|min:0'
        ]);

        $plan = Plan::create(array_merge($validated, [
            'tenant_id' => Auth::user()->tenant_id
        ]));

        return response()->json($plan, 201);
    }

    // Update plan (admin only)
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'sometimes|required|numeric|min:0',
            'yearly_price' => 'sometimes|required|numeric|min:0',
            'max_properties' => 'sometimes|required|integer|min:0',
            'max_units' => 'sometimes|required|integer|min:0',
            'max_users' => 'sometimes|required|integer|min:0',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'trial_days' => 'nullable|integer|min:0'
        ]);

        $plan->update($validated);

        return response()->json($plan, 200);
    }

    // Delete plan (admin only)
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $plan = Plan::findOrFail($id);

        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return response()->json(['message' => 'Cannot delete plan with active subscriptions'], 400);
        }

        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully'], 200);
    }
}
