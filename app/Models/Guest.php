<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'seating_preferences' => 'array',
    ];

    public $appends = ['seating_prefs_array', 'date', 'reservations_count'];

    public function getSeatingPrefsArrayAttribute()
    {
        return explode(', ', $this->seating_preferences);
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getReservationsCountAttribute()
    {
        return 0;
    }

    public function blockLists()
    {
        return $this->hasMany(BlockList::class, 'email', 'email');
    }
}
