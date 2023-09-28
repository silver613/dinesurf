<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function planFrequencies()
    {
        return $this->hasMany(PlanFrequency::class);
    }
}
