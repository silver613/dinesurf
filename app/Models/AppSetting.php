<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'events_percentage_fee' => 'float',
        'events_cap_fee' => 'float',
        'reservations_percentage_fee' => 'float',
        'reservations_cap_fee' => 'float',
        'orders_percentage_fee' => 'float',
        'orders_cap_fee' => 'float',
        'paystack_percentage_fee' => 'float',
        'paystack_flat_fee' => 'float',
        'paystack_cap_fee' => 'float',
        'paystack_wave_under' => 'float',
    ];
}
