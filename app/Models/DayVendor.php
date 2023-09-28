<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DayVendor extends Pivot
{
    use HasFactory;

    protected $table = 'day_vendor';

    protected $casts = [
        'open' => 'boolean',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
