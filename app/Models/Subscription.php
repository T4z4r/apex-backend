<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'billing_cycle',
        'trial_ends_at',
        'ends_at',
        'status',
    ];

    protected $appends = ['price', 'features'];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'features' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && (!$this->ends_at || $this->ends_at->isFuture());
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture() && $this->status === 'trial';
    }

    public function isExpired()
    {
        return $this->status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
    }

    public function getPriceAttribute()
    {
        return $this->plan ? $this->plan->getPriceForCycle($this->billing_cycle) : 0;
    }

    public function getFeaturesAttribute()
    {
        return $this->plan ? $this->plan->features : [];
    }
}
