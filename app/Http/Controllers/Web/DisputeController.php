<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Lease;
use App\Models\Dispute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisputeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $disputes = Dispute::with('lease.unit.property','lease.tenant','lease.landlord')->get();
        } elseif ($user->hasRole('tenant') || $user->hasRole('landlord')) {
            $disputes = Dispute::where('raised_by', $user->id)->with('lease.unit.property','lease.tenant','lease.landlord')->get();
        } else {
            $disputes = Dispute::with('lease.unit.property','lease.tenant','lease.landlord')->get();
        }
        $leases = Lease::get();
        $users= User::get();
        return view('disputes.index', compact('disputes', 'leases','users'));
    }

    public function create()
    {
        $leases = Lease::where('tenant_id', auth()->id())->with('unit.property')->get();
        return view('disputes.create', compact('leases'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'issue' => 'required|string|max:1000',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $lease = Lease::findOrFail($data['lease_id']);
        if (!in_array(auth()->id(), [$lease->tenant_id, $lease->landlord_id])) {
            abort(403);
        }

        $evidence = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $f) {
                $path = $f->store('disputes', 'public');
                $evidence[] = Storage::url($path);
            }
        }

        $dispute = Dispute::create([
            'lease_id' => $data['lease_id'],
            'raised_by' => auth()->id(),
            'issue' => $data['issue'],
            'evidence' => $evidence ? json_encode($evidence) : null,
            'status' => 'open'
        ]);

        return redirect()->route('disputes.index')->with('success', 'Dispute submitted.');
    }

    public function edit(Dispute $dispute)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);
        return view('disputes.edit', compact('dispute'));
    }

    public function update(Request $request, Dispute $dispute)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved,rejected',
            'admin_resolution_notes' => 'nullable|string'
        ]);

        $dispute->status = $data['status'];
        $dispute->admin_resolution_notes = $data['admin_resolution_notes'] ?? $dispute->admin_resolution_notes;
        $dispute->save();

        return redirect()->route('disputes.index')->with('success', 'Dispute updated.');
    }

    public function destroy(Dispute $dispute)
    {
        if (!in_array(auth()->id(), [$dispute->raised_by]) && !auth()->user()->hasRole('admin')) abort(403);
        $dispute->delete();
        return redirect()->route('disputes.index')->with('success', 'Dispute deleted.');
    }
}
