<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispute;
use App\Models\Lease;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisputeController extends Controller
{
    // Raise a dispute (tenant or landlord)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'issue' => 'required|string',
            'evidence.*' => 'nullable|file|max:5120'
        ]);

        $lease = Lease::findOrFail($validated['lease_id']);

        // Only involved parties can raise dispute
        if (!in_array(Auth::id(), [$lease->tenant_id, $lease->landlord_id])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $evidenceUrls = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('disputes', 'public');
                $evidenceUrls[] = Storage::url($path);
            }
        }

        $dispute = Dispute::create([
            'lease_id' => $lease->id,
            'raised_by' => Auth::id(),
            'issue' => $validated['issue'],
            'evidence' => $evidenceUrls ? json_encode($evidenceUrls) : null,
            'status' => 'open'
        ]);

        return response()->json($dispute, 201);
    }

    // Admin view all disputes
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $disputes = Dispute::with('lease.tenant', 'lease.landlord')->get();
        return response()->json($disputes, 200);
    }

    // Admin resolve or reject a dispute
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dispute = Dispute::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:resolved,rejected',
            'admin_resolution_notes' => 'nullable|string'
        ]);

        $dispute->status = $validated['status'];
        $dispute->admin_resolution_notes = $validated['admin_resolution_notes'] ?? null;
        $dispute->save();

        return response()->json($dispute, 200);
    }
}
