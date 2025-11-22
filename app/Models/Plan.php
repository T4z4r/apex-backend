<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'monthly_price',
        'yearly_price',
        'max_properties',
        'max_units',
        'max_users',
        'features',
        'is_active',
        'trial_days',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getPriceForCycle($cycle = 'monthly')
    {
        return $cycle === 'yearly' ? $this->yearly_price : $this->monthly_price;
    }
}
