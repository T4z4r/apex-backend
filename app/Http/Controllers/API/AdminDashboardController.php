<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Agent;
use App\Models\Dispute;
use App\Models\Tenant;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
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

    public function overview()
    {
        // Overall system statistics
        $stats = [
            'total_tenants' => Tenant::count(),
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'total_units' => Unit::count(),
            'active_leases' => Lease::where('status', 'active')->count(),
            'total_payments' => Payment::sum('amount'),
            'pending_maintenance' => MaintenanceRequest::where('status', 'open')->count(),
            'total_agents' => Agent::count(),
            'verified_agents' => Agent::where('is_verified', true)->count(),
            'open_disputes' => Dispute::where('status', 'open')->count(),
            'active_subscriptions' => Subscription::whereHas('plan', function($q) {
                $q->where('is_active', true);
            })->where(function($q) {
                $q->where('status', 'active')
                  ->orWhere(function($sq) {
                      $sq->where('status', 'trial')
                         ->where('trial_ends_at', '>', now());
                  });
            })->count(),
        ];

        return response()->json($stats);
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);

        $analytics = [
            'user_registrations' => [
                'total' => User::where('created_at', '>=', $startDate)->count(),
                'by_role' => User::selectRaw('role, COUNT(*) as count')
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('role')
                    ->pluck('count', 'role')
                    ->toArray(),
            ],
            'property_stats' => [
                'new_properties' => Property::where('created_at', '>=', $startDate)->count(),
                'total_properties' => Property::count(),
                'occupied_units' => Unit::whereHas('leases', function($q) {
                    $q->where('status', 'active');
                })->count(),
                'vacant_units' => Unit::whereDoesntHave('leases', function($q) {
                    $q->where('status', 'active');
                })->count(),
            ],
            'financial_stats' => [
                'total_revenue' => Payment::where('status', 'completed')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'monthly_revenue' => Payment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month')
                    ->toArray(),
            ],
            'maintenance_stats' => [
                'total_requests' => MaintenanceRequest::where('created_at', '>=', $startDate)->count(),
                'by_status' => MaintenanceRequest::selectRaw('status, COUNT(*) as count')
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
                'avg_resolution_time' => MaintenanceRequest::where('status', 'resolved')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
                    ->first()
                    ->avg_hours ?? 0,
            ],
            'dispute_stats' => [
                'total_disputes' => Dispute::where('created_at', '>=', $startDate)->count(),
                'by_status' => Dispute::selectRaw('status, COUNT(*) as count')
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
            ],
            'agent_stats' => [
                'new_agents' => Agent::where('created_at', '>=', $startDate)->count(),
                'pending_verification' => Agent::where('is_verified', false)->count(),
                'verified_agents' => Agent::where('is_verified', true)->count(),
            ],
        ];

        return response()->json($analytics);
    }

    public function recentActivity()
    {
        $activities = [];

        // Recent user registrations
        $recentUsers = User::with('tenant')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user_registration',
                    'message' => "New user {$user->name} registered",
                    'timestamp' => $user->created_at,
                    'data' => $user,
                ];
            });

        // Recent properties
        $recentProperties = Property::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($property) {
                return [
                    'type' => 'property_created',
                    'message' => "New property '{$property->title}' added",
                    'timestamp' => $property->created_at,
                    'data' => $property,
                ];
            });

        // Recent disputes
        $recentDisputes = Dispute::with('lease.tenant', 'lease.landlord')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($dispute) {
                return [
                    'type' => 'dispute_created',
                    'message' => "New dispute raised for lease #{$dispute->lease_id}",
                    'timestamp' => $dispute->created_at,
                    'data' => $dispute,
                ];
            });

        // Combine and sort by timestamp
        $activities = collect([...$recentUsers, ...$recentProperties, ...$recentDisputes])
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        return response()->json($activities);
    }

    public function tenantOverview()
    {
        $tenants = Tenant::with('subscription.plan', 'users')
            ->withCount(['properties', 'units', 'leases' => function($q) {
                $q->where('status', 'active');
            }])
            ->get()
            ->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'domain' => $tenant->domain,
                    'user_count' => $tenant->users_count,
                    'property_count' => $tenant->properties_count,
                    'unit_count' => $tenant->units_count,
                    'active_leases' => $tenant->leases_count,
                    'subscription_status' => $tenant->hasActiveSubscription() ? 'active' : 'inactive',
                    'plan_name' => $tenant->subscription?->plan?->name ?? 'No Plan',
                    'created_at' => $tenant->created_at,
                ];
            });

        return response()->json($tenants);
    }
}
