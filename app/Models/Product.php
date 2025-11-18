<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'category_id', 'name', 'description', 'buy_price',
        'sell_price', 'min_stock', 'current_stock', 'unit', 'location', 'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function restockItems()
    {
        return $this->hasMany(RestockItem::class);
    }
}
