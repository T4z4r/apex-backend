<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    // Add unit to a property
    public function store(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);

        // Ensure only landlord who owns the property can add units
        if ($property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'unit_label' => 'required|string|max:50',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'size_m2' => 'nullable|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'is_available' => 'boolean',
            'photos' => 'nullable|array'
        ]);

        $unit = Unit::create([
            'property_id' => $property->id,
            'unit_label' => $validated['unit_label'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'size_m2' => $validated['size_m2'] ?? null,
            'rent_amount' => $validated['rent_amount'],
            'deposit_amount' => $validated['deposit_amount'],
            'is_available' => $validated['is_available'] ?? true,
            'photos' => isset($validated['photos']) ? json_encode($validated['photos']) : null
        ]);

        return response()->json($unit, 201);
    }
}
