<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockList extends Model
{
    use HasFactory;

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'email', 'email');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
