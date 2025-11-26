<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminPaymentController extends Controller
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
        $query = Payment::with('lease.tenant', 'lease.unit.property');

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payment::with('lease.tenant', 'lease.unit.property')->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'payment_method' => 'sometimes|string',
            'notes' => 'nullable|string'
        ]);

        $payment->update($validated);

        return response()->json($payment);
    }

    public function stats(Request $request)
    {
        $query = Payment::query();

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $stats = [
            'total_payments' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'completed_payments' => (clone $query)->where('status', 'completed')->count(),
            'completed_amount' => (clone $query)->where('status', 'completed')->sum('amount'),
            'pending_payments' => (clone $query)->where('status', 'pending')->count(),
            'failed_payments' => (clone $query)->where('status', 'failed')->count(),
        ];

        return response()->json($stats);
    }
}
