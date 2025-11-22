<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return view('plans.index', [
            'plans' => Plan::all()
        ]);
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:plans,name',
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

        Plan::create($validated);

        return back()->with('success', 'Plan added');
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', [
            'plan' => $plan
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|unique:plans,name,' . $plan->id,
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

        $plan->update($validated);

        return back()->with('success', 'Plan updated');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted');
    }
}