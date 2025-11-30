<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'type',
        'date',
        'user_id',
        'supplier_id',
        'customer_name',
        'restock_order_id',
        'status',
        'notes',
        'total_amount',
        'total_profit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function restockOrder()
    {
        return $this->belongsTo(RestockOrder::class);
    }
}
