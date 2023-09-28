<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MenuCategoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order', 'status'];

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image == '' || $this->image == null) {
            return url('images/menu-item.jpg');
        }

        return Storage::cloud()->url($this->image);
    }

    public function categories()
    {
        return $this->belongsToMany(MenuCategory::class);
    }
}
