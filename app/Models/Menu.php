<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order'];

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image == '' || $this->image == null) {
            return null;
        }

        return Storage::cloud()->url($this->image);
    }

    public function menuCategories()
    {
        return $this->belongsToMany(MenuCategory::class);
    }
}
