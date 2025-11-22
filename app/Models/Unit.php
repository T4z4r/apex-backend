<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;

class Unit extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    protected $fillable = [
        'property_id',
        'unit_label',
        'bedrooms',
        'bathrooms',
        'size_m2',
        'rent_amount',
        'deposit_amount',
        'is_available',
        'photos',
        'tenant_id'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }


    public function leases()
    {
        return $this->hasMany(Lease::class);
    }


    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
