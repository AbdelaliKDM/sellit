<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'barcode',
        'image',
        'price',
        'quantity',
        'amount',
        'profit',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
