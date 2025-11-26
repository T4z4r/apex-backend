<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
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

    public function index()
    {
        $tenants = Tenant::with('subscription.plan', 'users')->paginate(15);
        return response()->json($tenants);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain'
        ]);

        $tenant = Tenant::create($validated);

        return response()->json($tenant, 201);
    }

    public function show($id)
    {
        $tenant = Tenant::with('subscription.plan', 'users', 'properties', 'units')->findOrFail($id);
        return response()->json($tenant);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'sometimes|string|unique:tenants,domain,' . $id
        ]);

        $tenant->update($validated);

        return response()->json($tenant);
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Check if tenant has active data
        if ($tenant->users()->exists() || $tenant->properties()->exists()) {
            return response()->json(['message' => 'Cannot delete tenant with existing data'], 400);
        }

        $tenant->delete();
        return response()->json(['message' => 'Tenant deleted successfully']);
    }

    public function stats($id)
    {
        $tenant = Tenant::findOrFail($id);

        $stats = [
            'total_users' => $tenant->users()->count(),
            'total_properties' => $tenant->properties()->count(),
            'total_units' => $tenant->units()->count(),
            'active_leases' => $tenant->leases()->where('status', 'active')->count(),
            'pending_maintenance' => $tenant->maintenanceRequests()->where('status', 'open')->count(),
            'total_payments' => $tenant->payments()->sum('amount'),
            'subscription_status' => $tenant->hasActiveSubscription() ? 'active' : 'inactive'
        ];

        return response()->json($stats);
    }
}
