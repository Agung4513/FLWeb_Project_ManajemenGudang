<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Product extends Model
// {
//     protected $fillable = [
//         'sku', 'category_id', 'name', 'description', 'buy_price',
//         'sell_price', 'min_stock', 'current_stock', 'unit', 'location', 'image'
//     ];

//     protected $appends = ['formatted_buy_price', 'formatted_sell_price'];

//     protected static function boot()
//     {
//         parent::boot();

//         static::creating(function ($product) {
//             if (empty($product->sku)) {
//                 $latest = self::latest('id')->first();
//                 $number = $latest ? $latest->id + 1 : 1;
//                 $product->sku = 'PRD' . str_pad($number, 6, '0', STR_PAD_LEFT);
//             }
//         });
//     }

//     public function category()
//     {
//         return $this->belongsTo(Category::class);
//     }

//     public function transactionItems()
//     {
//         return $this->hasMany(TransactionItem::class);
//     }

//     public function restockItems()
//     {
//         return $this->hasMany(RestockItem::class);
//     }

//     public function getFormattedBuyPriceAttribute()
//     {
//         return 'Rp ' . number_format($this->buy_price, 0, ',', '.');
//     }

//     public function getFormattedSellPriceAttribute()
//     {
//         return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
//     }

//     public function isLowStock()
//     {
//         return $this->current_stock <= $this->min_stock;
//     }
// }














namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'buy_price',
        'sell_price', 'min_stock', 'current_stock', 'unit', 'location', 'image'
    ];

    protected $appends = ['formatted_buy_price', 'formatted_sell_price'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latest = self::latest('id')->first();
            $number = $latest ? $latest->id + 1 : 1;
            $product->sku = 'PRD' . str_pad($number, 6, '0', STR_PAD_LEFT);
        });
    }

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

    public function getFormattedBuyPriceAttribute()
    {
        return 'Rp ' . number_format($this->buy_price, 0, ',', '.');
    }

    public function getFormattedSellPriceAttribute()
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }

    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->min_stock;
    }
}
