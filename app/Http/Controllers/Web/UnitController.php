<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('property')->get();
        $properties = Property::all();

        return view('units.index', compact('units', 'properties'));
    }

    public function create()
    {
        $properties = Property::all();
        return view('units.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_label' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_m2' => 'nullable|numeric',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = $request->has('is_available') ? (bool)$request->input('is_available') : true;
        Unit::create($data);

        return redirect()->route('units.index')->with('success', 'Unit created.');
    }

    public function edit(Unit $unit)
    {
        $properties = Property::all();
        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_label' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_m2' => 'nullable|numeric',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = $request->has('is_available') ? (bool)$request->input('is_available') : $unit->is_available;
        $unit->update($data);

        return redirect()->route('units.index')->with('success', 'Unit updated.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted.');
    }
}
