<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    // List subscriptions (user's own or all for admin)
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $subscriptions = Subscription::with('plan', 'tenant')->get();
        } else {
            // Assuming tenant_id is linked to user, but need to check how tenant is associated
            // For now, assume user has tenant relationship or find tenant by user
            $tenant = Tenant::where('user_id', $user->id)->first();
            if (!$tenant) {
                return response()->json(['message' => 'No tenant found for user'], 404);
            }
            $subscriptions = Subscription::where('tenant_id', $tenant->id)->with('plan')->get();
        }

        return response()->json($subscriptions, 200);
    }

    // Get single subscription
    public function show($id)
    {
        $subscription = Subscription::with('plan', 'tenant')->findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $subscription->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        return response()->json($subscription, 200);
    }

    // Subscribe to a plan
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if (!$tenant) {
                return response()->json(['message' => 'No tenant found for user'], 404);
            }
        } else {
            // Admin can subscribe for any tenant
            $tenant = Tenant::findOrFail($request->tenant_id);
        }

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly'
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        if (!$plan->is_active) {
            return response()->json(['message' => 'Plan is not active'], 400);
        }

        // Check if tenant already has active subscription
        $existing = Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Tenant already has an active subscription'], 400);
        }

        $trialEndsAt = $plan->trial_days ? Carbon::now()->addDays($plan->trial_days) : null;
        $endsAt = $trialEndsAt ? $trialEndsAt->copy()->addMonths($validated['billing_cycle'] === 'yearly' ? 12 : 1) : Carbon::now()->addMonths($validated['billing_cycle'] === 'yearly' ? 12 : 1);

        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $validated['billing_cycle'],
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $endsAt,
            'status' => $trialEndsAt ? 'trial' : 'active'
        ]);

        return response()->json($subscription->load('plan'), 201);
    }

    // Update subscription (change plan, cycle)
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $subscription->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $validated = $request->validate([
            'plan_id' => 'sometimes|exists:plans,id',
            'billing_cycle' => 'sometimes|in:monthly,yearly',
            'status' => 'sometimes|in:active,expired,cancelled'
        ]);

        if (isset($validated['plan_id'])) {
            $plan = Plan::findOrFail($validated['plan_id']);
            $subscription->plan_id = $plan->id;
        }

        if (isset($validated['billing_cycle'])) {
            $subscription->billing_cycle = $validated['billing_cycle'];
        }

        if (isset($validated['status'])) {
            $subscription->status = $validated['status'];
        }

        $subscription->save();

        return response()->json($subscription->load('plan'), 200);
    }

    // Cancel subscription
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $tenant = Tenant::where('user_id', $user->id)->first();
            if (!$tenant || $subscription->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $subscription->status = 'cancelled';
        $subscription->ends_at = Carbon::now(); // End immediately
        $subscription->save();

        return response()->json(['message' => 'Subscription cancelled'], 200);
    }
}
