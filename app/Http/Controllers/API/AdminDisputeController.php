<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispute;
use Illuminate\Support\Facades\Auth;

class AdminDisputeController extends Controller
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
        $query = Dispute::with('lease.tenant', 'lease.landlord', 'lease.unit.property');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $disputes = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($disputes);
    }

    public function show($id)
    {
        $dispute = Dispute::with('lease.tenant', 'lease.landlord', 'lease.unit.property')->findOrFail($id);
        return response()->json($dispute);
    }

    public function update(Request $request, $id)
    {
        $dispute = Dispute::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:open,resolved,rejected',
            'admin_resolution_notes' => 'nullable|string'
        ]);

        $dispute->status = $validated['status'];
        $dispute->admin_resolution_notes = $validated['admin_resolution_notes'] ?? null;
        $dispute->resolved_at = in_array($validated['status'], ['resolved', 'rejected']) ? now() : null;
        $dispute->save();

        return response()->json($dispute->load('lease.tenant', 'lease.landlord'));
    }

    public function assignToAdmin(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id'
        ]);

        $dispute = Dispute::findOrFail($id);
        $dispute->assigned_admin_id = $validated['admin_id'];
        $dispute->save();

        return response()->json([
            'message' => 'Dispute assigned to admin successfully',
            'dispute' => $dispute->load('lease.tenant', 'lease.landlord')
        ]);
    }

    public function stats(Request $request)
    {
        $query = Dispute::query();

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $stats = [
            'total_disputes' => $query->count(),
            'open_disputes' => (clone $query)->where('status', 'open')->count(),
            'resolved_disputes' => (clone $query)->where('status', 'resolved')->count(),
            'rejected_disputes' => (clone $query)->where('status', 'rejected')->count(),
            'avg_resolution_time' => Dispute::where('status', 'resolved')
                ->whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
                ->first()
                ->avg_hours ?? 0,
            'disputes_by_month' => Dispute::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray(),
        ];

        return response()->json($stats);
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'dispute_ids' => 'required|array',
            'dispute_ids.*' => 'exists:disputes,id',
            'status' => 'required|in:resolved,rejected',
            'admin_resolution_notes' => 'nullable|string'
        ]);

        Dispute::whereIn('id', $validated['dispute_ids'])
            ->update([
                'status' => $validated['status'],
                'admin_resolution_notes' => $validated['admin_resolution_notes'] ?? null,
                'resolved_at' => now(),
                'updated_at' => now()
            ]);

        return response()->json([
            'message' => count($validated['dispute_ids']) . ' disputes updated successfully'
        ]);
    }
}
