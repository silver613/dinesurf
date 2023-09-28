<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    public $appends = ['vendor_attached'];

    public function getVendorAttachedAttribute()
    {
        return false;
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }
}
