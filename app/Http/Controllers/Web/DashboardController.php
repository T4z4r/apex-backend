<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\MaintenanceRequest;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'propertiesCount' => Property::count(),
            'unitsCount' => Unit::count(),
            'leasesCount' => Lease::count(),
            'maintenanceCount' => MaintenanceRequest::count(),
        ]);
    }
}
