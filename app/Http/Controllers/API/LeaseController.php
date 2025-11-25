<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class LeaseController extends Controller
{
    // Tenant requests a lease
    public function requestLease(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        if (!$unit->is_available) {
            return response()->json(['message' => 'Unit not available'], 400);
        }

        // Ensure tenant role
        if (Auth::user()->role !== 'tenant') {
            return response()->json(['message' => 'Only tenants can request leases'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'payment_frequency' => 'required|in:monthly,weekly,quarterly'
        ]);

        $lease = Lease::create([
            'unit_id' => $unit->id,
            'tenant_id' => Auth::user()->tenant_id,
            'landlord_id' => $unit->property->landlord_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rent_amount' => $unit->rent_amount,
            'deposit_amount' => $unit->deposit_amount,
            'payment_frequency' => $validated['payment_frequency'],
            'status' => 'pending'
        ]);

        return response()->json($lease, 201);
    }

    // View lease details
    public function show($id)
    {
        $lease = Lease::with('unit.property', 'tenant', 'landlord')->findOrFail($id);

        // Ensure only involved parties can view
        if (!in_array(Auth::id(), [$lease->tenant_id, $lease->landlord_id]) && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($lease, 200);
    }

    // Sign a lease (tenant e-sign)
    public function sign(Request $request, $id)
    {
        $lease = Lease::findOrFail($id);

        if ($lease->status !== 'pending') {
            return response()->json(['message' => 'Lease cannot be signed'], 400);
        }

        if (Auth::id() !== $lease->tenant_id) {
            return response()->json(['message' => 'Only the tenant can sign this lease'], 403);
        }

        $validated = $request->validate([
            'signature_type' => 'required|in:typed,image',
            'signature' => 'required|string' // typed name or base64 image URL
        ]);

        // Optionally overlay image signature onto PDF (advanced)
        // For MVP, we store typed name
        $lease->tenant_signature = $validated['signature'];
        $lease->status = 'active';
        $lease->signed_at = now();
        $lease->save();

        // Mark unit as unavailable
        $lease->unit->is_available = false;
        $lease->unit->save();

        return response()->json($lease, 200);
    }



    public function generatePdf($leaseId)
    {
        $lease = Lease::with('tenant', 'landlord', 'unit.property')->findOrFail($leaseId);

        // Ensure only tenant/landlord/admin can generate
        if (!in_array(Auth::id(), [$lease->tenant_id, $lease->landlord_id]) && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = Pdf::loadView('leases.template', compact('lease'));
        $fileName = 'leases/lease_' . $lease->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $lease->lease_pdf_url = Storage::url($fileName);
        $lease->save();

        return response()->json(['lease_pdf_url' => $lease->lease_pdf_url], 200);
    }

    // List leases for the authenticated user
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Lease::where('tenant_id', $user->tenant_id);

        if ($user->role === 'tenant') {
            $query->where('tenant_id', $user->id);
        } elseif ($user->role === 'landlord') {
            $query->where('landlord_id', $user->id);
        }
        // For admin, no additional filter

        $leases = $query->with('unit.property', 'tenantUser', 'landlord')->get();

        return response()->json($leases, 200);
    }

    // Update lease (e.g., status, dates if pending)
    public function update(Request $request, $id)
    {
        $lease = Lease::findOrFail($id);
        $user = Auth::user();

        // Only involved parties or admin
        if (!in_array($user->id, [$lease->tenant_id, $lease->landlord_id]) && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:pending,active,terminated',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if (isset($validated['status'])) {
            // Only landlord or admin can change status
            if ($user->id !== $lease->landlord_id && $user->role !== 'admin') {
                return response()->json(['message' => 'Only landlord can update status'], 403);
            }
            $lease->status = $validated['status'];
        }

        if (isset($validated['start_date']) || isset($validated['end_date'])) {
            // Only if pending
            if ($lease->status !== 'pending') {
                return response()->json(['message' => 'Cannot update dates after signing'], 400);
            }
            if (isset($validated['start_date'])) $lease->start_date = $validated['start_date'];
            if (isset($validated['end_date'])) $lease->end_date = $validated['end_date'];
        }

        $lease->save();

        return response()->json($lease, 200);
    }

    // Cancel/delete lease
    public function destroy($id)
    {
        $lease = Lease::findOrFail($id);
        $user = Auth::user();

        if (!in_array($user->id, [$lease->tenant_id, $lease->landlord_id]) && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($lease->status === 'active') {
            return response()->json(['message' => 'Cannot cancel active lease'], 400);
        }

        // Mark unit as available if pending
        if ($lease->status === 'pending') {
            $lease->unit->is_available = true;
            $lease->unit->save();
        }

        $lease->delete();

        return response()->json(['message' => 'Lease cancelled successfully'], 200);
    }
}
