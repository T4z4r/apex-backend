<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\MaintenanceRequest;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'properties' => Property::count(),
            'leases' => Lease::count(),
            'maintenance' => MaintenanceRequest::count(),
        ]);
    }
}
