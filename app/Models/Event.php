<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    public $appends = ['image_url', 'event_type'];

    public function getImageUrlAttribute()
    {
        if ($this->image == '' || $this->image == null) {
            return url('images/product.png');
        }

        return Storage::cloud()->url($this->image);
    }

    public function getEventTypeAttribute()
    {
        if ($this->end_date < now()->toDateString()) {
            return 'past';
        }

        if ($this->start_date >= now()->toDateString()) {
            return 'upcoming';
        }

        if ($this->start_date <= now()->toDateString() && $this->end_date > now()->toDateString()) {
            return 'live';
        }
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reservations()
    {
        return $this->hasMany(EventReservation::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
