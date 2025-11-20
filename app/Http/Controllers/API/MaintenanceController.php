<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    // Tenant submits maintenance request
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            return response()->json(['message' => 'Only tenants can create requests'], 403);
        }

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'photos.*' => 'nullable|image|max:5120' // max 5MB
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);

        // Upload photos if any
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('maintenance', 'public');
                $photoUrls[] = Storage::url($path);
            }
        }

        $requestModel = MaintenanceRequest::create([
            'unit_id' => $unit->id,
            'tenant_id' => Auth::id(),
            'landlord_id' => $unit->property->landlord_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'photos' => $photoUrls ? json_encode($photoUrls) : null,
            'status' => 'open'
        ]);

        return response()->json($requestModel, 201);
    }

    // View all maintenance requests (tenant sees theirs, landlord sees property requests)
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'tenant') {
            $requests = MaintenanceRequest::where('tenant_id', $user->id)->with('unit.property')->get();
        } elseif ($user->role === 'landlord') {
            $requests = MaintenanceRequest::where('landlord_id', $user->id)->with('unit.property')->get();
        } else {
            $requests = MaintenanceRequest::with('unit.property')->get();
        }

        return response()->json($requests, 200);
    }

    // Update maintenance request (assign, status, resolution notes)
    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $user = Auth::user();

        // Only landlord or assigned agent can update
        if (!in_array($user->role, ['landlord','agent']) || $user->id !== $maintenance->landlord_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:open,in_progress,resolved,rejected',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string'
        ]);

        if (isset($validated['status'])) $maintenance->status = $validated['status'];
        if (isset($validated['assigned_to'])) $maintenance->assigned_to = $validated['assigned_to'];
        if (isset($validated['resolution_notes'])) $maintenance->resolution_notes = $validated['resolution_notes'];
        if ($validated['status'] === 'resolved') $maintenance->resolved_at = now();

        $maintenance->save();

        return response()->json($maintenance, 200);
    }
}
