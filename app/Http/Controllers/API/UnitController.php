<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    // List units (with optional filters)
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        $units = $query->with('property')->get();

        return response()->json($units, 200);
    }

    // Show single unit
    public function show($id)
    {
        $unit = Unit::with('property')->findOrFail($id);
        return response()->json($unit, 200);
    }

    // Add unit to a property
    public function store(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Ensure only landlord who owns the property can add units
        if ($property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'unit_label' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_m2' => 'nullable|numeric',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'photos' => 'nullable|array'
        ]);

        $data = $validated;
        $data['property_id'] = $property->id;
        $data['tenant_id'] = $property->tenant_id;
        $data['is_available'] = isset($validated['is_available']) ? (bool)$validated['is_available'] : true;
        $unit = Unit::create($data);

        return response()->json($unit, 201);
    }

    // Update unit
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        if ($unit->property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'unit_label' => 'sometimes|required|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_m2' => 'nullable|numeric',
            'rent_amount' => 'sometimes|required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'photos' => 'nullable|array'
        ]);

        $data = $validated;
        if (isset($validated['is_available'])) {
            $data['is_available'] = (bool)$validated['is_available'];
        }
        $unit->update($data);

        return response()->json($unit, 200);
    }

    // Delete unit
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        if ($unit->property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if unit has active leases
        if ($unit->leases()->where('status', 'active')->exists()) {
            return response()->json(['message' => 'Cannot delete unit with active leases'], 400);
        }

        $unit->delete();

        return response()->json(['message' => 'Unit deleted successfully'], 200);
    }
}
