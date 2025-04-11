<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'barcode',
        'purchase_price',
        'selling_price',
        'quantity',
        'description',
        'status'
    ];

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount()
    {
        $now = now();
        return $this->discounts()
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->orderBy('new_price')
            ->first();
    }

    public function getCurrentPrice()
    {
        $discount = $this->activeDiscount();
        return $discount ? $discount->new_price : $this->selling_price;
    }

    public function hasActiveDiscount()
    {
        return $this->activeDiscount() !== null;
    }
}
