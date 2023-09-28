<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MenuSetting extends Model
{
    use HasFactory, SoftDeletes;

    public $appends = ['image_url', 'theme_url', 'pdf_url'];

    public function getImageUrlAttribute()
    {
        if ($this->bg_type == 'image') {
            return Storage::cloud()->url($this->bg);
        }
    }

    public function getPdfUrlAttribute()
    {
        if ($this->page == 'pdf-menu' && $this->pdf) {
            return Storage::cloud()->url($this->pdf);
        }
    }

    public function getThemeUrlAttribute()
    {
        if ($this->bg_type == 'theme') {
            return  url('images/themes/'.$this->bg.'.png');
        }
    }
}
