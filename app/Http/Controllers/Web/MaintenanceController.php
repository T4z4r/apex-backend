<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('tenant')) {
            $items = MaintenanceRequest::where('tenant_id', auth()->id())->with('unit.property','assigned')->get();
        } else {
            $items = MaintenanceRequest::with('unit.property','tenant','assigned')->get();
        }
        return view('maintenance.index', compact('items'));
    }

    public function create()
    {
        $units = Unit::where('is_available', true)->with('property')->get();
        return view('maintenance.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'photos.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120'
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $f) {
                $path = $f->store('maintenance', 'public');
                $photos[] = Storage::url($path);
            }
        }

        $data['tenant_id'] = auth()->id();
        $data['landlord_id'] = Unit::find($data['unit_id'])->property->landlord_id ?? null;
        $data['photos'] = $photos ? json_encode($photos) : null;
        $data['status'] = 'open';

        MaintenanceRequest::create($data);

        return redirect()->route('maintenance.index')->with('success', 'Maintenance request submitted.');
    }

    public function edit(MaintenanceRequest $maintenance)
    {
        $technicians = User::role('technician')->get();
        return view('maintenance.edit', compact('maintenance','technicians'));
    }

    public function update(Request $request, MaintenanceRequest $maintenance)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,rejected',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string'
        ]);

        if (isset($data['assigned_to'])) $maintenance->assigned_to = $data['assigned_to'];
        $maintenance->status = $data['status'];
        $maintenance->resolution_notes = $data['resolution_notes'] ?? $maintenance->resolution_notes;
        if ($data['status'] === 'resolved') $maintenance->resolved_at = now();
        $maintenance->save();

        return redirect()->route('maintenance.index')->with('success','Maintenance updated.');
    }

    public function destroy(MaintenanceRequest $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success','Deleted.');
    }
}
