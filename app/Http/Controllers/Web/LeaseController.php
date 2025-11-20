<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaseController extends Controller
{
    public function index()
    {
        $leases = Lease::with(['unit.property', 'tenant', 'landlord'])->get();
        return view('leases.index', compact('leases'));
    }

    public function create()
    {
        $units = Unit::where('is_available', true)->with('property')->get();
        $tenants = User::role('tenant')->get();
        return view('leases.create', compact('units', 'tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'required|in:monthly,weekly,quarterly',
        ]);

        $unit = Unit::findOrFail($data['unit_id']);
        $data['landlord_id'] = $unit->property->landlord_id ?? null;
        $data['status'] = 'pending';

        $lease = Lease::create($data);

        return redirect()->route('leases.index')->with('success', 'Lease requested.');
    }

    public function edit(Lease $lease)
    {
        $units = Unit::with('property')->get();
        $tenants = User::role('tenant')->get();
        return view('leases.edit', compact('lease', 'units', 'tenants'));
    }

    public function update(Request $request, Lease $lease)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'required|in:monthly,weekly,quarterly',
            'status' => 'required|in:pending,active,expired,terminated',
        ]);

        $lease->update($data);
        return redirect()->route('leases.index')->with('success', 'Lease updated.');
    }

    public function destroy(Lease $lease)
    {
        $lease->delete();
        return redirect()->route('leases.index')->with('success', 'Lease deleted.');
    }

    // Generate PDF (web)
    public function generatePdf(Lease $lease)
    {
        $pdf = Pdf::loadView('leases.template', compact('lease'));
        $fileName = 'leases/lease_'.$lease->id.'_'.time().'.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());
        $lease->lease_pdf_url = Storage::url($fileName);
        $lease->save();

        return redirect()->back()->with('success', 'PDF generated.');
    }

    // Sign (web typed signature)
    public function sign(Request $request, Lease $lease)
    {
        $request->validate(['signature' => 'required|string|max:255']);

        $lease->tenant_signature = $request->signature;
        $lease->status = 'active';
        $lease->signed_at = now();
        $lease->save();

        $lease->unit->update(['is_available' => false]);

        return redirect()->back()->with('success', 'Lease signed.');
    }
}
