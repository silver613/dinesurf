<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReservation extends Model
{
    use HasFactory;

    public $appends = ['event_status', 'iso_start', 'iso_end'];

    public function getEventStatusAttribute()
    {
        if ($this->event_date < now()->toDateString()) {
            return 'past';
        }

        if ($this->event_date > now()->toDateString()) {
            return 'upcoming';
        }

        if ($this->event_date === now()->toDateString()) {
            return 'live';
        }
    }

    public function getIsoStartAttribute()
    {
        return $this->date.' '.$this->time;
    }

    public function getIsoEndAttribute()
    {
        return Carbon::parse($this->date.' '.$this->time)->addHour();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
