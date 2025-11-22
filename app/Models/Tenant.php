<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function hasActiveSubscription()
    {
        return $this->subscription && ($this->subscription->isActive() || $this->subscription->isOnTrial());
    }

    public function getMaxProperties()
    {
        return $this->subscription?->plan?->max_properties ?? 0;
    }

    public function getMaxUnits()
    {
        return $this->subscription?->plan?->max_units ?? 0;
    }

    public function getMaxUsers()
    {
        return $this->subscription?->plan?->max_users ?? 0;
    }

    public function canAddProperty()
    {
        $max = $this->getMaxProperties();
        if ($max === -1) return true; // unlimited
        return $this->properties()->count() < $max;
    }

    public function canAddUnit()
    {
        $max = $this->getMaxUnits();
        if ($max === -1) return true; // unlimited
        return $this->units()->count() < $max;
    }

    public function canAddUser()
    {
        $max = $this->getMaxUsers();
        if ($max === -1) return true; // unlimited
        return $this->users()->count() < $max;
    }
}
