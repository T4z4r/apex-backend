@extends('layouts.master')

{{-- @section('title', 'Dashboard')
@section('page_title', 'Dashboard') --}}

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-blue-500 text-white rounded-lg shadow p-6">
        <h5 class="text-lg font-semibold">Properties</h5>
        <p class="text-2xl font-bold">{{ $propertiesCount }}</p>
    </div>
    <div class="bg-green-500 text-white rounded-lg shadow p-6">
        <h5 class="text-lg font-semibold">Units</h5>
        <p class="text-2xl font-bold">{{ $unitsCount }}</p>
    </div>
    <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
        <h5 class="text-lg font-semibold">Leases</h5>
        <p class="text-2xl font-bold">{{ $leasesCount }}</p>
    </div>
    <div class="bg-red-500 text-white rounded-lg shadow p-6">
        <h5 class="text-lg font-semibold">Maintenance Requests</h5>
        <p class="text-2xl font-bold">{{ $maintenanceCount }}</p>
    </div>
</div>
@endsection
