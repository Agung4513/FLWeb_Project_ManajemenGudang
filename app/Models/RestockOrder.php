<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'manager_id', 'order_date',
        'expected_delivery_date', 'status', 'notes'
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function items()
    {
        return $this->hasMany(RestockItem::class);
    }
}
