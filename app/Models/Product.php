<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'category_id', 'name', 'description',
        'buy_price', 'sell_price', 'min_stock',
        'current_stock', 'unit', 'location', 'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }
}
