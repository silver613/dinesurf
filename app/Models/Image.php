<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->path == '' || $this->path == null) {
            return url('images/product.png');
        }

        return Storage::cloud()->url($this->path);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'model_id', 'id')->approved();
    }

    public function event()
    {
        return $this->belongsTo(Vendor::class, 'event_id');
    }
}
