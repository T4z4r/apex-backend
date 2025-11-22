<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;


class PropertyController extends Controller
{
    public function index()
    {
        return view('properties.index', ['properties' => Property::all()]);
    }


    public function create()
    {
        return view('properties.create');
    }


    public function store(Request $r)
    {
        $tenant = auth()->user()->tenant;

        if (!$tenant->canAddProperty()) {
            return back()->with('error', 'You have reached your property limit. Please upgrade your plan.');
        }

        $data = $r->validate([
            'title' => 'required',
            'description' => 'nullable',
            'address' => 'required',
            'neighborhood' => 'required',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'amenities' => 'nullable|json'
        ]);
        $data['landlord_id'] = auth()->id();
        $data['tenant_id'] = auth()->user()->tenant_id;
        Property::create($data);
        return back()->with('success', 'Property added');
    }


    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }


    public function update(Request $r, Property $property)
    {
        $property->update($r->validate(['name' => 'required', 'location' => 'required']));
        return back()->with('success', 'Property updated');
    }


    public function destroy(Property $property)
    {
        $property->delete();
        return back()->with('success', 'Deleted');
    }
}
