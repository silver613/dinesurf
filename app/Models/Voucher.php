<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'discount_until' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function voucherUsageLogs()
    {
        return $this->hasMany(VoucherUsageLog::class);
    }
}
