<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $casts = [
        'plan_start' => 'datetime',
        'plan_end' => 'datetime',
        'active' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function planFrequency()
    {
        return $this->belongsTo(PlanFrequency::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_reference', 'reference');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = [
        'date', 'start', 'end',
    ];

    public function getDateAttribute()
    {
        return $this->created_at->format('jS F Y');
    }

    public function getStartAttribute()
    {
        return Carbon::parse($this->plan_start)->format('jS F Y');
    }

    public function getEndAttribute()
    {
        return Carbon::parse($this->plan_end)->format('jS F Y');
    }

    public function subscribedBy()
    {
        return $this->belongsTo(User::class, 'subscribed_by', 'id');
    }
}
