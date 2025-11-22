<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    protected $fillable = [
        'unit_id',
        'tenant_id',
        'landlord_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'photos'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
