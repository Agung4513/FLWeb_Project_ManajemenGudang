<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    protected $fillable = [
        'po_number', 'manager_id', 'supplier_id', 'order_date',
        'expected_delivery_date', 'status', 'notes',
        'confirmed_by_supplier_at', 'supplier_notes' 
    ];

    protected $casts = [
        'order_date' => 'date:Y-m-d',
        'expected_delivery_date' => 'date:Y-m-d',];

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
