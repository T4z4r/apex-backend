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

        if ($request->filled('neighborhood')) {
            $query->where('neighborhood', $request->neighborhood);
        }

        if ($request->filled('price_min')) {
            $query->whereHas('units', fn($q) => $q->where('rent_amount', '>=', $request->price_min));
        }

        if ($request->filled('price_max')) {
            $query->whereHas('units', fn($q) => $q->where('rent_amount', '<=', $request->price_max));
        }

        if ($request->filled('bedrooms')) {
            $query->whereHas('units', fn($q) => $q->where('bedrooms', $request->bedrooms));
        }

        $properties = $query->with('units')->get();

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
        $this->authorize('create', Property::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:100',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'amenities' => 'nullable|array'
        ]);

        $property = Property::create(array_merge($validated, [
            'landlord_id' => Auth::id(),
            'amenities' => isset($validated['amenities']) ? json_encode($validated['amenities']) : null
        ]));

        return response()->json($property, 201);
    }
}
