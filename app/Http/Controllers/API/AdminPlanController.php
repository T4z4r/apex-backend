<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class AdminPlanController extends Controller
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
        $query = Plan::query();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $plans = $query->withCount('subscriptions')->paginate(15);

        return response()->json($plans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'max_properties' => 'required|integer|min:0',
            'max_units' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'trial_days' => 'nullable|integer|min:0'
        ]);

        $plan = Plan::create($validated);

        return response()->json($plan, 201);
    }

    public function show($id)
    {
        $plan = Plan::with('subscriptions.tenant')->findOrFail($id);
        return response()->json($plan);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:plans,name,' . $id,
            'description' => 'nullable|string',
            'monthly_price' => 'sometimes|numeric|min:0',
            'yearly_price' => 'sometimes|numeric|min:0',
            'max_properties' => 'sometimes|integer|min:0',
            'max_units' => 'sometimes|integer|min:0',
            'max_users' => 'sometimes|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'trial_days' => 'nullable|integer|min:0'
        ]);

        $plan->update($validated);

        return response()->json($plan);
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);

        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return response()->json(['message' => 'Cannot delete plan with active subscriptions'], 400);
        }

        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully']);
    }

    public function toggleActive($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->is_active = !$plan->is_active;
        $plan->save();

        return response()->json([
            'message' => 'Plan ' . ($plan->is_active ? 'activated' : 'deactivated') . ' successfully',
            'plan' => $plan
        ]);
    }

    public function stats()
    {
        $stats = [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('is_active', true)->count(),
            'inactive_plans' => Plan::where('is_active', false)->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'monthly_subscriptions' => Subscription::where('billing_cycle', 'monthly')->where('status', 'active')->count(),
            'yearly_subscriptions' => Subscription::where('billing_cycle', 'yearly')->where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')
                ->selectRaw('SUM(CASE WHEN billing_cycle = "monthly" THEN plan.monthly_price ELSE plan.yearly_price END) as revenue')
                ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                ->first()
                ->revenue ?? 0,
            'plans_by_popularity' => Plan::withCount('subscriptions')
                ->orderBy('subscriptions_count', 'desc')
                ->take(5)
                ->get(['id', 'name', 'subscriptions_count'])
                ->toArray(),
        ];

        return response()->json($stats);
    }

    public function duplicate($id)
    {
        $originalPlan = Plan::findOrFail($id);

        $duplicatedPlan = Plan::create([
            'name' => $originalPlan->name . ' (Copy)',
            'description' => $originalPlan->description,
            'monthly_price' => $originalPlan->monthly_price,
            'yearly_price' => $originalPlan->yearly_price,
            'max_properties' => $originalPlan->max_properties,
            'max_units' => $originalPlan->max_units,
            'max_users' => $originalPlan->max_users,
            'features' => $originalPlan->features,
            'is_active' => false, // Duplicated plans start inactive
            'trial_days' => $originalPlan->trial_days,
        ]);

        return response()->json([
            'message' => 'Plan duplicated successfully',
            'plan' => $duplicatedPlan
        ], 201);
    }
}
