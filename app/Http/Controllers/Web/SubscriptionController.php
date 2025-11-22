<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscriptions.index', [
            'subscriptions' => Subscription::with('plan', 'tenant')->get(),
            'plans' => Plan::all(),
            'tenants' => Tenant::all()
        ]);
    }

    public function create()
    {
        return view('subscriptions.create', [
            'plans' => Plan::where('is_active', true)->get(),
            'tenants' => Tenant::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'status' => 'required|in:active,trial,expired,cancelled'
        ]);

        Subscription::create($validated);

        return back()->with('success', 'Subscription added');
    }

    public function edit(Subscription $subscription)
    {
        return view('subscriptions.edit', [
            'subscription' => $subscription,
            'plans' => Plan::where('is_active', true)->get(),
            'tenants' => Tenant::all()
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'status' => 'required|in:active,trial,expired,cancelled'
        ]);

        $subscription->update($validated);

        return back()->with('success', 'Subscription updated');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return back()->with('success', 'Subscription deleted');
    }
}