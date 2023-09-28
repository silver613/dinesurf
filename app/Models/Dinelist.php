<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Dinelist extends Model
{
    use HasFactory;

    protected $appends = ['liked', 'slug'];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function likes()
    {
        return $this->hasMany(DinelistLike::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLikedAttribute()
    {
        $user = Auth::user();
        $like = null;
        if ($user) {
            $like = $this->likes->where('user_id', $user->id)->first();
        }

        return $like ? true : false;
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name, '-');
    }
}
