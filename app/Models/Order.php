<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'table_number',
        'amount',
        'status',
        'name',
        'email',
        'rating',
        'transaction_id',
        'vendor_id',
        'notes',
        'mira_id',
        'client_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
