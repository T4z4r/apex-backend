<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    // List all properties (with optional filters)
    public function index(Request $request)
    {
        $query = Property::query();

        // if ($request->filled('neighborhood')) {
        //     $query->where('neighborhood', $request->neighborhood);
        // }

        // if ($request->filled('price_min')) {
        //     $query->whereHas('units', fn($q) => $q->where('rent_amount', '>=', $request->price_min));
        // }

        // if ($request->filled('price_max')) {
        //     $query->whereHas('units', fn($q) => $q->where('rent_amount', '<=', $request->price_max));
        // }

        // if ($request->filled('bedrooms')) {
        //     $query->whereHas('units', fn($q) => $q->where('bedrooms', $request->bedrooms));
        // }

        $properties = $query->with('units')->get();
        // dd($properties);

        return response()->json($properties, 200);
    }

    // Show single property
    public function show($id)
    {
        $property = Property::with('units')->findOrFail($id);
        return response()->json($property, 200);
    }

    // Create property (landlord only)
    public function store(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant->canAddProperty()) {
            return response()->json(['message' => 'You have reached your property limit. Please upgrade your plan.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'address' => 'required',
            'neighborhood' => 'required',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'amenities' => 'nullable|json'
        ]);

        $data = $validated;
        $data['landlord_id'] = Auth::id();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $property = Property::create($data);

        return response()->json($property, 201);
    }

    // Update property (landlord only)
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        if ($property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required',
            'description' => 'nullable',
            'address' => 'sometimes|required',
            'neighborhood' => 'sometimes|required',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'amenities' => 'nullable|json'
        ]);

        $property->update(array_merge($validated, [
            'amenities' => isset($validated['amenities']) ? json_encode($validated['amenities']) : $property->amenities
        ]));

        return response()->json($property, 200);
    }

    // Delete property (landlord only)
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        if ($property->landlord_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if property has active leases
        if ($property->units()->whereHas('leases', function($q) {
            $q->where('status', 'active');
        })->exists()) {
            return response()->json(['message' => 'Cannot delete property with active leases'], 400);
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully'], 200);
    }
}
