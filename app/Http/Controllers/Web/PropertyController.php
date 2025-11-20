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
        Property::create($r->validate(['name' => 'required', 'location' => 'required']));
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
