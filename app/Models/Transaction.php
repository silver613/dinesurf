<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency',
        'status',
        'paid_amount',
        'balance',
    ];

    public function planFrequency()
    {
        return $this->belongsTo(PlanFrequency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
