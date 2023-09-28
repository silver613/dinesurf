<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function users()
    {
        return $this->hasMany(user::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
